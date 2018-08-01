import React from 'react';

const ErrorMessage = (props) => {
    if(props.error){
        return (
            <div className="error-message usa-alert usa-alert-error">
              <div className="usa-alert-body">{props.error}</div>
            </div>
          );
    }else{
        return null
    }

}

export default ErrorMessage;
