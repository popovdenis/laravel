<?php

namespace Modules\Security\Contracts;

/**
 * Interface AttemptRequestEventInterface
 *
 * @package Modules\Security\Contracts
 */
interface AttemptRequestEventInterface
{
    public function setRequestType(int $requestType): self;

    public function getRequestType(): int;

    public function setAccountReference(string $accountReference): self;

    public function getAccountReference(): string;
}
