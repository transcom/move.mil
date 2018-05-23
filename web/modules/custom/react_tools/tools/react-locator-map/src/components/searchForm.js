import React, { Component } from 'react';

class SearchForm extends Component {
  constructor(){
    super();
  }

  handleChange = (event) =>{
    this.props.setSearchLocationFn(event.target.value);
  }

  handleClick = (isLocationServices) =>{
    if(!isLocationServices && !this.props.searchLocation) return;
    this.props.searchFn(isLocationServices);
  }

  render() {
    return (
      <div className="search-container">
        <input type="text" placeholder="e.g. 62225, For Belvoir" value={this.props.searchLocation} onChange={(e) => {this.handleChange(e)}}/>
        <button onClick={() => this.handleClick(false)}>Search</button>
        <button onClick={() => this.handleClick(true)} disabled={this.props.geoLocationDisabled}>Search By Your Location</button>
      </div>
    );
  }
}

export default SearchForm;
