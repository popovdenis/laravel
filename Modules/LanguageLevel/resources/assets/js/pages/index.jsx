import ReactDOM from 'react-dom/client'
import BookingPage from '../components/BookingPage'

const el = document.getElementById('booking-root')
if (el) {
    const lessonType = el.dataset.lessonType;
    const visibleDatesCount = el.dataset.visibleDatesCount;

    ReactDOM.createRoot(el).render(
        <BookingPage
            lessonType={lessonType}
            visibleDatesCount={visibleDatesCount}
        />
    )
}
