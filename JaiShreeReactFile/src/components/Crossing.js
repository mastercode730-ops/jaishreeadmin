import { React, useState, useContext, useEffect } from "react";
import "react-toastify/dist/ReactToastify.css";
import { useNavigate, Link, useParams } from "react-router-dom";
import { toast } from "react-toastify";
import { AuthContext } from "../context/AuthContext"; // Adjust path
import { useSwipeable } from 'react-swipeable';
import GamePageLayout from "./GamePageLayout"; // Adjust path
import styles from '../assets/css/Crossing.module.css'; // Import CSS Module
import { MdDelete } from "react-icons/md"; // Delete Icon

function Crossing() {
  const { id } = useParams(); // Game ID
  const [values, setValues] = useState([]); // Array to hold generated crossing pairs
  const [DigitInput, setDigitInput] = useState(""); // Input for digits
  const [AmountInput, setAmountInput] = useState(""); // Input for amount per bet
  // const [Repetition] = useState(0); // Repetition state seems unused in generateArray logic shown
  const { isAuthenticated, Addbet, Gameinfo } = useContext(AuthContext);
  const [isLoading, setIsLoading] = useState(false); // Loading state for submit
  const navigate = useNavigate();
  // const [new_message, setnew_message] = useState(""); // Replaced by toast
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
     }
      return () => { isMounted = false; };
  }, [id, Gameinfo]);

  // Prevent back navigation (optional)
  useEffect(() => {
    window.history.pushState(null, "", window.location.href);
    const handlePopState = (event) => {
        navigate("/dashboard", { replace: true }); // Navigate home/dashboard
    };
    window.addEventListener("popstate", handlePopState);
    return () => window.removeEventListener("popstate", handlePopState);
  }, [navigate]);

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

  // Calculate total bet amount
  const calculateSum = () => {
    const amount = parseFloat(AmountInput);
    return values.length * (isNaN(amount) ? 0 : amount);
  };

  // Generate Crossing Pairs
  const generateCrossingArray = (digitInput) => {
    const digitString = String(digitInput).replace(/[^0-9]/g, ''); // Ensure string and numeric only
    const uniqueDigits = [...new Set(digitString)].join(''); // Remove duplicates

    // Validate input length (minimum 2, maximum 6 as per original logic)
    if (uniqueDigits.length < 2) {
      return [0, [], "Please enter at least 2 unique digits."];
    }
    if (uniqueDigits.length > 6) {
       return [0, [], "Maximum 6 unique digits allowed for crossing."];
    }

    const resultArray = [];
    for (let i = 0; i < uniqueDigits.length; i++) {
      for (let j = i; j < uniqueDigits.length; j++) { // Start j from i to avoid duplicates like 12 and 21 initially
        const digit1 = uniqueDigits[i];
        const digit2 = uniqueDigits[j];

        // Add the pair itself
        resultArray.push(digit1 + digit2);

        // Add the reversed pair only if digits are different
        if (i !== j) {
           resultArray.push(digit2 + digit1);
        }
      }
    }
    // Convert string pairs to numbers
    const numericArray = resultArray.map(pair => parseInt(pair, 10));
    return [1, numericArray, "success"];
  };

  // Handle Add Bet button click
  const handleAddBet = () => {
    if (DigitInput === "" || DigitInput.length < 2) {
      message(0, "Please enter at least 2 unique digits.");
      return;
    }
    if (AmountInput === "" || parseFloat(AmountInput) <= 0) {
      message(0, "Please enter a valid amount.");
      return;
    }

    const [status, generatedValues, msg] = generateCrossingArray(DigitInput);
    if (status === 0) {
      message(0, msg);
      setValues([]); // Clear previous values on error
    } else {
      setValues(generatedValues);
      // Optional: Clear inputs after adding
      // setDigitInput("");
      // setAmountInput("");
    }
  };

  // Handle deleting a specific bet pair
  const handleDelete = (indexToDelete) => {
    setValues(currentValues => currentValues.filter((_, index) => index !== indexToDelete));
  };

  // Render the table rows for generated bets
  const renderRows = () => {
    return values.map((betNumber, index) => (
      <tr key={index} className={styles.tableRow}>
        <td className={styles.tableCell}>{String(betNumber).padStart(2, '0')}</td>
        <td className={styles.tableCell}>{AmountInput}</td>
        <td className={styles.tableCell}>
          <button onClick={() => handleDelete(index)} className={styles.deleteButton}>
            <MdDelete />
          </button>
        </td>
      </tr>
    ));
  };

  // Handle final bet submission
  const handleBettingSubmit = () => {
    if (isLoading) return; // Prevent double submission
    if (values.length === 0) {
      message(0, "No bets added to submit.");
      return;
    }
    setTimeout(() => {
    
    const numericAmount = parseFloat(AmountInput);
    if (isNaN(numericAmount) || numericAmount <= 0) {
        message(0, "Invalid amount entered.");
        return;
    }

    setIsLoading(true);

    const transformedValues = values.map((value) => ({
      bet_numbers: String(value).padStart(2, '0'), // Ensure 2 digits
      bet_money: numericAmount, // Use the single amount input
      type: 1, // Assuming crossing generates Jodi (type 1)
    }));

    let grandTotal = calculateSum();

    if (Addbet) {
        Addbet(id, 1, JSON.stringify(transformedValues), grandTotal, (res) => {
            setIsLoading(false);
            if (res.status === 0) {
                message(0, res.msg || "Failed to place bet.");
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
                    transformedValues, // Send structured bet data
                    grandTotal,
                    'Crossing' // Type for receipt
                ];
                // Clear inputs and navigate
                setValues([]);
                setDigitInput("");
                setAmountInput("");
                setTimeout(() => {
                    navigate('/receipt', { state: { receiptData } });
                }, 500);
            }
        });
    } else {
        setIsLoading(false);
        message(0, "Betting function not available.");
    }
  }, 1000);
  };

  // Swipe handlers
  const handlers = useSwipeable({
    onSwipedLeft: () => navigate(`/copypaste/${id}`),
    onSwipedRight: () => navigate(`/play/${id}`),
    // preventDefaultTouchmoveEvent: true,
    // trackMouse: true,
  });

  return (
    <>
      {/* Assuming GamePageLayout handles Preloader */}
      <GamePageLayout handlers={handlers} id={id} GameName={GameName} new_message={null}>
        {/* Game Type Tabs */}
        <div className={styles.gameList}>
          <Link to={`/play/${id}`} className={styles.gameLink}>
            JODI/HARUF
          </Link>
          <Link to={`/crossing/${id}`} className={`${styles.gameLink} ${styles.activeGameLink}`}>
            CROSSING
          </Link>
          <Link to={`/copypaste/${id}`} className={styles.gameLink}>
            COPY PASTE
          </Link>
        </div>

        {/* Input Area */}
        <div className={styles.inputArea}>
           <div className={styles.inputGroup}>
               <label htmlFor="digitInput" className={styles.inputLabel}>Enter Digits (2-6 unique digits)</label>
               <input
                   id="digitInput"
                   type="tel"
                   inputMode="numeric"
                   maxLength={6} // Limit input length
                   className={styles.formInput}
                   value={DigitInput}
                   onInput={(e) => setDigitInput(e.target.value.replace(/[^0-9]/g, ''))}
                   placeholder="e.g., 1234"
               />
           </div>
           <div className={styles.inputGroup}>
               <label htmlFor="amountInput" className={styles.inputLabel}>Enter Amount (Per Bet)</label>
               <input
                   id="amountInput"
                   type="number"
                   inputMode="decimal"
                   className={styles.formInput}
                   value={AmountInput}
                   onInput={(e) => setAmountInput(e.target.value.replace(/[^0-9]/g, ''))}
                   placeholder="e.g., 10"
               />
           </div>
           <div className={styles.buttonContainer}>
               <button onClick={handleAddBet} className={styles.addButton}>
                   Add Bet
               </button>
           </div>
        </div>

        {/* Bet Preview Table */}
        {values.length > 0 && (
          <div className={styles.previewArea}>
            <h3 className={styles.previewTitle}>Preview Bets ({values.length} Pairs)</h3>
            <div className={styles.tableContainer}>
                <table className={styles.previewTable}>
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>{renderRows()}</tbody>
                </table>
            </div>
          </div>
        )}

        {/* Summary and Play Button */}
        {values.length > 0 && (
            <div className={styles.summaryContainer}>
              <span className={styles.totalAmount}>
                Total: ₹{calculateSum()}
              </span>
              <button
                className={styles.playButton}
                onClick={handleBettingSubmit}
                disabled={isLoading}
              >
                {isLoading ? 'Processing...' : 'PLAY'}
              </button>
            </div>
        )}
      </GamePageLayout>
    </>
  );
}

export default Crossing;
