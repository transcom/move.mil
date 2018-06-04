import React, { Component } from 'react';
import LocatorMap from './locatorMap';
import ListContainer from './listContainer';
import * as _ from 'lodash';

class Results extends Component {
  constructor(){
    super();

  }

  render() {
    let itemsPerPage = this.props.itemsPerPage;
    let selectedPage = this.props.resultData.selectedPage || 1;
    let totalPages =  Math.ceil(this.props.resultData.offices.length / this.props.itemsPerPage);
    let startIndex = (selectedPage - 1) * itemsPerPage;
    let endIndex = startIndex + itemsPerPage;
    let viewablelistItems = _.cloneDeep(this.props.resultData.offices).slice(startIndex, endIndex);

    return (
      <div className="locator-results-container">
        <LocatorMap centerCoords={this.props.resultData.geolocation} offices={viewablelistItems} firstViewableIndex={startIndex} lastViewableIndex={endIndex}/>
        <div>Displaying results near <span className="bold">{this.props.resultData.geolocation.lat}, {this.props.resultData.geolocation.lon}</span> (page {selectedPage} of {totalPages}).</div>
        <ListContainer viewablelistItems={viewablelistItems} totalPages={totalPages} selectedPage={selectedPage} changePageFn={this.props.changePageFn} />
      </div>
    );
  }
}

export default Results;
