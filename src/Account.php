<?php

namespace GlobalMoo;

final readonly class Account
{

    public function __construct(
        public int $id,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $disabledAt,
        public string $company,
        public string $name,
        public string $email,
        public ?string $apiKey,
        public ?string $timeZone,
        public ?string $customerId,
    )
    {
    }

}
