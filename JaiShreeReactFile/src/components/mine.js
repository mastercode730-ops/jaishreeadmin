import { useState, useContext, useEffect } from 'react';
import { motion } from "framer-motion";
import Footer from '../includes/footer-tab'; // Adjust path if needed
import Dropdown from '../includes/Dropdown'; // Adjust path if needed
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Preloader from './Preloader'; // Adjust path if needed
import { useNavigate, Link } from 'react-router-dom'; // Import Link
import { AuthContext } from '../context/AuthContext'; // Adjust path if needed
import $ from 'jquery'; // Keep jQuery for modal as per original code
import styles from '../assets/css/Mine.module.css'; // Import CSS Module
import { FaUserCircle } from "react-icons/fa"; // Example profile icon
import { IoLogOutOutline } from "react-icons/io5"; // Logout icon

function Mine() {
    const [userid, setUserId] = useState('');
    const [userName, setUserName] = useState(''); // Added state for username
    const [preloader, setPreloader] = useState(true);
    const { isAuthenticated, logout, profile, WhatsappMessage, WhastappMessage } = useContext(AuthContext);
    const navigate = useNavigate();

    // Redirect if not authenticated
    useEffect(() => {
        if (!isAuthenticated) {
            navigate('/login');
        }
    }, [isAuthenticated, navigate]);

    // Toast message function
    const message = (status, text) => {
        const options = {
            position: "top-right", autoClose: 3000, hideProgressBar: false,
            closeOnClick: true, pauseOnHover: true, draggable: true,
            progress: undefined, theme: "dark",
        };
        if (status === 1) toast.success(text, options);
        else if (status === 0) toast.error(text, options);
        else if (status === 2) toast.warning(text, options);
    };

    // Fetch profile data
    useEffect(() => {
        let isMounted = true;
        setPreloader(true);
        if (profile) {
            profile((response) => {
                if (!isMounted) return;
                setPreloader(false);
                if (response.status === 0) {
                    message(0, response.msg || "Failed to load profile");
                } else if (response.data && response.data.length > 0) {
                    setUserId(response.data[0].mobile || '');
                    setUserName(response.data[0].name || 'User'); // Get username
                } else {
                     message(0,"Profile data not found");
                }
            });
        } else {
             setPreloader(false); // Ensure preloader hides if profile function isn't available
        }
        return () => { isMounted = false; }; // Cleanup
    }, [profile]); // Dependency

    // Logout handler
    const handleLogout = () => {
        logout();
        // Optional: Redirect after logout if needed
        // navigate('/login');
    };

    // Framer Motion variants
    const containerVariants = {
        hidden: { opacity: 0 },
        visible: { opacity: 1, transition: { staggerChildren: 0.05 } },
    };
    const itemVariants = {
        hidden: { x: -20, opacity: 0 },
        visible: { x: 0, opacity: 1, transition: { type: 'tween', duration: 0.3 } },
    };


    // Function to handle external links or specific actions
    const handleDropdownClick = (url, isExternal = false, action = null) => {
        if (action === 'share') {
            WhastappMessage().then(shareUrl => {
                if (shareUrl) {
                    window.open(shareUrl, '_blank');
                } else {
                    message(0, "Could not generate share link.");
                }
            }).catch(err => {
                 message(0, "Error generating share link.");
                 console.error(err);
            });
        } else if (isExternal && url) {
            window.open(url, '_blank');
        } else if (url) {
            navigate(url);
        }
        // If only itemclick is needed without navigation, handle it here
    };


    return (
        <>
            <Preloader status={preloader} />
            <ToastContainer /* Props remain same */ />

            {/* Use the Guruji theme container */}
            <div className={styles.mineContainer}>
                {!preloader && (
                    <motion.div
                        variants={containerVariants}
                        initial="hidden"
                        animate="visible"
                        exit={{ opacity: 0 }}
                    >
                        {/* --- Header Section --- */}
                        <motion.div className={styles.mineHeader} variants={itemVariants}>
                            <div className={styles.profileImage}>
                                {/* Placeholder Icon - Replace with actual image if available */}
                                <FaUserCircle />
                            </div>
                            <div className={styles.profileInfo}>
                                <p className={styles.profileName}>{userName}</p>
                                <p className={styles.profileId}>ID: {'+91' + userid}</p>
                            </div>
                        </motion.div>

                        {/* --- List of Options --- */}
                        <motion.div className={styles.optionsList} variants={itemVariants}>
                            {/* Use Link directly for simple navigation */}
                            <Link to="/dashboard" className={styles.optionItem}>
                                <img src="/icons_asset/home.png" alt="Dashboard" className={styles.optionIcon} />
                                <span className={styles.optionName}>Dashboard</span>
                                <span className={styles.optionArrow}>&gt;</span>
                            </Link>

                            {/* Use Dropdown component where needed */}
                            {/* Note: Styling might need adjustment based on Dropdown's internal structure */}
                            

                            <Link to="/recharge" className={styles.optionItem}>
                                <img src="/icons_asset/wallet.png" alt="Win History" className={styles.optionIcon} />
                                <span className={styles.optionName}>Recharge <small>(पैसा जोड़े)</small></span>
                                <span className={styles.optionArrow}>&gt;</span>
                            </Link>
                            <Link to="/withdrawal" className={styles.optionItem}>
                                <img src="/icons_asset/wallet.png" alt="Win History" className={styles.optionIcon} />
                                <span className={styles.optionName}>Withdrawal <small>(अपना पैसा निकाले)</small></span>
                                <span className={styles.optionArrow}>&gt;</span>
                            </Link>

                            <Link to="/history/win" className={styles.optionItem}>
                                <img src="/icons_asset/win_record.png" alt="Win History" className={styles.optionIcon} />
                                <span className={styles.optionName}>Win History <small>(अपना जीत देखे)</small></span>
                                <span className={styles.optionArrow}>&gt;</span>
                            </Link>

                            <Link to="/history/marketcancel" className={styles.optionItem}>
                                <img src="/icons_asset/cancel_record.png" alt="Cancel Box" className={styles.optionIcon} />
                                <span className={styles.optionName}>Kabad cancel box</span>
                                <span className={styles.optionArrow}>&gt;</span>
                            </Link>

                            <Link to="/history/market" className={styles.optionItem}>
                                <img src="/icons_asset/history.png" alt="Bid History" className={styles.optionIcon} />
                                <span className={styles.optionName}>Bid History <small>(अपना खेला हुआ गेम देखे)</small></span>
                                <span className={styles.optionArrow}>&gt;</span>
                            </Link>

                            <Link to="/ratelist" className={styles.optionItem}>
                                <img src="/icons_asset/rate_list.png" alt="Rate List" className={styles.optionIcon} />
                                <span className={styles.optionName}>Rate List</span>
                                <span className={styles.optionArrow}>&gt;</span>
                            </Link>
                            <Link to={WhatsappMessage} className={styles.optionItem}>
                                <img src="/icons_asset/share.png" alt="Rate List" className={styles.optionIcon} />
                                <span className={styles.optionName}>Share App <small>(जो गेम खेलता है उसे शेयर करें)</small></span>
                                <span className={styles.optionArrow}>&gt;</span>
                            </Link>
                            {/* Logout Button */}
                            <motion.div className={styles.logoutButtonContainer} variants={itemVariants}>
                                <button className={styles.logoutButton} onClick={() => { $('.logout-modal.modal').addClass('active') }}>
                                    <IoLogOutOutline /> Logout
                                </button>
                            </motion.div>
                        </motion.div>
                    </motion.div>
                )}
            </div>

            {/* --- Logout Modal --- */}
            {/* Apply styles via CSS Module */}
            <section className={`${styles.logoutModal} logout-modal modal`}>
                <div className={styles.modalSection}>
                    <p className={styles.modalTitle}>Confirm Logout</p>
                    <p className={styles.modalText}>Do you want to continue?</p>
                    <div className={styles.modalBtnContainer}>
                        <div className={styles.modalActionBtn}>
                            <div onClick={() => { $('.logout-modal.modal').removeClass('active') }} className={styles.modalCancelBtn}>CANCEL</div>
                            <div onClick={handleLogout} className={styles.modalConfirmBtn}>YES</div>
                        </div>
                    </div>
                </div>
            </section>

            {/* --- Footer --- */}
            {/* Ensure Footer component uses the correct CSS */}
            <Footer />
        </>
    );
}

export default Mine;
