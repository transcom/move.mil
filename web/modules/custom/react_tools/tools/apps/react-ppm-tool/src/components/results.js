import React, { Component }  from 'react';
import ResultMap from './resultMap'
import moment from 'moment';

class Results extends Component {
    spouseTotal = () =>{
        if(this.props.isDependencies){
            return (
                <div className="flex-container sum-line">
                    <div className="flex-item">
                        Spouse Pro Gear
                    </div>
                    <div className="flex-item arithmatic-sign">
                        <span>+</span>
                    </div>
                    <div className="flex-item right-align small">
                        {this.props.results.weightOptions.dependent} lbs
                    </div>
                </div>
            )
        }
    }

    renderPlusSign = () =>{
        if(!this.props.isDependencies){
            return (
                <span>+</span>
            )
        }
    }

    handlePrint = (e) =>{
        window.print();
    }

    render() {
        this.rank_dependents = this.props.isDependencies ? `${this.props.rank} with dependents.` : `${this.props.rank} without dependents.`;
        this.sumlineClass = this.props.isDependencies ? '' : 'sum-line';
        let dateFormatted = moment(this.props.results.selectedMoveDate).format("dddd, MMMM Do YYYY");

        let mapOptions = {
            coords: {
                origin: [this.props.results.locations.origin.lat, this.props.results.locations.origin.lon],
                destination: [this.props.results.locations.destination.lat, this.props.results.locations.destination.lon]
            },
            center: [this.props.results.locations.origin.lat, this.props.results.locations.origin.lon]
        }

        return (
            <div className="results">

                <div className="flex-container">
                    <div className="title flex-item">Your PPM Incentive Estimate:</div>
                    <div className="flex-item half"></div>
                </div>

                <div className="flex-container">
                    <div className="flex-item">
                        <span>From:</span><span className="bold"> {this.props.results.locations.origin.address} </span><span>to</span> <span className="bold"> {this.props.results.locations.destination.address} </span>
                    </div>
                    <div className="flex-item half right-align">
                        <button className="usa-button usa-button-secondary" onClick={(e)=>this.handlePrint()}>Print</button>
                    </div>
                </div>



                <ResultMap map={mapOptions}/>
                <div className="details">
                    <div className="flex-container">
                        <div className="flex-item ie-2-col">Your Details:</div>
                        <div className="flex-item ie-2-col right-align">
                            {this.rank_dependents}
                        </div>
                    </div>
                    <div className="flex-container table">
                        <div className="flex-item ie-2-col">
                            <div>Moving Date:</div>
                            <div>{dateFormatted}</div>
                        </div>
                        <div className="flex-item ie-2-col totals">
                            <div className="flex-container">
                                <div className="flex-item">
                                    Anticipated Weight:
                                </div>
                            </div>
                            <div className="flex-container">
                                <div className="flex-item">
                                    Household Goods
                                </div>
                                <div className="flex-item arithmatic-sign"></div>
                                <div className="flex-item right-align">
                                    {this.props.results.weightOptions.houseHold} lbs
                                </div>
                            </div>
                            <div className={"flex-container " + this.sumlineClass}>
                                <div className="flex-item">
                                    Pro Gear
                                </div>
                                <div className="flex-item half">
                                    {this.renderPlusSign()}
                                </div>
                                <div className="flex-item right-align half">
                                    {this.props.results.weightOptions.proGear} lbs
                                </div>
                            </div>
                            {this.spouseTotal()}
                            <div className="flex-container totals">
                                <div className="flex-item">
                                    TOTAL	
                                </div>
                                <div className="flex-item half">
                                    =
                                </div>
                                <div className="flex-item right-align half">
                                    {this.props.results.weightOptions.total} lbs
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="flex-container incentive">
                        <div className="flex-item ie-2-col">Your PPM Incentive:</div>
                        <div className="flex-item ie-2-col right-align">
                            ${Math.round(this.props.results.incentive.min)}-${Math.round(this.props.results.incentive.max)}
                        </div>
                    </div>
                    <div className="flex-container advance">
                        <div className="flex-item ie-2-col">Max. Advance Payment:</div>
                        <div className="flex-item ie-2-col right-align">
                            ${Math.round(this.props.results.advancePayment.min)}-${Math.round(this.props.results.advancePayment.max)} ({this.props.results.advancePayment.percentage}%)
                           
                        </div>
                    </div>
                </div>

                <div></div>
                <div></div>
                <div></div>
            </div>
        )
    }
}

export default Results;
