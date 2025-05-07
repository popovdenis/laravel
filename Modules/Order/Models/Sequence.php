<?php
declare(strict_types=1);

namespace Modules\Order\Models;

use Modules\Order\Contracts\SequenceInterface;

/**
 * Class Sequence
 *
 * @package Modules\Order\Models
 */
class Sequence implements SequenceInterface
{
    /**
     * Default pattern for Sequence
     */
    const DEFAULT_PATTERN  = "%s%'.09d%s";

    /**
     * @var string
     */
    private $pattern;

    public function __construct($pattern = self::DEFAULT_PATTERN)
    {
        $this->pattern = $pattern;
    }

    public function getCurrentValue($id)
    {
        return sprintf(
            $this->pattern,
            null,
            $id,
            null
        );
    }
}
