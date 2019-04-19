import React from 'react';
import * as _ from 'lodash';

const Phones = (props) =>{
  let index = 0;
  return _.map(props.phones, (phone, type)=>{
    let element = [],
    largestArray = Number.NEGATIVE_INFINITY;
    largestArray = phone.numbers.commercial.length > largestArray ? phone.numbers.commercial.length : largestArray;
    largestArray = phone.numbers.dsnVoice.length > largestArray ? phone.numbers.dsnVoice.length : largestArray;
    largestArray = phone.numbers.fax.length > largestArray ? phone.numbers.fax.length : largestArray;
    largestArray = phone.numbers.dsnFax.length > largestArray ? phone.numbers.dsnFax.length : largestArray;
    
    let odd =  index % 2 ? 'odd' : '';
    for (let i=0; i< largestArray; i++){
      element.push(
          <div key={`${type}_${i}`} className={`flex-container ${odd}`}>
            <div className="flex-item">{i === 0 ? phone.name : null}</div>
            <div className="flex-item">{formatPhone(phone.numbers.commercial[i] || null)}</div>
            <div className="flex-item">{formatPhone(phone.numbers.dsnVoice[i] || null)}</div>
            <div className="flex-item">{formatPhone(phone.numbers.dsnFax[i] || null)}</div>
            <div className="flex-item">{formatPhone(phone.numbers.fax[i] || null)}</div>
          </div>
      )
    }
    index++;
    return element;
  })
}

const Emails = (props) =>{
  let i = -1;
  return _.map(props.addresses, (emailAddress, key)=>{
    i++;
    return (
      <div className={`flex-container ${props.oddClass}`} key={i}>
        <div className="flex-item half">{i === 0 ? props.category : null}</div>
        <div className="flex-item">
          <a href={`mailto:${emailAddress}`}>{emailAddress}}</a>  
        </div>
      </div>
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
    let model = buildPhoneModel(phones);
    return (
      <div className="shipping-office-body usa-grid-full">
        <div className="flex-container header-row">
          <div className="flex-item">Phone Numbers</div>
          <div className="flex-item">Commercial</div>
          <div className="flex-item">DSN</div>
          <div className="flex-item">Fax DSN</div>
          <div className="flex-item">Fax Commercial</div>
        </div>
        <Phones phones={model} />
      </div>
    )
  }
}

this.showEmails = (emails) =>{
  if(emails && emails.length > 0){
    let emailModel = buildEmailModel(emails);
    return (
      <div className="email-container">
        <div className="flex-container header-row">
          <div className="flex-item half">Contacts</div>
          <div className="flex-item">Email Address</div>
         </div>
         <div>
            {this.emailCategories(emailModel)}
        </div>
      </div>
    )
  }
}

this.emailCategories = (emailModel) =>{
  let i = 0;
  return _.map(emailModel, category =>{
    let odd =  i % 2 ? 'odd' : '';
    i++;
    return(
      <Emails addresses={category.emails} category={category.name} oddClass={odd} />
    )
  })
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

          {this.showPhones(props.office.phones)}
          {this.showEmails(props.office.email_addresses)}
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
              {this.showPhones(props.item.phones)}
              {this.showEmails(props.item.email_addresses)}
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
