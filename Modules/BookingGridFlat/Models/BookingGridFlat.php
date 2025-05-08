<?php
declare(strict_types=1);

namespace Modules\BookingGridFlat\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\BookingGridFlat\Contracts\BookingGridFlatInterface;

class BookingGridFlat extends Model implements BookingGridFlatInterface
{
    protected $table = 'booking_grid_flat';

    protected $fillable = [
        'booking_id',
        'student_id',
        'student_firstname',
        'student_lastname',
        'teacher_id',
        'teacher_firstname',
        'teacher_lastname',
        'stream_id',
        'level_title',
        'subject_title',
        'current_subject_number',
//        'subject_category',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}

