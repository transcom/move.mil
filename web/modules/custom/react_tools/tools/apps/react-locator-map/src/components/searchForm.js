import React, { Component } from 'react';
import InputValidation from './inputValidation';

class SearchForm extends Component {

  handleChange = (event) =>{
    if(this.props.isInvalidFields){
      this.props.setValidationFlagFn(false);
    }
    this.props.setSearchLocationFn(event.target.value);
  };

  handleClick = (isLocationServices) =>{
    if(!isLocationServices && !this.props.searchLocation) {
      this.warningMessage = "Please fill out this field.";
      this.props.setValidationFlagFn(true);
      return;
    }

    if(this.props.isInvalidFields){
      this.props.setValidationFlagFn(false);
    }
    this.props.searchFn(isLocationServices);
  }

  validationDisplay = (isInvalid, message) =>{
    if(isInvalid){
        return (
          <InputValidation type="warning" message={message} />
        )
    }
  };

  render() {
    let placeholderText = 'e.g. 62225, Fort Belvoir';
    return (
      <div className="search-container">
        <div>
          <label for="search" className="visually-hidden">Search By Zip or City and State</label>
          <input id="search" type="text" name="search" placeholder={placeholderText} value={this.props.searchLocation} onChange={(e) => {this.handleChange(e)}}/>
          {this.validationDisplay(this.props.isInvalidFields, this.warningMessage)}
        </div>
        <button onClick={() => this.handleClick(false)}>Search</button>
        <button onClick={() => this.handleClick(true)} disabled={this.props.geoLocationDisabled}>Search By Your Location</button>
      </div>
    );
  }
}

export default SearchForm;
