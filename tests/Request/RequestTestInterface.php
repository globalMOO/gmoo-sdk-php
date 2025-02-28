<?php

namespace GlobalMoo\Tests\Request;

interface RequestTestInterface
{

    public function testGettingMethod(): void;
    public function testGettingUrl(): void;
    public function testGettingType(): void;
    public function testToArray(): void;

}
