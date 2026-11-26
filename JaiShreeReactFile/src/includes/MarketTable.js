import React from 'react';
import { MdOutlineDelete } from "react-icons/md";
import styles from '../assets/css/Transaction.module.css'; // Import the SAME CSS module

// Function to format game type (adjust based on your actual data values)
const formatGameType = (type) => {
    switch (String(type)) { // Ensure comparison is string-based
        case "1": return "JODI";
        case "2": return "ANDER H"; // Assuming H is for Haruf
        case "3": return "BAHAR H"; // Assuming H is for Haruf
        // Add other types as needed
        default: return "UNKNOWN";
    }
};

// Function to format date (basic example)
const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    try {
        const date = new Date(dateString);
        // Example format: DD/MM/YYYY HH:MM
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${day}/${month}/${year} ${hours}:${minutes}`;
    } catch (error) {
        console.error("Error formatting date:", error);
        return dateString; // Return original string on error
    }
};

const MarketTable = ({ data, purpose, Oncancelfun }) => {
  // Destructure data with defaults
  const {
    game_name = "N/A",
    type = "",
    game_number = "N/A",
    created_at = "",
    money = 0,
    isResult = "0", // Default to '0' (not resulted)
    id = null // Bet ID for cancellation
  } = data || {}; // Handle case where data might be null/undefined

  const canCancel = purpose === "market" && String(isResult) === "0" && id !== null;

  return (
    // Use the card style defined in Transaction.module.css
    <div className={styles.historyCard}>
        <div className={styles.cardRow}>
            <span className={styles.gameDetails}>
                {game_name} - {formatGameType(type)} - <span className={styles.gameNumber}>{game_number}</span>
            </span>
            <span className={`${styles.amount} ${styles.debit}`}>
                -₹{money}
            </span>
        </div>
        <div className={styles.cardRow}>
            <span className={styles.dateTime}>{formatDate(created_at)}</span>
            {canCancel ? (
                <button
                    className={styles.cancelButton}
                    onClick={() => Oncancelfun(id)} // Pass the bet ID
                    aria-label="Cancel Bet"
                >
                    <MdOutlineDelete /> Cancel
                </button>
            ) : (
                // Optional: Placeholder or status if needed when not cancellable
                <span className={styles.statusPlaceholder}></span>
            )}
        </div>
    </div>
  );
};

export default MarketTable;