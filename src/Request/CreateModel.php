<?php

namespace GlobalMoo\Request;

use GlobalMoo\Model;

final readonly class CreateModel extends AbstractRequest
{

    public function __construct(
        public string $name,
        public string $description,
    )
    {
    }

    public function getUrl(): string
    {
        return 'models';
    }

    public function getType(): string
    {
        return Model::class;
    }

    /**
     * @return array<string, ?string>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

}
