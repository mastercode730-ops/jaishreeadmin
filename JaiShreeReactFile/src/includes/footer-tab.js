import React, { useContext } from 'react';
import { Link } from 'react-router-dom';
// Import appropriate icons (adjust based on the exact style you want)
import { FaHistory, FaShareAlt, FaUserAlt, FaQuestionCircle } from "react-icons/fa"; // Example icons
import { MdHome } from "react-icons/md";
import { AuthContext } from '../context/AuthContext'; // Adjust path if needed
import styles from '../assets/css/Footer.module.css'; // Import CSS Module

function Footer() {
  const { WhastappMessage } = useContext(AuthContext);

  const isLoggedIn = !!localStorage.getItem('login');

  const handleShare = async (event) => {
    event.preventDefault();
    const messageUrl = await WhastappMessage();
    if (messageUrl) {
      window.location.href = messageUrl;
    } else {
      console.error("Could not get WhatsApp message URL.");
    }
  };

  // Helper function to determine if a link is active (optional for this style)
  // const isActive = (path) => location.pathname === path;

  return (
    <div className={styles.footerContainer}>
      {/* Top border element */}
      <div className={styles.footerTopBorder}></div>

      <div className={styles.footerTab}>

        {/* Profile Button (Added) */}
       <div className={styles.footerTabs}>
       <Link
          to={isLoggedIn ? '/mine' : '/login'} // Example link
          className={styles.footerBtnContainer}
          aria-label="Profile"
        >
          <FaUserAlt className={styles.icon} /> {/* Profile Icon */}
          <p className={styles.label}>Profile</p>
        </Link>

        {/* History Button (Conditional) */}
        {isLoggedIn && (
          <Link
            to='/history/all'
            className={styles.footerBtnContainer}
            aria-label="History"
          >
            <FaHistory className={styles.icon} />
            <p className={styles.label}>History</p>
          </Link>
        )}
       </div>

        {/* Home Button (Center - Special Styling) */}
        <Link
          to='/dashboard'
          className={`${styles.footerBtnContainer} ${styles.centerButton}`}
          aria-label="Home"
        >
          <div className={styles.centerButtonBackground}>
             <MdHome className={`${styles.icon} ${styles.centerIcon}`} />
          </div>
          {/* No label for center button */}
        </Link>

        {/* Share Button (Conditional) */}
        <div className={styles.footerTabs}>
        {isLoggedIn && (
          <Link
            to="#"
            onClick={handleShare}
            className={styles.footerBtnContainer}
            aria-label="Share"
          >
            <FaShareAlt className={styles.icon} />
            <p className={styles.label}>Share</p>
          </Link>
        )}

        {/* Help/Login Button */}
        <Link
          to={isLoggedIn ? '/help' : '/login'}
          className={styles.footerBtnContainer}
          aria-label={isLoggedIn ? 'Help' : 'Login'}
        >
          {/* Apply specific style if needed, or use a wrapper */}
          <div className={styles.helpIconWrapper}>
             <FaQuestionCircle className={`${styles.icon} ${styles.helpIcon}`} />
          </div>
          <p className={styles.label}>{isLoggedIn ? 'Help' : 'Login'}</p>
        </Link>
        </div>
      </div>
    </div>
  );
}

export default Footer;
