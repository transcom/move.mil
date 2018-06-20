import React, { Component }  from 'react';
import * as _ from 'lodash';
import Input from './input';

class Items extends Component {

    handleInputChange = (params, val) => {
        let updatedItem = {
            itemType: this.props.itemType,
            roomKey: this.props.roomKey,
            itemKey: params.itemKey,
            valKey: params.valKey,
            val: val
        }
        this.props.updateRoomQuanties(updatedItem);
    }

    itemComponent = () =>{    
       let elements = _.map(this.props.items,(item, key) => {
           if(this.props.itemType === 'items'){
            return (
                <div className="flex-container item" key={key}>
                    <div className="flex-item">
                        <div className="flex-item-content">
                            <span>{item.displayName}</span>
                        </div>
                    </div>
                    <div className="flex-item small">
                        <div className="flex-item-content right-align">
                            <Input type="number" 
                                validationType="positiveNumbers" 
                                placeholder="qty" 
                                value={item.qty}
                                params={{itemKey: key, valKey: 'qty'}}
                                onChangeFn={this.handleInputChange}/>
                        </div>
                    </div>
                    <div className="flex-item small">
                        <div className="flex-item-content right-align">
                            <div>{item.weight}</div>
                        </div>
                    </div>
                </div>
            )
           }else{
            return (
                <div className="flex-container item" key={key}>
                    <div className="flex-item">
                        <div className="flex-item-content">
                            <Input type="text" 
                                validationType="" 
                                placeholder="Item Description"
                                value={item.displayName}
                                params={{itemKey: key, valKey: 'displayName'}}
                                onChangeFn={this.handleInputChange}/>   
                        </div>
                    </div>
                    <div className="flex-item small">
                        <div className="flex-item-content right-align">
                            <Input type="number" 
                                validationType="positiveNumbers" 
                                placeholder="qty" 
                                value={item.qty}
                                params={{itemKey: key, valKey: 'qty'}}
                                onChangeFn={this.handleInputChange}/>
                        </div>
                    </div>
                    <div className="flex-item small">
                        <div className="flex-item-content right-align">
                            <Input type="number" 
                                validationType="number" 
                                placeholder="lbs"
                                value={item.weight}
                                params={{itemKey: key, valKey: 'weight'}}
                                onChangeFn={this.handleInputChange}/>
                         </div>
                    </div>
                </div>
            )
           }
        });

        return _.sortBy(elements, 'key');
    }

    render() {
        return (
            <div>
                {this.itemComponent()}
            </div>
        )
    }
}

export default Items;
