import React, { useState } from 'react';
import { toast } from 'sonner';
import { bookSlot, cancelBooking } from './bookingApi';
import { useBooking } from './BookingContext';

export default function SlotCard({ item }) {
    const { slots, setSlots, loading, setLoading } = useBooking();
    const [confirmed, setConfirmed] = useState(false);
    const [cancelConfirm, setCancelConfirm] = useState(false);
    const isBooked = !!item.bookingId;

    const handleBooking = async () => {
        try {
            setConfirmed(false);
            setLoading(true);

            const result = await bookSlot(item.stream.id, item.slot.id, item.slotStartAt);

            setSlots(prev => {
                const updated = { ...prev }
                for (const date in updated) {
                    updated[date] = updated[date].map(slot =>
                        slot.slot.id === item.slot.id && slot.slotStartAt === item.slotStartAt
                            ? { ...slot, bookingId: result.data.booking_id }
                            : slot
                    )
                }
                return updated
            });

            toast.success(result.message);
        } catch (error) {
            toast.error(error.response?.data?.message || 'Booking failed.')
        } finally {
            setLoading(false);
        }
    }

    const handleCancel = async () => {
        try {
            setConfirmed(false);
            setLoading(true);

            await cancelBooking(item.bookingId);

            setSlots(prev => {
                const updated = { ...prev }
                for (const date in updated) {
                    updated[date] = updated[date].map(slot =>
                        slot.slot.id === item.slot.id && slot.slotStartAt === item.slotStartAt
                            ? { ...slot, bookingId: null }
                            : slot
                    )
                }
                return updated
            })
        } catch (error) {
            toast.error(error.response?.data?.message || 'Booking cancellation is failed.')
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className={`flex items-start justify-between border ${isBooked ? 'bg-purple-100 border-purple-400' : 'bg-white border-gray-200'} rounded-md px-6 py-4 relative`}>
            {/* Time */}
            <div className="w-24 px-2 text-blue-700 font-bold text-sm uppercase">{item.time}</div>

            {/* Subject details */}
            <div className="flex-1 px-2">
                <p className="text-xs text-gray-500 uppercase tracking-wide">
                    {item.stream.language_level.title}
                    • Chapter {item.stream.current_subject_number}
                    • {(item.subject?.category ?? '').toUpperCase()}
                </p>
                <p className="text-sm text-gray-800 font-semibold">{item.subject?.title ?? 'No subject selected'}</p>
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
                    <>
                        <button className="btn btn-cancel-booking" onClick={() => setCancelConfirm(true)}>Cancel</button>
                        {cancelConfirm && (
                            <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                <div className="bg-white p-6 rounded shadow-md text-center">
                                    <p className="mb-4 text-gray-800 font-medium">Are you sure you want to cancel?</p>
                                    <div className="flex justify-center space-x-2">
                                        <button className="btn btn-secondary" onClick={() => setCancelConfirm(false)}>No</button>
                                        <button className="btn btn-cancel" onClick={handleCancel}>Yes</button>
                                    </div>
                                </div>
                            </div>
                        )}
                    </>
                ) : (
                    <>
                        <button className="btn btn-primary" onClick={() => setConfirmed(true)}>Book</button>
                        {confirmed && (
                            <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                <div className="bg-white p-6 rounded shadow-md text-center">
                                    <p className="mb-4 text-gray-800 font-medium">Are you sure you want to book?</p>
                                    <div className="flex justify-center space-x-2">
                                        <button className="btn btn-secondary" onClick={() => setConfirmed(false)}>Cancel</button>
                                        <button className="btn btn-primary" onClick={handleBooking}>Confirm</button>
                                    </div>
                                </div>
                            </div>
                        )}
                    </>
                )}
                <button className="btn btn-secondary">Details</button>
            </div>
        </div>
    );
}
