<?php

namespace GlobalMoo;

final readonly class Event
{

    public function __construct(
        public int $id,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $disabledAt,
        public EventName $name,
        public ?string $subject,
        public Project|Objective|Inverse $data,
    )
    {
    }

}
