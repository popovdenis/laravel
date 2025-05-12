import React, { useEffect, useRef, useState } from 'react'
import { BookingProvider, useBooking } from './BookingContext'
import axios from 'axios'
import SidebarFilters from "./SidebarFilters.jsx";
import SlotsList from "./SlotsList.jsx";
import TopFilters from "./TopFilters.jsx";

function BookingPageContent() {
    const {
        setLevels, setSubjects, setSelectedLevelId,
        setSlots, setSelectedSubjectIds, setDateRange, setLessonType,
    } = useBooking()

    const hasFetched = useRef(false);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        if (hasFetched.current) return;
        hasFetched.current = true;

        const init = async () => {
            try {
                setLoading(true)
                const response = await axios.get('/levels/init')
                const data = response.data;

                setLevels(data.levels)
                setSubjects(data.subjects)
                setSelectedLevelId(data.selectedLevelId)
                setSelectedSubjectIds(data.selectedSubjectIds || [])
                setLessonType(data.lessonType || 'individual')
                setDateRange({ start: data.startDate, end: data.endDate })
                setSlots(data.slots)
            } catch (e) {
                console.error('Init error:', e)
            } finally {
                setLoading(false)
            }
        }

        init()
    }, [])

    if (loading) return <div>Loading booking data...</div>

    return (
        <>
            <SidebarFilters/>
            <div className="md:col-span-3 space-y-6">
                <TopFilters />
                <SlotsList/>
            </div>
        </>
    )
}

export default function BookingPage() {
    return (
        <BookingProvider>
            <BookingPageContent/>
        </BookingProvider>
    )
}
