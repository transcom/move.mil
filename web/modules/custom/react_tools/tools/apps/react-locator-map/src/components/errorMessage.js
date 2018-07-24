import React from 'react';

const ErrorMessage = (props) => {
    if(props.error){
        return (
            <div className="error-message usa-alert usa-alert-error">{props.error}</div>
          );
    }else{
        return null
    }

}

export default ErrorMessage;
