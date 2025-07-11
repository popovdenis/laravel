<?php
declare(strict_types=1);

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Order\Contracts\QuoteInterface;
use Modules\Payment\Contracts\PaymentInterface;
use Modules\Payment\Models\Payment;
use Modules\User\Models\User;

/**
 * Class Quote
 *
 * @package Modules\Order\Models
 */
abstract class Quote implements QuoteInterface
{
    protected ?PaymentInterface $currentPayment = null;
    protected ?Model $model = null;
    protected User $student;
    protected User $teacher;

    public function getPayment()
    {
        if ($this->currentPayment instanceof PaymentInterface) {
            return $this->currentPayment;
        }

        $payment = app()->make(Payment::class);
        $this->addPayment($payment);

        return $payment;
    }

    /**
     * Adds a payment to quote
     *
     * @param PaymentInterface $payment
     * @return $this
     */
    protected function addPayment(PaymentInterface $payment)
    {
        $this->currentPayment = $payment;
        $payment->setQuote($this);
        return $this;
    }

    public function setPayment(PaymentInterface $payment)
    {
        $this->addPayment($payment);
        return $payment;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setModel(Model $model)
    {
       $this->model = $model;
    }

    public function setStudent(User $student)
    {
        $this->student = $student;
    }

    public function getStudent(): User
    {
        return $this->student;
    }

    public function setTeacher(User $teacher)
    {
        $this->teacher = $teacher;
    }

    public function getTeacher(): User
    {
        return $this->teacher;
    }
}
