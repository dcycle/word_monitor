Word Monitor
=====

[![CircleCI](https://circleci.com/gh/dcycle/word_monitor.svg?style=svg)](https://circleci.com/gh/dcycle/word_monitor)

Allows you to define a list of words or expressions and be warned if one of them
appears on your site. For example, if you refer to a website using an outdated
name, you can periodically be warned if that name appears anywhere in your site.

Typical usage
-----

(1) install and enable word_monitor.

(2) enable these included modules:

* **word_monitor**, which provides basic, behind-the-scenes functionality;
* **word_monitor_search**, which integrates word_monitor with core search and
  searches for entities;
* **word_monitor_status_warning**, which will provide a warning on the
  /admin/reports/status page if a word or expression appears somewhere on your site.

(3) go to /admin/content/word and enter a list of banned words.

(4) you should now see a list of entities containing banned words at
/admin/content/word, and if there are any entities in that list,
/admin/reports/status will contain a warning.

If you are not seeing entities, you need to make sure the site is completely
indexed at /admin/config/search/pages

Extending this module
-----

This module can be extended via the Drupal plugin system. Developers are
encouraged to examine the structure of the included word_monitor_search
submodule as a basis for their own extensions. Suggestions for more modules are
welcome via the Drupal issue queue.

Local development
-----

If you install Docker on your computer:

* you can set up a complete local development workspace by downloading this
  codebase and running `./scripts/deploy.sh`. To test with Drupal 9, use
  `./scripts/deploy.sh 9`. You do not need a separate Drupal instance.
  `./scripts/uli.sh` will provide you with a login link to your environment.
* you can destroy your local environment by running `./scripts/destroy.sh`.
* you can run all tests by running `./scripts/ci.sh`; please make sure all tests
  before submitting a patch.

Automated testing
-----

This module's main page is on
[Drupal.org](http://drupal.org/project/word_monitor).
A mirror is kept on [GitHub](http://github.com/dcycle/word_monitor).

Unit tests are performed on Drupal.org's infrastructure and in GitHub using
CircleCI. Linting is performed on GitHub using CircleCI and Drupal.org.
For details please see
[Start unit testing your Drupal and other PHP code today, Oct 16, 2019, Dcycle
Blog](https://blog.dcycle.com/blog/2019-10-16/unit-testing/).

* [Test results on Drupal.org's testing infrastructure](https://www.drupal.org/project/word_monitor)
* [Test results on CircleCI](https://circleci.com/gh/dcycle/word_monitor)

To run automated tests locally, install Docker and type:

    ./scripts/ci.sh

Drupal 9 readiness
-----

This module is Drupal 9-compatible.
