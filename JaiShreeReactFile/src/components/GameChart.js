import { useState, useContext, useEffect } from "react";
import { motion } from "framer-motion";
import Footer from "../includes/footer-tab"; // Adjust path
import Preloader from "./Preloader"; // Adjust path
import BackButton from "../includes/Back_btn"; // Adjust path
import { useParams, useNavigate } from "react-router-dom";
import { IoIosArrowBack, IoIosArrowForward } from "react-icons/io";
import { AuthContext } from "../context/AuthContext"; // Adjust path
import { ToastContainer, toast } from "react-toastify"; // Added Toast
import 'react-toastify/dist/ReactToastify.css';
import styles from '../assets/css/GameChart.module.css'; // Import CSS Module

function GameChart() {
  const { id } = useParams(); // Game ID
  const [preloader, setPreloader] = useState(true);
  const [data, setData] = useState([]); // Chart data
  const [pagenumber, setPagenumber] = useState(1); // Keep for potential future use
  const [Name, setName] = useState("Loading..."); // Game Name
  // const [new_message, setnew_message] = useState(""); // Replaced with toast
  const { isAuthenticated, GameChart: fetchGameChart } = useContext(AuthContext); // Renamed context function
  const navigate = useNavigate();

  // Redirect if not authenticated
  useEffect(() => {
    if (!isAuthenticated) {
      navigate("/login");
    }
  }, [isAuthenticated, navigate]);


  // Function to capitalize the first letter
  function capitalize(str) {
    if (typeof str !== "string" || str.length === 0) return "";
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

  // Pagination handlers (currently commented out as API logic wasn't implemented)
  const handleBack = () => {
    message(2, "Pagination not yet implemented.");
    // if (pagenumber > 1) {
    //   // Fetch previous page data
    //   // setPagenumber(pagenumber - 1);
    // } else {
    //   message(2, "You are already on the first page.");
    // }
  };

  const handleNext = () => {
     message(2, "Pagination not yet implemented.");
    // Fetch next page data
    // setPagenumber(pagenumber + 1);
  };

  // Fetch Chart Data
  useEffect(() => {
    let isMounted = true;
    setPreloader(true);
    if (fetchGameChart && id) {
      fetchGameChart(id, (res) => { // Assuming page number isn't needed for chart API
        if (!isMounted) return;
        setPreloader(false);
        if (res.status === 0) {
          message(0, res.msg || `Failed to load chart for ID: ${id}`);
          setName("Error");
          setData([]);
        } else {
          setData(res.chart || []);
          setName(res.name || "Unknown Game");
        }
      });
    } else {
        setPreloader(false);
        message(0, "Chart data function not available.");
        setName("Error");
    }
     return () => { isMounted = false; }; // Cleanup
  }, [fetchGameChart, id]); // Dependency array

  // Framer Motion variants
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: { opacity: 1 },
  };
  const itemVariants = {
    hidden: { y: 20, opacity: 0 },
    visible: { y: 0, opacity: 1, transition: { type: 'tween', duration: 0.3 } },
  };

  return (
    <>
      <Preloader status={preloader} />
      <ToastContainer /* Props remain same */ />

      {/* Use the Guruji theme container */}
      <div className={styles.chartContainer}>
        {!preloader && (
            <motion.div
                variants={containerVariants}
                initial="hidden"
                animate="visible"
                exit={{ opacity: 0 }}
            >
                {/* --- Header --- */}
                <motion.div className={styles.chartHeader} variants={itemVariants}>
                    <BackButton className={styles.backButton} />
                    {/* Use text-transform: uppercase in CSS */}
                    <p className={styles.headerTitle}>{Name} Result Chart</p>
                    {/* Optional: Placeholder for right-side icon/link if needed */}
                    <div className={styles.headerPlaceholder}></div>
                </motion.div>

                {/* --- Chart Table --- */}
                <motion.div className={styles.tableWrapper} variants={itemVariants}>
                   {data.length > 0 ? (
                       <table className={styles.chartTable}>
                         <thead>
                           <tr>
                             <th>Date</th>
                             <th>Number</th>
                           </tr>
                         </thead>
                         <tbody>
                           {data.map((element, i) => (
                             <tr key={i}>
                               {/* Format date if needed */}
                               <td>{element.date}</td>
                               {/* Ensure number is padded */}
                               <td>{String(element.number).padStart(2, '0')}</td>
                             </tr>
                           ))}
                         </tbody>
                       </table>
                   ) : (
                       // Show message when no data
                       !preloader && <p className={styles.noDataMessage}>No chart data available.</p>
                   )}
                </motion.div>

                {/* --- Pagination (Keep structure, but disable buttons) --- */}
                {(data.length > 0) && ( // Only show if there's data
                    <motion.div className={styles.pagination} variants={itemVariants}>
                        <ul className={styles.pageBox}>
                            <li className={styles.pageInfo}>
                                Page {pagenumber}
                                {/* <span>1-10</span> of {pagenumber} */}
                            </li>
                            <li className={styles.pageBtnContainer}>
                                <button onClick={handleBack} disabled={true} className={styles.pageButton}>
                                    <IoIosArrowBack />
                                </button>
                                <button onClick={handleNext} disabled={true} className={styles.pageButton}>
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

export default GameChart;
