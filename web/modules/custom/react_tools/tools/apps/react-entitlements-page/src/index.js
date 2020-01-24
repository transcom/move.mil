import 'react-app-polyfill/ie11';
import 'react-app-polyfill/stable';
import React from 'react';
import ReactDOM from 'react-dom';
import './localcss/main.css';
import App from './App';

ReactDOM.render(<App />, document.getElementById('entitlements-block'));
