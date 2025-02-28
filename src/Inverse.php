<?php

namespace GlobalMoo;

final readonly class Inverse
{

    /**
     * @param list<int|float> $input
     * @param list<int|float> $output
     * @param list<Result> $results
     */
    public function __construct(
        public int $id,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $disabledAt,
        public ?\DateTimeImmutable $loadedAt,
        public ?\DateTimeImmutable $satisfiedAt,
        public ?\DateTimeImmutable $stoppedAt,
        public ?\DateTimeImmutable $exhaustedAt,
        public int $iteration,
        public array $input,
        public array $output,
        public float $l1Norm,
        public int $suggestTime,
        public int $computeTime,
        public array $results = [],
    )
    {
    }

    public function getStopReason(): StopReason
    {
        if (null !== $this->satisfiedAt) {
            return StopReason::Satisfied;
        }

        if (null !== $this->stoppedAt) {
            return StopReason::Stopped;
        }

        if (null !== $this->exhaustedAt) {
            return StopReason::Exhausted;
        }

        return StopReason::Running;
    }

    public function shouldStop(): bool
    {
        return $this->getStopReason()->shouldStop();
    }

}
