import React, { Component } from 'react';
import * as axios from 'axios';
import SearchForm from './components/searchForm';
import Results from './components/results';
import LoadingScreen from  './components/loadingScreen';
import ErrorMessage from './components/errorMessage';
import * as _ from 'lodash';

class App extends Component {
  constructor(){
    super();

    this.itemsPerPage = 10;
    this.baseUrl = process.env.BASE_URL;
    this.state = {
      isLoading: false,
      geolocation: {
        disabled: false,
        coords: null
      },
      searchLocation: '',
      results: null,
      errorMessage: null
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
        nextFn({title: "We can’t get your location", message: "Your browser is set to block your physical location. To search by location, please allow access."});
      });
    }else{
      nextFn({title: "We can’t get your location", message: "Either your browser or the Windows operating system is set to block your physical location. To search by location, you need to allow this."});
    }
  }

  setSearchLocation = (val) =>{
    this.setState({
      searchLocation: val
    });
  }

  onInitialSearchLocation = (isLocationServices) =>{
    if(!isLocationServices && !this.state.searchLocation) return;

    this.setState({isLoading: true, errorMessage: null});
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

  setValidationFlag = (val) =>{
    this.setState({
      validationError: val
    });
  }

  handleError = (errMessage) => {
    this.setState({
      isLoading: false,
      errorMessage: errMessage,
      geolocation: {...this.state.geolocation, disabled: true}
    });
  }

  requestData = (options) =>{
      let url = `${this.baseUrl}parser/locator-maps`;
      axios.post(url, options)
       .then(res => {
          this.handleSearchResponse(res.data);
       }).catch(error => {
         this.handleError({title: "Connection problem", message: "There was a problem connecting to the map service. Please refresh and try again."});
       });
  }

  handleSearchResponse = (results) =>{
    if(!results.offices){
      this.handleError({title: "We can’t find that location", message: "There was a problem. Please double check the location and try again."});
      return;
    }

    let coords = {
      latitude: results.geolocation.lat,
      longitude: results.geolocation.lon
    }
    results.selectedPage = 1;
    results.offices = _.sortBy(results.offices, 'distance_mi');

    _.each(results.offices, (office, i)=>{
        office.id = `office-${i}`;
    });

    this.setState({
      geolocation: {...this.state.geolocation, coords: coords},
      results: results,
      isLoading: false,
      errorMessage: null
    });
  }

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

  render() {
    return (
      <div className="locator-map-container">
        <LoadingScreen isLoading={this.state.isLoading}/>
        <SearchForm setSearchLocationFn={this.setSearchLocation} 
                    searchFn={this.onInitialSearchLocation} 
                    searchLocation={this.state.searchLocation}
                    geoLocationDisabled={this.state.geolocation.disabled}
                    isInvalidFields={this.state.validationError}
                    setValidationFlagFn={this.setValidationFlag}/>

        <ErrorMessage error={this.state.errorMessage}/>
                    
        {this.displayResultComponent()}
      </div>
    );
  }
}

export default App;
