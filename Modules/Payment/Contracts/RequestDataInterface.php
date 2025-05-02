<?php

namespace Modules\Payment\Contracts;

use Illuminate\Http\Request;

/**
 * Interface RequestDataInterface
 *
 * @package Modules\Payment\Contracts
 */
interface RequestDataInterface
{
    public static function rules(): array;

    public static function fromRequest(Request $request): static;
}
