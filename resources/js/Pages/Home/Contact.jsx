import React, {useState} from 'react';
import Navbar from "../Navbar/Navbar.jsx";
import Footer from "./Footer.jsx";
import axios from "axios";
import {toast, ToastContainer} from "react-toastify";

const Contact = () => {

    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
        address: '',
        description: '',
    });

    const [apiProcess, setApiProcess] = useState(false);

    const handleChange = (e) => {
        const {name, value} = e.target;
        setFormData({...formData, [name]: value});
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            setApiProcess(true); // Indicate API call in progress
            const response = await axios.post('/api/v1/insert-contact', formData);

            if (response.status === 201) {
                toast.success("Contact submitted successfully", {
                    position: "top-center",
                });
                setFormData({
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                    description: '',
                }); // Clear the form
            }
        } catch (error) {
            if (error.response && error.response.data) {
                toast.error(error.response.data.message || "Submission failed", {
                    position: "top-center",
                });
            } else {
                toast.error("An unexpected error occurred", {position: "top-center"});
            }
        } finally {
            setApiProcess(false); // Reset API call state
        }
    };


    return (
        <>
            <ToastContainer/>

            <Navbar/>

            <div className="container min-h-16 pt-6 mt-10 mb-16" style={{marginTop: '50px', marginBottom: '50px'}}>
                <form
                    onSubmit={handleSubmit}
                    className="max-w-xl mx-auto p-6 bg-white shadow-lg rounded-xl border border-gray-300 space-y-6 mb-8"
                    style={{marginBottom: '178px'}}>

                    <h1 className="text-gray-700 text-xl font-semibold text-center">Contact with us</h1>

                    <hr/>

                    {/* Full Name Input */}
                    <div className="space-y-2">
                        <label htmlFor="name" className="block text-gray-700 text-lg font-semibold">
                            Full Name
                        </label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value={formData.name}
                            onChange={handleChange}
                            className="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:outline-none"
                            placeholder="Enter your full name"
                            required
                        />
                    </div>

                    {/* Email Input */}
                    <div className="space-y-2">
                        <label htmlFor="email" className="block text-gray-700 text-lg font-semibold">
                            Email Address
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value={formData.email}
                            onChange={handleChange}
                            className="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:outline-none"
                            placeholder="Enter your email address"
                        />
                    </div>

                    {/* Contact Number Input */}
                    <div className="space-y-2">
                        <label htmlFor="phone" className="block text-gray-700 text-lg font-semibold">
                            Contact Number
                        </label>
                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            value={formData.phone}
                            onChange={handleChange}
                            className="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:outline-none"
                            placeholder="Enter your contact number"
                        />
                    </div>

                    {/* Additional Notes Input */}
                    <div className="space-y-2">
                        <label htmlFor="description" className="block text-gray-700 text-lg font-semibold">
                            Message
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            value={formData.description}
                            onChange={handleChange}
                            className="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:outline-none"
                            rows="4"
                            placeholder="Enter any special instructions or message"
                        />
                    </div>

                    {/* Submit Button */}
                    <button
                        type="submit"
                        className="w-full bg-red-600 text-white py-3 rounded-lg shadow-lg hover:bg-red-700 transition duration-300"
                        disabled={apiProcess}>

                        {apiProcess ? "Submitting..." : "Submit"}
                    </button>
                </form>
            </div>

            <Footer/>
        </>
    );
};

export default Contact;
