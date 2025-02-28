<?php

namespace GlobalMoo\Request;

use GlobalMoo\Account;

final readonly class RegisterAccount extends AbstractRequest
{

    public function __construct(
        public string $company,
        public string $name,
        public string $email,
        public string $password,
        public string $timeZone,
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
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'company' => $this->company,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'timeZone' => $this->timeZone,
        ];
    }

}
