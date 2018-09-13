import React, { Component }  from 'react';

class Dependents extends Component {

    handleChange = (isDependent) => {
        if(isDependent !== this.props.isDependencies){
            this.props.selectedDependentsFn(isDependent);
        }
    }

    radioComp = () =>{
        return (
            <div className="flex-container  wrapper">
                <div className="flex-item ie-2-col">
                    <div className="flex-container">
                        <div className="flex-item small">
                            <input
                                type="radio"
                                name="with-dependent"
                                id="with-dependent"
                                value="with-dependent"
                                onChange={(e) => this.handleChange(true)}
                                checked={this.props.isDependencies} />
                        </div>
                        <div className="flex-item">
                          <label for="with-dependent">Yes, I have dependents</label>
                        </div>
                    </div>
                </div>
                <div className="flex-item ie-2-col">
                    <div className="flex-container">
                        <div className="flex-item small">
                            <input
                                type="radio"
                                name="not-dependent"
                                id="not-dependent"
                                value="not-dependent"
                                onChange={(e) => this.handleChange(false)}
                                checked={!this.props.isDependencies} />
                        </div>
                        <div className="flex-item">
                            <label for="not-dependent">No, I do not have dependents</label>
                        </div>
                    </div>
                </div>
           </div>
        )
    }

    render() {
        return (
            <div>
                {this.radioComp()}
            </div>
        )
    }
}

export default Dependents;
