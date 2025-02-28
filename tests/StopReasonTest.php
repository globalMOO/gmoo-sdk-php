<?php

namespace GlobalMoo\Tests;

use GlobalMoo\StopReason;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
final class StopReasonTest extends TestCase
{

    #[DataProvider('providerStopReasonAndShouldStop')]
    public function testShouldStop(StopReason $stopReason, bool $shouldStop): void
    {
        $this->assertSame($stopReason->shouldStop(), $shouldStop);
    }

    /**
     * @return list<list<bool|StopReason>>
     */
    public static function providerStopReasonAndShouldStop(): array
    {
        $provider = [
            [StopReason::Running, false],
            [StopReason::Satisfied, true],
            [StopReason::Stopped, true],
            [StopReason::Exhausted, true],
        ];

        return $provider;
    }

}
