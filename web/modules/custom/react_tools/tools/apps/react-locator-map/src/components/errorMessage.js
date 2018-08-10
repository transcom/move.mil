import React from 'react';

const title = (error) =>{
    if(error.title){
        return (
            <h3 class="usa-alert-heading">{error.title}</h3>
        )
    }
};

const ErrorMessage = (props) => {
    if(props.error){
        return (
            <div className="error-message usa-alert usa-alert-error">
              {title(props.error)}
              <div className="usa-alert-body">
                <div className="usa-alert-text">{props.error.message}</div>
              </div>
            </div>
          );
    }else{
        return null
    }

}

export default ErrorMessage;
