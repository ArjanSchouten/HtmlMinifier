# HtmlMinifier

[![Build Status](https://travis-ci.org/ArjanSchouten/HtmlMinifier.svg?branch=master)](https://travis-ci.org/ArjanSchouten/HtmlMinifier)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ArjanSchouten/HtmlMinifier/?branch=master)

This HTML minifier is currently in development. You can install this package through composer as a vcs repository. 
Currently it isn't submitted to packagist.

The goal of this package is to minify as much as possible without any performance loss for the whole application.
Minification is only done when you run a command and not on the fly. Most of the packages handles minification on the fly.

Features included (or are WIP):

 * Support for Laravel/Lumen
 * Support for Symfony
 * Support for empty composer projects
 * Attribute quote minification
 * Removing comments
 * Removing empty attributes
 * Removing redundant attributes
 * Removing unnecessary whitespaces
  
This minifier takes 3 steps:

 * Replacing critical contents with a placeholder
 * Run the minification rules
 * Restore the original content
