<?php

namespace GlobalMoo\Tests;

use GlobalMoo\Credentials;
use GlobalMoo\Exception\InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
final class CredentialsTest extends TestCase
{

    protected function setUp(): void
    {
        $environmentVars = [
            'GMOO_API_KEY',
            'GMOO_API_URI',
        ];

        foreach ($environmentVars as $var) {
            putenv($var);

            if (isset($_ENV[$var])) {
                unset($_ENV[$var]);
            }
        }
    }

    public function testConstructorRequiresValidBaseUri(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The API URI "my-dev-server/api/" is not a valid URI and can not be used.');

        $credentials = new Credentials(...[
            'apiKey' => 'test_api_key',
            'apiUri' => 'my-dev-server/api/',
        ]);
    }

    #[DataProvider('providerGlobalMooBaseUri')]
    public function testConstructorRequiresValidatingTlsWhenUsingGlobalMooBaseUri(string $baseUri): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The "validateTls" argument must be true when using an official globalMOO base URI.');

        new Credentials(...[
            'apiKey' => 'test_key',
            'apiUri' => $baseUri,
            'validateTls' => false,
        ]);
    }

    /**
     * @return list<list<string>>
     */
    public static function providerGlobalMooBaseUri(): array
    {
        $provider = [
            ['https://globalmoo.ai'],
            ['https://GLOBALMOO.AI'],
            ['https://GlObaLmOo.Ai'],
            ['https://globalmoo.ai:443'],
            ['https://globalmoo.ai/api'],
            ['https://globalmoo.ai:443/api'],
            ['https://api-dev.globalmoo.ai'],
            ['https://api-dev.globalmoo.ai:443'],
            ['https://api-dev.globalmoo.ai/api'],
            ['https://api-dev.globalmoo.ai:443/api'],
            ['https://API-DEV.GLOBALMOO.AI'],
            ['https://ApI-dEv.GlObAlMoO.Ai'],
            ['https://api.dev.globalmoo.ai'],
        ];

        return $provider;
    }

    public function testConstructorDoesNotRequireValidatingTlsWhenUsingNonGlobalMooBaseUri(): void
    {
        $credentials = new Credentials(...[
            'apiKey' => 'test_api_key',
            'apiUri' => 'http://localhost:8080/gmoo/',
            'validateTls' => false,
        ]);

        $this->assertFalse($credentials->shouldValidateTls());
    }

    /**
     * @param array<string, ?string> $constructorArgs
     */
    #[DataProvider('providerCredentialKeyAndConstructorArgs')]
    public function testConstructorRequiresApiKey(string $credentialKey, array $constructorArgs): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('The globalMOO SDK could not be created because the "%s" environment variable is not set and the corresponding constructor argument was empty.', $credentialKey));

        $this->assertEmpty(getenv($credentialKey));
        $this->assertArrayNotHasKey($credentialKey, $_ENV);

        new Credentials(...$constructorArgs);
    }

    /**
     * @return list<list<string|array<string, ?string>>>
     */
    public static function providerCredentialKeyAndConstructorArgs(): array
    {
        $provider = [
            ['GMOO_API_KEY', ['apiKey' => null, 'apiUri' => 'https://api.example.com/']],
            ['GMOO_API_URI', ['apiKey' => 'WwM1fs36T2YMGrUHsmA93Wh5', 'apiUri' => null]],
        ];

        return $provider;
    }

    public function testConstructorUsesArgumentsOverEnvironmentVariables(): void
    {
        $key = 'GMOO_API_KEY';
        $value = 'argument_value';

        putenv("{$key}=envvar_value1");
        $_ENV[$key] = 'envvar_value2';

        $credentials = new Credentials(...[
            'apiKey' => $value,
            'apiUri' => 'https://api.example.com/',
        ]);

        $this->assertEquals($value, $credentials->getApiKey());
    }

    public function testConstructorUsesEnvironmentVariablesWhenArgumentsAreEmpty(): void
    {
        $_ENV['GMOO_API_KEY'] = 'WwM1fs36T2YMGrUHsmA93Wh5';
        $_ENV['GMOO_API_URI'] = 'https://api.example.com/';

        $credentials = new Credentials(...[
            'apiKey' => null,
            'apiUri' => null,
        ]);

        $this->assertEquals($_ENV['GMOO_API_KEY'], $credentials->getApiKey());
        $this->assertEquals($_ENV['GMOO_API_URI'], $credentials->getBaseUri());
    }

    public function testConstructorUsesGetenvBeforeEnvSuperglobal(): void
    {
        $key = 'GMOO_API_KEY';
        $value = md5(uniqid());

        putenv("{$key}={$value}");

        $this->assertArrayNotHasKey($key, $_ENV);
        $this->assertEquals($value, getenv($key));

        $credentials = new Credentials(...[
            'apiKey' => null,
            'apiUri' => 'https://api.example.com/',
        ]);

        $this->assertEquals(getenv($key), $credentials->getApiKey());
    }

    public function testCreatingProductionCredentialsUsesProductionUri(): void
    {
        $credentials = Credentials::createProduction(...[
            'apiKey' => 'production_api_key',
        ]);

        $this->assertEquals('https://app.globalmoo.com/api/', $credentials->getBaseUri());
    }

    public function testCreatingDevelopmentCredentialsUsesProductionUri(): void
    {
        $credentials = Credentials::createDevelopment(...[
            'apiKey' => 'development_api_key',
        ]);

        $this->assertEquals('https://api-dev.globalmoo.ai/api/', $credentials->getBaseUri());
    }

}
