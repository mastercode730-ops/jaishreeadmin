import { React, useState, useContext, useEffect } from "react";
import "react-toastify/dist/ReactToastify.css";
import { useNavigate, Link, useParams } from "react-router-dom";
import { toast } from "react-toastify"; // Use toast instead of alert
import { AuthContext } from "../context/AuthContext"; // Adjust path
import { useSwipeable } from "react-swipeable";
import GamePageLayout from "./GamePageLayout"; // Adjust path
import styles from '../assets/css/Playground.module.css'; // Import CSS Module


function Playground() {
  const { id } = useParams(); // Game ID
  const [values, setValues] = useState(Array(100).fill("")); // Jodi bets
  const [Harufvalues, HarufsetValues] = useState(Array(10).fill("")); // Ander Haruf bets
  const [Baharvalues, BaharsetValues] = useState(Array(10).fill("")); // Bahar Haruf bets
  const { isAuthenticated, Addbet, Gameinfo } = useContext(AuthContext);
  // const [new_message, setnew_message] = useState(""); // Replaced by toast
  const navigate = useNavigate();
  const [isLoading, setIsLoading] = useState(false); // Loading state for submit button
  const [error, setError] = useState(null); // Error state (optional)
  const [GameName, setGameName] = useState("Loading..."); // Game Name state

  // Redirect if not authenticated
  useEffect(() => {
    if (!isAuthenticated) {
      navigate("/login");
    }
  }, [isAuthenticated, navigate]);

  // Fetch Game Info
  useEffect(() => {
    let isMounted = true;
    if (Gameinfo && id) {
        Gameinfo(id, (res) => {
            if (!isMounted) return;
            if (res.status === 0) {
                message(0, res.msg || "Failed to load game info");
                setGameName("Unknown Game");
            } else if (res.data && res.data.length > 0) {
                setGameName(res.data[0].game_name || "Unknown Game");
            } else {
                 setGameName("Unknown Game");
            }
        });
    } else {
        setGameName("Unknown Game");
        console.error("Gameinfo function or game ID missing.");
    }
     return () => { isMounted = false; };
  }, [id, Gameinfo]);

  // Prevent back navigation (optional, kept from original)
  useEffect(() => {
    window.history.pushState(null, "", window.location.href);
    const handlePopState = (event) => {
        // Prevent default back behavior if needed, or navigate specifically
        // window.history.pushState(null, "", window.location.href); // Force stay
        navigate("/dashboard", { replace: true }); // Or navigate home/dashboard
    };
    window.addEventListener("popstate", handlePopState);
    return () => window.removeEventListener("popstate", handlePopState);
  }, [navigate]);

  // Toast message function
  const message = (status, text) => {
    // return alert(text); // Replaced alert with toast
    const options = {
        position: "top-right", autoClose: 3000, hideProgressBar: false,
        closeOnClick: true, pauseOnHover: true, draggable: true,
        progress: undefined, theme: "dark",
    };
    if (status === 1) toast.success(text, options);
    else if (status === 0) toast.error(text, options);
    else if (status === 2) toast.warning(text, options);
  };

  // --- Input Handling ---
  const handleInputChange = (index, newValue, purpose) => {
    // Ensure only numbers are entered and handle empty string
    const numericValue = newValue.replace(/[^0-9]/g, '');
    const finalValue = numericValue === "" ? "" : parseInt(numericValue, 10);

    if (purpose === "jodi") {
      const updatedValues = [...values];
      updatedValues[index] = finalValue;
      setValues(updatedValues);
    } else if (purpose === "ander") {
      const AnderupdatedValues = [...Harufvalues];
      AnderupdatedValues[index] = finalValue;
      HarufsetValues(AnderupdatedValues);
    } else if (purpose === "bahar") {
      const BaharupdatedValues = [...Baharvalues];
      BaharupdatedValues[index] = finalValue;
      BaharsetValues(BaharupdatedValues);
    }
  };

  // --- Calculations ---
  const calculateSum = (purpose = 'all') => {
    let sum = 0;
    if (purpose === 'all' || purpose === 1) { // Jodi
        sum += values.reduce((acc, value) => acc + (value || 0), 0);
    }
    if (purpose === 'all' || purpose === 2) { // Ander
        sum += Harufvalues.reduce((acc, value) => acc + (value || 0), 0);
    }
    if (purpose === 'all' || purpose === 3) { // Bahar
        sum += Baharvalues.reduce((acc, value) => acc + (value || 0), 0);
    }
    return sum;
  };

  // --- Bet Submission ---
  const handleBetting = async () => {
    if (isLoading) return; // Prevent double submission
    setTimeout(() => {
      
    
    const transformedValuesJodi = values
      .map((value, index) => value ? { bet_numbers: String(index).padStart(2, '0'), bet_money: value, type: 1 } : null)
      .filter(entry => entry !== null);

    const transformedValuesAnderHaruf = Harufvalues
      .map((value, index) => value ? { bet_numbers: String(index), bet_money: value, type: 2 } : null)
      .filter(entry => entry !== null);

    const transformedValuesBaharHaruf = Baharvalues
      .map((value, index) => value ? { bet_numbers: String(index), bet_money: value, type: 3 } : null)
      .filter(entry => entry !== null);

    const mergedArray = [
      ...transformedValuesJodi,
      ...transformedValuesAnderHaruf,
      ...transformedValuesBaharHaruf,
    ];

    if (mergedArray.length === 0) {
      message(0, "Please place at least one bet.");
      return;
    }

    let grandTotal = calculateSum('all');
    if (grandTotal <= 0) {
        message(0, "Total bet amount must be greater than zero.");
        return;
    }

    setIsLoading(true);
    setError(null);
    // console.log("Submitting Bets:", JSON.stringify(mergedArray), "Total:", grandTotal);

    // Simulate API call delay for testing loading state
    // await new Promise(resolve => setTimeout(resolve, 1500));

    if (Addbet) {
        Addbet(
            id, // game_id
            1, // Assuming session '1' - adjust if needed
            JSON.stringify(mergedArray),
            grandTotal,
            (res) => {
                setIsLoading(false);
                if (res.status === 0) {
                    message(0, res.msg || "Failed to place bet.");
                    setError(res.msg || "Failed to place bet.");
                } else {
                    message(1, res.msg || "Bet placed successfully!");
                    // Prepare receipt data
                    const today = new Date();
                    const day = String(today.getDate()).padStart(2, '0');
                    const month = String(today.getMonth() + 1).padStart(2, '0');
                    const year = today.getFullYear();
                    const formattedDate = `${day}-${month}-${year}`;
                    const receiptData = [
                        GameName,
                        formattedDate,
                        mergedArray, // Send the structured bet data
                        grandTotal,
                        'Jodi/Haruf' // Generic type for receipt
                    ];

                    // Clear inputs and navigate to receipt page
                    setValues(Array(100).fill(""));
                    HarufsetValues(Array(10).fill(""));
                    BaharsetValues(Array(10).fill(""));

                    setTimeout(() => {
                        navigate('/receipt', { state: { receiptData } });
                    }, 500); // Shorter delay after success message
                }
            }
        );
    } else {
         setIsLoading(false);
         message(0, "Betting function not available.");
         setError("Betting function not available.");
    }
  }, 1000);
  };

  // --- Swipe Handlers ---
  const handlers = useSwipeable({
    // Navigate to crossing page on left swipe
    onSwipedLeft: () => navigate(`/crossing/${id}`),
    // Prevent default scroll behavior during swipe
    // trackMouse: true, // Optional: Enable mouse swiping on desktop
    // preventDefaultTouchmoveEvent: true,
  });

  // --- Generate Table Rows ---
  const generateTableRows = (dataArray, purpose) => {
      const rows = [];
      const numRows = purpose === 'jodi' ? 10 : 1; // 10 rows for Jodi, 1 row for Haruf
      const numCols = purpose === 'jodi' ? 10 : 10; // 10 columns always

      for (let i = 0; i < numRows; i++) {
          const headers = [];
          const inputs = [];
          for (let j = 0; j < numCols; j++) {
              const number = purpose === 'jodi' ? (i * 10 + j) : j; // 0-99 for Jodi, 0-9 for Haruf
              const displayValue = purpose === 'jodi' ? String(number).padStart(2, '0') : String(number);
              const index = number; // Index matches the number

              headers.push(
                  <th key={`th-${purpose}-${number}`} className={styles.tableHeader}>
                      {displayValue}
                  </th>
              );
              inputs.push(
                  <td key={`td-${purpose}-${number}`} className={styles.tableCell}>
                      <input
                          type="number"
                          inputMode="numeric" // Use numeric keyboard on mobile
                          min="0" // Allow 0 but it won't be submitted
                          step="1"
                          className={styles.betInput}
                          value={dataArray[index] ?? ""} // Use dataArray passed in
                          onChange={(e) => handleInputChange(index, e.target.value, purpose)}
                          placeholder="0" // Placeholder
                      />
                  </td>
              );
          }
          rows.push(<tr key={`header-row-${purpose}-${i}`}>{headers}</tr>);
          rows.push(<tr key={`input-row-${purpose}-${i}`}>{inputs}</tr>);
      }
      return rows;
  };

  const jodiRows = generateTableRows(values, 'jodi');
  const anderHarufRows = generateTableRows(Harufvalues, 'ander');
  const baharHarufRows = generateTableRows(Baharvalues, 'bahar');

  return (
    <>
      {/* Assuming GamePageLayout handles Preloader and Footer */}
      <GamePageLayout handlers={handlers} id={id} GameName={GameName} new_message={null}> {/* Pass GameName, new_message not used */}
        {/* Game Type Tabs */}
        <div className={styles.gameList}>
          <Link to={`/play/${id}`} className={`${styles.gameLink} ${styles.activeGameLink}`}>
            JODI/HARUF
          </Link>
          <Link to={`/crossing/${id}`} className={styles.gameLink}>
            CROSSING
          </Link>
          <Link to={`/copypaste/${id}`} className={styles.gameLink}>
            COPY PASTE
          </Link>
        </div>

        {/* Betting Area */}
        <div className={styles.bettingArea}>
          {/* Jodi Table */}
          <h3 className={styles.tableTitle}>JODI</h3>
          <div className={styles.tableContainer}>
             <table className={styles.betTable}>
               <tbody>{jodiRows}</tbody>
             </table>
          </div>

          {/* Ander Haruf Table */}
          <h3 className={styles.tableTitle}>ANDER HARUF</h3>
           <div className={styles.tableContainer}>
             <table className={styles.betTable}>
               <tbody>{anderHarufRows}</tbody>
             </table>
          </div>


          {/* Bahar Haruf Table */}
          <h3 className={styles.tableTitle}>BAHAR HARUF</h3>
           <div className={styles.tableContainer}>
             <table className={styles.betTable}>
               <tbody>{baharHarufRows}</tbody>
             </table>
          </div>

        </div>

        {/* Summary and Play Button */}
        <div className={styles.summaryContainer}>
          <span className={styles.totalAmount}>
            Total: ₹{calculateSum('all')}
          </span>
          <button
            className={styles.playButton}
            onClick={handleBetting}
            disabled={isLoading} // Disable button when loading
          >
            {isLoading ? 'Processing...' : 'PLAY'}
          </button>
        </div>
        {error && <p className={styles.errorMessage}>{error}</p>}
      </GamePageLayout>
    </>
  );
}

export default Playground;
