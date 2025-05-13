import React, { useContext, useEffect, useRef, useState } from 'react'
import { BookingContext } from './BookingContext';
import SlotCard from './SlotCard';

export default function SlotsList() {
    const { slots, selectedSubjectIds } = useContext(BookingContext);
    const [visibleDatesCount, setVisibleDatesCount] = useState(5)
    const loaderRef = useRef(null)

    const allDates = Object.keys(slots || {})
    const visibleDates = allDates.slice(0, visibleDatesCount)

    useEffect(() => {
        const observer = new IntersectionObserver(([entry]) => {
            if (entry.isIntersecting) {
                setVisibleDatesCount(prev => prev + 5)
            }
        }, {
            rootMargin: '200px',
        })

        if (loaderRef.current) observer.observe(loaderRef.current)

        return () => observer.disconnect()
    }, [])

    if (!slots || allDates.length === 0) {
        return <p className="text-gray-500">No available streams for the selected filters.</p>
    }

    return (
        <>
            {visibleDates.map(date => {
                const slotList = slots[date] || []

                const filtered = selectedSubjectIds.length
                    ? slotList.filter(slot => selectedSubjectIds.includes(slot.subject?.id))
                    : slotList;

                if (filtered.length === 0) return null;

                return (
                    <div key={date}>
                        <h3 className="text-md font-medium text-gray-600 bg-gray-100 rounded">
                            {new Date(date).toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' })}
                        </h3>
                        <div className="mt-4 space-y-4">
                            {filtered.map((item, index) => (
                                <SlotCard key={`${index}`} item={item} />
                            ))}
                        </div>
                    </div>
                )
            })}

            {visibleDatesCount < allDates.length && (
                <div ref={loaderRef} className="h-12" />
            )}
        </>
    )
}
