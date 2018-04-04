# Contributing to Move.mil

Anyone is welcome to contribute code changes and additions to this project. If you'd like your changes merged into the dev branch, please read the following document before opening a [pull request][pulls].

There are several ways in which you can help improve this project:

1. Fix an existing [issue][issues] and submit a [pull request][pulls].
1. Review open [pull requests][pulls].
1. Report a new [issue][issues]. _Only do this after you've made sure the behavior or problem you're observing isn't already documented in an open issue._

## Table of Contents

- [Getting Started](#getting-started)
- [Making Changes](#making-changes)
- [Code Style](#code-style)
- [Legalese](#legalese)
- [FAQ](#faq)

## Getting Started

Move.mil is a [Drupal](https://www.drupal.org/) (version 8.5.x) content management system with a [MariaDB](https://mariadb.org/) database version (version 10.2). Development dependencies are managed using [Composer](https://getcomposer.org/).

### How is this site laid out?

When installing the given `composer.json` some tasks are taken care of:

* Drupal will be installed in the `web`-directory.
* Autoloader is implemented to use the generated composer autoloader in `vendor/autoload.php`,
  instead of the one provided by Drupal (`web/vendor/autoload.php`).
* Modules (packages of type `drupal-module`) will be placed in `web/modules/contrib/`
* Theme (packages of type `drupal-theme`) will be placed in `web/themes/contrib/`
* Profiles (packages of type `drupal-profile`) will be placed in `web/profiles/contrib/`
* Creates default writable versions of `settings.php` and `services.yml`.
* Creates `web/sites/default/files`-directory.
* Latest version of drush is installed locally for use at `vendor/bin/drush`.
* Latest version of DrupalConsole is installed locally for use at `vendor/bin/drupal`.
* Creates environment variables based on your .env file. See [.env.docker.example](.env.docker.example).

### Usage

First you need to [install docker](https://docs.docker.com/docker-for-mac/install/).

Clone this repo in your desired directory (move.mil by default):
```
git clone git@github.com:Bixal/move.mil.git [move.mil]
cd move.mil
```

Set default environment variables:
```
cp .env.docker.example .env
```

Initialize the docker containers with Ngnix, Drupal app, and MariaDB:
```
docker-compose up -d
docker-compose run php composer install
```

Install a standard Drupal application:
```
docker-compose run php drupal site:install --force --no-interaction
```

Setup Move.mil:

> Note: You don't have to execute this command if you have a db dump file(s) in your mariadb-init folder. 

```
docker-compose run php drupal config:import --no-interaction
```
_See [this article](https://www.drupal.org/docs/8/configuration-management) for more information about configuramtion management_

To stop the containers execute:
```
make stop
```

If you want to know more available commands, please review the following document [Makefile][makefile]

Redirect move.mil.localhost to your localhost:
```
sudo sh -c "echo '127.0.0.1 move.mil.localhost' >> /etc/hosts"
```

Lastly, navigate to [move.mil.localhost:8000](move.mil.localhost:8000) in your Web browser of choice.


### Database Import

Instead of installing the site, you can choose to place a .sql or .sql.gz file
in mariadb-init. All files in this folder will be imported, in alphabetical order.
.sql and .sql.gz files are gitignored so you do not have to worry about them
getting commited.

## Making Changes

1. Fork and clone the project's repo.
2. Setup your environment as outlined above.
3. Create a feature branch for the code changes you're looking to make: `git checkout -b your-descriptive-branch-name origin/1.x-dev`.
4. Install missing dependencies: `make composer`.
5. Import the current configuration: `make cim` (If this does not work, try a `make up` again).
5. _Write some code!_
6. Run the application and verify that your changes function as intended.
7. If your changes would benefit from testing, add the necessary tests and verify everything passes.
8. Export the configuration with your changes: `make cex`.
9. Commit your changes: `git commit -am 'Add some new feature or fix some issue'`. _(See [this excellent article](https://chris.beams.io/posts/git-commit) for tips on writing useful Git commit messages.)_
10. Push the branch to your fork: `git push -u origin your-descriptive-branch-name`.
11. Create a new pull request and we'll review your changes.


### Update Menu items

Menu items are defined at `web/modules/custom/custom_move_mil_menus`.

If you want to add an new menu, add the definition at `config/install/system.menu.new_menu.yml`. It is important that the file starts with `system.menu` so Drupal knows it has to be added to the menus configuration.


To update or add the configuration of a menu, open `custom_move_mil_menus.links.menu.yml` file, here you'll update or add the menu links with the label, url, weight and other settings.

After the configuration changes are done, execute `make menu-update` in order to see your changes on the site.


### Verifying Changes

We use a number of tools to evaluate the quality and security of this project's code:

CircleCI is the test runner for this project and testing can be run inside a
container.

* Install [CircleCI Cli](https://circleci.com/docs/2.0/local-cli/)
* ```circleci build --job behat```
* ```circleci build --job code-sniffer```
* ```circleci build --job code-coverage```

Locally run tests will have notices that creating a local artifact 
is not supported. This is a limitation to the circleci cli. This
is not any of the Drupal tests failing.

e.g - 
```Error: Failed uploading test results directory
   Error &errors.errorString{s:"not supported"}
```

### Updating Drupal Core

This project will attempt to keep all of your Drupal Core files up-to-date; the 
project [drupal-composer/drupal-scaffold](https://github.com/drupal-composer/drupal-scaffold) 
is used to ensure that your scaffold files are updated every time drupal/core is 
updated. If you customize any of the "scaffolding" files (commonly .htaccess), 
you may need to merge conflicts if any of your modified files are updated in a 
new release of Drupal core.

Follow the steps below to update your core files.

1. Run `composer update drupal/core webflo/drupal-core-require-dev symfony/* --with-dependencies` to update Drupal Core and its dependencies.
1. Run `git diff` to determine if any of the scaffolding files have changed. 
   Review the files for any changes and restore any customizations to 
  `.htaccess` or `robots.txt`.
1. Commit everything all together in a single commit, so `web` will remain in
   sync with the `core` when checking out branches or running `git bisect`.
1. In the event that there are non-trivial conflicts in step 2, you may wish 
   to perform these steps on a branch, and use `git merge` to combine the 
   updated core files with your customized files. This facilitates the use 
   of a [three-way merge tool such as kdiff3](http://www.gitshah.com/2010/12/how-to-setup-kdiff-as-diff-tool-for-git.html). This setup is not necessary if your changes are simple; 
   keeping all of your modifications at the beginning or end of the file is a 
   good strategy to keep merges easy.

## Code Style

_Pending_

Your bug fix or feature addition won't be rejected if it runs afoul of any (or all) of these guidelines, but following the guidelines will definitely make everyone's lives a little easier.

## Legalese

Before submitting a pull request to this repository for the first time, you'll need to sign a [Developer Certificate of Origin](https://developercertificate.org) (DCO). To read and agree to the DCO, you'll add your name and email address to [CONTRIBUTORS.md][contributors]. At a high level, this tells us that you have the right to submit the work you're contributing in your pull request and says that you consent to us treating the contribution in a way consistent with the license associated with this software (as described in [LICENSE.md][license]) and its documentation ("Project").

You may submit contributions anonymously or under a pseudonym if you'd like, but we need to be able to reach you at the email address you provide when agreeing to the DCO. Contributions you make to this public Department of Defense repository are completely voluntary. When you submit a pull request, you're offering your contribution without expectation of payment and you expressly waive any future pay claims against the U.S. Federal Government related to your contribution.

## FAQ

### Should I commit the contrib modules I download?

Composer recommends **no**. They provide [argumentation against but also 
workrounds if a project decides to do it anyway](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).

### Should I commit the scaffolding files?

The [drupal-scaffold](https://github.com/drupal-composer/drupal-scaffold) plugin can download the scaffold files (like
index.php, update.php, …) to the web/ directory of your project. If you have not customized those files you could choose
to not check them into your version control system (e.g. git). If that is the case for your project it might be
convenient to automatically run the drupal-scaffold plugin after every install or update of your project. You can
achieve that by registering `@drupal-scaffold` as post-install and post-update command in your composer.json:

```json
"scripts": {
    "drupal-scaffold": "DrupalComposer\\DrupalScaffold\\Plugin::scaffold",
    "post-install-cmd": [
        "@drupal-scaffold",
        "..."
    ],
    "post-update-cmd": [
        "@drupal-scaffold",
        "..."
    ]
},
```
### How can I apply patches to downloaded modules?

If you need to apply patches (depending on the project being modified, a pull 
request is often a better solution), you can do so with the 
[composer-patches](https://github.com/cweagans/composer-patches) plugin.

To add a patch to drupal module foobar insert the patches section in the extra 
section of composer.json:
```json
"extra": {
    "patches": {
        "drupal/foobar": {
            "Patch description": "URL or local path to patch"
        }
    }
}
```

[contributors]: https://github.com/Bixal/move.mil/blob/master/CONTRIBUTORS.md
[issues]: https://github.com/Bixal/move.mil/issues
[license]: https://github.com/Bixal/move.mil/blob/master/LICENSE.md
[pulls]: https://github.com/Bixal/move.mil/pulls
[makefile]: https://github.com/Bixal/move.mil/blob/master/Makefile
