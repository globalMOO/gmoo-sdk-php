<?php

namespace GlobalMoo;

final readonly class Error
{

    /**
     * @param list<array<string>> $errors
     */
    public function __construct(
        public int $status,
        public string $title,
        public string $message,
        public array $errors = [],
    )
    {
    }

}
