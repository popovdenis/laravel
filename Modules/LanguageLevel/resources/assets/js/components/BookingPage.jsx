import React, { useEffect, useRef, useState } from 'react';
import { BookingProvider, useBooking } from './BookingContext';
import axios from 'axios';
import dayjs from 'dayjs';
import { Toaster } from 'sonner';
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter';
dayjs.extend(isSameOrAfter)
import SidebarFilters from "./SidebarFilters.jsx";
import SlotsList from "./SlotsList.jsx";
import TopFilters from "./TopFilters.jsx";
import FullscreenLoader from './FullscreenLoader';

function BookingPageContent() {
    const {
        setLevels,
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
        currentEndDate,
        setCurrentEndDate,
        setVisibleDatesCount
    } = useBooking()

    const [loading, setLoading] = useState(true);
    const [isFetchingMore, setIsFetchingMore] = useState(false);
    const hasInitialized = useRef(false);
    const DAYS_RANGE = 7;

    // 1. Init from URL once
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

        const today = dayjs().format('YYYY-MM-DD');
        if (startDate) {
            setFilterStartDate(startDate);
            setCurrentEndDate(startDate);
        } else {
            setFilterStartDate(today);
            setCurrentEndDate(today);
        }
        if (endDate) {
            setFilterEndDate(endDate);
        } else {
            setFilterEndDate(dayjs(today).add(DAYS_RANGE, 'day').format('YYYY-MM-DD'));
        }
    }, []);

    // 2. Update URL when filters change
    useEffect(() => {
        const params = new URLSearchParams()

        if (selectedLevelId) params.set('level_id', selectedLevelId)
        selectedSubjectIds.forEach(id => params.append('subject_ids[]', id))
        if (lessonType) params.set('lesson_type', lessonType)
        if (filterStartDate) params.set('start_date', filterStartDate)
        if (filterEndDate) params.set('end_date', filterEndDate)

        const newUrl = `${window.location.pathname}?${params.toString()}`
        window.history.replaceState({}, '', newUrl)
    }, [selectedLevelId, selectedSubjectIds, lessonType, filterStartDate, filterEndDate])

    // 3. Fetch initial slots when currentEndDate sets
    useEffect(() => {
        const fetchInitial = async () => {
            try {
                setLoading(true);

                const response = await axios.get('/levels/init', {
                    params: {
                        level_id: selectedLevelId,
                        subject_ids: selectedSubjectIds,
                        start_date: filterStartDate,
                        end_date: filterEndDate,
                        lesson_type: lessonType,
                    }
                });

                const data = response.data;
                setLevels(data.levels)
                setSubjects(data.subjects)
                setSelectedLevelId(data.selectedLevelId)
                setLessonType(data.lessonType || 'individual')
                setSlots(data.slots)
                setCurrentEndDate(filterEndDate)
            } catch (e) {
                console.error('Initial load error', e)
            } finally {
                setLoading(false)
            }
        }

        if (filterStartDate && filterEndDate) {
            fetchInitial()
        }
    }, [selectedLevelId, selectedSubjectIds, lessonType, filterStartDate, filterEndDate])

    const loadMore = async () => {
        if (!currentEndDate || dayjs(currentEndDate).isSameOrAfter(filterEndDate)) return

        const nextEnd = dayjs(currentEndDate).add(DAYS_RANGE, 'day').format('YYYY-MM-DD')

        try {
            setIsFetchingMore(true);

            const response = await axios.get('/levels/init', {
                params: {
                    level_id: selectedLevelId,
                    subject_ids: selectedSubjectIds,
                    start_date: currentEndDate,
                    end_date: nextEnd,
                    lesson_type: lessonType,
                }
            })

            setSlots(function(prev) {
                return ({ ...prev, ...response.data.slots });
            })
            setVisibleDatesCount(prev => prev + 5)
            setCurrentEndDate(nextEnd)
        } catch (e) {
            console.error('Load more error', e)
        } finally {
            setIsFetchingMore(false)
        }
    }

    return (
        <>
            <SidebarFilters/>
            <div className="md:col-span-3 space-y-6">
                <TopFilters />
                { loading && <FullscreenLoader />}
                <SlotsList/>
                {(
                    <div className="text-center">
                        <button onClick={loadMore} disabled={isFetchingMore} className="btn btn-primary">
                            {isFetchingMore ? 'Loading...' : 'Load More'}
                        </button>
                    </div>
                )}
            </div>
        </>
    )
}

export default function BookingPage() {
    return (
        <BookingProvider>
            <>
                <BookingPageContent />
                <Toaster richColors position="top-right" />
            </>
        </BookingProvider>
    )
}
