import React, {useEffect, useState} from "react";
import {Carousel} from "react-responsive-carousel";
import "react-responsive-carousel/lib/styles/carousel.min.css"; // requires a loader


const Slider = ({data}) => {
    return (
        <div style={{maxWidth: "100%"}}>
            <Carousel
                showArrows
                autoPlay
                infiniteLoop
                showThumbs={false}
                dynamicHeight={false}
                emulateTouch
                interval={3000}
            >
                {
                    data.map((value, index) => {
                        return (
                            <div key={index}>
                                <img src={value.image} alt={value.description}/>
                                {/*<p className="legend">{value.description}</p>*/}
                            </div>
                        )
                    })
                }
            </Carousel>
        </div>

    )

}

export default Slider;
