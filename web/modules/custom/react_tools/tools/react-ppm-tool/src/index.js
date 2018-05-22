import React from 'react';
import ReactDOM from 'react-dom';
import './localcss/main.css';
import 'leaflet/dist/leaflet.css';
import App from './App';
import registerServiceWorker from './registerServiceWorker';

ReactDOM.render(<App />, document.getElementById('ppm-tool'));
registerServiceWorker();
