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
    const hasInitialized = useRef(false);

    useEffect(() => {
        if (hasInitialized.current) return;
        hasInitialized.current = true;

        const params = new URLSearchParams(window.location.search);
        const levelId = params.get('level_id');
        const subjectIds = params.getAll('subject_ids[]');
        const lessonType = params.get('lesson_type');
        const startDate = params.get('start_date');
        const endDate = params.get('end_date');

        if (levelId) setSelectedLevelId(Number(levelId));
        if (subjectIds.length) setSelectedSubjectIds(subjectIds.map(id => Number(id)));
        if (lessonType) setLessonType(lessonType);
        if (startDate) setFilterStartDate(startDate);
        if (endDate) setFilterEndDate(endDate);
    }, []);

    useEffect(() => {
        const params = new URLSearchParams()

        if (selectedLevelId) params.set('level_id', selectedLevelId)
        if (selectedSubjectIds.length > 0) {
            selectedSubjectIds.forEach(id => params.append('subject_ids[]', id))
        }
        if (lessonType) params.set('lesson_type', lessonType)
        if (filterStartDate) params.set('start_date', filterStartDate)
        if (filterEndDate) params.set('end_date', filterEndDate)

        const newUrl = `${window.location.pathname}?${params.toString()}`
        window.history.replaceState({}, '', newUrl)
    }, [selectedLevelId, selectedSubjectIds, lessonType, filterStartDate, filterEndDate])

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
