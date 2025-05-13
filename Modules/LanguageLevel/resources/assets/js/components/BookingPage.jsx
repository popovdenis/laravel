import React, { useEffect, useRef, useState } from 'react'
import { BookingProvider, useBooking } from './BookingContext'
import axios from 'axios'
import SidebarFilters from "./SidebarFilters.jsx";
import SlotsList from "./SlotsList.jsx";
import TopFilters from "./TopFilters.jsx";

function BookingPageContent() {
    const {
        levels,
        setLevels,
        subjects,
        setSubjects,
        selectedLevelId,
        setSelectedLevelId,
        setSlots,
        selectedSubjectIds,
        setSelectedSubjectIds,
        lessonType,
        setLessonType,
        filterStartDate,
        filterEndDate,
        setFilterStartDate,
        setFilterEndDate,
    } = useBooking()

    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchSlots = async () => {
            try {
                setLoading(true)
                const response = await axios.get('/levels/init', {
                    params: {
                        level_id: selectedLevelId,
                        subject_ids: selectedSubjectIds,
                        start_date: filterStartDate,
                        end_date: filterEndDate,
                        lesson_type: lessonType,
                    },
                });

                const data = response.data;

                setLevels(data.levels)
                setSubjects(data.subjects)
                setSelectedLevelId(data.selectedLevelId)
                // setSelectedSubjectIds(data.selectedSubjectIds || [])
                setLessonType(data.lessonType || 'individual')
                setFilterStartDate(data.filterStartDate)
                setFilterEndDate(data.filterEndDate)
                setSlots(data.slots)
            } catch (e) {
                console.error('Init error:', e)
            } finally {
                setLoading(false)
            }
        }
        fetchSlots();
    }, [selectedLevelId, selectedSubjectIds, lessonType, filterStartDate, filterEndDate])

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
