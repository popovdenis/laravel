<?php
namespace App\Blog\Captcha;

/**
 * Trait UsesCaptcha
 *
 * For instantiating the config("blog.captcha.captcha_type") object.
 *
 * @package App\Blog\Captcha
 */
trait UsesCaptcha
{
    /**
     * Return either null (if captcha is not enabled), or the captcha object (which should implement CaptchaInterface interface / extend the CaptchaAbstract class)
     * @return null|CaptchaAbstract
     */
    private function getCaptchaObject()
    {
        if (!config("blog.captcha.captcha_enabled")) {
            return null;
        }

        // else: captcha is enabled
        /** @var string $captcha_class */
        $captcha_class = config("blog.captcha.captcha_type");
        return new $captcha_class;
    }

}
