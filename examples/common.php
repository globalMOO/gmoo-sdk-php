<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GlobalMoo\Objective;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\NullHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

define('DEBUG', ('--debug' === ($argv[1] ?? null)));

// Set Up Logging
$logger = new Logger('globalmoo');

$handler = new StreamHandler(...[
    'stream' => 'php://stdout',
    'level' => Level::Info,
]);

$handler->setFormatter(new LineFormatter(...[
    'ignoreEmptyContextAndExtra' => true,
]));

$logger->pushHandler($handler);

if (false === DEBUG) {
    $logger->setHandlers([
        new NullHandler()
    ]);
}

function formatOutput(Objective $objective): void
{
    $inverse = $objective->lastInverse;

    writeLine();

    if ($objective->stopReason->isSatisfied()) {
        writeLine(vsprintf('🎉 Optimization satisfied in %d iterations!', [
            $objective->iterationCount
        ]));
    } else {
        writeLine(vsprintf('🤬 Optimization did not converge because it %s.', [
            $objective->stopReason->description()
        ]));
    }

    writeLine();
    writeLineBold('Final Input');
    writeLine(formatVector($inverse->input));

    writeLine();
    writeLineBold('Final Output');
    writeLine(formatVector($inverse->output));

    writeLine();
    writeLineBold('Objectives');
    writeLine(formatVector($objective->objectives));

    writeLine();
    writeLineBold('Objective Results');

    foreach ($inverse->results as $result) {
        $satisfiedStatus = "✅";

        if (!$result->satisfied) {
            $satisfiedStatus = "⛔️";
        }

        writeLine(vsprintf("%s Objective[%d]: %s", [
            $satisfiedStatus, $result->number, $result->detail
        ]));
    }
}

function formatDecimal(int|float $value, int $scale = 6): string
{
    $scale = max(0, $scale);
    $scale = min(8, $scale);
    $sigDigits = $scale + 1;

    if (
        !is_int($value) &&
        !is_float($value) &&
        !is_string($value)
    ) {
        $value = 0.0;
    }

    try {
        // Begin by attempting to format the number as a decimal. If that
        // fails, then we assume we have to format it in scientific notation.
        return (new \BcMath\Number((string)$value))->round($scale)->__toString();
    } catch (\ValueError $e) {}

    $fmt = new NumberFormatter('en', NumberFormatter::SCIENTIFIC);
    $fmt->setAttribute(NumberFormatter::MIN_SIGNIFICANT_DIGITS, ($scale+1));
    $fmt->setAttribute(NumberFormatter::MAX_SIGNIFICANT_DIGITS, ($scale+1));

    $num = $fmt->format((float)$value);

    if (!is_string($num)) {
        return 'NaN';
    }

    return strtolower($num);
}

function formatVector(array $vector): string
{
    foreach ($vector as $idx => $e) {
        $vector[$idx] = formatDecimal($e);
    }

    $string = vsprintf('[%s]', [
        implode(', ', $vector)
    ]);

    return $string;
}

function writeLine(?string $line = null): void
{
    echo(sprintf("%s\n", trim((string)$line)));
}

function writeLineBold(string $line): void
{
    writeLine(sprintf("\033[1m%s\033[0m", $line));
}
