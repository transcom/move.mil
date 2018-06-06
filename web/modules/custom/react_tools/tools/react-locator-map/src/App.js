import React, { Component } from 'react';
import * as axios from 'axios';
import SearchForm from './components/searchForm';
import Results from './components/results';
import LoadingScreen from  './components/loadingScreen';
import * as _ from 'lodash';

class App extends Component {
  constructor(){
    super();

    this.itemsPerPage = 10;
    this.baseUrl = process.env.NODE_ENV == 'development' ? 'http://move.mil.localhost:8000/' : '/';
    this.state = {
      isLoading: false,
      geolocation: {
        disabled: false,
        coords: null
      },
      searchLocation: '',
      results: null
    };
  }


  getGeolocation = (nextFn) =>{
    let response = {
      disabled: false,
      coords: null
    };

    if("geolocation" in navigator){
      navigator.geolocation.getCurrentPosition((position) => {
        response.disabled = false;
        response.coords = {
          latitude: position.coords.latitude,
          longitude: position.coords.longitude
        }
        nextFn(null, response);
      }, (err)=>{
        nextFn(err.message);
      });
    }else{
      nextFn("Navigator doesn't support geolocation");
    }
  }

  setSearchLocation = (val) =>{
    this.setState({
      searchLocation: val
    });
  }

  onInitialSearchLocation = (isLocationServices) =>{
    if(!isLocationServices && !this.state.searchLocation) return;

    this.setState({isLoading: true});
    if(isLocationServices){
      this.getGeolocation((navigatorErr, navigatorRes)=>{
        if(navigatorErr){
          this.handleError(navigatorErr);
        }else{
          this.requestData(navigatorRes.coords);
        }
      });
    }else{
      this.requestData({ query: this.state.searchLocation });
    }   
  }

  handleError = (errMessage) => {
    this.setState({
      isLoading: false,
      errMessage: errMessage,
      geolocation: {...this.state.geolocation, disabled: true}
    });
  }

  requestData = (options) =>{
      let url = `${this.baseUrl}parser/locator-maps`;
      let coords = options.query ? options : {};

      axios.post(url, options)
        .then(res => {
          let results = res.data;
          coords = {
            latitude: results.geolocation.lat,
            longitude: results.geolocation.lon
          }
          results.selectedPage = 1;

          _.each(results.offices, (office, i)=>{
            office.distanceKm = this.getDistanceFromLatLonInKm(results.geolocation.lat, results.geolocation.lon, office.location.lat, office.location.lon);
            office.distanceMi = 0.621371 * office.distanceKm;
          });

          results.offices = _.sortBy(results.offices, 'distanceMi');

          _.each(results.offices, (office, i)=>{
              office.id = `office-${i}`;
          });

          this.setState({
            geolocation: {...this.state.geolocation, coords: coords},
            results: results,
            isLoading: false
          });
        });
  }


  //TODO REMOVE 
  // ******************** TODO REMOVE **************************
  getDistanceFromLatLonInKm = (lat1,lon1,lat2,lon2) => {
    var R = 6371; // Radius of the earth in km
    var dLat = this.deg2rad(lat2-lat1);  // deg2rad below
    var dLon = this.deg2rad(lon2-lon1); 
    var a = 
      Math.sin(dLat/2) * Math.sin(dLat/2) +
      Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) * 
      Math.sin(dLon/2) * Math.sin(dLon/2)
      ; 
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
    var d = R * c; // Distance in km
    return d;
  }
  
  deg2rad = (deg) => {
    return deg * (Math.PI/180);
  }
  // ******************** END TODO REMOVE **************************

  changePageNo = (pageNo, totalPages) => {
    pageNo = pageNo === '1' ? this.state.results.selectedPage + 1 : pageNo;
    pageNo = pageNo === '-1' ? this.state.results.selectedPage - 1 : pageNo;

    if(pageNo < 1 || pageNo > totalPages || pageNo === this.state.results.selectedPage) return;

    this.setState({
      results: {...this.state.results, selectedPage: pageNo}
    });
  }

  displayResultComponent = () =>{
    if(this.state.results){
      return (
        <Results resultData={this.state.results}
                 itemsPerPage={this.itemsPerPage}
                 changePageFn={this.changePageNo}/>
      )
    }
  }

  showErrorMessage = () =>{
    if(this.state.errMessage){
      return (
        <div className="errorMessage">{this.state.errMessage}</div>
      )
    }
  }

  render() {
    return (
      <div className="locator-map-container">
        <LoadingScreen isLoading={this.state.isLoading}/>
        <SearchForm setSearchLocationFn={this.setSearchLocation} 
                    searchFn={this.onInitialSearchLocation} 
                    searchLocation={this.state.searchLocation}
                    geoLocationDisabled={this.state.geolocation.disabled}/>
                    
        {this.showErrorMessage()}
        {this.displayResultComponent()}
      </div>
    );
  }
}

export default App;
