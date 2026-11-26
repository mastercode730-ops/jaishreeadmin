import React from 'react';
import { useNavigate } from 'react-router-dom';

const BackButton = () => {
    const navigate = useNavigate();

    const handleGoBack = () => {
        navigate(-1);
    };

    return (
        <>
            <img src="/icons_asset/backs.png" onClick={handleGoBack} alt="Back" />
        </>
    );
};

export default BackButton;
