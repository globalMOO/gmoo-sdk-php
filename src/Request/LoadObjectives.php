<?php

namespace GlobalMoo\Request;

use GlobalMoo\Objective;
use GlobalMoo\ObjectiveType;

final readonly class LoadObjectives extends AbstractRequest
{

    /**
     * @param list<int|float> $objectives
     * @param list<ObjectiveType> $objectiveTypes
     * @param list<int|float> $minimumBounds
     * @param list<int|float> $maximumBounds
     * @param list<int|float> $initialInput
     * @param list<int|float> $initialOutput
     */
    public function __construct(
        public int $trialId,
        public float $desiredL1Norm,
        public array $objectives,
        public array $objectiveTypes,
        public array $minimumBounds,
        public array $maximumBounds,
        public array $initialInput,
        public array $initialOutput,
    )
    {
    }

    /**
     * @param list<int|float> $objectives
     * @param list<int|float> $initialInput
     * @param list<int|float> $initialOutput
     */
    public static function createExact(
        int $trialId,
        float $desiredL1Norm,
        array $objectives,
        array $initialInput,
        array $initialOutput,
    ): self
    {
        $count = count($objectives);

        $exactTypes = array_fill(
            0, $count, ObjectiveType::Exact
        );

        $minMaxBounds = array_fill(
            0, $count, 0.0
        );

        $request = new self(...[
            'trialId' => $trialId,
            'desiredL1Norm' => $desiredL1Norm,
            'objectives' => $objectives,
            'objectiveTypes' => $exactTypes,
            'minimumBounds' => $minMaxBounds,
            'maximumBounds' => $minMaxBounds,
            'initialInput' => $initialInput,
            'initialOutput' => $initialOutput,
        ]);

        return $request;
    }

    public function getUrl(): string
    {
        return sprintf('trials/%d/objectives', $this->trialId);
    }

    public function getType(): string
    {
        return Objective::class;
    }

    /**
     * @return array<string, float|list<int|float>|list<ObjectiveType>>
     */
    public function toArray(): array
    {
        return [
            'desiredL1Norm' => $this->desiredL1Norm,
            'objectives' => $this->objectives,
            'objectiveTypes' => $this->objectiveTypes,
            'minimumBounds' => $this->minimumBounds,
            'maximumBounds' => $this->maximumBounds,
            'initialInput' => $this->initialInput,
            'initialOutput' => $this->initialOutput,
        ];
    }

}
