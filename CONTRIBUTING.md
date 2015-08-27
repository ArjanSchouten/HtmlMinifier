# Contribution guide
**Contributions are always welcome!**
Please rather create a pull request with a fix, or if you can't fix it a pull request with a failing test, than an issue!

### Coding style
For pull requests please follow the [PSR-2](http://www.php-fig.org/psr/psr-2/) and [PSR-1](http://www.php-fig.org/psr/psr-1/) standards.

### Testing
Always test you're contributions on errors by running the unit test suite with ```vendor/bin/phpunit```. It isn't a goal of the maintainer to achieve 100% code coverage. However every vital and complex part should be tested. 
In the case of this package that means that every Minifier and Placeholder strategy must be tested. The regexes in it are rather complex. When bugs are found we've to ensure that the old cases are still working.
Therefore add an unit test to you're pull request if you think it's needed based on the described situation above!

All unit tests are runned on every pull request by [Travis-CI](https://travis-ci.org/ArjanSchouten/HtmlMinifier). Make sure the tests succeed! Travis is testing on both PHP and HHVM! Both have to succeed!
