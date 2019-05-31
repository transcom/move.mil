import React from 'react';
import * as _ from 'lodash';

const Phones = (props) =>{
  let index = 0;
  return _.map(props.phones, (phone, type)=>{
    let odd = index % 2 !== 0 ? 'odd' : '';
    index++;
    return(
      <tr key={`${type}_${index}`} className={`${odd}`}>
        <th scope="row">{phone.name}</th>
        <td data-header="Commercial" className={phone.numbers.commercial.length < 1 ? 'mobile-hide' : ''}>
          <PhoneNumber numbers={phone.numbers.commercial}></PhoneNumber> 
        </td>
        <td data-header="DSN" className={phone.numbers.dsnVoice.length < 1 ? 'mobile-hide odd' : 'odd'}>  
          <PhoneNumber numbers={phone.numbers.dsnVoice}></PhoneNumber> 
        </td>
        <td data-header="Fax DSN" className={phone.numbers.dsnFax.length < 1 ? 'mobile-hide' : ''}>  
          <PhoneNumber numbers={phone.numbers.dsnFax}></PhoneNumber> 
        </td>
        <td data-header="Fax Commercial" className={phone.numbers.fax.length < 1 ? 'mobile-hide odd' : 'odd'}>  
          <PhoneNumber numbers={phone.numbers.fax}></PhoneNumber> 
        </td>
      </tr>
    )
  })
}

const PhoneNumber = (props) =>{
  let index = -1;
  return _.map(props.numbers, (p)=>{
    index++;
    return (
      <p key={`p_${index}`} className={index > 0 ? 'margin-left' : ''}>{formatPhone(p || null)}</p>
    )
  });
}

const Emails = (props) =>{
  let index = 0;
  return _.map(props.emailModel, (email)=>{
    let odd = index % 2 !== 0 ? 'odd' : '';
    index++;
    return (
      <tr key={`${email.name}_${index}`} className={`${odd}`}>
        <th scope="row">{email.name}</th>
        <td data-header="" className={email.emails.length < 1 ? 'mobile-hide' : ''}>
          <EmailHref addressList={email.emails}></EmailHref> 
        </td>
      </tr>
    )
  })
}

const EmailHref = (props) => {
  let index = -1;
  return _.map(props.addressList, (address)=>{
    index++;
    return (
      <p key={`p_${index}`} className={index > 0 ? 'margin-left' : ''}><a href={`mailto:${address}`}>{address}</a></p> 
    )
  });
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

const PhonesTable = (props) =>{
  if(props.phones){
    let model = buildPhoneModel(props.phones);
    return (
      <div className="shipping-office-body usa-grid-full responsive-table-container">
        <table className="table">
          <thead>
            <tr>
              <th scope="row">Phone Numbers</th>
              <th scope="col">Commercial</th>
              <th scope="col">DSN</th>
              <th scope="col">Fax DSN</th>
              <th scope="col">Fax Commercial</th>
            </tr>
          </thead>
          <tbody>
            <Phones phones={model} />
          </tbody>
        </table>
      </div>
    )
  }else{
    return null;
  }
}

const EmailsTable = (props) =>{
  if(props.emails && props.emails.length > 0){
    let emailModel = buildEmailModel(props.emails);
    return (
      <div className="email-container responsive-table-container">
        <table className="table">
          <thead>
            <tr>
              <th scope="row">Contacts</th>
              <th scope="col">Email Address</th>
            </tr>
          </thead>
          <tbody>
            <Emails emailModel={emailModel}></Emails>
          </tbody>
        </table>
      </div>
    )
  }else{
    return null;
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
  if(location.address_line1 ||
     location.address_line2 ||
     location.locality ||
     location.administrative_area ||
     location.postal_code ||
     location.country_code){
     return (
        <div className="mailing-address">
          <div className="bold-header">Mailing Address</div>
          <div>
            <LocationItem item={location.address_line1} comma={!!location.address_line2} />
            <LocationItem item={location.address_line2} />
          </div>
          <div>
            <LocationItem item={location.locality} comma={!!location.administrative_area}/>
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
  if (!props.item || !props.item.length){
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

          <PhonesTable phones={props.office.phones}></PhonesTable>
          <EmailsTable emails={props.office.email_addresses}></EmailsTable>
        </div>
    )
  }else{
    return null;
  }
}

const ListItem = (props) => {
  let officeTypeClass = props.item.type.replace(' ', '-').toLowerCase(),
  _geolocation = props.item.location.geolocation;

  return (
    <li>
        <div className={`${"location-search-result " + officeTypeClass}`}
          data-latitude={_geolocation ? _geolocation.lat : null}
          data-longitude={_geolocation ? _geolocation.lng : null}
          data-name={props.item.title}
          data-type={props.item.type}
          id={props.item.id}>

          <div className="location-search-result-header">
            <div>{props.item.title}</div>
          </div>

          <div className="location-search-result-body">
            <div className="usa-grid-full">
              <div className="">
                  {this.renderLocationItem(props.item.location)}
              </div>
              <PhonesTable phones={props.item.phones}></PhonesTable>
              <EmailsTable emails={props.item.email_addresses}></EmailsTable>
              <div className="">
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

function buildPhoneModel(_data){
  let phonesModel = {};
  _.each(_data, phone =>{
    let type = phone.field_type[0] ? phone.field_type[0].value.replace(' ', '_') : {},
    isDSN = phone.field_dsn[0] !== undefined ? parseInt(phone.field_dsn[0].value, 0) === 1 : 0,
    isVoice = phone.field_voice[0] !== undefined ? parseInt(phone.field_voice[0].value, 0) === 1 : 0;

    if(!phonesModel[type]){
      phonesModel[type] = {
        name: phone.field_type[0].value,
        numbers: {
          dsnVoice: [],
          commercial: [],
          dsnFax: [],
          fax: []
        }
      }
    }

    if(isVoice){
      if(isDSN){
        phonesModel[type].numbers.dsnVoice.push(phone.field_phonenumber[0].value);
      }else{
        phonesModel[type].numbers.commercial.push(phone.field_phonenumber[0].value);
      }
    }else{
      if(isDSN){
        phonesModel[type].numbers.dsnFax.push(phone.field_phonenumber[0].value);
      }else{
        phonesModel[type].numbers.fax.push(phone.field_phonenumber[0].value);
      }
    }
  });
  return phonesModel;
}

function buildEmailModel(_data){
  let emailModel = {}

  _.each(_data, email =>{
    let [_name, _address] = email.value.split('%');

    if(!emailModel[_name]){
      emailModel[_name] = {
        name: _name,
        emails: []
      }
    }
    emailModel[_name].emails.push(_address);
  });
  return emailModel;
}

function formatPhone(val){
  if(!val || val === '') return null;
  let reg = /\D/g; //numbers only
  val = val.replace(reg,'');
  let _len = val.length;
  if( _len > 6 && _len < 10){
    return `${val.substring(0,3)}-${val.substring(3,_len)}`;
  }

  if(_len > 9 && _len < 11){
    return `(${val.substring(0,3)}) ${val.substring(3,6)}-${val.substring(6,_len)}`;
  }

  if(_len > 10){
    return `${val.substring(0,1)} (${val.substring(1,4)}) ${val.substring(4,7)}-${val.substring(7,_len)}`;
  }
}


export default ListItem;
