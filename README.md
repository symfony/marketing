The Symfony Website Content
===========================

This repository stores the information about the [Symfony Components][1] and
the [Symfony Projects][2] displayed on symfony.com.

The contents of this repository and all the contributed contents are licensed
under a [Creative Commons Attribution-Share Alike 3.0 Unported License](http://creativecommons.org/licenses/by-sa/3.0/).

How to Add a Project to the List of Projects Made with Symfony
--------------------------------------------------------------

Follow these instructions to show your Symfony-based project on [symfony.com/projects][2]:

 1. Add a YAML file with the basic project information in `projects/xxx.yml`
    where `xxx` is the slug of your project (e.g. `projects/acme.yml`). Use any
    of the existing YAML files as the reference of your own file.
 2. Add a square PNG image with the logo of your project in `projects/xxx.png`
    where `xxx` is the slug of your project (e.g. `projects/acme.png`). It's
    recommended to create a 160px x 160px image.
 3. Add your project to the `projects.yml` file at the root of this repository.
    Append your project at the end of the list and we'll change its position if
    needed.

[1]: https://symfony.com/components
[2]: https://symfony.com/projects
