# providus-bank-api-client

[![Latest Stable Version](https://img.shields.io/github/v/release/brokeyourbike/providus-bank-api-client-php)](https://github.com/brokeyourbike/providus-bank-api-client-php/releases)
[![Total Downloads](https://poser.pugx.org/brokeyourbike/providus-bank-api-client/downloads)](https://packagist.org/packages/brokeyourbike/providus-bank-api-client)
[![Maintainability](https://api.codeclimate.com/v1/badges/850e505d6973c5ac1312/maintainability)](https://codeclimate.com/github/brokeyourbike/providus-bank-api-client-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/850e505d6973c5ac1312/test_coverage)](https://codeclimate.com/github/brokeyourbike/providus-bank-api-client-php/test_coverage)

Providus Bank API Client for PHP

## Installation

```bash
composer require brokeyourbike/providus-bank-api-client
```

## Usage

```php
use BrokeYourBike\ProvidusBank\Client;
use BrokeYourBike\ProvidusBank\Interfaces\ConfigInterface;

assert($config instanceof ConfigInterface);
assert($httpClient instanceof \GuzzleHttp\ClientInterface);

$apiClient = new Client($config, $httpClient);
$apiClient->fetchAccount('12345');
```

## Authors

- [Ivan Stasiuk](https://github.com/brokeyourbike) | [Twitter](https://twitter.com/brokeyourbike) | [LinkedIn](https://www.linkedin.com/in/brokeyourbike) | [stasi.uk](https://stasi.uk)

## License

[Mozilla Public License v2.0](https://github.com/brokeyourbike/providus-bank-api-client-php/blob/main/LICENSE)
