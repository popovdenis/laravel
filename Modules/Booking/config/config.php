<?php

return [
    'name' => 'Booking',
    'rules' => [
        'group_lesson_duration' => 90,
        'individual_lesson_duration' => 60,
        'cancellation_deadline' => 120
    ],
    'listing' => [
        'initial_days_range' => 5,
        'load_more_days' => 5,
    ]
];
