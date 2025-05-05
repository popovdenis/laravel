<?php

namespace Modules\User\Filament\Resources\TeacherResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\User\Filament\Resources\TeacherResource;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;
    protected static ?string $title = 'Create Teacher';
    protected static ?string $breadcrumb = 'Create Teacher';
}
