<?php

namespace GlobalMoo\Request;

use GlobalMoo\Inverse;

final readonly class LoadInverseOutput extends AbstractRequest
{

    /**
     * @param list<int|float> $output
     */
    public function __construct(
        public int $inverseId,
        public array $output,
    )
    {
    }

    public function getUrl(): string
    {
        return sprintf('inverses/%d/load-output', $this->inverseId);
    }

    public function getType(): string
    {
        return Inverse::class;
    }

    /**
     * @return array<string, list<int|float>>
     */
    public function toArray(): array
    {
        return [
            'output' => $this->output,
        ];
    }

}
