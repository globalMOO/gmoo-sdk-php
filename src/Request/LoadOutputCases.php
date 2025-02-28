<?php

namespace GlobalMoo\Request;

use GlobalMoo\Trial;

final readonly class LoadOutputCases extends AbstractRequest
{

    /**
     * @param list<list<int|float>> $outputCases
     */
    public function __construct(
        public int $projectId,
        public int $outputCount,
        public array $outputCases,
    )
    {
    }

    public function getUrl(): string
    {
        return sprintf('projects/%d/output-cases', $this->projectId);
    }

    public function getType(): string
    {
        return Trial::class;
    }

    /**
     * @return array<string, int|list<list<int|float>>>
     */
    public function toArray(): array
    {
        return [
            'outputCount' => $this->outputCount,
            'outputCases' => $this->outputCases,
        ];
    }

}
