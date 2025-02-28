<?php

namespace GlobalMoo\Request;

use GlobalMoo\Objective;

final readonly class ReadObjective extends AbstractRequest
{

    public function __construct(public int $objectiveId)
    {
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getUrl(): string
    {
        return sprintf('objectives/%d', $this->objectiveId);
    }

    public function getType(): string
    {
        return Objective::class;
    }

}
