import React, { Component } from 'react';
import LocatorMap from './locatorMap';
import ListContainer from './listContainer';

class Results extends Component {
  constructor(){
    super();

  }

  render() {
    let initialMapData = this.props.resultData;
    let listData = this.props.resultData.offices;
    return (
      <div className="locator-results-container">
        {/* <LocatorMap initialMapData={initialMapData} /> */}
        <ListContainer listData={listData} selectedPage={this.props.resultData.selectedPage} changePageFn={this.props.changePageFn} />
      </div>
    );
  }
}

export default Results;
