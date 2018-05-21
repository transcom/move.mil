import React, { Component }  from 'react';
import warning from '../warning.svg'

class InputValidation extends Component {
    constructor(props) {
        super(props);
    }

    validationComponent = () =>{
        this.icon = this.props.type == 'warning' ? warning : '';
        return (
            <div className="validation-container">
                <div className={'validation-icon ' + this.props.type}>
                    <img className="validation-icon" src={this.icon} alt={this.props.type}/>
                </div>
                <div className={'validation-content ' + this.props.type}>
                    {this.props.message}
                </div>
            </div>
        )
    }

    render() {
        return (
            <div>
                {this.validationComponent()}
            </div>
        )
    }
}

export default InputValidation;