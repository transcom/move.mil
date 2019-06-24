import React, { Component }  from 'react';

class InputValidation extends Component {
    validationComponent = () =>{
        return (
            <div role="alert" className="validation-container">
                <div className={'validation-icon ' + this.props.type}>
                </div>
                <div className={'validation-content ' + this.props.type}>
                    {this.props.message}
                </div>
            </div>
        )
    };

    render() {
        return (
            <div>
                {this.validationComponent()}
            </div>
        )
    }
}

export default InputValidation;