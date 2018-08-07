import React, { Component } from 'react';
import Rooms from './components/rooms';
import Total from './components/total';
import * as _ from 'lodash';
import * as axios from 'axios';

class App extends Component {
  constructor(){
    super();

    this.baseUrl = process.env.NODE_ENV === 'development' ? 'http://move.mil.localhost:8000/' : '/';
    this.state = {
      rooms: null,
      totalEstimate: 0,
      totalQuantity: 0,
      isFixed: true
    };
  }

  componentDidMount = () =>{
    let url = `${this.baseUrl}parser/weight_calculator`;

    axios.get(url)
      .then(res => {
        let data = res.data;
        _.each(data, (room)=>{
          room.customItems = {};
          room.tempItem = {
            displayName: '',
            qty: 0,
            weight: 0
          }
        });
        this.setState({ rooms: res.data});
        this.appDivOffsetTop = this.appDiv.offsetTop;
        this.appDivHeight = this.appDiv.clientHeight;
    }).catch(error => {
      this.handleError("An error occurred.");
    });
  }

  handleError = (errMessage) => {
    this.setState({
      errorMessage: errMessage
    });
  }

  setFixedState = () => { 
    let scrollPos = window.pageYOffset;
    let windowHeight = window.innerHeight;
    let stickyPos = this.appDivHeight + this.appDivOffsetTop;
    let isSticky = scrollPos < stickyPos;
    let _isFixed = null;

    isSticky = stickyPos > (scrollPos + windowHeight);

    if(isSticky && !this.state.isFixed){
      _isFixed = true;
    }

    if(!isSticky && this.state.isFixed){
      _isFixed = false;
    }

    if(_isFixed !== null){
      this.setState({
        isFixed: _isFixed
      });
    }
  }

  createUpdateTempItem = (roomKey, itemKey, value) => {
    let newState = this.state.rooms;
    let _tempItem;

    if(!newState[roomKey].tempItem){
      newState[roomKey].tempItem = {};
    }

    _tempItem = newState[roomKey].tempItem;

    if(!_tempItem[itemKey]){
      _tempItem[itemKey] = {};
    }

    _tempItem[itemKey] = value;

    this.setState({
      rooms: newState
    });
  }

  addNewItem = (roomKey) => {
    let newState = this.state.rooms;
    let newItem = newState[roomKey].tempItem;
    let idString, id;

    if(!newState[roomKey].customItems){
      newState[roomKey].customItems = {};
    }

    idString = newState[roomKey].tempItem.displayName.replace(/[^\w\s]/gi, '').replace(' ', '');
    id = isUnique(idString);

    function isUnique(_id){
      if(_id !== "" && !newState[roomKey].customItems[_id]){
        return _id;
      }
      else{
        _id = Math.floor(Math.random(1000000) * 10);
        return isUnique(_id);
      }
    }

    newState[roomKey].customItems[id] = newItem;
    newState[roomKey].tempItem = {
      displayName: '',
      qty: 0,
      weight: 0,
      isFocus: true
    };

    this.setState({
      rooms: newState
    },  () => {
      this.appDivOffsetTop = this.appDiv.offsetTop;
      this.appDivHeight = this.appDiv.clientHeight;
      this.calculateRoomWeightTotals(roomKey);
    });
  }

  updateRoomQuanties = (updatedItem) => {
    let newState = this.state.rooms;
    newState[updatedItem.roomKey][updatedItem.itemType][updatedItem.itemKey][updatedItem.valKey] = updatedItem.val;

    this.setState({
      rooms: newState
    },  () => {
      this.calculateRoomWeightTotals(updatedItem.roomKey);
    });
  }

  calculateRoomWeightTotals = (roomKey) =>{
    let totalweight = 0;
    let totalQty = 0;
    _.each(this.state.rooms[roomKey].items, (item)=>{
      if(item.qty){
        totalweight += (parseInt(item.qty, 10) * item.weight);
        totalQty += parseInt(item.qty, 10);
      }
    });

    _.each(this.state.rooms[roomKey].customItems, (item)=>{
      if(item.qty){
        totalweight += (parseInt(item.qty, 10) * item.weight);
        totalQty += parseInt(item.qty, 10);
      }
    });

    this.setState({
      rooms: {...this.state.rooms, [roomKey]: {...this.state.rooms[roomKey], totalweight: totalweight, totalQty: totalQty}},
    },  () => {
      this.sumRoomTotals();
    });
  }
  
  sumRoomTotals = () =>{
    let totalEstimate = 0;
    let totalQuantity = 0;
    _.each(this.state.rooms, (room)=>{
      if(room.totalweight){
        totalEstimate += room.totalweight;
      }
      if(room.totalQty){
        totalQuantity += room.totalQty;
      }
    });

    this.setState({
      totalEstimate: totalEstimate,
      totalQuantity: totalQuantity
    })
  }

  render() {
    return (
      <div className="estimator-container"
           ref={(appDiv)=>{this.appDiv = appDiv;}}>
        <Rooms rooms={this.state.rooms}
               baseUrl={this.baseUrl}
               updateRoomQuanties={this.updateRoomQuanties} 
               createUpdateTempItem={this.createUpdateTempItem}
               addNewItem={this.addNewItem}/>
               
        <Total totalEstimate={this.state.totalEstimate}
               totalQuantity={this.state.totalQuantity}
               isFixed={this.state.isFixed}
               fixedFn={this.setFixedState}/>
      </div>
    );
  }
}

export default App;
