<?php

require_once __DIR__ . '/../vendor/autoload.php';

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
