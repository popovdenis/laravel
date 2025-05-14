import { createContext, useContext, useState } from 'react';

export const BookingContext = createContext();

export function BookingProvider({ children }) {
    const [levels, setLevels] = useState([]);
    const [subjects, setSubjects] = useState([]);
    const [selectedLevelId, setSelectedLevelId] = useState(null);
    const [selectedSubjectIds, setSelectedSubjectIds] = useState([]);
    const [lessonType, setLessonType] = useState('individual');
    const [filterStartDate, setFilterStartDate] = useState(null);
    const [filterEndDate, setFilterEndDate] = useState(null);
    const [slots, setSlots] = useState([]);
    const [currentEndDate, setCurrentEndDate] = useState(null);
    const [visibleDatesCount, setVisibleDatesCount] = useState(5);

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
            setVisibleDatesCount
        }}>
            {children}
        </BookingContext.Provider>
    );
}

export const useBooking = () => useContext(BookingContext);
