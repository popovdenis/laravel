<?php
declare(strict_types=1);

namespace Modules\Invoice\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Class ScheduleService
 *
 * @package Modules\Invoice\Services
 */
class ScheduleService
{
    /**
     * Handle schedules and prepare to save
     *
     * @param array $formData.
     * @return Collection.
     */
    public function processFormData(array $formData): Collection
    {
        $scheduleData = collect();

        if (isset($formData['schedule_items']) && is_array($formData['schedule_items'])) {
            foreach ($formData['schedule_items'] as $item) {
                $scheduleData->push([
                    'day_of_week' => $item['day'],
                    'start_time' => $this->formatTime($item['start_time']),
                    'end_time' => $this->formatTime($item['end_time']),
                    'some_foreign_key' => $formData['some_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return $scheduleData;
    }

    /**
     * Format date to DB date format.
     *
     * @param string|null $timeString
     * @return string|null
     */
    protected function formatTime(?string $timeString): ?string
    {
        if ($timeString) {
            return \Carbon\Carbon::parse($timeString)->format('H:i:s');
        }

        return null;
    }

    /**
     * Store the data
     *
     * @param Collection $scheduleData Коллекция данных расписания.
     * @param string $modelClass Имя Eloquent модели для расписания.
     * @return bool True, если сохранение прошло успешно, false в противном случае.
     */
    public function saveScheduleData(Collection $scheduleData, string $modelClass): bool
    {
        try {
            // Очищаем старое расписание (если это необходимо)
            // $modelClass::where('some_foreign_key', $someId)->delete();

            // Вставляем новое расписание
//            $modelClass::insert($scheduleData->toArray());

            return true;
        } catch (\Exception $e) {
            Log::error('Error while saving the schedule: ' . $e->getMessage());
            return false;
        }
    }
}

