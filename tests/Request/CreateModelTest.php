<?php

namespace GlobalMoo\Tests\Request;

use GlobalMoo\Model;
use GlobalMoo\Request\CreateModel;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
final class CreateModelTest extends TestCase implements RequestTestInterface
{

    public function testGettingMethod(): void
    {
        $this->assertEquals('POST', $this->createRequest()->getMethod());
    }

    public function testGettingUrl(): void
    {
        $this->assertEquals('models', $this->createRequest()->getUrl());
    }

    public function testGettingType(): void
    {
        $this->assertEquals(Model::class, $this->createRequest()->getType());
    }

    public function testToArray(): void
    {
        $model = [
            'name' => 'Simple Test Model',
            'description' => 'Testing a simple linear model',
        ];

        $this->assertSame($model, (new CreateModel(...$model))->toArray());
    }

    private function createRequest(): CreateModel
    {
        $request = new CreateModel(...[
            'name' => 'Simple Test Model',
            'description' => 'Testing a simple linear model',
        ]);

        return $request;
    }

}
