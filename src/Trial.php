<?php

namespace GlobalMoo;

final readonly class Trial
{

    /**
     * @param list<list<int|float>> $outputCases
     * @param list<Objective> $objectives
     */
    public function __construct(
        public int $id,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $disabledAt,
        public int $number,
        public int $outputCount,
        public array $outputCases,
        public int $caseCount,
        public array $objectives = [],
    )
    {
    }

}
