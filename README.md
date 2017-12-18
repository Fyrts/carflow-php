# PHP wrapper for the Carflow API

A simple library for communicating with the [Carflow](http://carflow.pro) API.

Some oddities from the original API have been changed. For example, properties are consistently CamelCased.

## Usage

Install using Composer:

`composer require fyrts/carflow-php`

Create an instance of *Carflow\Client*:

```php
$client = new Carflow\Client(Carflow\Language::DUTCH);
$vehicles = $client->getBranchVehicles($branch_id, true);
```
