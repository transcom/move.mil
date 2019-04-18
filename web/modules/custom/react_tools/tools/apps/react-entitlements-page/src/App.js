import React, { Component } from 'react';
import DropDown from './components/dropdown';
import Dependents from './components/dependents';
import Conus from './components/conus';
import Table from './components/table'
import * as axios from 'axios';
import * as _ from 'lodash';

class App extends Component {
  constructor() {
    super();

    this.baseUrl = process.env.BASE_URL;

    this.state = {
      entitlements: null,
      dropdowns: {
        entitlement: this.getEntitlementDropDownData(null)
      },
      selectedEntitlmentOptions: null,
      selectedEntitlement: -1,
      isDependencies: true,
      isConus: true,
      table: null,
    }
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

  getEntitlementDropDownData = (data) => { 
    let model = [{
        value: '-1',
        label: '- Select -'
    }];

    _.each(data, (option, key) => {
        let o = {
            value: key,
            label: option.rank
        }
        model.push(o);
    });
    return model;
  }

  onSelectEntitlment = (value) => {
    this.setState({
      selectedEntitlmentOptions: value !== '-1' ? this.state.entitlements[value] : {},
      selectedEntitlement: value
    });
  }

  selectedDependents = (isDependencies) => {
    this.setState({
      isDependencies: isDependencies
    });
  }

  selectedConus = (isConus) => {
    this.setState({
      isConus: isConus
    });
  }

  render() {
    return (
      <div className="app">
         <div className="list-item">
            <div className="number">1</div>
            <div className="content">
                <div className="title">What is your rank?</div>
                <DropDown data={this.state.dropdowns.entitlement} onSelectFn={this.onSelectEntitlment} name="entitlement" invalidFields={this.state.invalidFields}/>
            </div>
        </div>
        <div className="list-item">
            <div className="number">2</div>
            <div className="content">
                <div className="title">Do you have dependents?</div>
                <Dependents isDependencies={this.state.isDependencies} selectedDependentsFn={this.selectedDependents} />
            </div>
        </div>
        <div className="list-item">
            <div className="number">3</div>
            <div className="content">
                <div className="title">Is your move completely within the Continental United States (CONUS)?</div>
                <Conus isConus={this.state.isConus} selectedConusFn={this.selectedConus} />
            </div>
        </div>
          <Table isDependencies={this.state.isDependencies} 
                 isConus={this.state.isConus} 
                 selectedEntitlement={this.state.selectedEntitlement}
                 entitlements={this.state.entitlements}
          />
      </div>
    );
  }
}

export default App;
