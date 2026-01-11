import React from "react";

const TopNav = () => {


    return (
        <>
            <div className="hidden lg:block">
                <div className="border-b border-gray-200 py-2">
                    <div className="container">
                        <div className="flex w-fit gap-10 mx-auto font-medium py-2 text-dark">
                            <ul className="groccery_nav relative"> Home</ul>
                            <ul className="groccery_nav relative"> About</ul>
                            <ul className="groccery_nav relative"> Terms and conditions</ul>

                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default TopNav;


