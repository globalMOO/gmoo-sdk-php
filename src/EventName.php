<?php

namespace GlobalMoo;

enum EventName: string
{

    case ProjectCreated = 'project.created';
    case ObjectivesLoaded = 'objectives.loaded';
    case InverseSuggested = 'inverse.suggested';
    case InverseOutputLoaded = 'inverse.output_loaded';

    public function dataType(): string
    {
        $dataType = match($this) {
            static::ProjectCreated => Project::class,
            static::ObjectivesLoaded => Objective::class,
            static::InverseSuggested => Inverse::class,
            static::InverseOutputLoaded => Objective::class,
        };

        return $dataType;
    }

}
