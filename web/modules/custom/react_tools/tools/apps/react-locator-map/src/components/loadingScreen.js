import React from 'react';

const LoadingScreen = (props) => {
    let loadingText = 'Searching for nearby locations!';

    if(props.isLoading){
        return (
                <div className="loading-container">
                    <div className="bg"></div>
                    <div className="loading-content">
                        <div className="content-padding">
                            <div className="spinner">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                            <div className="message">{loadingText}</div>
                        </div>
                    </div>
                </div>
        )
    }else{
        return null;
    }
}

export default LoadingScreen;