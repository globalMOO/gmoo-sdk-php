<?php

namespace GlobalMoo\Request;

abstract readonly class AbstractRequest implements RequestInterface
{

    public function getMethod(): string
    {
        return 'POST';
    }

    public function toArray(): ?array
    {
        return null;
    }

}
