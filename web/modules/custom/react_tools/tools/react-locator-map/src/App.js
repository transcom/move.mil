import React, { Component } from 'react';
import * as axios from 'axios';
import SearchForm from './components/searchForm';
import Results from './components/results';
import * as _ from 'lodash';

class App extends Component {
  constructor(){
    super();

    this.baseUrl = process.env.NODE_ENV == 'development' ? 'http://move.mil.localhost:8000/' : '/';
    this.state = {
      geolocation: {
        disabled: true,
        coords: null
      },
      searchLocation: '',
      results: null
    };
  }

  componentDidMount = () => {
    this.getGeolocation((res)=>{
      this.setState({
        geolocation: res
      });
    });
  }

  getGeolocation = (nextFn) =>{
    let response = {
      disabled: false,
      coords: null
    };

    if("geolocation" in navigator){
      navigator.geolocation.getCurrentPosition(function(position) {
        response.disabled = false;
        response.coords = {
          latitude: position.coords.latitude,
          longitude: position.coords.longitude
        }
        nextFn(response);
      });
    }else{
      nextFn(response);
    }
  }

  setSearchLocation = (val) =>{
    this.setState({
      searchLocation: val
    });
  }

  onInitialSearchLocation = (isLocationServices) =>{
    if(!isLocationServices && !this.state.searchLocation) return;

    let url = `${this.baseUrl}parser/locator-maps`;

    let options = isLocationServices ? this.state.geolocation.coords : { query: '22314' /*this.state.searchLocation*/ };

    axios.post(url, options)
      .then(res => {
        let results = res.data;
        results.selectedPage = 1;
        results.offices = _.sortBy(results.offices, 'distance');

        this.setState({
          results: results
        });
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
        <Results initSearchLocation={this.state.searchLocation} 
                 resultData={this.state.results}
                 changePageFn={this.changePageNo}/>
      )
    }
  }

  render() {
    return (
      <div className="locator-map-container">
        <SearchForm setSearchLocationFn={this.setSearchLocation} 
                    searchFn={this.onInitialSearchLocation} 
                    searchLocation={this.state.searchLocation}
                    geoLocationDisabled={this.state.geolocation.disabled}/>
        {this.displayResultComponent()}
      </div>
    );
  }
}

export default App;
