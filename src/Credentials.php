<?php

namespace GlobalMoo;

use GlobalMoo\Exception\InvalidArgumentException;

readonly class Credentials implements CredentialInterface
{

    /**
     * @var non-empty-string
     */
    private string $apiKey;

    /**
     * @var non-empty-string
     */
    private string $apiUri;

    public function __construct(
        ?string $apiKey = null,
        ?string $apiUri = 'https://api-dev.globalmoo.ai/api/',
        private bool $validateTls = true,
    )
    {
        $this->apiKey = $this->load('GMOO_API_KEY', $apiKey);

        $baseUri = $this->load('GMOO_API_URI', $apiUri);
        $isValid = filter_var($baseUri, FILTER_VALIDATE_URL);

        if (false === $isValid) {
            throw new InvalidArgumentException(sprintf('The API URI "%s" is not a valid URI and can not be used.', $baseUri));
        }

        $host = parse_url($baseUri, PHP_URL_HOST);

        if (is_string($host) && !$this->shouldValidateTls() && str_contains(strtolower($host), 'globalmoo.ai')) {
            throw new InvalidArgumentException('The "validateTls" argument must be true when using an official globalMOO base URI.');
        }

        $this->apiUri = $baseUri;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getBaseUri(): string
    {
        return $this->apiUri;
    }

    public function shouldValidateTls(): bool
    {
        return $this->validateTls;
    }

    /**
     * @return non-empty-string
     */
    private function load(string $key, ?string $default): string
    {
        if (!empty($default)) {
            return $default;
        }

        if (false === ($value = getenv($key))) {
            $value = ($_ENV[$key] ?? null);
        }

        if (!is_string($value) || empty($value)) {
            throw new InvalidArgumentException(sprintf('The globalMOO SDK could not be created because the "%s" environment variable is not set and the corresponding constructor argument was empty.', $key));
        }

        return $value;
    }

}
