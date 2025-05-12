// components/SlotCard.jsx
import React from 'react';

export default function SlotCard({ item }) {
    const isBooked = !!item.bookingId;

    return (
        <div className={`flex items-start justify-between border ${isBooked ? 'bg-purple-100 border-purple-400' : 'bg-white border-gray-200'} rounded-md px-6 py-4`}>
            {/* Time */}
            <div className="w-24 px-2 text-blue-700 font-bold text-sm uppercase">
                {item.time}
            </div>

            {/* Subject details */}
            <div className="flex-1 px-2">
                <p className="text-xs text-gray-500 uppercase tracking-wide">
                    {item.stream.language_level.title} • Chapter {item.stream.current_subject_number} • {(item.subject?.category ?? '').toUpperCase()}
                </p>
                <p className="text-sm text-gray-800 font-semibold">
                    {item.subject?.title ?? 'No subject selected'}
                </p>
            </div>

            {/* Teacher */}
            <div className="flex px-4 items-center gap-2">
                <img
                    src={`https://ui-avatars.com/api/?name=${encodeURIComponent(item.teacher.firstname)}&size=32`}
                    alt={item.teacher.firstname}
                    className="w-8 h-8 rounded-full"
                />
                <span className="text-sm text-gray-500 uppercase tracking-wide">
                    Group Class with {item.teacher.firstname}
                </span>
            </div>

            {/* Actions */}
            <div className="flex space-x-2">
                {isBooked ? (
                    <form method="POST" action="/booking/cancel">
                        <input type="hidden" name="_token" value={document.querySelector('meta[name=csrf-token]').content} />
                        <input type="hidden" name="booking_id" value={item.bookingId} />
                        <button type="submit" className="btn btn-cancel-booking">Cancel Booking</button>
                    </form>
                ) : (
                    <form method="POST" action="/booking/store">
                        <input type="hidden" name="_token" value={document.querySelector('meta[name=csrf-token]').content} />
                        <input type="hidden" name="stream_id" value={item.stream.id} />
                        <input type="hidden" name="slot_id" value={item.slot.id} />
                        <button type="submit" className="btn btn-primary">Book</button>
                    </form>
                )}
                <button className="btn btn-secondary">Details</button>
            </div>
        </div>
    );
}
