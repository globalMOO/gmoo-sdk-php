<?php

namespace GlobalMoo\Exception;

use GlobalMoo\Error;
use GlobalMoo\Request\RequestInterface;

class InvalidRequestException extends \RuntimeException implements ExceptionInterface
{

    public function __construct(
        public readonly RequestInterface $request,
        public readonly Error $error,
    )
    {
        parent::__construct($this->error->message, $this->error->status);
    }

    /**
     * @return list<array<string>>
     */
    public function getErrors(): array
    {
        return $this->error->errors;
    }

}
