In order to avoid duplicate work, it would be nice to follow a few conventions for PR.

Adding a new language
---------------------

**There should be only one pull request for a language at a time.**

- Pull request title should follow this convention: `[wip] [<language code>] <language name> translation`.
  For instance for the french translation: `[wip] [fr] french translation`.
- Before sending a new pull request, make sure there is not WIP for the language you want to add.
- Start a PR early and then add commits to be sure that no one will start a PR with the same language (first commit may be empty).

Contribute to a WIP
-------------------

**The language WIP owner should manage its translation.**

- Just make a PR on the WIP owner repository.
- If you recieve a PR on your repository, please don't forget to merge or comment it.

Fix an existing translation
---------------------------

**Fixes may be small and should not be WIP.**

- Fix PR should follow this convention: `[fix] [<language code>] <language name> translation fix`. For instance for the french translation: `[fix] [fr] french translation fix`.
- A fix PR should not be a WIP.

Glossary
--------

- WIP: Work In Progress
- PR: Pull Request
