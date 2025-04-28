<?php
declare(strict_types=1);

namespace Modules\Security\Models;

use Carbon\Carbon;
use Modules\Security\Contracts\AttemptRequestFactoryInterface;
use Modules\Security\Contracts\SecurityCheckerInterface;

/**
 * Class SecurityManager
 *
 * @package Modules\Security\Models
 */
class SecurityManager
{
    /**
     * Security control records time life
     */
    const SECURITY_CONTROL_RECORDS_LIFE_TIME =  86400;

    /**
     * @var \Modules\Security\Contracts\SecurityCheckerInterface
     */
    private SecurityCheckerInterface $securityChecker;
    /**
     * @var \Modules\Security\Contracts\AttemptRequestFactoryInterface
     */
    private AttemptRequestFactoryInterface $attemptRequestFactory;

    public function __construct(
        SecurityCheckerInterface $securityChecker,
        AttemptRequestFactoryInterface $attemptRequestFactory,
    )
    {
        $this->securityChecker = $securityChecker;
        $this->attemptRequestFactory = $attemptRequestFactory;
    }

    /**
     * Perform security check
     *
     * @param int         $requestType
     * @param string|null $accountReference
     *
     * @return $this
     * @throws \Modules\Security\Exceptions\SecurityViolationException
     */
    public function performSecurityCheck(int $requestType, string $accountReference = null): static
    {
        $this->securityChecker->check($requestType, $accountReference);

        $this->createNewAttemptRequestEventRecord($requestType, $accountReference);

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function cleanExpiredRecords(): static
    {
        $this->attemptRequestFactory->create()->deleteRecordsOlderThan(
            Carbon::now('UTC')->timestamp - self::SECURITY_CONTROL_RECORDS_LIFE_TIME
        );

        return $this;
    }

    protected function createNewAttemptRequestEventRecord($requestType, $accountReference)
    {
        $attemptRequest = $this->attemptRequestFactory->create();
        $attemptRequest->setRequestType($requestType)
            ->setAccountReference($accountReference)
            ->save();

        return $attemptRequest;
    }
}
