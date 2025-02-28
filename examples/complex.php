<?php

require_once __DIR__ . '/common.php';

use GlobalMoo\Client;
use GlobalMoo\InputType;
use GlobalMoo\ObjectiveType;
use GlobalMoo\Request\CreateModel;
use GlobalMoo\Request\CreateProject;
use GlobalMoo\Request\LoadInverseOutput;
use GlobalMoo\Request\LoadObjectives;
use GlobalMoo\Request\LoadOutputCases;
use GlobalMoo\Request\ReadObjective;
use GlobalMoo\Request\SuggestInverse;

// Calculate Objectives
$truthCase = [
    2.5,
    1.5,
    3.0,
    2.0,
    1.0,
];

$objectives = complexMixedFunction(...[
    'input' => $truthCase,
]);

// Model and Project Settings
$inputCount = count($truthCase);
$outputCount = count($objectives);

// Initialize Client
$client = new Client();

// 1. Create Model
$request = new CreateModel(...[
    'name' => 'Complex 1.0 - SDK',
    'description' => 'Created using the PHP SDK',
]);

$model = $client->createModel($request);

$logger->info('Model created.', [
    'modelId' => $model->id,
    'name' => $model->name,
]);

// 2. Create Project and Input Cases
$project = $client->createProject(
    new CreateProject(...[
        'modelId' => $model->id,
        'name' => 'Project #1 - SDK',
        'inputCount' => $inputCount,
        'minimums' => [
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
        ],
        'maximums' => [
            5.0,
            5.0,
            5.0,
            5.0,
            5.0,
        ],
        'inputTypes' => [
            InputType::Float,
            InputType::Float,
            InputType::Float,
            InputType::Float,
            InputType::Float,
        ]
    ])
);

$logger->info('Project created.', [
    'projectId' => $project->id,
    'inputCount' => $inputCount,
    'minimums' => $project->minimums,
    'maximums' => $project->maximums,
    'caseCount' => $project->caseCount,
]);

// 3. Calculate Output Cases
$outputCases = array_map(function(array $input): array {
    return complexMixedFunction($input);
}, $project->inputCases);

// 4. Load Output Cases
$trial = $client->loadOutputCases(
    new LoadOutputCases(...[
        'projectId' => $project->id,
        'outputCount' => $outputCount,
        'outputCases' => $outputCases,
    ])
);

$initialInput = $project->inputCases[
    array_key_last($project->inputCases)
];

$initialOutput = $trial->outputCases[
    array_key_last($trial->outputCases)
];

$logger->info('Trial created.', [
    'trialId' => $trial->id,
    'outputCount' => $trial->outputCount,
]);

// 5. Load Objectives
$objective = $client->loadObjectives(
    new LoadObjectives(...[
        'trialId' => $trial->id,
        'desiredL1Norm' => 0.0,
        'objectives' => $objectives,
        'objectiveTypes' => [
            ObjectiveType::Percent,
            ObjectiveType::Percent,
            ObjectiveType::Percent,
            ObjectiveType::Percent,
            ObjectiveType::Percent,
            ObjectiveType::Percent,
            ObjectiveType::Percent,
            ObjectiveType::Percent,
            ObjectiveType::Percent,
            ObjectiveType::Percent,
        ],
        'minimumBounds' => [
            -0.1,
            -0.1,
            -0.1,
            -0.1,
            -0.1,
            -0.1,
            -0.1,
            -0.1,
            -0.1,
            -0.1,
        ],
        'maximumBounds' => [
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
            0.1,
        ],
        'initialInput' => $initialInput,
        'initialOutput' => $initialOutput,
    ])
);

$logger->info('Objective created.', [
    'objectiveId' => $objective->id,
    'desiredL1Norm' => $objective->desiredL1Norm,
    'minimumBounds' => $objective->minimumBounds,
    'maximumBounds' => $objective->maximumBounds,
]);

$inverse = $objective->lastInverse;

$logger->info('Initial inverse created.', [
    'inverseId' => $inverse->id,
]);

do {
    // 6. Suggest Next Inverse
    $inverse = $client->suggestInverse(
        new SuggestInverse($objective->id)
    );

    $logger->info(sprintf('Inverse %d suggested.', $inverse->iteration), [
        'inverseId' => $inverse->id,
    ]);

    // 7. Calculate Inverse Output
    $inverseOutput = complexMixedFunction(...[
        'input' => $inverse->input,
    ]);

    // 8. Load Inverse Output
    $inverse = $client->loadInverseOutput(
        new LoadInverseOutput(...[
            'inverseId' => $inverse->id,
            'output' => $inverseOutput,
        ])
    );

    $reason = $inverse->getStopReason();

    $logger->info(sprintf('Inverse %d loaded.', $inverse->iteration), [
        'inverseId' => $inverse->id,
        'stopReason' => $reason->name,
    ]);
} while(!$inverse->shouldStop());

// 9. Refresh Objective
$objective = $client->readObjective(
    new ReadObjective($objective->id)
);

formatOutput($objective);

function complexMixedFunction(array $input): array
{
    list($v1, $v2, $v3, $v4, $v5) = $input;

    // Exponential Components
    $o1 = exp(0.5 * $v1) + $v2 ** 2;
    $o2 = exp(-0.3 * $v3) + $v4 * $v5;

    // Polynomial Components
    $o3 = $v1 ** 3 + $v2 ** 2 + $v1;
    $o4 = ($v1 - 2) ** 2 * $v2;

    // Logarithmic Components
    $o5 = log($v1 + 2.5) * $v2 + $v3;
    $o6 = log10($v4 + 3) * $v5 ** 2;

    // Mixed Components
    $o7 = exp(0.1 * $v1) * log($v2 + 2) + $v3 ** 2;
    $o8 = $v4 ** 3 * log10($v5 + 1.5);

    // Complex Combinations
    $o9 = exp(0.2 * $v1) * ($v2 ** 2) * log($v3 + 1.5) + $v4 * $v5;
    $o10 = log10($v1 + 2) * ($v2 ** 2) + exp(0.15 * $v3) + ($v4 ** 2) * $v5;

    return [$o1, $o2, $o3, $o4, $o5, $o6, $o7, $o8, $o9, $o10];
}
