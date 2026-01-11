const Notice = ({title = ''}) => {

    return (
        <>
            {title !== '' && (
                <div className="container p-2 mt-8 text-red-600">
                    <p className="text-[22px] font-bold mb-2 text-center">
                        {title}
                    </p>
                </div>)}
        </>
    )
}

export default Notice;
