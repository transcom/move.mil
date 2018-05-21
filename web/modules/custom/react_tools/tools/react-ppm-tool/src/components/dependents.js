import React, { Component }  from 'react';
import * as _ from 'lodash';

class Dependents extends Component {
    constructor(props) {
        super(props);
        this.selectedRadio;
    }

    handleChange = (isDependent) => {
        if(isDependent !== this.props.isDependencies){
            this.props.selectedDependentsFn(isDependent);
        }
    }

    radioComp = () =>{
        return (
            <div className="flex-container  wrapper">
                <div className="flex-item">
                    <div className="flex-container">
                        <div className="flex-item small">
                            <input type="radio" name="dependent" onChange={(e) => this.handleChange(true)} checked={this.props.isDependencies} />
                        </div>
                        <div className="flex-item">
                            <div>Yes, I have dependents</div>
                        </div>
                    </div>
                </div>
                <div className="flex-item">
                    <div className="flex-container">
                        <div className="flex-item small">
                            <input type="radio" name="not-dependent" onChange={(e) => this.handleChange(false)}  checked={!this.props.isDependencies}/>
                        </div>  
                        <div className="flex-item">
                            <div>No, I do not have dependents</div>
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
