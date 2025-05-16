import { createContext, useContext, useState } from 'react';

export const BookingContext = createContext();

export function BookingProvider({ children, defaultLessonType, defaultVisibleDatesCount }) {
    const [levels, setLevels] = useState([]);
    const [subjects, setSubjects] = useState([]);
    const [selectedLevelId, setSelectedLevelId] = useState(null);
    const [selectedSubjectIds, setSelectedSubjectIds] = useState([]);
    const [initialLessonType, setInitialLessonType] = useState(defaultLessonType);
    const [lessonType, setLessonType] = useState(defaultLessonType);
    const [filterStartDate, setFilterStartDate] = useState(null);
    const [filterEndDate, setFilterEndDate] = useState(null);
    const [slots, setSlots] = useState([]);
    const [currentEndDate, setCurrentEndDate] = useState(null);
    const [visibleDatesCount, setVisibleDatesCount] = useState(defaultVisibleDatesCount);
    const [loading, setLoading] = useState(false);

    return (
        <BookingContext.Provider value={{
            levels,
            setLevels,
            subjects,
            setSubjects,
            selectedLevelId,
            setSelectedLevelId,
            selectedSubjectIds,
            setSelectedSubjectIds,
            initialLessonType,
            lessonType,
            setLessonType,
            filterStartDate,
            setFilterStartDate,
            filterEndDate,
            setFilterEndDate,
            slots,
            setSlots,
            currentEndDate,
            setCurrentEndDate,
            visibleDatesCount,
            setVisibleDatesCount,
            loading,
            setLoading,
        }}>
            {children}
        </BookingContext.Provider>
    );
}

export const useBooking = () => useContext(BookingContext);
