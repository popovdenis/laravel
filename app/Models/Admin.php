<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Class Admin
 *
 * @package App\Models
 */
class Admin extends \Modules\User\Models\User
{
    protected $table = 'users';
}
