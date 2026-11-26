import React, { useState, useContext, useEffect } from "react";
import {
  initializeFirebase,
  requestNotificationPermission,
  // getFirebaseMessaging // Uncomment if using foreground messages
} from "../firebase"; // Adjust path if needed
import { motion } from "framer-motion";
import { Link, useNavigate } from "react-router-dom";
import Footer from "../includes/footer-tab"; // Adjust path if needed
import { FaWhatsapp } from "react-icons/fa"; // Added Telegram
import { GrMenu } from "react-icons/gr";
import { BiMoneyWithdraw } from "react-icons/bi";
import { FaChartLine, FaPlay, FaChevronRight } from "react-icons/fa"; // Keep ChevronRight
import { AuthContext } from "../context/AuthContext"; // Adjust path if needed
import { ToastContainer, toast } from "react-toastify";
import Preloader from "./Preloader"; // Adjust path if needed
import "react-responsive-carousel/lib/styles/carousel.min.css";
import { Carousel } from "react-responsive-carousel";
// import { onMessage } from 'firebase/messaging'; // Uncomment if using foreground messages
import styles from '../assets/css/Welcome.module.css'; // Import CSS Module

function Dashboard() {
  const [preloader, setPreloader] = useState(true);
  const [MarketData, setMarketData] = useState([]);
  const [UpcommingMarketData, setUpcommingMarketData] = useState([]);
  const [Walletd, setWalletd] = useState(0);
  const [banner, setBanner] = useState([]);
  const [NoticeData, setNoticeData] = useState("");
  const [NoticeStatus, setNoticeStatus] = useState(true); // Keep notice logic

  const {
    app_name = "JAI SHREE MATKA", // Default App Name from image
    getMarkets,
    isAuthenticated,
    profile,
    WhatsappMessageSupport, // Assuming this is the WhatsApp link
    base_url,
    FSC_KEY_UPDATE,
  } = useContext(AuthContext);

  const navigate = useNavigate();

  // Redirect if not authenticated
  useEffect(() => {
    if (!isAuthenticated) {
      navigate('/login');
    }
  }, [isAuthenticated, navigate]);

  // --- Toast Message Function ---
  const message = (status, text) => {
    const options = {
      position: "top-right",
      autoClose: 3000, // Shorter duration
      hideProgressBar: false,
      closeOnClick: true,
      pauseOnHover: true,
      draggable: true,
      progress: undefined,
      theme: "dark", // Dark theme for toast
    };
    if (status === 1) toast.success(text, options);
    else if (status === 0) toast.error(text, options);
    else if (status === 2) toast.warning(text, options);
  };

  // --- Firebase Initialization & Notification Permission ---
  useEffect(() => {
    initializeFirebase();
    requestNotificationPermission()
      .then((token) => {
        if (token && FSC_KEY_UPDATE) {
          FSC_KEY_UPDATE(token, (d) => {
            console.log("FCM Token Update Response:", d);
          });
        }
      })
      .catch((error) => {
        console.error("Error getting FCM token:", error);
      });
     // Add foreground message handling if needed (commented out)
  }, [FSC_KEY_UPDATE]);

  // --- Notice Visibility Logic ---
  useEffect(() => {
    const today = new Date().toDateString();
    const savedDate = localStorage.getItem("noticeDate");
    const savedStatus = localStorage.getItem("noticeStatus");
    if (savedDate === today && savedStatus === "false") {
      setNoticeStatus(false);
    } else {
      localStorage.setItem("noticeDate", today);
      localStorage.setItem("noticeStatus", "true");
      setNoticeStatus(true); // Default to true if not dismissed today
    }
  }, []);

  // eslint-disable-next-line no-unused-vars
  const handleCloseNotice = () => {
    setNoticeStatus(false);
    localStorage.setItem("noticeStatus", "false");
  };

  // --- Fetch Market and Profile Data ---
  useEffect(() => {
    let isMounted = true;
    setPreloader(true);

    const fetchAllData = async () => {
        try {
            // Fetch Markets
            if (getMarkets) {
                await new Promise((resolve, reject) => {
                    getMarkets((response, boolRes) => {
                        if (!isMounted) return reject('Component unmounted');
                        if (!boolRes && response.response?.status !== 200) {
                            message(0, response.response?.data?.error || "Failed to fetch markets");
                            reject('Market fetch error');
                        } else if (response.status === 0) {
                            message(0, response.msg);
                            reject('Market API error');
                        } else {
                            setMarketData(response.running_game || []);
                            const sortedData = (response.upcomming_game || [])
                                .sort((a, b) => {
                                    if (a.sequence !== b.sequence) return a.sequence - b.sequence;
                                    const dateA = new Date(a.datetime);
                                    const dateB = new Date(b.datetime);
                                    return dateA - dateB;
                                });
                            setUpcommingMarketData(sortedData);
                            setBanner(response.banner || []);
                            setNoticeData(response.notice?.[0]?.text_msg || "");
                            // Update notice status based on fetched data only if not dismissed
                            const today = new Date().toDateString();
                            const savedDate = localStorage.getItem("noticeDate");
                            const savedStatus = localStorage.getItem("noticeStatus");
                            if (!(savedDate === today && savedStatus === "false")) {
                                setNoticeStatus(!!response.notice?.[0]?.text_msg);
                            }
                            resolve();
                        }
                    });
                });
            }

            // Fetch Profile
            if (profile) {
                await new Promise((resolve, reject) => {
                    profile((response) => {
                        if (!isMounted) return reject('Component unmounted');
                        if (response.status === 0) {
                            message(0, response.msg);
                            reject('Profile API error');
                        } else {
                            setWalletd(response.data?.[0]?.wallet || 0);
                            resolve();
                        }
                    });
                });
            }
        } catch (error) {
            console.error("Error fetching data:", error);
            // Handle errors if needed, maybe show a generic error message
        } finally {
            if (isMounted) {
                setPreloader(false); // Hide preloader after all fetches attempt
            }
        }
    };

    fetchAllData();

    // Cleanup function
    return () => {
      isMounted = false;
    };

  }, [getMarkets, profile, isAuthenticated]); // Add isAuthenticated


  // --- Framer Motion Variants ---
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { staggerChildren: 0.05 } }, // Slightly faster stagger
  };
  const itemVariants = {
    hidden: { y: 10, opacity: 0 },
    visible: { y: 0, opacity: 1, transition: { type: 'tween', duration: 0.3 } }, // Smoother tween
  };


  return (
    // Use the specific container style for this design
    <div className={styles.gurujiContainer}>
      <Preloader status={preloader} />
      <ToastContainer
        position="top-right"
        autoClose={3000}
        hideProgressBar={false}
        newestOnTop={false}
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
        theme="dark"
      />

      {/* Main content area */}
      {!preloader && (
        <motion.div
          // No specific wrapper needed if container handles padding
          variants={containerVariants}
          initial="hidden"
          animate="visible"
          exit={{ opacity: 0 }}
        >
          {/* --- Header --- */}
          <motion.div className={styles.gurujiHeader} variants={itemVariants}>
            <button onClick={() => navigate("/mine")} className={styles.gurujiHeaderMenuBtn} aria-label="Menu">
              <GrMenu />
            </button>
            <h1 className={styles.gurujiHeaderTitle}>{app_name}</h1>
            <Link to="/recharge" className={styles.gurujiHeaderWallet} aria-label="Wallet Balance">
              {Walletd} ₹
            </Link>
          </motion.div>
          <Carousel
          showThumbs={false}
          showStatus={false}
          infiniteLoop={true}
          showArrows={false}
          autoPlay={true}
        >
          {banner.map((market) => (
          <div>
              <img src={base_url+market.banner_img} alt="help"/>
          </div>
            ))}
        </Carousel>

          {/* --- Banner/Notice Area --- */}
          {/* Using NoticeData for the banner as per image */}
          {NoticeStatus && NoticeData && (
             <motion.div className={styles.gurujiTopNoticeBanner} variants={itemVariants}>
               {/* Optional close button if this should be dismissible */}
               {/* <button onClick={handleCloseNotice} className={styles.gurujiNoticeClose}>&times;</button> */}
               {/* Render the notice HTML */}
               <div dangerouslySetInnerHTML={{ __html: NoticeData }}></div>
             </motion.div>
           )}

          {/* --- Action Buttons --- */}
          <motion.div className={styles.gurujiActionButtons} variants={itemVariants}>
             {/* WhatsApp Button */}
             <button onClick={() => { window.open(WhatsappMessageSupport, '_blank'); }} className={`${styles.gurujiActionButton} ${styles.gurujiWhatsapp}`}>
               <FaWhatsapp />
             </button>
             {/* Telegram Button */}
             
             {/* Add Fund Button */}
             <button onClick={() => navigate('/recharge')} className={`${styles.gurujiActionButton} ${styles.gurujiAddFund}`}>
               <span className={styles.gurujiAddFundIcon}>$</span> ADD FUND
             </button>
             {/* Withdraw Button */}
             <button onClick={() => navigate('/withdrawal')} className={`${styles.gurujiActionButton} ${styles.gurujiWithdraw}`}>
               <BiMoneyWithdraw /> WITHDRAW
             </button>
          </motion.div>

          <div className="FastResult">
            <Link to="https://mahadevmatka.me">Fast Result</Link>
          </div>
          {/* --- Market List --- */}
          <motion.div className={styles.gurujiMarketList} variants={itemVariants}>
             {/* Upcoming Markets */}
             {UpcommingMarketData.map((market) => (
                <div className={`${styles.gurujiMarketCard} ${styles.gurujiUpcomingMarket}`} key={`upcoming-${market.game_id}`}>
                  <div className={styles.gurujiMarketCardHeader}>
                    {market.game_name.toUpperCase()}
                  </div>
                  <div className={styles.gurujiMarketCardBody}>
                    <Link to={`/gamechart/${market.match_id || market.game_id}`} className={styles.gurujiMarketChartLink} aria-label={`Chart for ${market.game_name}`}>
                       <FaChartLine />
                       <span>Game record</span>
                    </Link>
                    <div className={styles.gurujiMarketResultSection}>
                       <span className={`${styles.gurujiMarketResultOld} ${styles.gurujiResultLabel}`}>
                         {market.result_old || 'XX'} {/* Use actual old result if available */}
                         <small>OLD</small>
                       </span>
                       <FaChevronRight className={styles.gurujiResultSeparator}/>
                       <span className={`${styles.gurujiMarketResultNew} ${styles.gurujiResultLabel}`}>
                         {market.result || 'XX'}
                         <small>NEW</small>
                       </span>
                    </div>
                    <div className={styles.gurujiMarketPlayButtonContainer}>
                        {market.active ? (
                          <Link to={`/play/${market.game_id}`} className={`${styles.gurujiMarketPlayButton} ${styles.gurujiPlayActive}`} aria-label={`Play ${market.game_name}`}>
                            PLAY <FaPlay className={styles.gurujiPlayIcon}/>
                          </Link>
                        ) : (
                          <span className={`${styles.gurujiMarketPlayButton} ${styles.gurujiPlayInactive}`}>TIME OUT</span>
                        )}
                    </div>
                  </div>
                  <div className={styles.gurujiMarketCardFooter}>
                    <span>{market.start_time}</span>
                    <span>{market.end_time}</span>
                  </div>
                </div>
             ))}

             {/* Active/Regular Markets */}
             {MarketData.map((market) => (
                 <div className={styles.gurujiMarketCard} key={`active-${market.game_id}`}>
                   <div className={styles.gurujiMarketCardHeader}>
                     {market.game_name.toUpperCase()}
                   </div>
                   <div className={styles.gurujiMarketCardBody}>
                     <Link to={`/gamechart/${market.game_id}`} className={styles.gurujiMarketChartLink} aria-label={`Chart for ${market.game_name}`}>
                        <FaChartLine />
                        <span>Game record</span>
                     </Link>
                     <div className={styles.gurujiMarketResultSection}>
                        <span className={`${styles.gurujiMarketResultOld} ${styles.gurujiResultLabel}`}>
                          {market.result_old || 'XX'} {/* Use actual old result */}
                          <small>OLD</small>
                        </span>
                        <FaChevronRight className={styles.gurujiResultSeparator}/>
                        <span className={`${styles.gurujiMarketResultNew} ${styles.gurujiResultLabel}`}>
                          {market.result || 'XX'}
                          <small>NEW</small>
                        </span>
                     </div>
                     <div className={styles.gurujiMarketPlayButtonContainer}>
                         <Link to={`/play/${market.game_id}`} className={`${styles.gurujiMarketPlayButton} ${styles.gurujiPlayActive}`} aria-label={`Play ${market.game_name}`}>
                           PLAY <FaPlay className={styles.gurujiPlayIcon}/>
                         </Link>
                         {/* Optional: Add Fast Result/Refresh buttons if needed */}
                         {/* <button className={styles.gurujiFastResultButton}>Fast Result</button> */}
                     </div>
                   </div>
                   <div className={styles.gurujiMarketCardFooter}>
                     <span>{market.start_time}</span>
                     <span>{market.end_time}</span>
                   </div>
                 </div>
             ))}
          </motion.div>

        </motion.div>
      )} {/* End !preloader condition */}

      {/* Footer Component - Assuming Footer component uses the correct CSS */}
      <Footer />
    </div>
  );
}

export default Dashboard;
