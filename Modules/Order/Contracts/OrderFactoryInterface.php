<?php
declare(strict_types=1);

namespace Modules\Order\Contracts;

/**
 * Class OrderFactory
 *
 * @package Modules\Order\Contracts
 */
interface OrderFactoryInterface
{
    public function createFromQuote(QuoteInterface $quote): OrderInterface;
}
