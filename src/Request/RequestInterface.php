<?php

namespace GlobalMoo\Request;

interface RequestInterface
{

    public function getMethod(): string;
    public function getUrl(): string;
    public function getType(): string;

    /**
     * @return ?array<string, mixed>
     */
    public function toArray(): ?array;

}
