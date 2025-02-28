<?php

namespace GlobalMoo\Request;

use GlobalMoo\Model;

final readonly class ReadModels extends AbstractRequest
{

    public function __construct()
    {
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getUrl(): string
    {
        return 'models';
    }

    public function getType(): string
    {
        return Model::class . '[]';
    }

    public function toArray(): null
    {
        return null;
    }

}
