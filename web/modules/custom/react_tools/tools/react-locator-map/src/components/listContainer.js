import React, { Component } from 'react';
import ListItem from './listItem';
import PageTab from './pageTab';
import * as _ from 'lodash';

class ListContainer extends Component {

  pageTabs = () =>{
    let pageTabsArray = this.getPageTabsArray();

    let pageTabs = _.map(pageTabsArray, (val, i) => {   
        let pageNo = val;
        let isSelected = pageNo === this.props.selectedPage;
        if(val){
          return (
            <PageTab key={i} isSelected={isSelected} 
                    pageNo={pageNo}
                    changePageFn={this.props.changePageFn}
                    totalPages={this.props.totalPages}/>
          )
        }
    });

    return pageTabs;
  }

  getPageTabsArray = () => {
    let array = [];
    let selectedPage = this.props.selectedPage;
    let totalPages = this.props.totalPages;
    let previous;

    for (let i = 1; i <= totalPages; i++){
      previous = tabMarker(i);
      array.push(previous);
    }

    switch(true){
      case selectedPage === 1:
        array.push('1');
        break;
      case selectedPage === totalPages:
        array.unshift('-1');
        break;
      default:
        array.unshift('-1');
        array.push('1');
        break;
    }

    return array;

    function tabMarker(index, prev){
      if(index < 3 ||
        (selectedPage < 9 && index < 9) ||
        (selectedPage > (totalPages - 9) && index > (totalPages - 9)) ||
        (index < selectedPage + 4 && index > selectedPage - 4) ||
        index > totalPages-2){
        return index;
      }else if(previous === parseInt(previous, 10)){
        return '...';
      }
      return null;
    }
  }



  paginatedListItems = () =>{
    let listElements = _.map(this.props.viewablelistItems, (item, i)=>{
        return (
          <ListItem key={i} item={item} />
        )
    });

    return listElements;
  }

  render() {
    return (
      <div className="list-container">
          <ul className="result-page">
            {this.paginatedListItems()}
          </ul>
          <ul className="page-tabs">
            {this.pageTabs()}
          </ul>
      </div>
    );
  }
}

export default ListContainer;
