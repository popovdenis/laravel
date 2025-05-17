<?php
declare(strict_types=1);

namespace Modules\Base\Framework\Serialize\Serializer;

use Modules\Base\Framework\Serialize\SerializerInterface;

/**
 * Class Json
 *
 * @package Modules\Base\Framework\Serialize\Serializer
 */
class Json implements SerializerInterface
{
    /**
     * @inheritDoc
     * @since 101.0.0
     */
    public function serialize($data)
    {
        $result = json_encode($data);
        if (false === $result) {
            throw new \InvalidArgumentException("Unable to serialize value. Error: " . json_last_error_msg());
        }
        return $result;
    }

    /**
     * @inheritDoc
     * @since 101.0.0
     */
    public function unserialize($string)
    {
        if ($string === null) {
            throw new \InvalidArgumentException(
                'Unable to unserialize value. Error: Parameter must be a string type, null given.'
            );
        }
        $result = json_decode($string, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException("Unable to unserialize value. Error: " . json_last_error_msg());
        }
        return $result;
    }
}
