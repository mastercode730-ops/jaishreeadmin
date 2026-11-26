import { React, useState, useContext, useEffect } from "react";
import "react-toastify/dist/ReactToastify.css";
import { useNavigate, Link, useParams } from "react-router-dom";
import { toast } from "react-toastify";
import { AuthContext } from "../context/AuthContext";
import { useSwipeable } from 'react-swipeable';
import GamePageLayout from "./GamePageLayout";

function Crossing() {
  const { id } = useParams();
  const [values, setValues] = useState([]);
  const [DigitInput, setDigitInput] = useState("");
  const [AmountInput, setAmountInput] = useState("");
  const [Repetition] = useState(0);
  const { isAuthenticated, Addbet, Gameinfo } = useContext(AuthContext);
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();
  const [new_message, setnew_message] = useState("");
  if (!isAuthenticated) {
    navigate("/login");
  }
  const [GameName, setGameName] = useState("");
  useEffect(() => {
    Gameinfo(id, (res) => {
      if (res.status === 0) {
        message(0, res.msg);
        return;
      }
      let daa = res.data[0];
      setGameName(daa.game_name);
    });
  }, [id,Gameinfo]);
  useEffect(() => {
    // Push a new state into history so the back button won't go back
    window.history.pushState(null, "", window.location.href);

    // Handle the popstate event (back button)
    const handlePopState = () => {
      // Navigate to /playground when the back button is pressed
      navigate("/playground", { replace: true });
    };

    // Attach the event listener
    window.addEventListener("popstate", handlePopState);

    // Cleanup when the component unmounts
    return () => {
      window.removeEventListener("popstate", handlePopState);
    };
  }, [navigate]);
  const message = (status, text) => {
    if (status === 1) {
      toast.success(text, {
        position: "top-right",
        autoClose: 5000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
        progress: undefined,
        theme: "colored",
      });
    } else if (status === 0) {
      toast.error(text, {
        position: "top-right",
        autoClose: 5000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
        progress: undefined,
        theme: "colored",
      });
    } else if (status === 2) {
      toast.warning(text, {
        position: "top-right",
        autoClose: 5000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
        progress: undefined,
        theme: "colored",
      });
    }
  };
  // Calculate the sum of all values
  const calculateSum = () => {
    return values.length * AmountInput;
  };
  const generateArray = (digitInput) => {
    const digitString = digitInput.toString();
    const resultArray = [];
    // Ensure digit count is even
    
    if (digitString.length > 6) {
      return [0, [1, 2], "Fill Digit Based On Even(2)"];
    }
    for (let i = 0; i < digitString.length; i++) {
      for (let j = 0; j < digitString.length; j++) {
        const pair = `${digitString[i]}${digitString[j]}`;
        resultArray.push(parseInt(pair, 10)); // Add the pair to the result array
      }
    }

    return [1, resultArray, "success"];
  };
  const handleDelete = (index) => {
    setValues(values.filter((_, i) => i !== index));
  };
  const renderRows = () => {
    const rows = [];
    values.forEach((element, index) => {
      rows.push(
        <tr key={index}>
          <td>{element>9?element:'0'+element}</td>
          <td>{AmountInput}</td>
          <td>
            <button onClick={() => handleDelete(index)}>Delete</button>
          </td>
        </tr>
      );
    });
    return rows;
  };
  const handleCopyPaste = () => {
    if (DigitInput === "") {
      message(0, "Please Enter Digit ");
      return;
    } else if (AmountInput === "") {
      message(0, "Please Enter Amount ");
      return;
    }

    const ArrayOutput = generateArray(DigitInput, Repetition);
    if (ArrayOutput[0] === 0) {
      message(0, ArrayOutput[2]);
      setValues([]);
      return;
    }
    setValues(ArrayOutput[1]);
  };
  const handleBetting = () => {
    if (isLoading) {
      // console.log("Request already in progress, skipping.");
      return; // Do nothing if already submitting
    }
    
    // 2. Set loading state to true
    setIsLoading(true);
    setTimeout(() => {
    if (values.length === 0) {
      message(0, "Empty Response");
      return;
    }
    const transformedValues = values.map((value) => ({
      bet_numbers: value,
      bet_money: AmountInput,
      type: 1,
    }));
    let grandTotal = values.length * AmountInput;
    const today = new Date();
  const day = String(today.getDate()).padStart(2, '0');
  const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
  const year = today.getFullYear();
  const formattedDate = `${day}-${month}-${year}`;
    Addbet(id, 1, JSON.stringify(transformedValues), grandTotal, (res) => {
      if (res.status === 0) {
        setnew_message(res.msg);
        return;
      }
      setnew_message(res.msg);
      setTimeout(() => {
        navigate('/receipt', {
          state: {
            // You can name the property anything, e.g., 'receiptDetails'
            receiptData: [GameName, formattedDate, (transformedValues), grandTotal,'Crossing']
          }
        });
      }, 1000);
      setValues([]);
    });
  },1000);
  };
  const handlers = useSwipeable({
    onSwipedLeft: () => navigate("/copypaste/" + id),
    onSwipedRight: () => navigate("/play/" + id),
  });
  return (
    <>
      <GamePageLayout handlers={handlers} id={id} new_message={new_message}>
        <div className="game-list">
          <Link to={"/play/" + id} className="games">
            JODI
          </Link>
          <Link to={"/crossing/" + id} className="games active">
            CROSSING
          </Link>
          <Link to={"/copypaste/" + id} className="games">
            COPY PASTE
          </Link>
        </div>
        {/* bet form design */}
        <div className="CopypASTE-container">
          <label class="custom-field one">
            <input type="tel"
              onInput={(event) => {
                // Sanitize the input by removing non-numeric characters
                const sanitizedValue = event.target.value.replace(/[^0-9]/g, '');
                
                // Limit the value to 6 digits
                if (sanitizedValue.length <= 6) {
                  setDigitInput(sanitizedValue);
                }
              }}
              onPaste={(event) => {
                // Prevent the default paste behavior
                event.preventDefault();
            
                // Get the pasted content
                const pastedValue = event.clipboardData.getData('text');
            
                // Remove spaces and non-numeric characters, and limit to 6 digits
                const sanitizedPastedValue = pastedValue.replace(/\s+/g, '').replace(/[^0-9]/g, '');
                
                // Only update if the pasted value has 6 or fewer digits
                if (sanitizedPastedValue.length <= 6) {
                  setDigitInput(sanitizedPastedValue);
                }else{
                  message(0,"Only 6 digit");
                }
              }}
              placeholder="&nbsp;"
              value={DigitInput}/>
            <span class="placeholder">Enter Digit</span>
            <span class="border"></span>
          </label>
          <label class="custom-field one">
            <input type="tel"
              onInput={(event) => {
                setAmountInput((event.target.value));
              }}
              placeholder="&nbsp;"
              value={AmountInput}/>
            <span class="placeholder">Enter Amount</span>
            <span class="border"></span>
          </label>
          <div className="buttonCollection">
            <button onClick={handleCopyPaste} className="m-0">
              <span>Add Bet</span>
            </button>
          </div>
        </div>

        {values.length > 0 ? (
          <table className="JODITable">
            <tr>
              <th>NUMBER</th>
              <th>AMOUNT</th>
              <th>ACTION</th>
            </tr>
            <tbody>{renderRows()}</tbody>
          </table>
        ) : (
          ""
        )}
        <div className="SumOfAmount">
          <h3> ₹ {calculateSum()}</h3>
          {isLoading ? (
             <button disabled={true}>
             Processing...
           </button>
          ) : (
   <button onClick={handleBetting} disabled={isLoading}> {/* Disabled here is redundant */}
     PLAY
   </button>
)}
        </div>
      </GamePageLayout>
    </>
  );
}

export default Crossing;
