import { useState, useContext } from "react";
import { motion } from "framer-motion";
import Footer from "../../includes/footer-tab"; // Adjust path
import Preloader from "../Preloader"; // Adjust path
import BackButton from "../../includes/Back_btn"; // Adjust path
import { AuthContext } from "../../context/AuthContext"; // Adjust path
import styles from '../../assets/css/Privacypolicy.module.css'; // Import CSS Module
import { FaWhatsapp, FaTelegramPlane } from "react-icons/fa"; // Icons

function Privacypolicy() {
  // Preloader state - kept false as per original, adjust if loading is needed
  const [preloader] = useState(false);
  const { WhatsappMessageSupport, TelegramLink = "https://t.me/pushpendratechnology" } = useContext(AuthContext); // Get links from context, provide default Telegram

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
      <div className={styles.policyContainer}>
        <motion.div
          variants={containerVariants}
          initial="hidden"
          animate="visible"
          exit={{ opacity: 0 }}
        >
          {/* --- Header --- */}
          <motion.div className={styles.policyHeader} variants={itemVariants}>
            <BackButton className={styles.backButton} />
            <p className={styles.headerTitle}>Contact Us</p>
            {/* Optional: Placeholder for right-side icon/link if needed */}
            <div className={styles.headerPlaceholder}></div>
          </motion.div>

          {/* --- Content Area --- */}
          <motion.div className={styles.contentArea} variants={itemVariants}>
            {/* Informational Text */}
            <h1 className={styles.infoHeading}>
              {/* Keep original text or update as needed */}
              सब भाई ये फ़ोन पे से पेमेंट किया करो ऑटोमैटिक जुड़ जाता है अपलोड करने के बाद
            </h1>

            {/* Help Section */}
            <h2 className={styles.helpHeading}>
              Need Help (मदद की जरूरत है)
            </h2>

            {/* Button Container */}
            <div className={styles.buttonContainer}>
              {/* WhatsApp Button */}
              <button
                className={`${styles.contactButton} ${styles.whatsappButton}`}
                onClick={() => {
                  if (WhatsappMessageSupport) {
                    window.open(WhatsappMessageSupport, '_blank');
                  }
                }}
                disabled={!WhatsappMessageSupport} // Disable if link is missing
              >
                <FaWhatsapp /> हेल्प चाहिए WHATSAPP करो
              </button>

              {/* Telegram Button */}
              <button
                className={`${styles.contactButton} ${styles.telegramButton}`}
                onClick={() => {
                  if (TelegramLink) {
                     window.open(TelegramLink, '_blank');
                  }
                }}
                disabled={!TelegramLink} // Disable if link is missing
              >
                <FaTelegramPlane /> हेल्प चाहिए TELEGRAM करो
              </button>

              {/* Call Button - Uncomment if needed */}
              {/* <button
                className={`${styles.contactButton} ${styles.callButton}`}
                onClick={() => {
                  window.open("tel:+919979068000"); // Replace with actual number
                }}
              >
                 <FaPhoneAlt /> Call Now
              </button> */}
            </div>
          </motion.div>
        </motion.div>
      </div>
      {/* --- Footer --- */}
      {/* Ensure Footer component uses the correct CSS */}
      <Footer />
    </>
  );
}

export default Privacypolicy;
