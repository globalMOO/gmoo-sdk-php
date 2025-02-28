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
    5.4321,
    5.4321,
    5.4321,
];

$objectives = linearModelFunction(...[
    'input' => $truthCase,
]);

// [16.2963,12.2963,12.2963,12.2963,32.5926]

// Model and Project Settings
$inputCount = count($truthCase);
$outputCount = count($objectives);

// Initialize Client
$client = new Client();

// 1. Create Model
$request = new CreateModel(...[
    'name' => 'Linear 1.0 - SDK',
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
            0.0,
            0.0,
            0.0,
        ],
        'maximums' => [
            10.0,
            10.0,
            10.0,
        ],
        'inputTypes' => [
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
    return linearModelFunction($input);
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
        ],
        'minimumBounds' => [
            -1.0,
            -1.0,
            -1.0,
            -1.0,
            -1.0,
        ],
        'maximumBounds' => [
            1.0,
            1.0,
            1.0,
            1.0,
            1.0,
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
    $inverseOutput = linearModelFunction(...[
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

function linearModelFunction(array $input): array
{
    $v01 = $input[0];
    $v02 = $input[1];
    $v03 = $input[2];

    $o01 = $v01 + $v02 + $v03;
    $o02 = ($v01 - 2.0) + ($v02 - 2.0) + $v03;
    $o03 = ($v01 - 2.0) + $v02 + ($v03 - 2.0);
    $o04 = $v01 + ($v02 - 2.0) + ($v03 - 2.0);
    $o05 = 3.0 * $v01 + 2.0 * $v03 + 1.0 * $v03;

    return [$o01, $o02, $o03, $o04, $o05];
}
