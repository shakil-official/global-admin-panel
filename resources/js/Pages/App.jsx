import React from "react";
import {BrowserRouter, Route, Routes} from "react-router-dom";
import Landing from "./Home/Landing.jsx";
import About from "./Home/About.jsx";
import Term from "./Home/Term.jsx";
import Contact from "./Home/Contact.jsx";
import NotFound from "./Components/NotFound.jsx";


const App = () => {
    return (
        <>
            <BrowserRouter>
                <Routes>
                    <Route exact path="/" element={<Landing/>}></Route>
                    <Route exact path="/about" element={<About/>}></Route>
                    <Route exact path="/term" element={<Term/>}></Route>
                    <Route exact path="/user/contact" element={<Contact/>}></Route>
                    {/*<Route*/}
                    {/*    exact*/}
                    {/*    path="/cart"*/}
                    {/*    element={<Cart/>}*/}
                    {/*></Route>*/}
                    {/*<Route*/}
                    {/*    exact*/}
                    {/*    path="/details/:id"*/}
                    {/*    element={<ProductDetails/>}*/}
                    {/*></Route>*/}
                    <Route path="*" element={<NotFound/>}></Route>
                </Routes>
            </BrowserRouter>
        </>
    );
};

export default App;


