import { useEffect, useState } from "react";
import axios from "axios";

export default function PartnerLogos() {
    const [images, setImages] = useState([]);

    useEffect(() => {
        axios.get("/api/partner-images").then((res) => {
            setImages(res.data);
        });
    }, []);

    return (
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
            {images.map((url, index) => (
                <img key={index} src={url} alt={`Partner ${index + 1}`} className="h-auto max-w-full bg-white" />
            ))}
        </div>
    );
}
