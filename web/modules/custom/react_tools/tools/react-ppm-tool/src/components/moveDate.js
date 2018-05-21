import React, { Component }  from 'react';
import * as _ from 'lodash';
import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';

class MoveDate extends Component {
    constructor(props) {
        super(props);
    }

    handleChange = (date) => {
        if(date){
            this.props.onSelectDateFn(date);
        }
    }

    render() {
        return (
            <DatePicker
                selected={this.props.defaultDate}
                onChange={this.handleChange}
            />
        )
    }
}

export default MoveDate;
