# Se34 Tests Project

Se34 is a library of some handy classes for system testing of web applications.

This project provides test suite for that library, because only a moron would use an untested testing tool.

## Instalation

If you want to run these tests, follow following instructions. If you want just the library, go to [Clevisaci/Se34](https://github.com/Clevisaci/Se34).

1. Clone this project: `git clone git@github.com:VaclavSir/Se34-tests.git`
2. It should initialize the Se34 submodule also. If not, then it's probably time to upgrade your Git.
3. Use [Composer](http://getcomposer.org/) to download all other dependencies: `composer update --dev`
4. Finally, grant your web server write access to these three directories: `log`, `temp`, `temp/sessions`

## Why the submodule?

Composer is usually a better choice over submodules. If you plan to use Se34, I suggest you to install it with Composer.

But I'm using this project for Se34 development and my workflow would be slightly more complicated with Composer. That's why.

## How to download updates

	git pull
	composer update --dev
	cd libs/Se34
	git pull

## Stucture of the project

Basically it is a simple web application that uses [Nette Framework](http://nette.org/). It contains tests that use this application to check certain features of the Se34 library.

If you don't know Nette Framework yet, it is something like Symfony or Zend Framework, not so well known, but better on some points. Actually, this repository is a fork of [Nette Framework Sandbox](https://github.com/nette/sandbox), although I'm still not fully convinced it was a good idea. We'll see.

Description of top-level directories:

- **app** - application code.
- **libs** - third-party libraries.
- **log, temp** - temporary files.
- **www** - web application root, with static files and the front controller (ie. index.php).
- **tests** - tests written for [Nette/Tester](https://github.com/nette/tester) tool (none so far, but I plan to write some).
- **tests-phpunit** - tests written for PHPUnit.
