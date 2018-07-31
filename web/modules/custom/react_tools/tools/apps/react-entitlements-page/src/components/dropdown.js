import React, { Component }  from 'react';
import * as _ from 'lodash';
import InputValidation from './inputValidation';

class DropDown extends Component {
    constructor(props) {
        super(props);
        this.selectedValue = (this.props.data != null) ? this.props.data[0].value: null;
    }

    handleChange = (event) => {
        let val = event.target.value;
        if(val !== this.selectedValue){
            this.selectedValue = val;
            this.props.onSelectFn(val);
        }
    }

    validationDisplay = (value, message) =>{
        if(this.props.invalidFields && (!value || value === '-1')){
            return (
               <InputValidation type="warning" message={message} />
            )
        }
    }

    optionsComp = () =>{
        this.warningMessage = "Please select an item in the list.";
        return (
           _.map(this.props.data, (o, i)=>{
                return (
                    <option value={o.value} key={i}>{o.label}</option>
                )
            })
        )
    }

    render() {
        return (
            <div ref={(myElement)=>{this.myElement = myElement;}}>
                <select
                    name={this.props.name}
                    onChange={this.handleChange}
                >
                    {this.optionsComp()}
                </select>
                {this.validationDisplay(this.selectedValue, this.warningMessage)}
             </div>
        )
    }
}

export default DropDown;
