<?php

namespace GlobalMoo\Tests\Request;

use GlobalMoo\Model;
use GlobalMoo\Request\ReadModel;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class ReadModelTest extends TestCase implements RequestTestInterface
{

    public function testGettingMethod(): void
    {
        $this->assertEquals('GET', $this->createRequest()->getMethod());
    }

    public function testGettingUrl(): void
    {
        $modelId = random_int(1, 1000);

        $request = new ReadModel(...[
            'modelId' => $modelId,
        ]);

        $url = "models/{$modelId}";

        $this->assertEquals($url, $request->getUrl());
    }

    public function testGettingType(): void
    {
        $this->assertEquals(Model::class, $this->createRequest()->getType());
    }

    public function testToArray(): void
    {
        $this->assertNull($this->createRequest()->toArray());
    }

    private function createRequest(): ReadModel
    {
        $request = new ReadModel(...[
            'modelId' => random_int(1, 1000)
        ]);

        return $request;
    }

}
