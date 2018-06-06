import React, { Component }  from 'react';
import InputValidation from './inputValidation';
import Input from './input';

class Weights extends Component {
    constructor(props) {
        super(props);

        this.warningMessage = "Please fill out this field.";
    }


    handleChange = (params, value) => {
        this.props.changeWeightFn(params.key, value);
    } 

    validationDisplay = (value, message) =>{
        if(this.props.invalidFields && !value){
            return (
               <InputValidation type="warning" message={message} />
            )
        }
    }

    weightComp = () =>{
        let isRank = Object.keys(this.props.selectedEntitlmentOptions).length > 0;
        let houseHoldMaxAllowance = this.props.isDependencies ? this.props.selectedEntitlmentOptions.total_weight_self_plus_dependents : this.props.selectedEntitlmentOptions.total_weight_self;
        this.errClass = parseFloat(this.props.weightOptions.houseHold) > parseFloat(houseHoldMaxAllowance) ? 'input-error' : '';

        return (
            <div>
                <div className={"weight-item " + this.errClass}>
                    <div>Estimated Household Goods Weight (lbs)</div>
                    <div>
                        <Input validationType="number" 
                               type="text" 
                               placeholder=""
                               onChangeFn={this.handleChange} 
                               value={this.props.weightOptions.houseHold}
                               params={{key: 'houseHold'}} />
                        {this.validationDisplay(this.props.weightOptions.houseHold, this.warningMessage)}
                    </div>
                    <div style={{display: isRank ? 'inline-block' : 'none' }}>
                        <span>Your weight allowance is up to </span>
                        <span className="bold">{houseHoldMaxAllowance} lbs.</span>   
                    </div>
                </div>
                <div className="weight-item">
                    <div>Estimated Pro-Gear Weight (lbs)</div>
                        <Input validationType="number" 
                               value={this.props.weightOptions.proGear}
                               type="text" 
                               onChangeFn={this.handleChange} 
                               params={{key: 'proGear'}} />
                    <div style={{display: isRank ? 'inline-block' : 'none' }}>
                        <span>Your Pro-Gear allowance is up to </span>
                        <span className="bold" > {this.props.selectedEntitlmentOptions.pro_gear_weight} lbs.</span>
                    </div>
                </div>
                <div className="weight-item" style={{display: this.props.isDependencies ? 'inline-block' : 'none' }} >
                    <div>Estimated Spouse's Pro-Gear Weight (lbs)</div>
                    <Input validationType="number" 
                               value={this.props.weightOptions.dependent}
                               type="text" 
                               onChangeFn={this.handleChange} 
                               params={{key: 'dependent'}} />
                    <div style={{display: isRank ? 'inline-block' : 'none' }}>
                        <span>Your spouse's Pro-Gear allowance is up to </span>
                        <span className="bold"> {this.props.selectedEntitlmentOptions.pro_gear_weight_spouse} lbs.</span>
                    </div>
                </div>
            </div>
        )
    }

    render() {
        return (
            <div>
                {this.weightComp()}
            </div>
        )
    }
}

export default Weights;
