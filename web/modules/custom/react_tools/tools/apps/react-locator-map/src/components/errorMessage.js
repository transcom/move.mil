import React from 'react';

const ErrorMessage = (props) => {
    if(props.error){
        return (
            <div className="error-message usa-alert usa-alert-error">
              <div className="usa-alert-body">
                <div className="usa-alert-text">{props.error}</div>
              </div>
            </div>
          );
    }else{
        return null
    }

}

export default ErrorMessage;
