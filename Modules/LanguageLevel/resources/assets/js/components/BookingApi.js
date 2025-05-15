import axios from 'axios';

export async function bookSlot(streamId, slotId, slotStartAt) {
    const response = await axios.post('/booking/store', {
        stream_id: streamId,
        slot_id: slotId,
        slotStartAt: slotStartAt,
    })

    return { success: true, message: 'Booking successful.', data: response.data };
}

export async function cancelBooking(bookingId) {
    try {
        const response = await axios.post('/booking/cancel', {
            booking_id: bookingId,
        })

        return { success: true, message: 'Booking cancelled.', data: response.data }
    } catch (error) {
        const message = error.response?.data?.message || 'Cancellation failed.'
        return { success: false, message }
    }
}
