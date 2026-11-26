import { useState, useContext, useEffect } from "react";
import { motion } from "framer-motion";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { useNavigate, useLocation } from "react-router-dom"; // Added useLocation
import Preloader from "./Preloader"; // Adjust path
import { AuthContext } from "../context/AuthContext"; // Adjust path
import styles from '../assets/css/Login.module.css'; // Import CSS Module
import { FaWhatsapp, FaTelegramPlane } from "react-icons/fa"; // Icons

// Custom hook to get query params (if needed, kept from original)
function useQuery() {
  return new URLSearchParams(useLocation().search);
}

function Login() {
  const [preloader, setPreloader] = useState(false); // Default to false unless checking auth state
  const [username, setUsername] = useState(""); // Mobile Number
  const [password, setPassword] = useState(""); // OTP
  const [refferal_code, setRefferalCode] = useState("");
  const [ButtonName, setButtonName] = useState("Send OTP");
  const [OtpContainer, setOtpContainer] = useState(false); // Show OTP field flag
  const [RefferalContainer, setRefferalContainer] = useState(false); // Show Referral field flag
  const query = useQuery();
  const agentId = query.get('agent'); // Get agent ID from URL query param

  const {
      isAuthenticated,
      login,
      WhatsappMessageSupport,
      TelegramLink = "https://t.me/pushpendratechnology", // Default Telegram link
      app_name = "JAI SHREE MATKA", // Default App Name
      app_logo = "/favicon.png" // Default Logo
    } = useContext(AuthContext);

  const navigate = useNavigate();

  // Redirect if already authenticated
  useEffect(() => {
    if (isAuthenticated) {
      navigate("/dashboard");
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

  // Handle Login/OTP Request
  const handleLogin = () => {
    // Basic mobile number validation
    if (username === "" || !/^\d{10}$/.test(username)) {
      message(0, "Please enter a valid 10-digit Mobile Number.");
      return;
    }

    setPreloader(true); // Show preloader during API call

    if (!OtpContainer) {
      // --- Request OTP ---
      let data = { mobile: username };
      if (login) {
          login(data, (response) => {
              setPreloader(false);
              if (response.status === 0) {
                  message(0, response.msg || "Failed to send OTP.");
              } else {
                  setOtpContainer(true); // Show OTP field
                  setButtonName("Verify OTP & Login");
                  message(1, response.msg || "OTP sent successfully.");
                  // Show referral code input if API indicates new user
                  if (response.purpose === 1) {
                      setRefferalContainer(true);
                  } else {
                      setRefferalContainer(false); // Ensure it's hidden otherwise
                  }
              }
          });
      } else {
           setPreloader(false);
           message(0, "Login function not available.");
      }
    } else {
      // --- Verify OTP & Login ---
      if (password === "" || !/^\d{4,6}$/.test(password)) { // Basic OTP validation (4-6 digits)
        setPreloader(false);
        message(0, "Please enter a valid OTP.");
        return;
      }
      // Include referral code only if the container is shown
      let data = {
        mobile: username,
        otp: password,
        ...(RefferalContainer && { refferal: refferal_code }), // Conditionally add referral
        ...(agentId && { agentId: agentId }) // Conditionally add agentId
      };

      if (login) {
          login(data, (response) => {
              setPreloader(false);
              if (response.status === 0) {
                  message(0, response.msg || "Login failed.");
              } else {
                  message(1, response.msg || "Login successful!");
                  // Auth context should handle redirect via useEffect on isAuthenticated change
              }
          });
      } else {
           setPreloader(false);
           message(0, "Login function not available.");
      }
    }
  };


  // Framer Motion variants
  const containerVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: { opacity: 1, y: 0, transition: { staggerChildren: 0.1, delayChildren: 0.2 } },
  };
  const itemVariants = {
    hidden: { y: 15, opacity: 0 },
    visible: { y: 0, opacity: 1 },
  };

  return (
    <>
      <Preloader status={preloader} />
      <ToastContainer /* Props remain same */ />

      {/* Use the Guruji theme container */}
      <div className={styles.loginContainer}>
        <motion.div
            className={styles.loginBox}
            variants={containerVariants}
            initial="hidden"
            animate="visible"
            exit={{ opacity: 0 }}
        >
            {/* --- Logo and Title --- */}
            <motion.div className={styles.logoContainer} variants={itemVariants}>
              <img src={app_logo} className={styles.logo} alt="Logo" />
              <p className={styles.title}>{app_name}</p>
            </motion.div>

            {/* --- Input Fields --- */}
            <motion.div className={styles.inputGroup} variants={itemVariants}>
                <label htmlFor="mobileInput" className={styles.inputLabel}>Mobile Number</label>
                <input
                    id="mobileInput"
                    type="tel"
                    inputMode="numeric"
                    maxLength={10}
                    className={styles.formInput}
                    value={username}
                    onInput={(e) => setUsername(e.target.value.replace(/[^0-9]/g, ''))}
                    placeholder="Enter 10 Digit Number"
                    disabled={OtpContainer} // Disable after OTP sent
                />
            </motion.div>

            {/* Referral Code Input (Conditional) */}
            {RefferalContainer && (
                <motion.div className={styles.inputGroup} variants={itemVariants}>
                    <label htmlFor="referralInput" className={styles.inputLabel}>Referral Code (Optional)</label>
                    <input
                        id="referralInput"
                        type="text" // Allow text/numbers for referral
                        className={styles.formInput}
                        value={refferal_code}
                        onInput={(e) => setRefferalCode(e.target.value)}
                        placeholder="Enter Referral Code"
                    />
                </motion.div>
            )}

            {/* OTP Input (Conditional) */}
            {OtpContainer && (
                <motion.div className={styles.inputGroup} variants={itemVariants}>
                    <label htmlFor="otpInput" className={styles.inputLabel}>Enter OTP</label>
                    <input
                        id="otpInput"
                        type="tel" // Use tel for number pads
                        inputMode="numeric"
                        maxLength={6} // Assuming OTP length
                        className={styles.formInput}
                        value={password}
                        onInput={(e) => setPassword(e.target.value.replace(/[^0-9]/g, ''))}
                        placeholder="Enter OTP Received"
                    />
                </motion.div>
            )}

            {/* --- Login/OTP Button --- */}
            <motion.div className={styles.buttonContainer} variants={itemVariants}>
                <button className={styles.loginButton} onClick={handleLogin} disabled={preloader}>
                    {preloader ? 'Processing...' : ButtonName}
                </button>
            </motion.div>

            {/* --- Help Buttons --- */}
            <motion.div className={styles.helpButtonContainer} variants={itemVariants}>
                <button
                    className={`${styles.helpButton} ${styles.whatsappHelpButton}`}
                    onClick={() => { if (WhatsappMessageSupport) window.open(WhatsappMessageSupport, '_blank'); }}
                    disabled={!WhatsappMessageSupport}
                >
                    <FaWhatsapp /> WhatsApp Help
                </button>
                <button
                    className={`${styles.helpButton} ${styles.telegramHelpButton}`}
                    onClick={() => { if (TelegramLink) window.open(TelegramLink, '_blank'); }}
                    disabled={!TelegramLink}
                >
                    <FaTelegramPlane /> Telegram Help
                </button>
            </motion.div>

        </motion.div>
      </div>
    </>
  );
}

export default Login;
