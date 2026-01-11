import React from 'react';
import ClipLoader from 'react-spinners/ClipLoader';

const Spinner = () => {
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-dark/90">
            <div className="text-center">
                <ClipLoader size={80} color="#16A34A" />
                <p className="mt-4 text-lg font-medium text-red-700 dark:text-red-400">
                    Please wait a few seconds...
                </p>
            </div>
        </div>
    );
};

export default Spinner;
