import { useState, useContext, useEffect } from "react";
import { motion } from "framer-motion";
import Footer from "../../includes/footer-tab"; // Adjust path
import Preloader from "../Preloader"; // Adjust path
import BackButton from "../../includes/Back_btn"; // Adjust path
import Table from "../../includes/Table"; // Assuming this is styled separately or not used for market/marketcancel
import MarketTable from "../../includes/MarketTable"; // Adjust path
import { useParams, useNavigate } from "react-router-dom";
import { IoIosArrowBack, IoIosArrowForward } from "react-icons/io";
import { AuthContext } from "../../context/AuthContext"; // Adjust path
import { ToastContainer, toast } from "react-toastify"; // Added Toast
import 'react-toastify/dist/ReactToastify.css';
import styles from '../../assets/css/Transaction.module.css'; // Import CSS Module

function Transaction() {
  const { id } = useParams(); // History type (e.g., 'market', 'recharge', 'withdrawal')
  const [preloader, setPreloader] = useState(true);
  const [data, setData] = useState([]); // Changed state variable name
  const [pagenumber, setPagenumber] = useState(1);
  // const [new_message, setnew_message] = useState(""); // Replaced with toast
  const { isAuthenticated, Transactiondata, CancelBet } = useContext(AuthContext);
  const navigate = useNavigate();

  // Redirect if not authenticated
  useEffect(() => {
    if (!isAuthenticated) {
      navigate("/login");
    }
  }, [isAuthenticated, navigate]);

  // Function to capitalize the first letter
  function capitalize(str) {
    if (typeof str !== "string" || str.length === 0) return "History"; // Default title
    // Handle specific cases like 'marketcancel'
    if (str === 'marketcancel') return 'Market Cancel';
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  // Toast message function
  const message = (status, text) => {
    const options = {
        position: "top-right", autoClose: 3000, hideProgressBar: false,
        closeOnClick: true, pauseOnHover: true, draggable: true,
        progress: undefined, theme: "dark", // Use dark theme
    };
    if (status === 1) toast.success(text, options);
    else if (status === 0) toast.error(text, options);
    else if (status === 2) toast.warning(text, options);
  };


  // Fetch data function
  const fetchData = (page) => {
    setPreloader(true);
    if (Transactiondata) {
      Transactiondata(id, page, (res) => {
        setPreloader(false);
        if (res.status === 0) {
          message(0, res.msg || `No more ${capitalize(id)} history found.`);
          // If page > 1 and no data, likely went past the last page
          if (page > 1) {
            setPagenumber(page - 1); // Go back to the previous page number
          }
           setData([]); // Clear data if none found for the current page
        } else if (res.data && res.data.length > 0) {
          setData(res.data);
          setPagenumber(page); // Update page number only if data is found
        } else {
           message(2, `No ${capitalize(id)} history found for this page.`);
           if (page > 1) {
             setPagenumber(page - 1); // Go back
           }
           setData([]); // Clear data
        }
      });
    } else {
        setPreloader(false);
        message(0, "Transaction data function not available.");
    }
  };

  // Initial data fetch
  useEffect(() => {
    fetchData(1); // Fetch page 1 initially
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [id]); // Refetch if the history type 'id' changes

  // Pagination handlers
  const handleBack = () => {
    if (pagenumber > 1) {
      fetchData(pagenumber - 1);
    } else {
      message(2, "You are already on the first page.");
    }
  };

  const handleNext = () => {
    // We fetch optimistically, the API response will handle if it's the last page
    fetchData(pagenumber + 1);
  };

  // Cancel Bet handler
  const handleDeleteBet = (gameId) => {
    if (!gameId) {
        message(0, "Invalid bet ID.");
        return;
    }
    setPreloader(true);
    if (CancelBet) {
        CancelBet(gameId, (res) => {
            setPreloader(false);
            if (res.status === 1) {
                message(1, res.msg || "Bet cancelled successfully!");
                // Refetch current page data after cancellation
                fetchData(pagenumber);
            } else {
                message(0, res.msg || "Failed to cancel bet.");
            }
        });
    } else {
         setPreloader(false);
         message(0, "Cancel bet function not available.");
    }
  };

  // Framer Motion variants
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { staggerChildren: 0.05 } },
  };
  const itemVariants = {
    hidden: { y: 10, opacity: 0 },
    visible: { y: 0, opacity: 1, transition: { type: 'tween', duration: 0.3 } },
  };

  // Determine which table component to use
  const isMarketHistory = id === "market" || id === "marketcancel";

  return (
    <>
      <Preloader status={preloader} />
      <ToastContainer /* Props remain same */ />

      {/* Use the Guruji theme container */}
      <div className={styles.transactionContainer}>
        {!preloader && (
            <motion.div
                variants={containerVariants}
                initial="hidden"
                animate="visible"
                exit={{ opacity: 0 }}
            >
                {/* --- Header --- */}
                <motion.div className={styles.transactionHeader} variants={itemVariants}>
                    <BackButton className={styles.backButton} />
                    <p className={styles.headerTitle}>{capitalize(id)} History</p>
                    {/* Optional: Placeholder for right-side icon/link if needed */}
                    <div className={styles.headerPlaceholder}></div>
                </motion.div>

                {/* --- History List --- */}
                <motion.div className={styles.historyList} variants={itemVariants}>
                    {data.length > 0 ? (
                        isMarketHistory ? (
                            data.map((element) => (
                                <MarketTable
                                    key={element.id || element.game_id} // Use a unique key
                                    data={element}
                                    purpose={id}
                                    Oncancelfun={handleDeleteBet}
                                />
                            ))
                        ) : (
                            // Render generic Table component for other history types
                            // Ensure Table component is also styled appropriately
                            data.map((element) => (
                                <Table
                                    key={element.id} // Assuming a unique id exists
                                    data={element}
                                    purpose={id}
                                />
                            ))
                        )
                    ) : (
                        // Show message when no data is available for the current page/filter
                        !preloader && <p className={styles.noDataMessage}>No {capitalize(id)} history found.</p>
                    )}
                </motion.div>

                {/* --- Pagination --- */}
                {/* Only show pagination if there might be more data or not on page 1 */}
                {(data.length > 0 || pagenumber > 1) && (
                    <motion.div className={styles.pagination} variants={itemVariants}>
                        <ul className={styles.pageBox}>
                            <li className={styles.pageInfo}>
                                Page {pagenumber}
                                {/* Optional: Display item range if API provides total count */}
                                {/* <span>1-10</span> of {totalItems} */}
                            </li>
                            <li className={styles.pageBtnContainer}>
                                <button onClick={handleBack} disabled={pagenumber <= 1} className={styles.pageButton}>
                                    <IoIosArrowBack />
                                </button>
                                <button onClick={handleNext} className={styles.pageButton}>
                                    <IoIosArrowForward />
                                </button>
                            </li>
                        </ul>
                    </motion.div>
                )}
            </motion.div>
        )}
      </div>
      {/* --- Footer --- */}
      <Footer /> {/* Ensure Footer component uses the correct CSS */}
    </>
  );
}

export default Transaction;
