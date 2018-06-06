import React, { Component } from 'react';

class PageTab extends Component {
  constructor(){
    super();

  }

  handlePageChange = (pageNo) =>{
    this.props.changePageFn(pageNo, this.props.totalPages); 
  }
  render() {
    let element = null;
    let cssClass = this.props.isSelected ? 'selected' : '';

    switch(this.props.pageNo){
      case '1':
        element = <a className={cssClass} href="#map-container" onClick={() => this.handlePageChange(this.props.pageNo)}>Next &#8594;</a>
        break;
      case '-1':
        element = <a className={cssClass} href="#map-container" onClick={() => this.handlePageChange(this.props.pageNo)}>&#8592; Previous</a>
        break;
      case '...':
        element = <div className={cssClass}>{this.props.pageNo}</div>
        break;
      default:
        element = this.props.isSelected ? <div className={cssClass}>{this.props.pageNo}</div>: <a className={cssClass} href="#map-container" onClick={() => this.handlePageChange(this.props.pageNo)}>{this.props.pageNo}</a>
      break;
    }

    return (
      <li className="pages">
        {element}
      </li>
    );
  }
}

export default PageTab;
