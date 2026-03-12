<?php

namespace GlobalMoo\Request;

use GlobalMoo\Account;

final readonly class RegisterAccount extends AbstractRequest
{

    public function __construct(
        public string $company,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
        public string $timeZone,
        public bool $agreement = true,
    )
    {
    }

    public function getUrl(): string
    {
        return 'accounts/register';
    }

    /**
     * @return class-string<Account>
     */
    public function getType(): string
    {
        return Account::class;
    }

    /**
     * @return array<string, string|bool>
     */
    public function toArray(): array
    {
        return [
            'company' => $this->company,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
            'timeZone' => $this->timeZone,
            'agreement' => $this->agreement,
        ];
    }

}
