<?php

namespace GlobalMoo\Request;

use GlobalMoo\Model;

final readonly class ReadModel extends AbstractRequest
{

    public function __construct(public int $modelId)
    {
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getUrl(): string
    {
        return sprintf('models/%d', $this->modelId);
    }

    public function getType(): string
    {
        return Model::class;
    }

}
