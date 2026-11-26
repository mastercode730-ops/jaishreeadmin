import { React, useState, useContext, useEffect } from "react";
import { motion } from "framer-motion";
import Preloader from "../components/Preloader"; // Adjust path
import "react-toastify/dist/ReactToastify.css";
import { useNavigate } from "react-router-dom";
import { ToastContainer, toast } from "react-toastify";
import { AuthContext } from "../context/AuthContext"; // Adjust path
import BackButton from "../includes/Back_btn"; // Assuming you want a back button
import styles from '../assets/css/GamePageLayout.module.css'; // Import CSS Module

// Helper function to format time (HH:MM:SS)
const formatTime = (timeString) => {
    if (!timeString || timeString === "00:00:00") return "00:00:00";
    // Optional: Add more sophisticated parsing if needed
    return timeString;
};

const GamePageLayout = ({ children, handlers, id, GameName: initialGameName, new_message }) => {
  const [GameName, setGameName] = useState(initialGameName || "Loading..."); // Use prop or default
  const [preloader, setPreloader] = useState(!initialGameName); // Show preloader if name isn't passed initially
  const { isAuthenticated, Gameinfo } = useContext(AuthContext);
  const [timeLeft, setTimeLeft] = useState("00:00:00");
  const [motiJodiTimeLeft, setMotiJodiTimeLeft] = useState("00:00:00"); // Renamed state
  const [targetDateTime, setTargetDateTime] = useState(""); // End time for main game
  const [targetDateTimeMotiJodi, setTargetDateTimeMotiJodi] = useState(""); // End time for Moti Jodi

  const navigate = useNavigate();

  // Redirect if not authenticated
  useEffect(() => {
    if (!isAuthenticated) {
      navigate("/login");
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

  // Fetch Game Info if not passed via props or if id changes
  useEffect(() => {
    let isMounted = true;
    // Only fetch if Gameinfo function exists and id is provided
    if (Gameinfo && id) {
        setPreloader(true); // Show preloader when fetching
        Gameinfo(id, (res) => {
            if (!isMounted) return;
            setPreloader(false);
            if (res.status === 0) {
                message(0, res.msg || `Failed to load game info for ID: ${id}`);
                setGameName("Unknown Game");
            } else if (res.data && res.data.length > 0) {
                const daa = res.data[0];
                setGameName(daa.game_name || "Unknown Game");
                setTargetDateTime(daa.end_date && daa.end_time ? `${daa.end_date} ${daa.end_time}` : "");
                setTargetDateTimeMotiJodi(daa.result_date && daa.result_time ? `${daa.result_date} ${daa.result_time}` : "");
            } else {
                setGameName("Unknown Game");
            }
        });
    } else if (!initialGameName) {
         // If no Gameinfo or id, but also no initial name, set default and hide preloader
         setGameName("Unknown Game");
         setPreloader(false);
    } else {
         // If initial name was provided, no need to fetch, hide preloader
         setPreloader(false);
    }
    return () => { isMounted = false; };
  }, [id, Gameinfo, initialGameName]); // Rerun if id or Gameinfo changes

  // Timer Calculation Effect
  useEffect(() => {
    const calculateTimeLeft = (targetDateTimeString) => {
        if (!targetDateTimeString) return "00:00:00";
        const targetDate = new Date(targetDateTimeString);
        const now = new Date();
        const difference = targetDate - now;

        if (difference > 0) {
            const hours = String(Math.floor(difference / (1000 * 60 * 60))).padStart(2, '0');
            const minutes = String(Math.floor((difference / (1000 * 60)) % 60)).padStart(2, '0');
            const seconds = String(Math.floor((difference / 1000) % 60)).padStart(2, '0');
            return `${hours}:${minutes}:${seconds}`;
        } else {
            return "00:00:00"; // Time has passed
        }
    };

    // Initial calculation
    setTimeLeft(calculateTimeLeft(targetDateTime));
    setMotiJodiTimeLeft(calculateTimeLeft(targetDateTimeMotiJodi));

    // Update every second
    const interval = setInterval(() => {
        setTimeLeft(calculateTimeLeft(targetDateTime));
        setMotiJodiTimeLeft(calculateTimeLeft(targetDateTimeMotiJodi));
    }, 1000);

    // Cleanup interval on component unmount or when target times change
    return () => clearInterval(interval);
  }, [targetDateTime, targetDateTimeMotiJodi]);

  // Framer Motion variants
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: { opacity: 1 }, // Simple fade-in for layout
  };
  // Variants for header/message bar if needed
  const itemVariants = {
      hidden: { y: -10, opacity: 0 },
      visible: { y: 0, opacity: 1, transition: { duration: 0.3 } },
  };

  return (
    <>
      <Preloader status={preloader} />
      <ToastContainer /* Props remain same */ />

      {/* Use the Guruji theme container */}
      <div className={styles.layoutContainer}>
        <motion.div
            {...handlers} // Apply swipe handlers here
            variants={containerVariants}
            initial="hidden"
            animate="visible"
            exit={{ opacity: 0 }}
        >
            {/* --- Header --- */}
            <motion.div className={styles.gameHeader} variants={itemVariants}>
                <BackButton className={styles.backButton} />
                <p className={styles.gameName}>{GameName}</p>
                <div className={styles.timerContainer}>
                    <p className={styles.timerLabel}>Game Ends In</p>
                    <p className={styles.timerValue}>{formatTime(timeLeft)}</p>
                </div>
            </motion.div>

            {/* --- Moti Jodi Message Bar --- */}
            {/* Only show if time is available */}
            {targetDateTimeMotiJodi && (
                 <motion.div className={styles.motiJodiBar} variants={itemVariants}>
                    Moti Jodi Result In : {formatTime(motiJodiTimeLeft)}
                 </motion.div>
            )}


            {/* --- Custom Message Area (Optional) --- */}
            {new_message && (
              <motion.div className={styles.customMessage} variants={itemVariants}>
                <span>{new_message}</span>
              </motion.div>
            )}

            {/* --- Render Child Component (e.g., Playground content) --- */}
            <div className={styles.gameContent}>
                {children}
            </div>

        </motion.div>
      </div>
       {/* Footer is likely rendered by the parent page or here if needed */}
       {/* <Footer /> */}
    </>
  );
};

export default GamePageLayout;
