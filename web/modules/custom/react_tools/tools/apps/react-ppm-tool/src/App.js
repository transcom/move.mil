import React, { Component } from 'react';
import moment from 'moment';
import DropDown from './components/dropdown';
import Dependents from './components/dependents';
import Locations from './components/locations';
import MoveDate from './components/moveDate';
import Weights from './components/weights';
import Results from './components/results';
import * as _ from 'lodash';
import * as axios from 'axios';

class App extends Component {
  constructor(){
    super();

    this.baseUrl = process.env.BASE_URL;

    this.locations = {
      origin: '',
      destination: ''
    }

    this.weightOptions = {
      houseHold: '',
      proGear: '',
      dependent: ''
    }

    this.state = {
      entitlements: null,
      moveDate: moment(),
      selectedEntitlement: null,
      isDependencies: false,
      locations: this.locations,
      selectedEntitlmentOptions: {},
      weightOptions: this.weightOptions,
      selectedMoveDate: moment()._d,
      dropdowns: {
        entitlement: this.getEntitlementDropDownData(null)
      },
      invalidFields: false,
      request: {},
      results: null
    };
  }

  componentDidMount = () => {
    let url = `${this.baseUrl}parser/entitlements`;

    axios.get(url)
      .then(res => {
        let data = res.data;
        this.setState({
          entitlements: data,
          dropdowns: {...this.state.dropdowns, entitlement: this.getEntitlementDropDownData(data)}
        });
    });
  }

  getEntitlementDropDownData = (data) =>{
    let model = [{
        value: '-1',
        label: '- Select -'
    }];

    _.each(data, (option, key) =>{
        let o = {
            value: key,
            label: option.rank
        }
        model.push(o);
    });

    return model;
  }

  clearValidation = () =>{
    this.setState({
      invalidFields: false
    });
  }


  onSelectEntitlment = (value) =>{
    this.setState({
      selectedEntitlmentOptions: value !== '-1' ? this.state.entitlements[value] : {},
      selectedEntitlement: value
    }, ()=>{
      this.clearValidation();
    });
  }

  selectedDependents = (isDependencies) =>{
    this.setState({
      isDependencies: isDependencies
    }, ()=>{
      this.clearValidation();
    });
  }

  setLocation = (key, val) =>{
    this.setState({
      locations: {...this.state.locations, [key]: val}
    }, ()=>{
      this.clearValidation();
    });
  }

  selectDate = (date) =>{
    this.setState({
      moveDate: date,
      selectedMoveDate: date._d
    }, ()=>{
      this.clearValidation();
    });
  }

  changeWeight = (key, val) =>{
    this.setState({
      weightOptions: {...this.state.weightOptions, [key]: val},
    }, ()=>{
      this.clearValidation();
    });
  }

  calculate = () => {
    let data = {
      selectedEntitlement: this.state.selectedEntitlement,
      isDependencies: this.state.isDependencies,
      locations: this.state.locations,
      weightOptions: this.state.weightOptions,
      selectedMoveDate: this.state.selectedMoveDate,
    },
    unRequiredFields = ['proGear', 'dependent'];

    if(this.isValid(true, data, unRequiredFields)){
      this.setState({
        invalidFields: false,
        request: {...this.state.request, data}
      }, () => {
        this.getResults();
      });
    }else{
      this.setState({
        invalidFields: true
      });
    }
  }

  getResults = () =>{
    let url = `${this.baseUrl}parser/ppm_estimate`;
    let options = this.state.request.data;

    axios.post(url, options)
      .then(res => {
        let results = res.data;
        this.setState({
          results: results
      });
    });
  }
  
  isValid = (valid, data, excludedProps) =>{
    _.each(data, (item, key)=>{
      if(excludedProps.indexOf(key) === -1 && valid && item !== false){
        valid = (item != null && item !== undefined && item !== '');
        if(_.isObject(item)){
          valid = this.isValid(valid, item, excludedProps);
        }else{
          return valid;
        }
      }
    });
    return valid;
  }

  renderResults= () =>{
    if(this.state.results){
      this.selectedRank = this.state.selectedEntitlement ? this.state.entitlements[this.state.selectedEntitlement].rank : '';
      return (
        <Results results={this.state.results} isDependencies={this.state.isDependencies} rank={this.selectedRank}/>
      )
    }
  }

  render() {
    return (
      <div className="app">
        <div className="forms">

          <div className="list-item">
            <div className="flex-container">
                  <div className="flex-item number">1</div>
                  <div className="flex-item title">What is your rank?</div>
            </div>
            <div className="flex-container">
              <div className="flex-item number"></div>
              <div className="flex-item content">
                <DropDown data={this.state.dropdowns.entitlement} onSelectFn={this.onSelectEntitlment} name="entitlement" invalidFields={this.state.invalidFields}/>
              </div>
            </div>
          </div>

          <div className="list-item">
            <div className="flex-container">
                  <div className="flex-item number">2</div>
                  <div className="flex-item title">Do you have dependents?</div>
            </div>
            <div className="flex-container">
              <div className="flex-item number"></div>
              <div className="flex-item content">
                <Dependents isDependencies={this.state.isDependencies} selectedDependentsFn={this.selectedDependents} />
              </div>
            </div>
          </div>

          <div className="list-item">
            <div className="flex-container">
                  <div className="flex-item number">3</div>
                  <div className="flex-item title">Where are you moving from and to?</div>
            </div>
            <div className="flex-container">
              <div className="flex-item number"></div>
              <div className="flex-item sub-text">
                To get the most accurate estimate, enter the locations authorized by your orders. These locations might be different from where you live.
              </div>
            </div>
            <div className="flex-container">
              <div className="flex-item number"></div>
              <div className="flex-item content">
                <Locations locations={this.state.locations} setLocationFn={this.setLocation} invalidFields={this.state.invalidFields}/>
              </div>
            </div>
          </div>

          <div className="list-item">
            <div className="flex-container">
                  <div className="flex-item number">4</div>
                  <div className="flex-item title">When do you want to move?</div>
            </div>
            <div className="flex-container">
              <div className="flex-item number"></div>
              <div className="flex-item content">
                <MoveDate defaultDate={this.state.moveDate} onSelectDateFn={this.selectDate} invalidFields={this.state.invalidFields}/>
              </div>
            </div>
          </div>

          <div className="list-item">
            <div className="flex-container">
                <div className="flex-item number">5</div>
                <div className="flex-item title">How much, in pounds, do you expect to move without the government's help?</div>
            </div>
            <div className="flex-container">
              <div className="flex-item number"></div>
              <div className="flex-item sub-text">
                The government will only pay for the actual weight transported, up to your weight allowance. Don't forget - if you move some of your goods yourself (PPM) and have the government move the rest (HHG), the weights of both shipments count towards your allowance. <a href="/resources/weight-estimator"> Need help estimating your total household weight?</a>
              </div>
            </div>
            <div className="flex-container">
              <div className="flex-item number"></div>
              <div className="flex-item content">
                <Weights changeWeightFn={this.changeWeight} weightOptions={this.state.weightOptions} isDependencies={this.state.isDependencies} selectedEntitlmentOptions={this.state.selectedEntitlmentOptions} invalidFields={this.state.invalidFields}/>
              </div>
            </div>
          </div>

          <button className="calc-button" onClick={this.calculate}>Calculate</button>
        </div>
        {this.renderResults()}
      </div>
    );
  }
}

export default App;
