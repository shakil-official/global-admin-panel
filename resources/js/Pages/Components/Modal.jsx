import React, {useState} from 'react';
import {AiOutlineClose} from "react-icons/ai";

const Modal = ({isOpen, handleClose, children}) => {
    return (
        <>
            {isOpen && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 pt-2">
                    <div
                        className="relative bg-white rounded-lg shadow-lg p-6 w-full max-w-lg md:max-w-2xl overflow-y-auto max-h-[90vh] flex flex-col items-center space-y-6"
                    >
                        {/* Close Icon */}
                        <button
                            onClick={handleClose}
                            className="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
                        >
                            <AiOutlineClose size={24}/>
                        </button>

                        {/* Modal Content */}
                        <div className="w-full">{children}</div>
                    </div>
                </div>
            )}
        </>
    );
};

export default Modal;
