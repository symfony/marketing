The Symfony Website Content
===========================

This repository hosts the content of all the symfony.com website pages (except
for the documentation).

This is where you can submit pull requests if you find a typo somewhere on
the Symfony website or if you want to translate its contents to another
language.

The contents of this repository and all the contributed contents are licensed
under a [Creative Commons Attribution-Share Alike 3.0 Unported License](http://creativecommons.org/licenses/by-sa/3.0/).

How to Add a Project to the List of Projects Made with Symfony
--------------------------------------------------------------

In http://symfony.com/projects we list Open-Source projects that use Symfony
components. Follow these instructions to propose a new project for that list:

 1. Add a YAML file with the basic project information in `projects/xxx.yml`
    where `xxx` is the slug of your project (e.g. `projects/acme.yml`). Use any
    of the existing YAML files as the reference of your own file and take into
    account that:
      * If your project depends on Symfony components, list them under the
        `components` option.
      * If your project depends on the entire Symfony full-stack framework, leave
        the `components` option empty and add `symfonyfs`in the `dependencies`
        option.
 2. Add a square PNG image with the logo of your project in `projects/xxx.png`
    where `xxx` is the slug of your project (e.g. `projects/acme.png`). It's
    recommended to create a 256px x 256px image.
 3. Add your project to the `projects/_projects.yml` file which stores the full
    list of projects. Add your project at the end of the list and we'll change
    its position if needed.
