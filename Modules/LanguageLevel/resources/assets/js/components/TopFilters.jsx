import React, { useEffect, useRef } from 'react'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'
import { useBooking } from './BookingContext'

export default function TopFilters() {
    const {
        filterStartDate,
        setFilterStartDate,
        filterEndDate,
        setFilterEndDate,
        lessonType,
        setLessonType,
    } = useBooking()

    const ref = useRef(null)

    const formatLocalDate = (date) => {
        const offset = date.getTimezoneOffset();
        const localDate = new Date(date.getTime() - offset * 60 * 1000);
        return localDate.toISOString().slice(0, 10);
    };

    useEffect(() => {
        if (!ref.current) return

        flatpickr(ref.current, {
            mode: 'range',
            enableTime: false,
            noCalendar: false,
            dateFormat: 'Y-m-d',
            defaultDate: [filterStartDate, filterEndDate],
            time_24hr: true,
            onClose: function (selectedDates) {
                if (selectedDates.length === 2) {
                    setFilterStartDate(formatLocalDate(selectedDates[0]))
                    setFilterEndDate(formatLocalDate(selectedDates[1]))
                }
            },
        })
    }, [ref])

    return (
        <div className="bg-white border rounded-md p-4 mb-6 md:col-span-3">
            <div className="flex flex-wrap items-center gap-4 w-full">
                <div>
                    <p className="text-sm text-gray-700 font-semibold mb-1">Date and Time</p>
                    <input
                        ref={ref}
                        type="text"
                        id="datetime-range"
                        className="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2 w-64"
                        placeholder="Select date range"
                        readOnly
                    />
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
                        onClick={() => {
                            // setDateRange({ start: , end: null })
                            setLessonType(null)
                        }}
                        className="btn btn-primary-inverted"
                    >
                        Clear all
                    </button>
                </div>
            </div>
        </div>
    )
}
