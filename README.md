in-app purchase validator
============================

**iap-validator** is a php composer package to validate in-app purchase
receipts received from iTunes App Store. Also, this package provides easy
access to all the information and details of purchase identified by provided
receipt key.

**iap-validator** uses [Guzzle Http Client](https://github.com/guzzle/guzzle)
in background to make Http request to the iTunes App Store server.


## Requirement
- PHP >= 5.6
- Guzzle Http Client >= 6.3

_Package will automatically install **Guzzle Http Client** for your convenience_


## Installation
We recommend you to install **iap-validator** through Composer. Move to
https://getcomposer.org for detail instructions on installing and using
composer or run the following command on your terminal.

```bash
curl -sS https://getcomposer.org/installer | php
```

Run Composer command to install **iap-validator**

```bash
composer require raazpuspa/iap-validator
```

If you have not linked your composer installer to your bin path, checkout to
your directory consisting *composer.phar* and run composer with php.

```bash
php composer.phar require raazpuspa/iap-validator
```

For smooth running, you need to require Composer's autoloder:

```php
require 'vendor/autoload.php'
```

To pull latest update of **iap-validator** use composer update:

```bash
composer update
```

OR

```bash
composer.phar update
```


## Usage
Its too easy to use **iap-validator**. You just need to place single `use`
statement on your related file to include the package.

```php
use RaazPuspa\IAPValidator\iTunes\IAPValidator;
```

Next, initialize an object of `IAPValidator` class to access any provided
methods.

```php
$iapValidator = new IAPValidator();
```

We extract your application's secured secret key used to make Http requests to
iTunes App Store from your `.env` file. Set `IAP_ITUNES_SECRET=<your secured
secret key>` in your `.env` file.

Two constant values are provided for easy selection of server endpoint.

```php
const PRODUCTION_ENDPOINT = 'https://buy.itunes.apple.com/verifyReceipt';
const SANDBOX_ENDPOINT = 'https://sandbox.itunes.apple.com/verifyReceipt';
```


#### Example

```php
# import validator class
use RaazPuspa\IAPValidator\iTunes\IAPValidator;

# initialize new validator class instance
$iapValidator = new IAPValidator();

# Set server end-point for instance of IAPValidator class.
# Choose one from the two provided end-point constants. Select production
# end-point for live app while sandbox end-point during testing
$iapValidator->setEndPoint($iapValidator::PRODUCTION_ENDPOINT);

# Validates provided data and returns validation receipt.
# @param $receiptData string base64 encoded purchase receipt from App Store
# @param $endPoint string server end-point (optional, but is required if you
# had not set it earlier)
$response = $iapValidator->validateReceipt($receipt, $endPoint);

# get validation status code
$statusCode = $response->getStatusCode();

# if validation is successful, you can get receipt information with following
# method calls
# get current app environment upon which validation is performed
$statusCode = $response->getEnvironment();

# get just the receipt object
$receipt = $response->getReceipt();

# get in-app product information
$inApp = $response->getInApp();

# get latest receipt information
$latestReceiptInfo = $response->getLatestReceiptInfo();

# get latest base64 encoded receipt string
$latestReceipt = $response->getLatestReceipt();

# get pending renewal information if product is renewable/subscription based
$pendingRenewalInfo = $response->getPendingRenewalInfo();
```
