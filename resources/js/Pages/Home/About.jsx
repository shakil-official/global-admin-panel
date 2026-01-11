import React, {useEffect, useState} from 'react';
import Navbar from "../Navbar/Navbar.jsx";
import Spinner from "../Components/Spinner.jsx";

const About = () => {
    const [aboutContent, setAboutContent] = useState(''); // State to hold about content
    const [loading, setLoading] = useState(true); // State to handle loading state
    const [error, setError] = useState(null); // State to handle errors

    useEffect(() => {
        // Fetch about data from the server
        const fetchAboutContent = async () => {
            try {
                const response = await fetch('/api/about'); // Replace with your API endpoint
                if (!response.ok) {
                    console.log('Failed to fetch about content');
                }
                const data = await response.json();
                setAboutContent(data.htmlContent); // Assume the API returns an object with an `htmlContent` field
                setLoading(false);
            } catch (err) {
                setError(err.message);
                setLoading(false);
            }
        };

        fetchAboutContent().then((r) => console.log('About content fetched successfully!'));
    }, []);

    if (loading) {
        return <Spinner/>;
    }

    if (error) {
        return <p> Something is wrong </p>;
    }

    return (
        <>
            <Navbar/>

            <div className="about-container" style={{padding: '20px', maxWidth: '800px', margin: 'auto'}}>
                <h1>About Us</h1>
                <div
                    className="about-content"
                    dangerouslySetInnerHTML={{__html: aboutContent}}
                />
            </div>
        </>
    );
};

export default About;
