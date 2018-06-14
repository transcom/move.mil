import React from 'react';
import * as _ from 'lodash';

const Phones = (props) =>{
  return _.map(props.phones, (phone, i)=>{
    console.log(phone)
    return (
      <div key={i}>
        <span>{phone.field_phonenumber[0].value}</span>
        <span style={{display: phone.field_type.length > 0 ? 'inline-block:' : 'none'}}> ({phone.field_type[0].value})</span>
        <span style={{display: phone.field_dsn.length && phone.field_dsn[0] === '1' ? 'inline-block:' : 'none'}}> (DSN)</span>
      </div>
    )
  })
}

const Emails = (props) =>{
  return _.map(props.emails, (email, i)=>{
    return (
      <div key={i}><a href={"mailto:" + email.value}>{email.value}</a></div>
    )
  })
}

const Websites = (props) =>{
  return _.map(props.websites, (website, i)=>{
    return (
      <div key={i}><a href={website.value}>{website.value}</a></div>
    )
  })
}

const Notes = (props) =>{
  return _.map(props.notes, (note, i)=>{
    return (
      <div key={i}>{note.value}</div>
    )
  })
}

const Services = (props) =>{
  return _.map(props.services, (service, i)=>{
    return (
      <li key={i}>{service.value}</li>
    )
  })
}

this.showPhones = (phones) =>{
  if(phones){
    return (
      <div>
        <div className="bold-header">Phone:</div>
        <Phones phones={phones} />
      </div>
    )
  }
}

this.showEmails = (emails) =>{
  if(emails && emails.length > 0){
    return (
      <div>
        <div className="bold-header">Email:</div>
        <Emails emails={emails} />
      </div>
    )
  }
}

this.showWebsites = (websites) =>{
  if(websites && websites.length > 0){
    return (
      <div>
        <div className="bold-header">Websites:</div>
        <Websites websites={websites} />
      </div>
    )
  }
}

this.showNotes = (notes) =>{
  if(notes && notes.length > 0){
    return (
      <div>
        <div className="bold-header">Notes:</div>
        <Notes notes={notes} />
      </div>
    )
  }
}

this.showServices = (services) =>{
  if(services && services.length > 0){
    return (
      <div>
        <div className="bold-header">Services:</div>
        <ul className="location-search-result-services">
          <Services services={services} />
        </ul>
      </div>
    )
  }
}

this.showHours = (hours) =>{
  if(hours && hours.length > 0){
    return (
      <div>
        <div className="bold-header">Hours:</div>
        <div>{hours}</div>
      </div>
    )
  }
}

this.renderLocationItem = (location) =>{
  if(location.address_line || 
     location.address_line2 || 
     location.locality || 
     location.administrative_area || 
     location.postal_code || 
     location.country_code){
     return (
        <div>
          <div className="bold-header">Location</div>
          <div>
            <LocationItem item={location.address_line} comma={true} />
            <LocationItem item={location.address_line2} />
          </div>
          <div>
            <LocationItem item={location.locality} comma={true}/>
            <LocationItem item={location.administrative_area} />
            <LocationItem item={location.postal_code}/>
          </div>
          <LocationItem item={location.country_code}/>
        </div>
      )
  }else{
    return null;
  }
}

const LocationItem = (props) => {
  if (!props.item){
    return null;
  }

  if(props.comma){
    return (
      <span className="inline">{props.item}, </span>
    )
  }else{
    return (
      <span className="inline">{props.item} </span>
    )
  }
}

const ShippingOffice = (props) =>{
  if(props.office){
    return (
        <div className="shipping-office" id="office-5">
          <div className="shipping-office-header">
            <div>Regional Processing Office</div>
            <div>{props.office.title}</div>
          </div>

          <div className="shipping-office-body usa-grid-full">
              <div className="usa-width-one-third">
                {this.showPhones(props.office.phones)}
              </div>

              <div className="usa-width-one-third">
                {this.showEmails(props.office.email_addresses)}
              </div>
              
              <div className="usa-width-one-third">
                {this.showWebsites(props.office.websites)}
              </div>
          </div>
        </div>
    )
  }else{
    return null;
  }
}

const ListItem = (props) => {
  let officeTypeClass = props.item.type.replace(' ', '-').toLowerCase();
  
  return (
    <li>
        <div className={`${"location-search-result " + officeTypeClass}`}
          data-latitude={props.item.location.geolocation.lat}
          data-longitude={props.item.location.geolocation.lng} 
          data-name={props.item.title} 
          data-type={props.item.type} 
          id={props.item.id}>

          <div className="location-search-result-header">
            <div>{props.item.title}</div>
          </div>

          <div className="location-search-result-body">
            <div className="usa-grid-full">
              <div className="usa-width-one-third">
                  {this.renderLocationItem(props.item.location)}
                  {this.showPhones(props.item.phones)}
              </div>
              <div className="usa-width-one-third">
                  {this.showEmails(props.item.email_addresses)}
              </div>
              <div className="usa-width-one-third">
                  {this.showHours(props.item.location.hours)}
                  {this.showNotes(props.item.notes)}
                  {this.showServices(props.item.services)}
              </div>
            </div>
            <ShippingOffice office={props.item.shipping_office}/>
          </div>
        </div>
    </li>
  );
}

export default ListItem;
