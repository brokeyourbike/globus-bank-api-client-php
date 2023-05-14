# globus-bank-api-client

[![Latest Stable Version](https://img.shields.io/github/v/release/brokeyourbike/globus-bank-api-client-php)](https://github.com/brokeyourbike/globus-bank-api-client-php/releases)
[![Total Downloads](https://poser.pugx.org/brokeyourbike/globus-bank-api-client/downloads)](https://packagist.org/packages/brokeyourbike/globus-bank-api-client)
[![Maintainability](https://api.codeclimate.com/v1/badges/b6e3a44954f709e35158/maintainability)](https://codeclimate.com/github/brokeyourbike/globus-bank-api-client-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/b6e3a44954f709e35158/test_coverage)](https://codeclimate.com/github/brokeyourbike/globus-bank-api-client-php/test_coverage)

Globus Bank API Client for PHP

## Installation

```bash
composer require brokeyourbike/globus-bank-api-client
```

## Usage

```php
use BrokeYourBike\GlobusBank\Client;
use BrokeYourBike\GlobusBank\Interfaces\ConfigInterface;

assert($config instanceof ConfigInterface);
assert($httpClient instanceof \GuzzleHttp\ClientInterface);

$apiClient = new Client($config, $httpClient);
$apiClient->generateToken();
```

## Authors
- [Ivan Stasiuk](https://github.com/brokeyourbike) | [Twitter](https://twitter.com/brokeyourbike) | [LinkedIn](https://www.linkedin.com/in/brokeyourbike) | [stasi.uk](https://stasi.uk)

## License
[Mozilla Public License v2.0](https://github.com/brokeyourbike/globus-bank-api-client-php/blob/main/LICENSE)
