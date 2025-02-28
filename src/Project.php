<?php

namespace GlobalMoo;

final class Project
{

    /**
     * @param list<int|float> $minimums
     * @param list<int|float> $maximums
     * @param list<InputType> $inputTypes
     * @param list<list<string>> $categories
     * @param list<list<int|float>> $inputCases
     * @param list<Trial> $trials
     */
    public function __construct(
        public int $id,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $disabledAt,
        public ?\DateTimeImmutable $developedAt,
        public string $name,
        public int $inputCount,
        public array $minimums,
        public array $maximums,
        public array $inputTypes,
        public array $categories,
        public array $inputCases,
        public int $caseCount,
        public array $trials = [],
    )
    {
    }

}
