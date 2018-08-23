import React from 'react';

const title = (error) =>{
    if(error.title){
        return (
            <h3 role="alert" class="usa-alert-heading">{error.title}</h3>
        )
    }
};

const ErrorMessage = (props) => {
    if(props.error){
        return (
            <div className="error-message usa-alert usa-alert-error">
              <div className="usa-alert-body">
                {title(props.error)}  
                <div role="alert" className="usa-alert-text">{props.error.message}</div>
              </div>
            </div>
          );
    }else{
        return null
    }

}

export default ErrorMessage;
