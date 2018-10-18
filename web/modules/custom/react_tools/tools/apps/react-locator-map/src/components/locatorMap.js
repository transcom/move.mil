import React, { Component }  from 'react';
import * as _ from 'lodash';
import {
  withScriptjs,
  InfoWindow,
  withGoogleMap,
  GoogleMap,
  Marker,
} from "react-google-maps";
const { compose, withStateHandlers } = require("recompose");
const apiKey = process.env.GOOGLE_MAPS_API_KEY;
const baseUrl = process.env.BASE_URL;
const gMapUrl = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&v=3.exp&libraries=geometry,drawing,places`;
const iconPaths = {
  geolocation: `${baseUrl}/themes/custom/move_mil/assets/img/icons/map-pulse.svg`,
  weight: `${baseUrl}/themes/custom/move_mil/assets/img/icons/marker-weight-scale.svg`,
  transportation: `${baseUrl}/themes/custom/move_mil/assets/img/icons/marker-transportation-office.svg`
}

class LocatorMap extends Component {

    isType = (type, value) =>{
      return type.toLowerCase().indexOf(value) !== -1;
    }

    getIconObject = (marker) => {
      switch(true){
        case this.isType(marker.type, 'geolocation'):
          return iconPaths.geolocation;

        case this.isType(marker.type, 'weight'):
          return iconPaths.weight;

        case this.isType(marker.type, 'transportation'):
          return iconPaths.transportation;

        default: 
          return;
      }
    }

    getGoogleMarkersArray = (props) =>{
      return _.map(this.props.offices, (office, key) =>{
        if(office.location.geolocation.lat && office.location.geolocation.lng){
          let _icon = this.getIconObject(office);

          return <Marker key={`office_${key}`}
            position={{ lat: parseFloat(office.location.geolocation.lat), lng: parseFloat(office.location.geolocation.lng) }}
            icon={_icon}
            onClick={() => props.onToggleOpen(office.id)}
          >
            {props.isOpenId === office.id && <InfoWindow onCloseClick={() => props.onToggleOpen(null)}>
              <div className="map-popup">
                <div>{office.title}</div>
                <a href={`#${office.id}`}>View Details</a>
              </div>
            </InfoWindow>}
          </Marker>
        }
      });
    }

    render() {
      let defaultMapOptions = {
        disableDefaultUI: true,
        zoomControl: true
      }
      let center = { lat: parseFloat(this.props.centerCoords.lat), lng: parseFloat(this.props.centerCoords.lon) };
      const MapWithMarkerAndInfoWindow = compose(
        withScriptjs,
        withGoogleMap,
        withStateHandlers(() => ({
          isOpenId: null,
        }), {
          onToggleOpen: ({ isOpenId }) => (isOpenId) => ({
            isOpenId: isOpenId,
          })
        }),
      )(props =>
        <GoogleMap
          defaultZoom={8}
          defaultCenter={center}
          defaultOptions={defaultMapOptions}
        >
          <Marker key={`geolocation`}
            position={{ lat: parseFloat(this.props.centerCoords.lat), lng: parseFloat(this.props.centerCoords.lon) }}
            icon={this.getIconObject({type: 'geolocation'})}/>

          {this.getGoogleMarkersArray(props)}
        </GoogleMap>
      );
      
      return (
        <MapWithMarkerAndInfoWindow
          googleMapURL={gMapUrl}
          loadingElement={<div style={{ height: `100%` }} />}
          containerElement={<div style={{ height: `400px` }} />}
          mapElement={<div style={{ height: `100%` }} />}
      />
      )
    }
}



export default LocatorMap;
