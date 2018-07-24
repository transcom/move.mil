import Map from 'core-js/es6/map';
import Set from 'core-js/es6/set';
import React from 'react';
import ReactDOM from 'react-dom';
import './localcss/main.css';
import 'leaflet/dist/leaflet.css';
import App from './App';
import L from 'leaflet';
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png')
});

ReactDOM.render(<App />, document.getElementById('locator-map'));
