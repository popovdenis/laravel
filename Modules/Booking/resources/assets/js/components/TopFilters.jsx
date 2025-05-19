import React, { useEffect } from 'react';
import { useBooking } from './BookingContext'
import dayjs from 'dayjs'
import DatetimeRangePicker from './DatetimeRangePicker';
import TimeRangePicker from './TimeRangePicker';
import {toast} from "sonner";

export default function TopFilters() {
    const {
        filterStartDate, setFilterStartDate,
        filterEndDate, setFilterEndDate,
        filterStartTime, setFilterStartTime,
        filterEndTime, setFilterEndTime,
        initialLessonType,
        lessonType,
        setLessonType,
        initialVisibleDatesCount,
        setVisibleDatesCount
    } = useBooking()

    const clearAllFilters = () => {
        const today = dayjs().startOf('day')
        const endDate = dayjs(today).add(initialVisibleDatesCount, 'day')

        setVisibleDatesCount(initialVisibleDatesCount)
        setLessonType(initialLessonType)
        setFilterStartDate(today.format('YYYY-MM-DD'))
        setFilterEndDate(endDate.format('YYYY-MM-DD'))
    }

    useEffect(() => {
        const handlePreferredTimeUpdate = (event) => {
            if (event.detail) {
                const { start, end } = event.detail;
                setFilterStartTime(start);
                setFilterEndTime(end);
                toast.success('The preferred time is set.');
            }
        };

        window.addEventListener('preferred-time-updated', handlePreferredTimeUpdate);

        return () => {
            window.removeEventListener('preferred-time-updated', handlePreferredTimeUpdate);
        };
    }, []);

    return (
        <div className="bg-white border rounded-md p-4 mb-6 md:col-span-3">
            <div className="flex flex-wrap items-center gap-4 w-full">
                <div>
                    <p className="text-sm text-gray-700 font-semibold mb-1">Date Range</p>
                    <div className="flex space-x-2">
                        <DatetimeRangePicker
                            value={[filterStartDate, filterEndDate]}
                            onChange={(start, end) => {
                                setFilterStartDate(start)
                                setFilterEndDate(end)
                            }}
                        />

                        <TimeRangePicker
                            value={[filterStartTime, filterEndTime]}
                            onChange={(startTime, endTime) => {
                                setFilterStartTime(startTime)
                                setFilterEndTime(endTime)
                            }}
                        />
                    </div>
                </div>

                <div>
                    <p className="text-sm text-gray-700 font-semibold mb-1">Group or Private</p>
                    <div className="flex space-x-2">
                        <button
                            type="button"
                            onClick={() => setLessonType('group')}
                            className={`btn ${lessonType === 'group' ? 'btn-primary' : 'btn-secondary'}`}
                        >
                            Group
                        </button>
                        <button
                            type="button"
                            onClick={() => setLessonType('individual')}
                            className={`btn ${lessonType === 'individual' ? 'btn-primary' : 'btn-secondary'}`}
                        >
                            Private
                        </button>
                    </div>
                </div>

                <div className="self-end ml-auto">
                    <button
                        onClick={clearAllFilters}
                        className="btn btn-primary-inverted"
                    >
                        Clear all
                    </button>
                </div>
            </div>
        </div>
    )
}