import React, { useEffect, useRef } from 'react'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'

export default function TimeRangeFilter({ value = [], onChange }) {
    const startPickerRef = useRef(null);
    const endPickerRef = useRef(null);
    const startRef = useRef(null);
    const endRef = useRef(null);
    const [start, end] = value;

    useEffect(() => {
        startPickerRef.current = flatpickr(startRef.current, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            defaultDate: start,
            onClose: () => setTimeout(() => endRef.current._flatpickr.open(), 1),
            onChange: ([date]) => {
                endRef.current._flatpickr.set('minTime', date);
            },
        })
        endPickerRef.current = flatpickr(endRef.current, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            defaultDate: end,
            onChange: ([date]) => {
                startRef.current._flatpickr.set('maxTime', date);
            },
            onClose: () => setTimeout(function() {
                onChange?.(startRef.current.value, endRef.current.value)
            }, 1),
        })

        return () => {
            startPickerRef.current.destroy()
            endPickerRef.current.destroy()
        }
    }, []);

    useEffect(() => {
        if (startPickerRef.current) {
            startPickerRef.current.setDate(start, false);
        }
        if (endPickerRef.current) {
            endPickerRef.current.setDate(end, false);
        }
    }, [start, end]);

    return (
        <>
                <input
                    ref={startRef}
                    type="text"
                    className="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2 w-20"
                    placeholder="HH:mm"
                    readOnly
                />
                <input
                    ref={endRef}
                    type="text"
                    className="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2 w-20"
                    placeholder="HH:mm"
                    readOnly
                />
        </>
    )
}