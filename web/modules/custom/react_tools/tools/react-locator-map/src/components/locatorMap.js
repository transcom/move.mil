import React, { Component }  from 'react';
import { Map, TileLayer, Marker, Popup, L } from 'react-leaflet';
import { divIcon } from 'leaflet';
import * as _ from 'lodash';

class LocatorMap extends Component {
    constructor(props) {
        super(props);
    }

    isType = (type, value) =>{
      return type.toLowerCase().indexOf(value) !== -1;
    }

    markerComponent = (markers) =>{
      return  _.map(markers, (office, i)=>{
          let icon;
          let isPopup = true;
          switch(true){
            case this.isType(office.type, 'geolocation'):
              icon = divIcon({className: 'map-marker-pulse'});
              isPopup = false;
            break;
            case this.isType(office.type, 'weight'):
              icon = divIcon({className: 'map-marker weight-scale'});
            break;
            case this.isType(office.type, 'transportation'):
              icon = divIcon({className: 'map-marker processing-office'});
            break;
          }

          return (
            <Marker position={office.coords} key={i} icon={icon}>
              {isPopup ? <Popup>
                <div className="map-popup">
                  <div>{office.title}</div>
                  <a href={`#${office.id}`}>View Details</a>
                </div>
              </Popup> : null}
              
            </Marker>
          )
      });
    }

    getMarkersObject = () =>{
      let markersObject = {
        markers: [
          {
            type: 'geolocation',
            coords: this.props.centerCoords
          }
        ],
        bounds: []
      };

      _.each(this.props.offices, (office, i)=>{
        if(office.location.lat && office.location.lon){
          office.coords = [parseFloat(office.location.lat), parseFloat(office.location.lon)];
          markersObject.markers.push(office);
          markersObject.bounds.push(office.coords);
        }
      })
      return markersObject;
    }

    render() {
      let zoom = 13;
      let markersObject = this.getMarkersObject();

      return (
        // center={this.props.centerCoords} 
        <Map zoom={zoom} id="map-container" bounds={markersObject.bounds} scrollWheelZoom={false}>
          <TileLayer
            attribution="&amp;copy <a href=&quot;http://osm.org/copyright&quot;>OpenStreetMap</a> contributors"
            url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
          />
          {this.markerComponent(markersObject.markers)}
        </Map>
      )
    }
}

export default LocatorMap;
