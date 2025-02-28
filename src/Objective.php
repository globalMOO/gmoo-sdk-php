<?php

namespace GlobalMoo;

/**
 * @property-read int $iterationCount
 * @property-read Inverse $lastInverse
 */
final readonly class Objective
{

    /**
     * @param list<int|float> $objectives
     * @param list<ObjectiveType> $objectiveTypes
     * @param list<int|float> $minimumBounds
     * @param list<int|float> $maximumBounds
     * @param list<Inverse> $inverses
     */
    public function __construct(
        public int $id,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $disabledAt,
        public ?Inverse $optimalInverse,
        public int $number,
        public int $attemptCount,
        public StopReason $stopReason,
        public float $desiredL1Norm,
        public array $objectives,
        public array $objectiveTypes,
        public array $minimumBounds,
        public array $maximumBounds,
        public array $inverses = [],
    )
    {
    }

    public function __get(string $name): int|Inverse
    {
        $inverseCount = count($this->inverses);

        if ('iterationCount' === $name) {
            return $inverseCount;
        }

        if ('lastInverse' === $name) {
            if (0 === $inverseCount || !array_is_list($this->inverses)) {
                throw new \RuntimeException('The last inverse could not be found because the list is empty.');
            }

            return $this->inverses[$inverseCount-1];
        }

        throw new \InvalidArgumentException(sprintf('The property "%s" does not exist.', $name));
    }

}
