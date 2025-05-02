<?php
declare(strict_types=1);

namespace Modules\Payment\Models;

use Modules\Base\Models\ConfigProviderAbstract;

/**
 * Class ConfigProvider
 *
 * @package Modules\Payment\Models
 */
class ConfigProvider extends ConfigProviderAbstract
{
    protected $pathPrefix = 'payment.';

    /**
     * Get config name of method model
     *
     * @param string $code
     * @return string
     */
    protected function getMethodModelConfigName($code): string
    {
        return sprintf('%s.model', $code);
    }

    /**
     * Retrieve method model object
     *
     * @param string $code
     *
     * @return \Modules\Payment\Contracts\MethodInterface
     */
    public function getMethodInstance(string $code): \Modules\Payment\Contracts\MethodInterface
    {
        $class = $this->getValue($this->getMethodModelConfigName($code));

        if (!$class) {
            throw new \UnexpectedValueException('Payment model name is not provided in config!');
        }
dd($class);
        return app()->make($class);
    }

    /**
     * Get and sort available payment methods for specified or current store.
     */
    public function getStoreMethods($quote = null)
    {
        $res = [];
        $methods = $this->getPaymentMethods();

        foreach (array_keys($methods) as $code) {
            $model = $this->getValue($this->getMethodModelConfigName($code));
            if (!$model) {
                continue;
            }

            $methodInstance = app($model);
            if (!$methodInstance->isAvailable($quote)) {
                /* if the payment method cannot be used at this time */
                continue;
            }
            $res[] = $methodInstance;
        }
        // phpcs:ignore Generic.PHP.NoSilencedErrors
//        @uasort(
//            $res,
//            function (MethodInterface $a, MethodInterface $b) {
//                return (int)$a->getConfigData('sort_order') <=> (int)$b->getConfigData('sort_order');
//            }
//        );

        return $res;
    }

    /**
     * Retrieve all payment methods
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        return $this->_initialConfig->getData('default')[$this->pathPrefix];
    }

    /**
     * Retrieve all payment methods list as an array
     *
     * Possible output:
     * 1) assoc array as <code> => <title>
     * 2) array of array('label' => <title>, 'value' => <code>)
     * 3) array of array(
     *                 array('value' => <code>, 'label' => <title>),
     *                 array('value' => array(
     *                     'value' => array(array(<code1> => <title1>, <code2> =>...),
     *                     'label' => <group name>
     *                 )),
     *                 array('value' => <code>, 'label' => <title>),
     *                 ...
     *             )
     *
     * @param bool $sorted
     * @param bool $asLabelValue
     * @param bool $withGroups
     *
     * @return array
     */
    public function getPaymentMethodList($sorted = true, $asLabelValue = false, $withGroups = false)
    {
        $methods = [];
        $groups = [];
        $groupRelations = [];

        foreach ($this->getPaymentMethods() as $code => $data) {
            $storedTitle = $this->getMethodTitle($code);
            if (!empty($storedTitle)) {
                $methods[$code] = $storedTitle;
            }

            if ($asLabelValue && $withGroups && isset($data['group'])) {
                $groupRelations[$code] = $data['group'];
            }
        }
        if ($asLabelValue && $withGroups) {
            $groups = $this->_paymentConfig->getGroups();
            foreach ($groups as $code => $title) {
                $methods[$code] = $title;
            }
        }
        if ($sorted) {
            asort($methods);
        }
        if ($asLabelValue) {
            $labelValues = [];
            foreach ($methods as $code => $title) {
                $labelValues[$code] = [];
            }
            foreach ($methods as $code => $title) {
                if (isset($groups[$code])) {
                    $labelValues[$code]['label'] = $title;
                    if (!isset($labelValues[$code]['value'])) {
                        $labelValues[$code]['value'] = null;
                    }
                } elseif (isset($groupRelations[$code])) {
                    unset($labelValues[$code]);
                    $labelValues[$groupRelations[$code]]['value'][$code] = ['value' => $code, 'label' => $title];
                } else {
                    $labelValues[$code] = ['value' => $code, 'label' => $title];
                }
            }
            return $labelValues;
        }

        return $methods;
    }

    /**
     * Get config title of payment method
     *
     * @param string $code
     *
     * @return string
     */
    private function getMethodTitle(string $code): string
    {
        $configPath = sprintf('%s/%s/title', $this->pathPrefix, $code);

        return (string) $this->getValue($configPath);
    }
}
