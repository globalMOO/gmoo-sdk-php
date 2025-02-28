<?php

namespace GlobalMoo;

use GlobalMoo\Exception\InvalidArgumentException;
use GlobalMoo\Exception\InvalidRequestException;
use GlobalMoo\Exception\InvalidResponseException;
use GlobalMoo\Exception\NetworkConnectionException;
use GlobalMoo\Request\CreateModel;
use GlobalMoo\Request\CreateProject;
use GlobalMoo\Request\LoadInverseOutput;
use GlobalMoo\Request\LoadObjectives;
use GlobalMoo\Request\LoadOutputCases;
use GlobalMoo\Request\ReadModel;
use GlobalMoo\Request\ReadModels;
use GlobalMoo\Request\ReadObjective;
use GlobalMoo\Request\RegisterAccount;
use GlobalMoo\Request\RequestInterface;
use GlobalMoo\Request\SuggestInverse;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Client implements ClientInterface
{

    private HttpClientInterface $httpClient;
    private SerializerInterface&DenormalizerInterface $serializer;

    public function __construct(
        ?CredentialInterface $credentials = null,
        ?HttpClientInterface $httpClient = null,
    )
    {
        if (null === $httpClient) {
            $credentials ??= new Credentials();

            $httpClient = HttpClient::createForBaseUri($credentials->getBaseUri(), [
                'auth_bearer' => $credentials->getApiKey(),
                'verify_peer' => $credentials->shouldValidateTls(),
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
        }

        $this->httpClient = $httpClient;

        // Initialize the normalizers and serializer
        $constructorExtractor = new ConstructorExtractor(...[
            'extractors' => [new PhpDocExtractor()]
        ]);

        $typeExtractor = new PropertyInfoExtractor(...[
            'typeExtractors' => [$constructorExtractor]
        ]);

        $objectNormalizer = new ObjectNormalizer(...[
            'propertyTypeExtractor' => $typeExtractor
        ]);

        $this->serializer = new Serializer([
            new BackedEnumNormalizer(),
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            $objectNormalizer,
        ]);
    }

    public function registerAccount(RegisterAccount $request): Account
    {
        /** @var Account $account */
        $account = $this->sendRequest($request);

        return $account;
    }

    /**
     * @return list<Model>
     */
    public function readModels(?ReadModels $request = null): array
    {
        /** @var list<Model> $models */
        $models = $this->sendRequest(
            $request ?? new ReadModels()
        );

        return $models;
    }

    public function readModel(ReadModel $request): Model
    {
        /** @var Model $model */
        $model = $this->sendRequest($request);

        return $model;
    }

    public function createModel(CreateModel $request): Model
    {
        /** @var Model $model */
        $model = $this->sendRequest($request);

        return $model;
    }

    public function createProject(CreateProject $request): Project
    {
        /** @var Project $project */
        $project = $this->sendRequest($request);

        return $project;
    }

    public function loadOutputCases(LoadOutputCases $request): Trial
    {
        /** @var Trial $trial */
        $trial = $this->sendRequest($request);

        return $trial;
    }

    public function loadObjectives(LoadObjectives $request): Objective
    {
        /** @var Objective $objective */
        $objective = $this->sendRequest($request);

        return $objective;
    }

    public function readObjective(ReadObjective $request): Objective
    {
        /** @var Objective $objective */
        $objective = $this->sendRequest($request);

        return $objective;
    }

    public function suggestInverse(SuggestInverse $request): Inverse
    {
        /** @var Inverse $inverse */
        $inverse = $this->sendRequest($request);

        return $inverse;
    }

    public function loadInverseOutput(LoadInverseOutput $request): Inverse
    {
        /** @var Inverse $inverse */
        $inverse = $this->sendRequest($request);

        return $inverse;
    }

    public function handleEvent(string $payload): Event
    {
        $event = json_decode($payload, true);

        if (!is_array($event) || !isset($event['id'], $event['name'])) {
            throw new InvalidArgumentException('The payload provided does not appear to be a valid event.');
        }

        if (!is_string($event['name'])) {
            throw new InvalidArgumentException('The "name" property is expected to be a string.');
        }

        try {
            // Attempt to denormalize the data object first.
            $eventName = EventName::from($event['name']);

            $event['data'] = $this->serializer->denormalize(
                $event['data'], $eventName->dataType()
            );
        } catch (\ValueError $e) {
            throw new InvalidArgumentException(sprintf('The event name "%s" is invalid.', $event['name']));
        }

        /** @var Event $event */
        $event = $this->serializer->denormalize(
            $event, Event::class, null
        );

        return $event;
    }

    private function sendRequest(RequestInterface $request): mixed
    {
        try {
            $response = $this->httpClient->request($request->getMethod(), $request->getUrl(), [
                'json' => $request->toArray(),
            ]);

            $denormalizedResponse = $this->serializer->denormalize(
                $response->toArray(), $request->getType()
            );
        } catch (HttpExceptionInterface $e) {
            /** @var Error $denormalizedError */
            $denormalizedError = $this->serializer->denormalize(
                $e->getResponse()->toArray(false), Error::class
            );

            throw new InvalidRequestException($request, $denormalizedError);
        } catch (TransportExceptionInterface $e) {
            throw new NetworkConnectionException($e);
        } catch (DecodingExceptionInterface|SerializerExceptionInterface $e) {
            throw new InvalidResponseException($e);
        }

        return $denormalizedResponse;
    }

}
