# globalMOO SDK for PHP
This SDK makes it easy for PHP developers to integrate with the globalMOO API.

## Getting Started
1. **Create an account** To start, create a new account with [globalMOO](https://globalmoo.com/free-trial/)
   which will provide you with your API key.
2. **Install the SDK** Next, install this SDK on your machine with the following Composer command:
   ```shell
   composer require globalmoo/gmoo-sdk-php
   ```
   You will need PHP 8.3 compiled with the `curl` and `json` extensions.
3. **Configure credentials** The SDK depends on two environment variables to exist
    in the `$_ENV` superglobal: `GMOO_API_KEY` and `GMOO_API_URI`. See the `.env.dist`
    file for valid values of the `GMOO_API_URI` environment variable.

## Quick Examples
The `php` directory of the [gmoo-sdk-suite](https://github.com/globalMOO/gmoo-sdk-suite)
contains several complete examples on how to integrate with the SDK. Follow the instruction
in the [README](https://github.com/globalMOO/gmoo-sdk-suite/tree/main/php#readme) on how
to get started with it.

### Create Model
```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use GlobalMoo\Exception\ExceptionInterface;
use GlobalMoo\Exception\InvalidRequestException;
use GlobalMoo\Request\CreateModel;

try {
    // The globalMOO client requires the
    // GMOO_API_KEY and GMOO_API_URI keys
    // to present in the $_ENV superglobal
    $gmooClient = new \GlobalMoo\Client();

    $request = new \GlobalMoo\Request\CreateModel(...[
        'name' => 'Linear Regression Model - v1.0.0',
        'description' => 'Linear model with 3 input variables',
    ]);

    $model = $gmooClient->createModel($request);

    echo(sprintf("Successfully created a model with ID %d.\n", $model->id));
} catch (InvalidRequestException $e) {
    echo(sprintf("%s\n", $e->getMessage()));

    foreach ($e->error->errors as $error) {
        echo(sprintf("  %s: %s\n", $error['property'], $error['message']));
    }
} catch (ExceptionInterface $e) {
    echo(sprintf("%s\n", $e->getMessage()));
}
```
