import React, {useEffect, useState} from 'react';
import Navbar from "../Navbar/Navbar.jsx";
import Spinner from "../Components/Spinner.jsx";

const Term = () => {
    const [terms, setTerms] = useState(''); // State to hold the terms content
    const [loading, setLoading] = useState(true); // State to handle loading state
    const [error, setError] = useState(null); // State to handle errors

    useEffect(() => {
        // Fetch terms data from the server
        const fetchTerms = async () => {
            try {
                const response = await fetch('/api/terms'); // Replace it with your API endpoint
                if (!response.ok) {
                    console.log('Failed to fetch terms');
                }
                const data = await response.json();
                setTerms(data.htmlContent); // Assume the API returns an object with an `htmlContent` field
                setLoading(false);
            } catch (err) {
                setError(err.message);
                setLoading(false);
            }
        };

        fetchTerms().then((r) => console.log('Terms content fetched successfully!'));
    }, []);

    if (loading) {
        return <Spinner/>;
    }

    if (error) {
        return <p>Something is wrong</p>;
    }

    return (
        <>
            <Navbar/>

            <div className="terms-container" style={{padding: '20px', maxWidth: '800px', margin: 'auto'}}>
                <div className="terms-content" dangerouslySetInnerHTML={{__html: terms}}/>
            </div>
        </>
    );
};

export default Term;
