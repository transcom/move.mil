import React, { Component } from 'react';
import LocatorMap from './locatorMap';
import ListContainer from './listContainer';
import * as _ from 'lodash';

class Results extends Component {

  mapLegend = () => {
    return (
      <div className="location-search-types">
        <div className="usa-media_block">
          <div className="icon transportation-office" data-id="A blue icon of a Transportation Office"/>
          <div className="usa-media_block-body">
          <span><span className="bold">Transportation Offices</span> are your customer service “store front” of the moving process. In addition to providing information sessions about the moving process, they can also help with scheduling, changing delivery dates, and answering any other questions that come up during your move.</span>
          </div>
        </div>

        <div className="usa-media_block">
          <div className="icon weight-scale" data-id="An orange icon of a truck on a weight scale"/>
          <div className="usa-media_block-body">
            <span><span className="bold">Truck Weight Scales</span> are privately owned locations where you can take your vehicles to be weighed to receive required <abbr title="Personally Procured Move">PPM</abbr> “Do-It-Yourself” move weight tickets.</span>
          </div>
        </div>
      </div>
    )
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
        {this.mapLegend()}
        <LocatorMap centerCoords={this.props.resultData.geolocation} offices={viewablelistItems} firstViewableIndex={startIndex} lastViewableIndex={endIndex}/>
        <div className="display-results">Displaying results near <span className="bold">{this.props.resultData.geolocation.lat}, {this.props.resultData.geolocation.lon}</span> (page {selectedPage} of {totalPages}).</div>
        <ListContainer viewablelistItems={viewablelistItems} totalPages={totalPages} selectedPage={selectedPage} changePageFn={this.props.changePageFn} />
      </div>
    );
  }
}

export default Results;
