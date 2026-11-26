import React,{ useRef } from 'react';
import html2canvas from 'html2canvas';
import { useLocation,Link } from 'react-router-dom';
// Import icons from react-icons (using Font Awesome icons here)
import { FaSave, FaHome, FaGamepad } from 'react-icons/fa';
import styles from '../assets/css/Receipt.module.css'; // Import the new CSS module

// Sample data (same as before, or pass via props)
const receiptData = {
  gameName: 'DELHI',
  gameType: 'Copy Paste',
  totalBetAmount: 'RS 20',
  playDate: '25-04-2025',
  dateTime: '25 April 2025 01:09:49 PM',
  transactionId: 'tzgffg9r5q4bk0m7w0qod',
  numbers: [
    { number: '25', amount: '10 RS' }
  ],
};

// You can easily change the theme by modifying these CSS variables in the CSS file
// Or potentially by passing theme props down.

function Receipt() {
  const receiptRef = useRef(null);
  const location = useLocation();
  const receivedData = location.state?.receiptData;
  console.log("Received state in /receipt:", location.state);
  const handleSaveReceipt = () => {
    if (receiptRef.current) {
      // Options for html2canvas (can help with rendering)
      const options = {
          useCORS: true, // Important if you have external images/fonts loaded
          // scale: window.devicePixelRatio, // Capture at device resolution (sharper)
          // backgroundColor: '#1a1a2e', // Or null to try and capture transparency/element background
          logging: true, // Enable logging for debugging
          scrollY: -window.scrollY // Ensure it captures from the top if page is scrolled
      };

      html2canvas(receiptRef.current, options).then(canvas => {
          // Convert the canvas to a PNG image data URL
          const imageData = canvas.toDataURL('image/png');

          // Create a temporary link element to trigger the download
          const link = document.createElement('a');
          link.href = imageData;
          // Set the filename for the download
          link.download = `receipt-${receiptData.gameName}-${receiptData.playDate}.png`;

          // Append link to the body, click it, and then remove it
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);

          console.log('Receipt image download initiated.');
          // Optional: Add a user notification (e.g., using a state variable and displaying a message)
          // alert('Receipt image download started!');

      }).catch(err => {
          console.error("Error generating receipt image with html2canvas:", err);
          alert("Sorry, there was an error saving the receipt image.");
      });

    } else {
        console.error("Receipt element ref is not available.");
        alert("Could not find the receipt element to save.");
    }
  };

  // const handleGoHome = () => { alert('Home navigation not implemented yet.'); };

  return (
    // Note: html2canvas will capture the background of the TARGET element (receiptCard).
    // If you want the blue page background IN the image, you'd need to apply the ref
    // to the pageBackground div instead, or style the receiptCard itself with that background.
    <div className={styles.pageBackground}>
      {/* Attach the ref here */}
      <div ref={receiptRef} className={styles.receiptCard}>
        {/* Optional: Corner accents */}
        <div className={`${styles.corner} ${styles.topLeft}`}></div>
        <div className={`${styles.corner} ${styles.topRight}`}></div>
        <div className={`${styles.corner} ${styles.bottomLeft}`}></div>
        <div className={`${styles.corner} ${styles.bottomRight}`}></div>

        <div className={styles.header}>
          <h2 className={styles.title}><FaGamepad /> Receipt</h2>
           {/* Update button text for clarity */}
          <button className={styles.actionButton} onClick={handleSaveReceipt}>
            <FaSave />
            <span>Save</span>
          </button>
        </div>

        {/* ... rest of the details, numbersSection, footer ... */}
        <div className={styles.details}>
          <div className={styles.detailItem}>
            <strong className={styles.label}>Game Name :</strong>
            <span className={styles.value}>{receivedData[0]}</span>
          </div>
          <div className={styles.detailItem}>
            <strong className={styles.label}>Game Type :</strong>
            <span className={styles.value}>{receivedData[4]}</span>
          </div>
           <div className={styles.detailItem}>
            <strong className={styles.label}>Bet Amount :</strong>
            <span className={`${styles.value} ${styles.highlightValue}`}>{receivedData[3]}</span>
          </div>
          <div className={styles.detailItem}>
            <strong className={styles.label}>Play Date :</strong>
            <span className={styles.value}>{receivedData[1]}</span>
          </div>
        </div>

        <div className={styles.numbersSection}>
           <h3 className={styles.numbersLabel}>Played Numbers</h3>
          <div className={styles.numbersBox}>
            {receivedData[2].map((item, index) => (
              <span key={index} className={styles.numberItem}>
                <span className={styles.numberValue}>{item.bet_numbers}</span>
                <span className={styles.numberSeparator}>=</span>
                <span className={styles.numberAmount}>{item.bet_money}rs</span>
              </span>
            ))}
          </div>
        </div>

        <div className={styles.footer}>
          <Link className={styles.homeButton} to="/playground">
            <FaHome />
            <span>Home</span>
          </Link>
        </div>
         {/* End of receiptCard */}
      </div>
    </div>
  );
}

export default Receipt;