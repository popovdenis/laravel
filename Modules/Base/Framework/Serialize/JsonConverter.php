<?php
declare(strict_types=1);

namespace Modules\Base\Framework\Serialize;

/**
 * Class JsonConverter
 *
 * @package Modules\Base\Framework\Serialize
 */
class JsonConverter
{
    /**
     * This method should only be used by \Magento\Framework\DataObject::toJson
     * All other cases should use \Magento\Framework\Serialize\Serializer\Json::serialize directly
     *
     * @param string|int|float|bool|array|null $data
     * @return bool|string
     * @throws \InvalidArgumentException
     */
    public static function convert($data)
    {
        $serializer = new \Modules\Base\Framework\Serialize\Serializer\Json();
        return $serializer->serialize($data);
    }
}
