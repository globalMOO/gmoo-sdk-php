<?php

namespace GlobalMoo;

interface CredentialInterface
{

    /**
     * @return non-empty-string
     */
    public function getApiKey(): string;

    /**
     * @return non-empty-string
     */
    public function getBaseUri(): string;

    public function shouldValidateTls(): bool;

}
