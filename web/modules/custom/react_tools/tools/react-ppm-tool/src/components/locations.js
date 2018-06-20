import React, { Component }  from 'react';
import InputValidation from './inputValidation';
import Input from './input';

class Locations extends Component {

    handleChange = (params, value) => {
        this.props.setLocationFn(params.key, value);
    }

    validationDisplay = (value, message) =>{
        if(this.props.invalidFields && !value){
            return (
               <InputValidation type="warning" message={message} />
            )
        }
   }

    locationComp = () =>{
        this.warningMessage = "Please fill out this field.";
        return (
            <div className="flex-container wrapper">
                <div className="flex-item">
                    <div>Origin ZIP Code</div>
                    <div>
                        <Input validationType="number" 
                               type="text" 
                               placeholder=""
                               value={this.props.locations.origin}
                               onChangeFn={this.handleChange} 
                               params={{key: 'origin'}} />
                        {this.validationDisplay(this.props.locations.origin, this.warningMessage)}
                    </div>
                </div>
                <div className="flex-item">
                    <div>Destination ZIP Code</div>
                    <div>
                        <Input validationType="number" 
                               value={this.props.locations.destination}
                               type="text" 
                               placeholder=""
                               onChangeFn={this.handleChange} 
                               params={{key: 'destination'}} />
                        {this.validationDisplay(this.props.locations.destination, this.warningMessage)}
                    </div>
                </div>    
           </div>
        )
    }

    render() {
        return (
            <div>
                {this.locationComp()}
            </div>
        )
    }
}

export default Locations;
