import React from 'react';

const LoadingScreen = (props) => {
    if(props.isLoading){
        return (
                <div className="loading-container">
                    <div className="bg"></div>
                    <div className="loading-content">
                        <div className="message">Loading, please wait...</div>
                    </div>
                </div>
        )
    }else{
        return null;
    }
}

export default LoadingScreen;