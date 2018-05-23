import React from 'react';

const ListItem = (props) => {
  return (
    <li>
        <div>{props.item.title}</div>
        <div>{props.item.type}</div>
        <div>{props.item.distance}</div>
        <div>
          <span>{props.item.location.address_line1}</span>
          <span>{props.item.location.address_line2}</span>,
          <span>{props.item.location.administrative_area}</span>
          <span>{props.item.location.postal_code}</span>
          <span>{props.item.location.country_code}</span>
        </div>
    </li>
  );
}

export default ListItem;
