import React from 'react'; // Import React
import styles from '../assets/css/Preloader.module.css'; // Import CSS Module

function Preloader({ status }) {
    // Render nothing if status is false
    if (!status) {
        return null;
    }

    return (
        // Use the CSS module class for the main container
        <div className={styles.preloaderOverlay}>
            <div className={styles.loaderContainer}>
                {/* Optional: Add your logo */}
                {/* <img src="/favicon.png" alt="Loading Logo" className={styles.loaderLogo} /> */}
                <div className={styles.loader}>
                    {/* You can use a simpler spinner or keep the custom one */}
                    {/* Simple Spinner Example: */}
                    {/* <div className={styles.spinner}></div> */}

                    {/* Original Loader Structure (if keeping custom CSS animation) */}
                     <div className={styles.load}></div>
                     <div className={styles.load1}></div>
                </div>
                <p className={styles.loadingText}>Loading...</p>
            </div>
        </div>
    );
}

export default Preloader;
