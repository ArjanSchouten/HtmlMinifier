> This package is still in Beta version! Use it with care!

# HtmlMinifier

[![Build Status](https://travis-ci.org/ArjanSchouten/HtmlMinifier.svg?branch=master)](https://travis-ci.org/ArjanSchouten/HtmlMinifier)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/?branch=master)

## Installation
Let composer do the hard work for us!
```php
composer require arjanschouten/htmlminifier
```
#### Laravel 5.*
A Laravel package based on this minifier can be installed by running:
```php
composer require arjanschouten/laravelhtmlminifier
```

#### Plain php projects with composer
If you're not using a php framework you can use the minifier by using the code below:
```php
//include the composer autoloader
require __DIR__ . '/vendor/autoload.php';

// create a minify context which will be used through the minification process
$context = new MinifyContext(new PlaceholderContainer());
// save the html contents in the context
$context->setContents('<html>My html...</html>');
$minify = new Minify();
// start the process and give the context with it as parameter
$context = $minify->run($context);

// $context now contains the minified version
$minifiedContents = $context->getContents();

```

## Options
This minifier have some minification options which are:

| Minification strategy                                         | Option Name             | Enabled by default  |
|---------------------------------------------------------------|-------------------------| --------------------|
| Remove redundant whitespaces                                  | whitespaces             | yes                 |
| Remove comments                                               | comments                | yes                 |
| Collapse boolean attributes from checked="checked" to checked | boolean-attributes      | yes                 |
| Remove quotes around html attributes                          | remove-attributequotes  | no                  |
| Remove optional elements which can be implied by the browser  | optional-elements       | no                  |
| Remove defaults such as from ```<script type=text/javascript>```    | remove-defaults         | no                  |
| Remove empty attributes. HTML boolean attributes are skipped  | remove-empty-attributes | no                  |

You can enable the various minification options for example with:
```php
...
$options = [
  'whitespace' => false,
  'remove-defaults' => true,
];

$minify->run($context, $options);
```
This will disable ```whitespace``` and enables ```remove-defaults```.

### Contributing
**Contributions are welcome**. Please read the [CONTRIBUTING.md readme](https://github.com/ArjanSchouten/HtmlMinifier/blob/master/CONTRIBUTING.md).

### Testing
HtmlMinifier uses ```phpunit``` for testing. You can run the tests with ```vendor/bin/phpunit ```.

### License
This package is licensed under the [MIT License](https://github.com/ArjanSchouten/HtmlMinifier/blob/master/LICENSE).

### Creator and Maintainer
This package is created and maintained by [Arjan Schouten](http://arjan-schouten.nl).
