# CakePHP Timezone Plugin

[![Build Status](https://travis-ci.org/JeanValJeann/cakephp-timezone.svg?branch=master)](https://travis-ci.org/JeanValJeann/cakephp-timezone)
[![codecov](https://codecov.io/gh/JeanValJeann/cakephp-timezone/branch/master/graph/badge.svg)](https://codecov.io/gh/JeanValJeann/cakephp-timezone)
[![Latest Stable Version](https://poser.pugx.org/jeanvaljean/cakephp-timezone/v/stable)](https://packagist.org/packages/jeanvaljean/cakephp-timezone)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/jeanvaljean/cakephp-timezone/license)](https://packagist.org/packages/jeanvaljean/cakephp-timezone)
[![Total Downloads](https://poser.pugx.org/jeanvaljean/cakephp-timezone/downloads)](https://packagist.org/packages/jeanvaljean/cakephp-timezone)

A plugin to handle timezone and datetime.

This branch is for CakePHP 3.6+.

## What is this plugin for?

Easy Timezone and Datetime handling.

## Background

It's common to deal with timezone and datetime when you want your application to be able to:

- Store basic datetime from all client timezone possible.
- Store futur datetime and its own timezone related to from all client timezone possible.
- Display basic datetime to all client timezone possible.
- Display futur datetime and its own timezone.
- Compare datetime.

To do it, Timezone Plugin provides:

- A middleware detecting client timezone.
- A behavior converting datetime data from one timezone to another one.
- An herlper able to display one timezone name and its offset, get an array list of all timezone identifier, display a datetime input text, display a timezone select.

## Installation & Docs

* [Documentation](docs/README.md)

