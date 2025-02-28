<?php

namespace GlobalMoo\Exception;

class NetworkConnectionException extends RuntimeException
{

    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('A network error occurred when attempting to connect to the globalMOO API server.', 500, $previous);
    }

}
