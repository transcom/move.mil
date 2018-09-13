import React, { Component }  from 'react';
import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';

class MoveDate extends Component {

    handleChange = (date) => {
        if(date){
            let strippedDateTime = date.startOf('day');
            this.props.onSelectDateFn(strippedDateTime);
        }
    }

    render() {
        return (
            <DatePicker
                id="moveDate"
                selected={this.props.defaultDate}
                onChange={this.handleChange}
            />
        )
    }
}

export default MoveDate;
