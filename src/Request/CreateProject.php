<?php

namespace GlobalMoo\Request;

use GlobalMoo\Project;

final readonly class CreateProject extends AbstractRequest
{

    /**
     * @param list<int|float> $minimums
     * @param list<int|float> $maximums
     * @param list<string> $inputTypes
     * @param list<string> $categories
     */
    public function __construct(
        public int $modelId,
        public string $name,
        public int $inputCount,
        public array $minimums,
        public array $maximums,
        public array $inputTypes,
        public array $categories = [],
    )
    {
    }

    public function getUrl(): string
    {
        return sprintf('models/%d/projects', $this->modelId);
    }

    public function getType(): string
    {
        return Project::class;
    }

    /**
     * @return array<string, int|string|list<int|float|string>>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'inputCount' => $this->inputCount,
            'minimums' => $this->minimums,
            'maximums' => $this->maximums,
            'inputTypes' => $this->inputTypes,
            'categories' => $this->categories,
        ];
    }

}
