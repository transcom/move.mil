import React, { Component }  from 'react';
import Input from './input';

class NewItem extends Component {
    handleChange = (params, val, event) => {
        this.props.createUpdateTempItem(this.props.roomKey, params.objectKey, val);
    }

    tryAddNewItem = () => {
        let newItem = this.props.tempItem;
        if(newItem && newItem.qty && newItem.displayName && newItem.weight){
            this.props.addNewItem(this.props.roomKey);
        }
    }

    newItemComponent = () =>{
        return (
            <div className="flex-container item">
                <div className="flex-item">
                    <div className="flex-item-content">
                        <Input type="text" 
                                validationType=""
                                placeholder="Item Description" 
                                value={this.props.tempItem.displayName}
                                params={{objectKey: 'displayName'}}
                                tryAddNewItem={this.tryAddNewItem}
                                onChangeFn={this.handleChange} />
                    </div>
                </div>
                <div className="flex-item small">
                    <div className="flex-item-content right-align">
                        <Input type="number"
                                validationType="positiveNumbers" 
                                placeholder="qty" 
                                value={this.props.tempItem.qty}
                                params={{objectKey: 'qty'}}
                                tryAddNewItem={this.tryAddNewItem}
                                onChangeFn={this.handleChange}/>
                    </div>
                </div>
                <div className="flex-item small">
                    <div className="flex-item-content right-align">
                        <Input type="number"
                                validationType="positiveNumbers" 
                                placeholder="lbs"
                                value={this.props.tempItem.weight}
                                params={{objectKey: 'weight'}}
                                tryAddNewItem={this.tryAddNewItem}
                                onChangeFn={this.handleChange}/>
                    </div>
                </div>
            </div>
        )
     }

    render() {
        return (
            <div>
                {this.newItemComponent()}
            </div>
        )
       
    }
}

export default NewItem;
