import React from 'react'
import { useBooking } from './BookingContext'

export default function SidebarFilters() {
    const {
        levels,
        subjects,
        selectedLevelId,
        setSelectedLevelId,
        selectedSubjectIds,
        setSelectedSubjectIds,
    } = useBooking()

    const handleLevelChange = (e) => {
        const newLevelId = parseInt(e.target.value)
        setSelectedLevelId(newLevelId)
        // reset subjects
        setSelectedSubjectIds([])
    }

    const handleSubjectToggle = (subjectId) => {
        setSelectedSubjectIds(prev => {
            return prev.includes(subjectId)
                ? prev.filter(id => id !== subjectId)
                : [...prev, subjectId]
        })
    }

    const groupedSubjects = subjects.reduce((acc, subject) => {
        const chapter = subject.chapter || 'General'
        if (!acc[chapter]) acc[chapter] = []
        acc[chapter].push(subject)
        return acc
    }, {})

    return (
        <aside className="bg-white border rounded shadow-sm p-4 space-y-6 md:col-span-1">
            <div>
                <select
                    value={selectedLevelId || ''}
                    onChange={handleLevelChange}
                    className="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2"
                >
                    <option value="" disabled>Select Level</option>
                    {levels.map(level => (
                        <option key={level.id} value={level.id}>
                            {level.title}
                        </option>
                    ))}
                </select>
            </div>

            {selectedLevelId && (
                <div className="space-y-4">
                    {Object.entries(groupedSubjects).map(([chapter, group]) => (
                        <div key={chapter}>
                            <p className="text-sm font-semibold text-gray-800 mb-2">{chapter}</p>
                            <div className="space-y-2">
                                {group.map(subject => (
                                    <div key={subject.id} className="flex items-center">
                                        <input
                                            type="checkbox"
                                            id={`subject-${subject.id}`}
                                            checked={selectedSubjectIds.includes(subject.id)}
                                            onChange={() => handleSubjectToggle(subject.id)}
                                            className="text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        />
                                        <label htmlFor={`subject-${subject.id}`} className="ml-2 text-sm text-gray-700">
                                            {subject.title}
                                        </label>
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </aside>
    )
}
