<?php

namespace GlobalMoo;

interface CredentialInterface
{

    public function getApiKey(): string;
    public function getBaseUri(): string;
    public function shouldValidateTls(): bool;

}
