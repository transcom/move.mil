import React, { Component }  from 'react';
import * as _ from 'lodash';
import {
  withScriptjs,
  withGoogleMap,
  GoogleMap,
  Marker,
  Polyline
} from "react-google-maps";
const { compose, lifecycle } = require("recompose");
const apiKey = process.env.GOOGLE_MAPS_API_KEY;
const gMapUrl = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&v=3.exp&libraries=geometry,drawing,places`;

class ResultMap extends Component {

    markerComponent = () =>{
      return (
        _.map(this.props.map.coords, (coord, i)=>{
          return <Marker key={i} position={this.getGoogleMapsCoordObject(coord)} />
        })
      )
    }

    getGoogleMapsCoordObject = (coord) =>{
      return {
        lat: parseFloat(coord[0]),
        lng: parseFloat(coord[1]),
      }
    }

    render() {
      let defaultMapOptions = {
        disableDefaultUI: true,
        zoomControl: true
      }
      let zoom = 13;
      let lineColor = '#0400ff';
      let polyLinePath = [
        this.getGoogleMapsCoordObject(this.props.map.coords.origin),
        this.getGoogleMapsCoordObject(this.props.map.coords.destination)
      ];
      let _bounds = {
        origin: this.getGoogleMapsCoordObject(this.props.map.coords.origin),
        dest: this.getGoogleMapsCoordObject(this.props.map.coords.destination)
      };

      const MapWithMarkers = compose(
        lifecycle({
          componentDidMount() {
              this.setState({
                  zoomToMarkers: map => {
                      const bounds = new window.google.maps.LatLngBounds();
                      _.each(map.props.outerBounds, (_b)=>{
                        bounds.extend(new window.google.maps.LatLng(_b.lat, _b.lng));
                      });
                      map.fitBounds(bounds);
                  }
              })
          },
      }),
      withScriptjs,
      withGoogleMap
      )(props =>
        <GoogleMap 
          outerBounds={_bounds}
          ref={props.zoomToMarkers}
          defaultZoom={zoom}
          defaultOptions={defaultMapOptions}
          defaultCenter={this.getGoogleMapsCoordObject(this.props.map.center)}>
          {this.markerComponent()}
          <Polyline path={polyLinePath} options={{strokeColor: lineColor, strokeWeight: '3', strokeOpacity: '1'}}/>
        </GoogleMap>
      );
      
      return (
        <MapWithMarkers
          googleMapURL={gMapUrl}
          loadingElement={<div style={{ height: `100%` }} />}
          containerElement={<div style={{ height: `300px` }} />}
          mapElement={<div style={{ height: `100%` }} />}
      />
      )
    }
}

export default ResultMap;
