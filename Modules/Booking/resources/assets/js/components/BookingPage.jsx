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
        setLevels, setSubjects,
        selectedLevelId, setSelectedLevelId,
        setSlots,
        selectedSubjectIds, setSelectedSubjectIds,
        lessonType, setLessonType,
        filterStartDate, setFilterStartDate,
        filterEndDate, setFilterEndDate,
        filterStartTime, setFilterStartTime,
        filterEndTime, setFilterEndTime,
        currentEndDate, setCurrentEndDate,
        visibleDatesCount, setVisibleDatesCount,
        loading, setLoading,
    } = useBooking()

    const [isFetchingMore, setIsFetchingMore] = useState(false);
    const hasInitialized = useRef(false);
    const showLoadMoreButton = currentEndDate && dayjs(currentEndDate).isBefore(filterEndDate);

    const fetchDay = function (date) {
        const resultDate = dayjs(date).add(visibleDatesCount, 'day');
        const endDate = dayjs(filterEndDate);

        return resultDate.isAfter(endDate)
            ? endDate.format('YYYY-MM-DD')
            : resultDate.format('YYYY-MM-DD');
    }
    const buildDateTime = function (date, time) {
        return dayjs(`${date}T${time}`).format('YYYY-MM-DD HH:mm');
    };

    const fetchSlots = async ({ startDate, endDate, startTime, endTime, append = false }) => {
        try {
            setLoading(true);
            if (append) setIsFetchingMore(true);

            const response = await axios.get('/booking/list', {
                params: {
                    level_id: selectedLevelId,
                    subject_ids: selectedSubjectIds,
                    start_date: buildDateTime(startDate, startTime),
                    end_date: buildDateTime(endDate, endTime),
                    lesson_type: lessonType,
                }
            });

            const data = response.data;

            if (append) {
                setSlots(prev => ({ ...prev, ...data.slots }));
                setVisibleDatesCount(prev => prev + visibleDatesCount);
            } else {
                setLevels(data.levels);
                setSubjects(data.subjects);
                setSelectedLevelId(data.selectedLevelId);
                setLessonType(data.lessonType || defaultLessonType);
                setSlots(data.slots);
            }

            setCurrentEndDate(endDate);
        } catch (e) {
            console.error(append ? 'Load more error' : 'Initial load error', e);
        } finally {
            if (append) setIsFetchingMore(false);
            setLoading(false);
        }
    };

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
        const startTime = params.get('start_time');
        const endTime = params.get('end_time');
        const today = dayjs().format('YYYY-MM-DD');

        if (levelId) setSelectedLevelId(Number(levelId));
        if (subjectIds.length) setSelectedSubjectIds(subjectIds.map(id => Number(id)));
        if (lessonType) setLessonType(lessonType);
        startDate ? setFilterStartDate(startDate) : setFilterStartDate(today);
        startDate ? setCurrentEndDate(startDate) : setCurrentEndDate(today);
        endDate ? setFilterEndDate(endDate) : setFilterEndDate(fetchDay(today));
        if (startTime) setFilterStartTime(startTime);
        if (endTime) setFilterEndTime(endTime);
    }, []);

    // 2. Update URL when filters change
    useEffect(() => {
        const params = new URLSearchParams()

        if (selectedLevelId) params.set('level_id', selectedLevelId)
        selectedSubjectIds.forEach(id => params.append('subject_ids[]', id))
        if (lessonType) params.set('lesson_type', lessonType)
        if (filterStartDate) params.set('start_date', filterStartDate)
        if (filterEndDate) params.set('end_date', filterEndDate)
        if (filterStartTime) params.set('start_time', filterStartTime)
        if (filterEndTime) params.set('end_time', filterEndTime)

        const newUrl = `${window.location.pathname}?${params.toString()}`
        window.history.replaceState({}, '', newUrl)
    }, [
        selectedLevelId,
        selectedSubjectIds,
        lessonType,
        filterStartDate,
        filterEndDate,
        filterStartTime,
        filterEndTime
    ])

    // 3. Fetch initial slots when currentEndDate sets
    useEffect(() => {
        if (filterStartDate && filterEndDate) {
            const nextEnd = fetchDay(filterStartDate);

            fetchSlots({
                startDate: filterStartDate,
                endDate: nextEnd,
                startTime: filterStartTime,
                endTime: filterEndTime,
                append: false
            });
        }
    }, [
        selectedLevelId,
        selectedSubjectIds,
        lessonType,
        filterStartDate,
        filterEndDate,
        filterStartTime,
        filterEndTime
    ]);

    const loadMore = async () => {
        if (!currentEndDate || dayjs(currentEndDate).isSameOrAfter(filterEndDate)) return;
        const nextEnd = fetchDay(currentEndDate);

        await fetchSlots({
            startDate: currentEndDate,
            endDate: nextEnd,
            startTime: filterStartTime,
            endTime: filterEndTime,
            append: true
        });
    };

    return (
        <>
            <SidebarFilters/>
            <div className="md:col-span-3 space-y-6">
                <TopFilters />
                { loading && <FullscreenLoader />}
                <SlotsList/>
                {showLoadMoreButton && (
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

export default function BookingPage({ lessonType, visibleDatesCount, startPreferredTime, endPreferredTime }) {
    return (
        <BookingProvider
            defaultLessonType={lessonType}
            defaultVisibleDatesCount={visibleDatesCount}
            startPreferredTime={startPreferredTime}
            endPreferredTime={endPreferredTime}
        >
            <>
                <BookingPageContent />
                <Toaster richColors position="top-right" />
            </>
        </BookingProvider>
    )
}
