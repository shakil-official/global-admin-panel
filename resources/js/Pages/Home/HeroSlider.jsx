import Slider from "react-slick";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import "react-loading-skeleton/dist/skeleton.css";
import React, {useEffect, useState} from "react";
import axios from 'axios';

const HeroSlider = () => {

    const [loading, setLoading] = useState(true);
    const [slideData, setSlideData] = useState([]);

    // Fetch slide data only once on a component mount
    useEffect(() => {
        axios.get('/api/v1/slider-images')
            .then(response => {
                setSlideData(response.data.data);
                setLoading(false)
            })
            .catch(error => {
                console.error('Error fetching slider images:', error);
            });
    }, []); // Empty dependency array ensures this runs only once

    const settings = {
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        pauseOnHover: true,
    };

    if (loading) {
        return (
            <>

            </>
        );
    }

    return (
        <>
            <div className="desktop-slider"> {/* This centers it */}
                <Slider {...settings}>
                    {slideData.map((item, index) => (
                        <div className="outline-none border-none" key={index}>
                            <img
                                className="w-full object-cover"
                                src={item.img}
                                alt="banner"
                            />
                        </div>
                    ))}
                </Slider>
            </div>

        </>
    )
}

export default HeroSlider;
