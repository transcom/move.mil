import Map from 'core-js/es6/map';
import Set from 'core-js/es6/set';
import React from 'react';
import ReactDOM from 'react-dom';
import './localcss/main.css';
import App from './App';
import registerServiceWorker from './registerServiceWorker';

ReactDOM.render(<App />, document.getElementById('entitlements-block'));
registerServiceWorker();
