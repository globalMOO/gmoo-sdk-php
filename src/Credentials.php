<?php

namespace GlobalMoo;

use GlobalMoo\Exception\InvalidArgumentException;

class Credentials implements CredentialInterface
{

    public function __construct(
        private ?string $apiKey = null,
        private ?string $apiUri = null,
        private bool $validateTls = true,
    )
    {
        if (null === $this->apiKey) {
            $this->apiKey = $this->load(...[
                'key' => 'GMOO_API_KEY'
            ]);
        }

        if (null === $this->apiUri) {
            $this->apiUri = $this->load(...[
                'key' => 'GMOO_API_URI',
            ]);
        }

        // Ensure the API URI is valid
        if (false === filter_var($this->apiUri, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(sprintf('The API URI "%s" is not a valid URI and can not be used.', $this->apiUri));
        }

        /** @var non-empty-string $host */
        $host = parse_url($this->apiUri, PHP_URL_HOST);

        if (!$this->shouldValidateTls()) {
            $officialDomains = [
                'globalmoo.ai',
                'globalmoo.com',
            ];

            foreach ($officialDomains as $domain) {
                if (str_ends_with(strtolower($host), $domain)) {
                    throw new InvalidArgumentException('The "validateTls" argument must be true when using an official globalMOO base URI.');
                }
            }
        }
    }

    public static function createProduction(?string $apiKey = null): self
    {
        return new self($apiKey, 'https://app.globalmoo.com/api/', true);
    }

    public static function createDevelopment(?string $apiKey = null): self
    {
        return new self($apiKey, 'https://api-dev.globalmoo.ai/api/', true);
    }

    public function getApiKey(): string
    {
        return strval($this->apiKey);
    }

    public function getBaseUri(): string
    {
        return strval($this->apiUri);
    }

    public function shouldValidateTls(): bool
    {
        return $this->validateTls;
    }

    private function load(string $key): string
    {
        if (false === ($value = getenv($key))) {
            $value = ($_ENV[$key] ?? null);
        }

        if (!is_string($value) || empty($value)) {
            throw new InvalidArgumentException(sprintf('The globalMOO SDK could not be created because the "%s" environment variable is not set and the corresponding constructor argument was empty.', $key));
        }

        return $value;
    }

}
