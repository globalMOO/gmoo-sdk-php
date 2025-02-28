<?php

namespace GlobalMoo;

final readonly class Result
{

    public function __construct(
        public int $id,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $disabledAt,
        public int $number,
        public float $objective,
        public ObjectiveType $objectiveType,
        public float $minimumBound,
        public float $maximumBound,
        public float $output = 0.0,
        public float $error = 0.0,
        public ?string $detail = null,
        public bool $satisfied = true,
    )
    {
    }

}
