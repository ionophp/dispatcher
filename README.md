# Iono\Dispatcher

[![Build Status](https://travis-ci.org/ionophp/dispatcher.svg?branch=master)](https://travis-ci.org/ionophp/dispatcher)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c7d9d3bdfe22478c8872ed92d3d65359)](https://www.codacy.com/app/ionophp/dispatcher?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ionophp/dispatcher&amp;utm_campaign=Badge_Grade)
[![Coverage Status](https://coveralls.io/repos/github/ionophp/dispatcher/badge.svg?branch=master)](https://coveralls.io/github/ionophp/dispatcher?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ionophp/dispatcher/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ionophp/dispatcher/?branch=master)

[![StyleCI](https://styleci.io/repos/77292193/shield?branch=master)](https://styleci.io/repos/77292193)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/7ffbec4a-8730-4986-a839-e99378bbd6e6/mini.png)](https://insight.sensiolabs.com/projects/7ffbec4a-8730-4986-a839-e99378bbd6e6)

PHP flux pattern dispatcher library(facebook style)  
Dispatcher is used to broadcast payloads to registered callbacks  
pub-sub style

## install
the package to your composer.json and run composer update.
```json
"require": {
    "php": ">=7.0.0",
    "ionophp/dispatcher": "0.*"
},
```

## usage
### instance
```php
require __DIR__ . "/../vendor/autoload.php";

$dispatcher = new \Iono\Dispatcher\Dispatcher();
```
### register
```php
$id = $dispatcher->register(
    function ($payload) {
        return $payload["actionoType"];
    }
);
```

### dispatch the payload

```php
$dispatcher->dispatch([
    "actionType" => 'update',
]);
```

### unregister

```php
$id = $dispatcher->register(
    function () {
        return "testing";
    }
);
$dispatcher->unregister($id);
```
