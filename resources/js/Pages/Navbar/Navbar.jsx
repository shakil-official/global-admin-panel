import React, {useEffect} from "react";


const Navbar = ({children}) => {


    useEffect(() => {
        renderCartCount();
    }, []);

    return (
        <>

        </>
    );
};

export default Navbar;
