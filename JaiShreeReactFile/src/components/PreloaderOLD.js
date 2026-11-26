import '../assets/css/Preloader.css';
function Preloader({status}) {
    return (
        <>
            {status ? <div className="preloader">
                <div className="scene">
                    <div className="loader-container">
                        <p>Loading..</p>
                        <div className="loader">
                            <div className="load"></div>
                            <div className="load1"></div>
                        </div>
                    </div>
                </div>
            </div>
            : '' }
        </>
    )
}

export default Preloader;
