import React, { Component }  from 'react';
import * as _ from 'lodash';

class TotalBar extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="rooms-total flex-container">
                <div className="inline flex-item">
                    <div className="flex-item-content">
                        <span>{this.props.title.toUpperCase()} Total: </span>
                    </div>
                </div>
                <div className="flex-item small">
                    <div className="flex-item-content right-align">
                        <span className="inline">{this.props.totalQty} </span>
                        <span style={{display: this.props.totalQty ? 'inline-block' : 'none' }}> Items</span>
                    </div>
                </div>
                <div className="flex-item small"> 
                    <div className="flex-item-content right-align">
                        <span className="inline">{this.props.totalweight} </span>
                        <span style={{display: this.props.totalweight ? 'inline-block' : 'none' }}> lbs</span>
                    </div>
                </div>
            </div>
        )
    }
}

export default TotalBar;
