import React from "react";
import { Link } from "react-router-dom"; // আপনি যদি React Router ব্যবহার করেন

const NotFound = () => {
    return (
        <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 dark:bg-dark px-4">
            <h1 className="text-6xl font-bold text-primary mb-4">404</h1>
            <h2 className="text-2xl font-semibold text-gray-800 dark:text-white mb-2">
                Page Not Found
            </h2>
            <p className="text-gray-600 dark:text-gray-400 mb-6">
                The page you are looking for doesn’t exist or has been moved.
            </p>
            <Link
                to="/"
                className="px-6 py-2 bg-primary text-white rounded-lg shadow hover:bg-primary/90 transition"
            >
                Go Home
            </Link>
        </div>
    );
};

export default NotFound;
