<?php
declare(strict_types=1);

namespace Modules\Order\Models;

use Modules\Order\Contracts\QuoteInterface;

/**
 * Class QuoteValidator
 *
 * @package Modules\Order\Models
 */
class QuoteValidator
{
    /**
     * Validates quote before submit.
     *
     * @param QuoteInterface $quote
     *
     * @return $this
     */
    public function validateBeforeSubmit(QuoteInterface $quote)
    {
        $quote->validate();

        return $this;
    }
}
