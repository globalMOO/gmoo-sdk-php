<?php

namespace GlobalMoo\Request;

use GlobalMoo\Inverse;

final readonly class SuggestInverse extends AbstractRequest
{

    public function __construct(public int $objectiveId)
    {
    }

    public function getUrl(): string
    {
        return sprintf('objectives/%d/suggest-inverse', $this->objectiveId);
    }

    public function getType(): string
    {
        return Inverse::class;
    }

}
