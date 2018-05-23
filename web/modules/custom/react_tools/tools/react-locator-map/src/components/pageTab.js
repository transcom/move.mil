import React, { Component } from 'react';

class PageTab extends Component {
  constructor(){
    super();

  }

  handlePageChange = (pageNo) =>{
    this.props.changePageFn(pageNo, this.props.totalPages); 
  }

  render() {
    let value = this.props.pageNo === '1' ? 'next >>>' : this.props.pageNo;
    value = this.props.pageNo === '-1' ? '<<< prev' : value;

    return (
        <li className="pages">
            <a className={this.props.className} href="javascript:void(0)" onClick={() => this.handlePageChange(this.props.pageNo)}>{value}</a>
       </li>
    );
  }
}

export default PageTab;
