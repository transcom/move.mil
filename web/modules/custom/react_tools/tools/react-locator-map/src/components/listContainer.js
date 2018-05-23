import React, { Component } from 'react';
import ListItem from './listItem';
import PageTab from './pageTab';
import * as _ from 'lodash';

class ListContainer extends Component {
  constructor(){
    super();

    this.itemsPerPage = 10;
    this.totalPages = 0;
  }

  componentWillMount = () =>{
    this.totalPages = this.props.listData.length / this.itemsPerPage;
  }

  pageTabs = () =>{
    let pageTabsArray = this.getPageTabsArray();

    let pageTabs = _.map(pageTabsArray, (val, i) => {   
        let pageNo = val;
        let classes = pageNo === this.props.selectedPage ? 'selected' : '';
        return (
          <PageTab key={i} className={classes} 
                   pageNo={pageNo} 
                   selectedPage={this.props.selectedPage} 
                   changePageFn={this.props.changePageFn}
                   totalPages={this.totalPages}/>
        )
    });

    return pageTabs;
  }

  getPageTabsArray = () => {
    let array = [];

    for (let i = 1; i <= this.totalPages; i++){
      array.push(i);
    }

    switch(true){
      case this.props.selectedPage == 1:
        array.push('1');
        break;
      case this.props.selectedPage === this.totalPages:
        array.unshift('-1');
        break;
      default:
        array.unshift('-1');
        array.push('1');
        break;
    }

    return array;
  }

  paginatedListItems = () =>{
    let startIndex = (this.props.selectedPage - 1) * this.itemsPerPage;
    let endIndex = startIndex + this.itemsPerPage -1;
    let currentPage = _.cloneDeep(this.props.listData).slice((this.props.selectedPage - 1) * this.itemsPerPage, startIndex + this.itemsPerPage);
 
    let listElements = _.map(currentPage, (item, i)=>{
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
          <ul>
            {this.pageTabs()}
          </ul>
      </div>
    );
  }
}

export default ListContainer;
