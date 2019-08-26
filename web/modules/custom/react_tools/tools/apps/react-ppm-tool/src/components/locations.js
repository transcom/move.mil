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
            <div>
                <div className="inline-fifty">
                    <div className="ie-2-col">
                        <div>
                            <Input id="origin"
                                labelText="Origin ZIP Code"
                                validationType="number" 
                                type="text" 
                                placeholder=""
                                value={this.props.locations.origin}
                                onChangeFn={this.handleChange} 
                                params={{key: 'origin'}} />
                            {this.validationDisplay(this.props.locations.origin, this.warningMessage)}
                        </div>
                    </div>
                </div>
                <div className="inline-fifty">
                    <div className="ie-2-col">
                        <div>
                            <Input id="destination"
                                labelText="Destination ZIP Code"
                                validationType="number" 
                                value={this.props.locations.destination}
                                type="text" 
                                placeholder=""
                                onChangeFn={this.handleChange} 
                                params={{key: 'destination'}} />
                            {this.validationDisplay(this.props.locations.destination, this.warningMessage)}
                        </div>
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
