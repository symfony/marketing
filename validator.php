<?php

// validates that the information of this repository is valid

if (!is_file($autoloader = __DIR__.'/vendor/autoload.php')) {
    echo "Dependencies are not installed, please run 'composer install' first!\n";
    exit(1);
}
require $autoloader;

use Composer\Config;
use Composer\IO\NullIO;
use Composer\Repository\ComposerRepository;
use Composer\Repository\RepositoryInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

final class Validate extends Command
{
    private const EXIT_SUCCESSFUL = 0;
    private const EXIT_ERROR = 1;
    private $yamlParser;

    public function __construct()
    {
        parent::__construct('validate');

        $this->yamlParser = new Parser();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // check that the YAML code is valid for all files
        $yamlFiles = array_merge([__DIR__.'/components.yml', __DIR__.'/projects.yml'], glob(__DIR__.'/projects/*.yml', GLOB_NOSORT));
        foreach ($yamlFiles as $filePath) {
            try {
                $this->yamlParser->parseFile($filePath);
            } catch (ParseException $e) {
                $io->error(sprintf('The "%s" file does not have valid YAML syntax.', $filePath));

                return self::EXIT_ERROR;
            }
        }

        $projects = $this->yamlParser->parseFile(__DIR__.'/projects.yml');
        $components = $this->yamlParser->parseFile(__DIR__.'/components.yml');

        // check that project detail files use '.yml' extension instead of '.yaml'
        $filesWithYamlExtension = glob(__DIR__.'/projects/*.yaml', GLOB_NOSORT);
        if (count($filesWithYamlExtension) > 0) {
            $io->error(sprintf('All the YAML files in the "projects/" directory must use ".yml" as extension instead of ".yaml". The following files must change their extension: %s.', implode(', ', $filesWithYamlExtension)));

            return self::EXIT_ERROR;
        }

        // check that projects are only in one category (e.g. a project can't be in 'cms' and 'default')
        $allProjectSlugs = [];
        foreach ($projects as $category => $projectSlugs) {
            foreach ($projectSlugs as $projectSlug) {
                if (\in_array($projectSlug, $allProjectSlugs, true)) {
                    $io->error(sprintf('The "%s" project slug of "%s" category is included in another category (each projet can only be included in a single category.', $projectSlug, $category));

                    return self::EXIT_ERROR;
                }

                $allProjectSlugs[] = $projectSlug;
            }
        }

        // check that if a project is listed in the index, it defines a separate file with its information
        foreach ($allProjectSlugs as $projectSlug) {
            $expectedProjectFile = sprintf(__DIR__.'/projects/%s.yml', $projectSlug);
            if (!file_exists($expectedProjectFile)) {
                $io->error(sprintf('The "%s" project is missing a file with its detailed description in "%s".', $projectSlug, $expectedProjectFile));

                return self::EXIT_ERROR;
            }
        }

        // check that all project files correspond to a project listed in the index file
        foreach (glob(__DIR__.'/projects/*.yml', GLOB_NOSORT) as $filePath) {
            $projectSlug = basename($filePath,'.yml');
            if (!\in_array($projectSlug, $allProjectSlugs, true)) {
                $io->error(sprintf('The "%s" file refers to a project called "%s" which is not listed in the main projects.yml file.', $filePath, $projectSlug));

                return self::EXIT_ERROR;
            }
        }

        // check that ech project defines its own logo file
        foreach (glob(__DIR__.'/projects/*.yml', GLOB_NOSORT) as $filePath) {
            $projectSlug = basename($filePath,'.yml');
            if (!file_exists(str_replace('.yml', '.png', $filePath))) {
                $io->error(sprintf('The "%s" project does not define its logo in a "%s.png" file.', $projectSlug, $projectSlug));

                return self::EXIT_ERROR;
            }
        }

        // check that components are not repeated
        $allComponentNames = [];
        foreach ($components as $componentName => $componentData) {
            if (\in_array($componentName, $allComponentNames, true)) {
                $io->error(sprintf('The "%s" component is included more than once in the components.yml file.', $componentName));

                return self::EXIT_ERROR;
            }

            $allComponentNames[] = $componentName;
        }

        // check that components define some mandatory properties
        $mandatoryProperties = ['slug', 'docUrl', 'docPage', 'deprecated', 'description'];
        foreach ($components as $componentName => $componentData) {
            if (array_keys($componentData) !== $mandatoryProperties) {
                $io->error(sprintf('The "%s" component must only define the following mandatory properties: "%s".', $componentName, implode(', ', $mandatoryProperties)));

                return self::EXIT_ERROR;
            }
        }

        // check that component 'slug' does not include 'symfony/'
        foreach ($components as $componentName => $componentData) {
            if ('symfony/' === substr($componentData['slug'], 0, 8)) {
                $io->error(sprintf('The "slug" value of the "%s" component must not contain the "symfony/" prefix.', $componentName));

                return self::EXIT_ERROR;
            }
        }

        // check that component 'docUrl' is not an absolute URL
        foreach ($components as $componentName => $componentData) {
            if (0 === strpos($componentData['docUrl'], 'http://') || 0 === strpos($componentData['docUrl'], 'https://')) {
                $io->error(sprintf('The "docUrl" value of the "%s" component must be a relative URL ("%s" given).', $componentName, $componentData['docUrl']));

                return self::EXIT_ERROR;
            }
        }

        $io->success('All data is valid.');

        return self::EXIT_SUCCESSFUL;
    }
}

final class Validator extends Application
{
    protected function getCommandName(InputInterface $input)
    {
        return 'validate';
    }

    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new Validate();

        return $defaultCommands;
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}

$application = new Validator();
$application->run();
