import React, { useState } from 'react';
import { toast } from 'sonner';
import { bookSlot, cancelBooking } from './bookingApi';
import { ConfirmModal } from './ConfirmModal.jsx';
import { useBooking } from './BookingContext';

export default function SlotCard({ item }) {
    const { setSlots, setLoading } = useBooking();
    const [confirmed, setConfirmed] = useState(false);
    const [cancelConfirm, setCancelConfirm] = useState(false);
    const isBooked = !!item.bookingId;
    const isBookable = item.isBookable;

    const handleBooking = async () => {
        try {
            setConfirmed(false);
            setLoading(true);

            const result = await bookSlot(
                item.stream.id,
                item.slot.id,
                item.slotStartAt,
                item.slotEndAt,
                item.lessonType
            );

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

    const handleNotBookable = async () => {};

    return (
        <div
            className={`flex items-start justify-between border ${isBooked ? 'bg-purple-100 border-purple-400' : 'bg-white border-gray-200'} rounded-md px-6 py-4 relative`}>
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
                    src={`https://ui-avatars.com/api/?name=${encodeURIComponent(item.stream.teacher.firstname)}&size=32`}
                    alt={item.stream.teacher.firstname}
                    className="w-8 h-8 rounded-full"
                />
                <span className="text-sm text-gray-500 uppercase tracking-wide">
                    Group Class with {item.stream.teacher.firstname}
                </span>
            </div>

            {/* Actions */}
            <div className="flex space-x-2">
                {isBooked ? (
                    <>
                        <button
                            className="btn btn-cancel-booking"
                            onClick={() => setCancelConfirm(true)}
                        >
                            Cancel
                        </button>
                        {cancelConfirm && (
                            <ConfirmModal
                                message="Are you sure you want to cancel?"
                                onCancel={() => setCancelConfirm(false)}
                                onConfirm={handleCancel}
                            />
                        )}
                    </>
                ) : (
                    <>
                        <button
                            className={isBookable ? "btn btn-primary" : "btn btn-disabled cursor-not-allowed"}
                            disabled={!isBookable}
                            onClick={() => {
                                if (!isBookable) {
                                    handleNotBookable();
                                } else {
                                    setConfirmed(true);
                                }
                            }}
                        >
                            Book
                        </button>
                        {confirmed && (
                            <ConfirmModal
                                message="Are you sure you want to book?"
                                onCancel={() => setConfirmed(false)}
                                onConfirm={handleBooking}
                            />
                        )}
                    </>
                )}
                <button
                    className={isBookable ? "btn btn-secondary" : "btn btn-disabled cursor-not-allowed"}
                    disabled={!isBookable}>Details</button>
            </div>
        </div>
    );
}
