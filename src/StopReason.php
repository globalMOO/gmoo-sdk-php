<?php

namespace GlobalMoo;

enum StopReason: int
{

    case Running = 0;
    case Satisfied = 1;
    case Stopped = 2;
    case Exhausted = 3;

    public function description(): string
    {
        $description = match($this) {
            static::Running => 'is still running or being evaluated',
            static::Satisfied => 'found a satisfactory input and output',
            static::Stopped => 'stopped due to duplicate suggested inputs',
            static::Exhausted => 'exhausted all attempts to converge',
        };

        return $description;
    }

    public function isRunning(): bool
    {
        return ($this === static::Running);
    }

    public function isSatisfied(): bool
    {
        return ($this === static::Satisfied);
    }

    public function isStopped(): bool
    {
        return ($this === static::Stopped);
    }

    public function isExhausted(): bool
    {
        return ($this === static::Exhausted);
    }

    public function shouldStop(): bool
    {
        return !$this->isRunning();
    }

}
