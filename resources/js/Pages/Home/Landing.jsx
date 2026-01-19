import React from "react";
import {ToastContainer} from "react-toastify";
import {CheckCircle, Mail, Phone, MapPin} from "lucide-react";

const Landing = () => {
    return (
        <>
            <ToastContainer/>

            <div className="min-h-screen bg-white text-gray-800">
                {/* Navbar */}
                <nav className="fixed top-0 left-0 w-full bg-white/90 backdrop-blur shadow-sm z-50">
                    <div className="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                        <h1 className="text-2xl font-bold text-textPrimary">HelloSyl</h1>
                        <ul className="hidden md:flex space-x-8 font-medium">
                            <li><a href="#home" className="hover:text-blue-600">Home</a></li>
                            <li><a href="#features" className="hover:text-blue-600">Features</a></li>
                            <li><a href="#about" className="hover:text-blue-600">About</a></li>
                            <li><a href="#pricing" className="hover:text-blue-600">Pricing</a></li>
                            <li><a href="#contact" className="hover:text-blue-600">Contact</a></li>
                        </ul>
                        <button className="hidden md:inline-block bg-primary text-white px-5 py-2 rounded-xl">
                            Get Started
                        </button>
                    </div>
                </nav>

                {/* Hero */}
                <section
                    id="home"
                    className="pt-52 bg-primary text-white" style={{backgroundImage: "url('/images/hero/18338173.jpg')",}}>
                    <div className="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 className="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                                Professional Landing Pages for Modern Businesses
                            </h2>
                            <p className="text-lg opacity-90 mb-8">
                                Build scalable, responsive, and conversion-focused web experiences using React and
                                Tailwind CSS.
                            </p>
                            <div className="flex gap-4">
                                <button className="bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold">
                                    Start Free Trial
                                </button>
                                <button className="border border-white px-6 py-3 rounded-xl font-semibold">
                                    View Demo
                                </button>
                            </div>
                        </div>
                        <div className="hidden md:block">

                        </div>
                    </div>
                </section>

                {/* Features */}
                <section id="features" className="py-24 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-6 text-center">
                        <h3 className="text-3xl font-bold mb-4">Core Features</h3>
                        <p className="text-gray-600 mb-16">
                            Everything you need to launch and scale your product online.
                        </p>
                        <div className="grid md:grid-cols-3 gap-10">
                            {["Fast Performance", "Responsive Design", "Scalable Architecture"].map(
                                (item) => (
                                    <div key={item} className="bg-white p-10 rounded-3xl shadow-sm">
                                        <CheckCircle className="text-blue-600 mb-4"/>
                                        <h4 className="text-xl font-semibold mb-3">{item}</h4>
                                        <p className="text-gray-600">
                                            Optimized code and modern tooling ensure best-in-class user experience.
                                        </p>
                                    </div>
                                )
                            )}
                        </div>
                    </div>
                </section>

                {/* About */}
                <section id="about" className="py-24 bg-white">
                    <div className="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
                        <div>
                            <h3 className="text-3xl font-bold mb-6">Built for Growth</h3>
                            <p className="text-gray-600 mb-6">
                                Our solutions are designed with scalability, security, and performance in mind.
                            </p>
                            <ul className="space-y-4">
                                <li className="flex items-center gap-3">
                                    <CheckCircle className="text-blue-600"/> Enterprise-ready structure
                                </li>
                                <li className="flex items-center gap-3">
                                    <CheckCircle className="text-blue-600"/> Modern UI/UX standards
                                </li>
                                <li className="flex items-center gap-3">
                                    <CheckCircle className="text-blue-600"/> Long-term maintainability
                                </li>
                            </ul>
                        </div>
                        <div className="h-80 bg-gray-100 rounded-3xl"/>
                    </div>
                </section>

                {/* Pricing */}
                <section id="pricing" className="py-24 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-6 text-center">
                        <h3 className="text-3xl font-bold mb-12">Simple Pricing</h3>
                        <div className="grid md:grid-cols-3 gap-10">
                            {["Starter", "Professional", "Enterprise", "Starter", "Professional", "Enterprise", "Starter", "Professional", "Enterprise"].map((plan) => (
                                <div key={plan} className="bg-white p-10 rounded-3xl shadow-sm">
                                    <h4 className="text-xl font-semibold mb-4">{plan}</h4>
                                    <p className="text-4xl font-bold mb-6">$29</p>
                                    <button className="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold">
                                        Choose Plan
                                    </button>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* Contact */}
                <section id="contact" className="py-24 bg-white">
                    <div className="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12">
                        <div>
                            <h3 className="text-3xl font-bold mb-6">Get in Touch</h3>
                            <p className="text-gray-600 mb-8">
                                Have questions or want a custom solution? Contact us.
                            </p>
                            <ul className="space-y-4 text-gray-600">
                                <li className="flex gap-3"><Mail/> contact@mybrand.com</li>
                                <li className="flex gap-3"><Phone/> +1 234 567 890</li>
                                <li className="flex gap-3"><MapPin/> New York, USA</li>
                            </ul>
                        </div>

                        <form className="bg-gray-50 p-8 rounded-3xl shadow-sm space-y-6">
                            <input className="w-full border rounded-xl px-4 py-3" placeholder="Your Name"/>
                            <input className="w-full border rounded-xl px-4 py-3" placeholder="Your Email"/>
                            <textarea
                                className="w-full border rounded-xl px-4 py-3"
                                rows="4"
                                placeholder="Message"
                            />
                            <button className="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold">
                                Send Message
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </>
    );
};

export default Landing;
