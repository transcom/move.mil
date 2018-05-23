import React, { Component }  from 'react';
import * as _ from 'lodash';
import Items from './items';
import NewItem from './newItem';
import TotalBar from './totalBar';

class Rooms extends Component {
    constructor(props) {
        super(props);  
    }

    renderItemsHeaderBlock = (headerText) => {
        return (
            <div className="flex-container header">
                <div className="flex-item">
                    <div className="flex-item-content">
                        <span>{headerText}</span>
                    </div>
                </div>
                <div className="flex-item small">
                    <div className="flex-item">
                        <div className="flex-item-content right-align">
                            <span>Quantity</span>
                        </div>
                    </div>
                </div>
                <div className="flex-item small">
                    <div className="flex-item">
                        <div className="flex-item-content right-align">
                            <span>Weight (lbs)</span>
                        </div>
                    </div>
                </div>
            </div>
        )
    }

    renderItems = (items, key, itemType) => {
        let itemlist = <Items items={items} 
            roomKey={key} 
            itemType={itemType}
            updateRoomQuanties={this.props.updateRoomQuanties}
        />;

        return itemlist;
    }

    roomsComponent = () => {
        let elements = _.map(this.props.rooms, (room, key) => {
            return (
                <div className="room-container flex-container" key={key}>
                    <div className="logo-container flex-item logo">
                        <img className="logo" src={this.props.baseUrl + key.toLowerCase() + '.svg'} alt={key} />
                        {/* <img className="logo" src={room.icon} alt={key} /> */}
                        <div className="room-title">{room.displayName.replace('/', '/ ')}</div>
                    </div>
                    <div className="room-content flex-item"> 
                        <div className="items-container">
                            {this.renderItemsHeaderBlock('Items')}
                            {this.renderItems(room.items, key, "items")}
                            <div className="custom-items">
                                {this.renderItemsHeaderBlock('Custom Items')}
                                {this.renderItems(room.customItems, key, "customItems")}
                            </div>
                            <NewItem addNewItem={this.props.addNewItem} 
                                    createUpdateTempItem={this.props.createUpdateTempItem}
                                    roomKey={key} 
                                    tempItem={this.props.rooms[key].tempItem}/>
                        </div>

                        <TotalBar totalQty={this.props.rooms[key].totalQty} 
                                totalweight={this.props.rooms[key].totalweight} 
                                title={room.displayName} />
                    </div>
                </div>
            )
        });
        return _.sortBy(elements, 'key');
    }

    render() {
        return (
            <div>
                {this.roomsComponent()}
            </div>
        )
    }
}

export default Rooms;
