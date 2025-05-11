import React from 'react';
import ReactDOM from 'react-dom/client';

import CurrentUserSubscription from './components/CurrentUserSubscription';

const el = document.getElementById('current-user-subscription');
if (el) {
    const props = JSON.parse(el.dataset.props);
    const root = ReactDOM.createRoot(el);
    root.render(<CurrentUserSubscription {...props} />);
}
