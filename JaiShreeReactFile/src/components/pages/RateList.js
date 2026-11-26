import { useState, useContext, useEffect } from 'react';
import { motion } from "framer-motion";
import Footer from '../../includes/footer-tab'; // Adjust path
import Preloader from '../Preloader'; // Adjust path
import BackButton from '../../includes/Back_btn'; // Adjust path
import { AuthContext } from '../../context/AuthContext'; // Adjust path
import styles from '../../assets/css/RateList.module.css'; // Import CSS Module
import { useNavigate } from 'react-router-dom'; // Import useNavigate

function RateList() {
    const [preloader, setPreloader] = useState(true);
    // const [Data, setData] = useState(""); // Data state seems unused in the provided JSX
    const [jodirate, setJodiRate] = useState("N/A"); // Default value
    const [hurfrate, setHurfrate] = useState("N/A"); // Default value
    const { GameRule, app_name = "GURUJI MATKA", isAuthenticated } = useContext(AuthContext); // Get app_name from context
    const navigate = useNavigate(); // Hook for navigation

    // Redirect if not authenticated
    useEffect(() => {
        if (!isAuthenticated) {
            navigate('/login');
        }
    }, [isAuthenticated, navigate]);


    // Fetch game rules/rates
    useEffect(() => {
        let isMounted = true;
        setPreloader(true);
        if (GameRule) {
            GameRule((response) => {
                if (!isMounted) return;
                setPreloader(false);
                if (response.status === 0) {
                    console.error("Error fetching game rules:", response.msg);
                    // Optionally show a toast message here
                } else {
                    setJodiRate(response.jodirate || "N/A");
                    setHurfrate(response.hurfrate || "N/A");
                    // setData(response.data?.description || ""); // Set data if you plan to display it
                }
            });
        } else {
            setPreloader(false); // Hide preloader if function not available
            console.error("GameRule function not available in context.");
        }
        return () => { isMounted = false; }; // Cleanup
    }, [GameRule]); // Dependency

    // Framer Motion variants
    const containerVariants = {
        hidden: { opacity: 0 },
        visible: { opacity: 1, transition: { staggerChildren: 0.1 } },
    };
    const itemVariants = {
        hidden: { y: 20, opacity: 0 },
        visible: { y: 0, opacity: 1, transition: { type: 'spring', stiffness: 100 } },
    };

    return (
        <>
            <Preloader status={preloader} />
            {/* Use the Guruji theme container */}
            <div className={styles.rateListContainer}>
                {!preloader && (
                    <motion.div
                        variants={containerVariants}
                        initial="hidden"
                        animate="visible"
                        exit={{ opacity: 0 }}
                    >
                        {/* --- Header --- */}
                        <motion.div className={styles.rateListHeader} variants={itemVariants}>
                            <BackButton className={styles.backButton} />
                            <p className={styles.headerTitle}>Rate List</p>
                            {/* Optional: Placeholder for right-side icon/link if needed */}
                            <div className={styles.headerPlaceholder}></div>
                        </motion.div>

                        {/* --- Rate Card --- */}
                        <motion.div className={styles.rateCard} variants={itemVariants}>
                            <h2 className={styles.cardTitle}>{app_name} Rate List</h2>
                            <div className={styles.rateItem}>
                                <span className={styles.rateType}>Jodi:</span>
                                <span className={styles.rateWin}>100 <span className={styles.smallText}>के</span> {jodirate}</span>
                            </div>
                            <div className={styles.rateItem}>
                                <span className={styles.rateType}>Harf:</span>
                                <span className={styles.rateWin}>100 <span className={styles.smallText}>के</span> {hurfrate}</span>
                            </div>
                            {/* Optional: Display description if needed */}
                            {/* {Data && <p className={styles.description}>{Data}</p>} */}
                            <p className={styles.appMention}>
                                Experience the game on the {app_name} App.
                            </p>
                        </motion.div>

                        {/* You can add more content here if needed */}

                    </motion.div>
                )}
            </div>
            {/* --- Footer --- */}
            <Footer /> {/* Ensure Footer component uses the correct CSS */}
        </>
    );
}

export default RateList;
