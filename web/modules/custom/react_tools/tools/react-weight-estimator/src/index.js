import * as React from 'react';
import * as ReactDOM from 'react-dom';
import App from './App';
import registerServiceWorker from './registerServiceWorker';
import './localcss/main.css'

const mountNode = document.getElementById('weight-estimator');

ReactDOM.render(<App />, mountNode);
registerServiceWorker();