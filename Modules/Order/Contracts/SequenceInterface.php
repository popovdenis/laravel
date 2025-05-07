<?php

namespace Modules\Order\Contracts;

/**
 * Interface SequenceInterface
 *
 * @package Modules\Order\Contracts
 */
interface SequenceInterface
{
    /**
     * @param $id
     *
     * @return mixed
     */
    public function getCurrentValue($id);
}
