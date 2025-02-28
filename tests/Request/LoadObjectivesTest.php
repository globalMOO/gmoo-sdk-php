<?php

namespace GlobalMoo\Tests\Request;

use GlobalMoo\Objective;
use GlobalMoo\ObjectiveType;
use GlobalMoo\Request\LoadObjectives;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class LoadObjectivesTest extends TestCase implements RequestTestInterface
{

    public function testCreatingExactRequest(): void
    {
        $desiredL1Norm = 0.123;
        $count = random_int(1, 10);

        $objectives = array_fill(
            0, $count, 1.173
        );

        $zeroVector = array_fill(
            0, $count, 0.0
        );

        $exactTypes = array_fill(
            0, $count, ObjectiveType::Exact
        );

        $request = LoadObjectives::createExact(...[
            'trialId' => random_int(1, 100),
            'desiredL1Norm' => $desiredL1Norm,
            'objectives' => $objectives,
            'initialInput' => $objectives,
            'initialOutput' => $objectives,
        ]);

        $this->assertSame($exactTypes, $request->objectiveTypes);
        $this->assertSame($zeroVector, $request->minimumBounds);
        $this->assertSame($zeroVector, $request->maximumBounds);
    }

    public function testGettingMethod(): void
    {
        $this->assertEquals('POST', $this->createRequest()->getMethod());
    }

    public function testGettingUrl(): void
    {
        $this->assertEquals('trials/3/objectives', $this->createRequest(3)->getUrl());
    }

    public function testGettingType(): void
    {
        $this->assertEquals(Objective::class, $this->createRequest()->getType());
    }

    public function testToArray(): void
    {
        // @phpstan-ignore-next-line
        $this->assertIsArray($this->createRequest()->toArray());
    }

    private function createRequest(?int $trialId = null): LoadObjectives
    {
        $trialId ??= random_int(1, 100);

        $request = new LoadObjectives(...[
            'trialId' => $trialId,
            'desiredL1Norm' => 0.0,
            'objectives' => [],
            'objectiveTypes' => [],
            'minimumBounds' => [],
            'maximumBounds' => [],
            'initialInput' => [],
            'initialOutput' => [],
        ]);

        return $request;
    }

}
