import React, { Component }  from 'react';
import * as _ from 'lodash';

class Input extends Component {
    constructor(props) {
        super(props);
    }

    handleChange = (event) => {
        let val = event.target.value;
        let reg;
        let isValid = true;
        let changeFn = this.props.onChangeFn;
        
        switch(this.props.validationType){
            case 'number':
                reg = /^\d+$/;
                isValid = reg.test(val) || val == "" || val == undefined || val == null;
                break;
            case 'positiveNumbers':
                reg = /^\d*[0-9]\d*$/;
                isValid = reg.test(val) || val == "" || val == undefined || val == null;
                break;
            case 'alphaNumeric':
                reg = /^[a-z0-9]+$/i;
                isValid = reg.test(val) || val == "" || val == undefined || val == null;
                break;
            case 'nonEmpty':
                isValid = val != "" && val != undefined && val != null;
                break;
            default:
                isValid = true;
                break;
        }

        if(this.props.type == 'number'){
            val = parseInt(val);
        }

        if(isValid && changeFn){
            this.props.onChangeFn(this.props.params, val, event);
        }else{
            event.target.value = this.props.value || '';
        }
    }

    inputComponent = () =>{
        return (
            <input type={this.props.type} 
                   placeholder={this.props.placeholder} 
                   value={this.props.value}
                   onChange={(e) => {this.handleChange(e)}}/> 
        )
    }

    render() {
        return (
            <div>
                {this.inputComponent()}
            </div>
        )
    }
}

export default Input;
