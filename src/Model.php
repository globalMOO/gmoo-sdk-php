<?php

namespace GlobalMoo;

final readonly class Model
{

    /**
     * @param list<Project> $projects
     */
    public function __construct(
        public int $id,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $disabledAt,
        public string $name,
        public ?string $description,
        public array $projects = [],
    )
    {
    }

}
