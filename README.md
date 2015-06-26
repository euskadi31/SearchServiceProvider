# Silex Search Service Provider

[![Build Status](https://travis-ci.org/euskadi31/SearchServiceProvider.svg?branch=master)](https://travis-ci.org/euskadi31/SearchServiceProvider)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/942ec52e-ba58-478a-b212-36715220153b/mini.png)](https://insight.sensiolabs.com/projects/942ec52e-ba58-478a-b212-36715220153b)

A Search Service Provider for Silex 2.0

## Install

Add `euskadi31/search-service-provider` to your `composer.json`:

    % php composer.phar require euskadi31/search-service-provider:~1.0

## Usage

### Configuration

```php
<?php

$app = new Silex\Application;

$app->register(new \Euskadi31\Silex\Provider\SearchServiceProvider);
```

## License

SearchServiceProvider is licensed under [the MIT license](LICENSE.md).
