import React, { Component }  from 'react';

class Total extends Component {
    handleScroll = (event) => {
        this.props.fixedFn();
    }

    componentDidMount = () => {
        window.addEventListener('scroll', this.handleScroll);
    }

    componentWillUnmount = () => {
        window.removeEventListener('scroll', this.handleScroll);
    }

    render() {
        return (
            <div className={"total-container "  + (this.props.isFixed ? 'fixed' : '')}>
                <div className="total usa-grid">
                    <div className="flex-container no-pad">
                        <div className="flex-item logo" />
                        <div className="flex-item">
                            <div className="flex-container total-item">
                                <div className="flex-item">
                                    <div className="flex-item-content">
                                        <span>TOTAL Estimate:</span>
                                    </div>
                                </div>
                                <div className="flex-item small">
                                    <div className="flex-item-content right-align">
                                        <span className="inline">{this.props.totalQuantity} </span>
                                        <span style={{display: this.props.totalQuantity ? 'inline-block' : 'none' }}> Items</span>
                                    </div>
                                </div>
                                <div className="flex-item small">
                                    <div className="flex-item-content right-align">
                                        <span className="inline">{this.props.totalEstimate} </span>
                                        <span style={{display: this.props.totalEstimate ? 'inline-block' : 'none' }}> lbs</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default Total;
