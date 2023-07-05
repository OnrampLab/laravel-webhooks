# laravel-webhooks

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![CircleCI](https://circleci.com/gh/OnrampLab/laravel-webhooks.svg?style=shield)](https://circleci.com/gh/OnrampLab/laravel-webhooks)
[![Total Downloads](https://img.shields.io/packagist/dt/onramplab/laravel-webhooks.svg?style=flat-square)](https://packagist.org/packages/onramplab/laravel-webhooks)

The Laravel Webhook Package simplifies webhook dispatching in Laravel applications. It allows users to easily trigger webhooks, customize payload, set exclusion criteria, handle retries, and enhance security.


## Requirements
- PHP >= 8.0;
- composer.

## Features

Key features of the package include:

- **Webhook dispatching**: Seamlessly dispatch webhooks to external services or perform custom actions based on events in your Laravel application.

- **Customizable payload**: Empower users to define their own webhook payload, allowing them to include relevant data and tailor it to the requirements of the receiving service.

- **Exclusion criteria**: Enable users to set exclusion criteria to selectively prevent certain events or conditions from triggering webhooks. This provides flexibility in determining which events should dispatch webhooks.

- **Retry mechanism**: Implement a configurable retry mechanism to handle webhook dispatch failures, ensuring reliable delivery even in the face of temporary issues.

- **Security options**: Incorporate optional authentication mechanisms such as headers or secrets to secure the webhooks and ensure they are only triggered by trusted sources.

- **Dynamic Custom Fields**
## Installation

```bash
composer require onramplab/laravel-webhooks
```

This will create a basic project structure for you:

* **/build** is used to store code coverage output by default;
* **/src** is where your codes will live in, each class will need to reside in its own file inside this folder;
* **/tests** each class that you write in src folder needs to be tested before it was even "included" into somewhere else. So basically we have tests classes there to test other classes;
* **.gitignore** there are certain files that we don't want to publish in Git, so we just add them to this fle for them to "get ignored by git";
* **CHANGELOG.md** to keep track of package updates;
* **CONTRIBUTION.md** Contributor Covenant Code of Conduct;
* **LICENSE** terms of how much freedom other programmers is allowed to use this library;
* **README.md** it is a mini documentation of the library, this is usually the "home page" of your repo if you published it on GitHub and Packagist;
* **composer.json** is where the information about your library is stored, like package name, author and dependencies;
* **phpunit.xml** It is a configuration file of PHPUnit, so that tests classes will be able to test the classes you've written;
* **.travis.yml** basic configuration for Travis CI with configured test coverage reporting for code climate.

Please refer to original [article](http://www.darwinbiler.com/creating-composer-package-library/) for more information.

## Useful Tools

## Running Tests:

    php vendor/bin/phpunit

 or

    composer test

## Code Sniffer Tool:

    php vendor/bin/phpcs --standard=PSR2 src/

 or

    composer psr2check

## Code Auto-fixer:

    composer psr2autofix
    composer insights:fix
    rector:fix

## Building Docs:

    php vendor/bin/phpdoc -d "src" -t "docs"

 or

    composer docs

## Changelog

To keep track, please refer to [CHANGELOG.md](https://github.com/onramplab/laravel-webhooks/blob/master/CHANGELOG.md).

## Contributing

1. Fork it.
2. Create your feature branch (git checkout -b my-new-feature).
3. Make your changes.
4. Run the tests, adding new ones for your own code if necessary (phpunit).
5. Commit your changes (git commit -am 'Added some feature').
6. Push to the branch (git push origin my-new-feature).
7. Create new pull request.

Also please refer to [CONTRIBUTION.md](https://github.com/onramplab/laravel-webhooks/blob/master/CONTRIBUTION.md).

## License

Please refer to [LICENSE](https://github.com/onramplab/laravel-webhooks/blob/master/LICENSE).
