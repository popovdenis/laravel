import ReactDOM from 'react-dom/client'
import BookingPage from '../components/BookingPage'

const el = document.getElementById('booking-root')
if (el) {
    ReactDOM.createRoot(el).render(<BookingPage />)
}
