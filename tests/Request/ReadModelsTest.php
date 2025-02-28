<?php

namespace GlobalMoo\Tests\Request;

use GlobalMoo\Model;
use GlobalMoo\Request\ReadModels;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class ReadModelsTest extends TestCase implements RequestTestInterface
{

    public function testGettingMethod(): void
    {
        $this->assertEquals('GET', (new ReadModels())->getMethod());
    }

    public function testGettingUrl(): void
    {
        $this->assertEquals('models', (new ReadModels())->getUrl());
    }

    public function testGettingType(): void
    {
        $type = sprintf('%s[]', Model::class);

        $this->assertEquals($type, (new ReadModels())->getType());
    }

    public function testToArray(): void
    {
        // @phpstan-ignore-next-line
        $this->assertNull((new ReadModels())->toArray());
    }

}
