<?php

namespace GlobalMoo\Exception;

class InvalidResponseException extends RuntimeException
{

    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('An error occurred when attempting to decode the response from the globalMOO API.', 500, $previous);
    }

}
