# globalMOO SDK for PHP
This SDK makes it easy for PHP developers to integrate with the globalMOO API.

## Getting Started
1. **Create an account** To start, create a new account with [globalMOO](https://globalmoo.com/free-trial/)
   which will provide you with your API key.
2. **Install the SDK** Next, install this SDK on your machine with the following Composer command:
   ```shell
   composer require globalmoo/globalmoo-sdk
   ```
   You will need PHP 8.4 compiled with the `curl` and `json` extensions.
3. **Configure credentials** The SDK depends on two environment variables to exist
    in the `$_ENV` superglobal: `GMOO_API_KEY` and `GMOO_API_URI`.

## Quick Examples
The `php` directory of the [gmoo-sdk-suite](https://github.com/globalMOO/gmoo-sdk-suite)
contains several complete examples on how to integrate with the SDK. Follow the instruction
in the [README](https://github.com/globalMOO/gmoo-sdk-suite/tree/main/php#readme) on how
to get started with it.

### Create a Model
```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use GlobalMoo\Client;
use GlobalMoo\Exception\ExceptionInterface;
use GlobalMoo\Exception\InvalidRequestException;
use GlobalMoo\Request\CreateModel;

try {
    $gmooClient = new Client();

    $createModelRequest = new CreateModel(...[
        'name' => 'Linear Example - v1.0.0',
        'description' => 'Created using the globalMOO PHP SDK',
    ]);

    $model = $gmooClient->createModel(...[
        'request' => $createModelRequest,
    ]);

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
