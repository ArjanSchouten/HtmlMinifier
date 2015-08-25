> This package is still in Beta version! Use it with care!
# HtmlMinifier

[![Build Status](https://travis-ci.org/ArjanSchouten/HtmlMinifier.svg?branch=master)](https://travis-ci.org/ArjanSchouten/HtmlMinifier)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/?branch=master)

**Never lose customers because of a slow response time!**

[Research](http://www.nngroup.com/articles/response-times-3-important-limits/) has shown that the response time of a website is incredibly important. You should return a response within a second!

## Why should you use a/this html minifier?!
Html Minification can be extremely powerfull and can reduce the size of you're website drastically! This Html Minifier should be runned only once! It will minify you're templates so it's a one time process. During every request the Html Minifier isn't using  unneeded resources (also because of a defered service provider). **Finally there is no overhead, only advantages for you and your user!**

## Installation (Couldn't be easier!)
Let composer do the most work for us!
```php
composer require arjanschouten/htmlminifier dev-master
```
#### Laravel 5.1
With Laravel 5.1 you've to register the service provider in the ```config/app.php``` file and add it to the providers array:
```php
ArjanSchouten\HtmlMinifier\Laravel\HtmlMinifierServiceProvider::class
```

#### Lumen
With Lumen you've to register the service provider in the ```bootstrap/app.php``` file and add the following line:
```php
$app->register(ArjanSchouten\HtmlMinifier\Laravel\HtmlMinifierServiceProvider::class);
```

#### Plain php projects with composer
If you're not using a php framework you can use the minifier by using the code below:
```php
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

## Running
### Laravel 5 and Lumen
With Laravel 5 you can start the minification process by using ```artisan```:
```php
php artisan minify:views
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

For example running the defaults and remove-defaults with optional elements can be done with:
```php
php artisan minify:views --remove-defaults --optional-elements
```

If you want to run all minification strategies you can run:
```php 
php artisan minify:views --all
# or
php artisan minify:views -a
```

You can also do this from code by passing a second array to the run method:
```php
...
$options = [
  'whitespace' => false,
  'remove-defaults' => true,
];

$minify->run($context, $options);
```
This will disable ```whitespace``` and enables ```remove-defaults```.
