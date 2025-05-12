import React, { useContext } from 'react';
import { BookingContext } from './BookingContext';
import SlotCard from './SlotCard';

export default function SlotsList() {
    const { slots } = useContext(BookingContext);

    if (!slots || Object.keys(slots).length === 0) {
        return <p className="text-gray-500">No available streams for the selected filters.</p>;
    }

    return (
        <>
            {Object.entries(slots).map(([date, slots]) => (
                <div key={date}>
                    <h3 className="text-md font-medium text-gray-600 bg-gray-100 rounded">
                        {new Date(date).toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' })}
                    </h3>
                    <div className="mt-4 space-y-4">
                        {slots.map((item, index) => (
                            <SlotCard key={`${index}`} item={item} />
                        ))}
                    </div>
                </div>
            ))}
        </>
    );
}
