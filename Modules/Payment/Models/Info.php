<?php
declare(strict_types=1);

namespace Modules\Payment\Models;

use Illuminate\Validation\ValidationException;
use Modules\Payment\Contracts\InfoInterface;
use Modules\Payment\Contracts\MethodInterface;
use Spatie\LaravelData\Data;

/**
 * Class Info
 *
 * @package Modules\Payment\Models
 */
class Info extends Data implements InfoInterface
{
    public ?MethodInterface $methodInstance = null;
    /**
     * @var \Modules\Payment\Models\ConfigProvider
     */
    private static ?ConfigProvider $configProvider = null;

    protected function getConfigProvider(): ConfigProvider
    {
        if (self::$configProvider === null) {
            self::$configProvider = app(ConfigProvider::class);
        }

        return self::$configProvider;
    }

    public function getMethodInstance()
    {
        if (!$this->methodInstance) {
            if (!$this->getMethod()) {
                throw ValidationException::withMessages([
                    'payment' => [__('The payment method you requested is not available.')],
                ]);
            }

            try {
                $instance = $this->getConfigProvider()->getMethodInstance($this->getMethod());
            } catch (\UnexpectedValueException $e) {
                throw ValidationException::withMessages([
                    'payment' => [__(
                        'The payment method :method you requested is not available.',
                        ['method' => $this->getMethod()]
                    )],
                ]);
            }

            $this->methodInstance = $instance;
        }

        return $this->methodInstance;
    }
}
