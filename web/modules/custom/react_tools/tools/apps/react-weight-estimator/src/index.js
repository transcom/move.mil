import 'react-app-polyfill/ie11';
import 'react-app-polyfill/stable';
import * as React from 'react';
import * as ReactDOM from 'react-dom';
import App from './App';
import './localcss/main.css'

const mountNode = document.getElementById('weight-estimator');

ReactDOM.render(<App />, mountNode);
