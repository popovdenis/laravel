import React, { useEffect, useRef } from 'react'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'
import dayjs from 'dayjs'

export default function DatetimeRangePicker({ value = [], onChange }) {
    const inputRef = useRef(null)

    useEffect(() => {
        if (!inputRef.current) return

        const fp = flatpickr(inputRef.current, {
            mode: 'range',
            dateFormat: 'Y-m-d',
            defaultDate: value,
            onClose: function (selectedDates) {
                if (selectedDates.length === 2) {
                    const start = dayjs(selectedDates[0]).format('YYYY-MM-DD')
                    const end = dayjs(selectedDates[1]).format('YYYY-MM-DD')
                    onChange?.(start, end)
                }
            },
        })

        return () => fp.destroy()
    }, [value])

    return (
        <>
            <input
                ref={inputRef}
                type="text"
                className="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2 w-[13rem]"
                placeholder="Select date range"
                readOnly
            />
        </>
    )
}