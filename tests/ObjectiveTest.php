<?php

namespace GlobalMoo\Tests;

use GlobalMoo\Inverse;
use GlobalMoo\Objective;
use GlobalMoo\StopReason;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
final class ObjectiveTest extends TestCase
{

    public function testGettingIterationCountProperty(): void
    {
        $objective = $this->createObjective(
            $inverseCount = random_int(1, 10)
        );

        $this->assertEquals($inverseCount, $objective->iterationCount);
    }

    public function testGettingLastInverseRequiresNonEmptyList(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The last inverse could not be found because the list is empty.');

        // Implicitly Call __get('lastInverse')
        $this->createObjective(0)->lastInverse;
    }

    public function testGettingLastInverse(): void
    {
        $objective = $this->createObjective(...[
            'inverseCount' => random_int(1, 10)
        ]);

        $lastInverse = $objective->inverses[
            array_key_last($objective->inverses)
        ];

        $this->assertSame($lastInverse, $objective->lastInverse);
    }

    public function testGettingInvalidProperty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The property "invalid" does not exist.');

        // @phpstan-ignore-next-line
        $this->createObjective(4)->invalid;
    }

    private function createObjective(int $inverseCount = 0): Objective
    {
        $stopReason = StopReason::Satisfied;
        $createdAt = new \DateTimeImmutable();

        $inverses = array_fill(0, $inverseCount, new Inverse(...[
            'id' => random_int(1, 100),
            'createdAt' => $createdAt,
            'updatedAt' => $createdAt,
            'disabledAt' => null,
            'loadedAt' => null,
            'satisfiedAt' => null,
            'stoppedAt' => null,
            'exhaustedAt' => null,
            'iteration' => 1,
            'input' => [],
            'output' => [],
            'l1Norm' => 0.0,
            'suggestTime' => 0,
            'computeTime' => 0,
        ]));

        $objective = new Objective(...[
            'id' => random_int(1, 100),
            'createdAt' => $createdAt,
            'updatedAt' => $createdAt,
            'disabledAt' => null,
            'optimalInverse' => null,
            'number' => 1,
            'attemptCount' => 100,
            'stopReason' => $stopReason,
            'desiredL1Norm' => 0.0,
            'objectives' => [],
            'objectiveTypes' => [],
            'minimumBounds' => [],
            'maximumBounds' => [],
            'inverses' => $inverses,
        ]);

        return $objective;
    }

}
