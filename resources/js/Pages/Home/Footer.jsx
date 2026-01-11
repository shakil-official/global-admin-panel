import React from "react";

const Footer = () => {

    const year = new Date().getFullYear();

    return (
        <>
            <footer className=" mt-20">
                <div className="container mx-auto">
                    <div className="px-6 py-8 pb-6 mx-auto">
                        <div className="flex flex-wrap items-center justify-between">
                            <div className="w-full px-4">
                                <div
                                    className="flex flex-wrap justify-center -mx-3 xl:justify-center text-center xl:text-left">
                                    &copy; {year}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </>
    );
};

export default Footer;
