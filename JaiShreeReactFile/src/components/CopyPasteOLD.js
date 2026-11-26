import { React, useState, useContext, useEffect } from "react";
import "react-toastify/dist/ReactToastify.css";
import { useNavigate, Link, useParams } from "react-router-dom";
import { toast } from "react-toastify";
import { AuthContext } from "../context/AuthContext";
import { useSwipeable } from "react-swipeable";
import GamePageLayout from "./GamePageLayout";
import { FaSquareWhatsapp } from "react-icons/fa6";

function CopyPaste() {
  const { id } = useParams();
  const [values, setValues] = useState([]);
  const [DigitInput, setDigitInput] = useState("");
  const [DigitInputWhastapp, setDigitInputWhastapp] = useState("");
  const [AmountInput, setAmountInput] = useState("");
  const [Repetition, setRepetition] = useState(1);
  const [WhatsappDetail, setWhatsappDetail] = useState(false);
  const { Addbet,Gameinfo } = useContext(AuthContext);
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();
  const [new_message, setnew_message] = useState("");
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
  const calculateSum = () => {
    return values.reduce((sum, row) => {
      const num = Number(row[1]);
      return sum + (isNaN(num) ? 0 : num);
    }, 0);
  };

  const generateArray = (digitInput, repetition) => {
    let digitString = digitInput.toString();

    // Ensure digit count is even
    if (digitString.length % 2 !== 0) {
      return [0, [1, 2], "Fill Digit Based On Even(2)"];
    }

    const resultArray = [];
    // resultArray.push(parseInt(digitString[0], 10)); // First pair is the first digit

    for (let i = 0; i < digitString.length; i += 2) {
      const pair = digitString[i] + digitString[i + 1];

      if (repetition === 1) {
        resultArray.push([parseInt(pair, 10), AmountInput]); // Add the pair directly
      } else if (repetition === 0) {
        const reversedPair = digitString[i + 1] + digitString[i];
        resultArray.push(
          [parseInt(pair, 10), AmountInput],
          [parseInt(reversedPair, 10), AmountInput]
        ); // Add pair and reversed pair
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
          <td>
            {parseInt(element[0]) > 9 ? element[0] : "0" + parseInt(element[0])}
          </td>
          <td>{element[1]}</td>
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
  const [pickHaruf, setPickHaruf] = useState(1);

  const parseMessage = (msg) => {
    let mainNumberArray = [],
      mainAmountArray = [];
    let number = 0,
      lastnumber = 0,
      value = "";

    msg = msg.trim();
    msg = msg.replace(
      /\[\d{1,2}(:\d{2})?\s?(?:AM|PM|am|pm)?,?\s?\d{1,2}\/\d{1,2}\/\d{4}\]\s*[^:]*:|\[\d{1,2}\/\d{1,2}\/\d{4}\s\d{1,2}:\d{2}\s?(?:AM|PM)?\]\s*[^:]*:/gi,
      ""
    );
    msg = msg.replace(/(\w{3}\s\d{1,2}\w{2}\s\d{2}:\d{2})/gi, "");
    msg = msg.replace(/(\d{2}:\d{2}\s{4})/gi, "");

    if (!msg.match(/[kr&)x}₹\n]|(INTO)|(INTU)|(int)/gi)) msg += " k";

    if (msg.match(/[kr&)x}₹\n]|(INT)/gi)) {
      let list = msg.split(/[kr&)x}₹\n]|(INT)/gi);
      for (let i = 0; i < list.length; i++) {
        if (list[i]) {
          let str = list[i].replace(/[a-y]/gi, "").trim();
          if (str.match(/\d+/g) && str.length > 3) {
            if (pickHaruf === 1) {
              str = str.split(/[^\d+]+/);
            } else {
              str = str.split(/[^\d]+/);
            }
            for (let j = 0; j < str.length - 1; j++) {
              value += str[j];
              lastnumber = j + 1;
            }
            mainNumberArray[number] = value;
            mainAmountArray[number] = str[lastnumber];
            number++;
          }
        }
        value = "";
        lastnumber = 0;
      }
    }
    const resultArray = [];
    mainNumberArray.map((num, i) => {
      let finalAmount;
      let flag = 0;
      if (num.includes("+")) {
        let plusCount = (num.match(/\+/g) || []).length;
        finalAmount =
          num.replace(/\+/g, "").length * plusCount * mainAmountArray[i];
      } else if (num.length % 2 !== 0) {
        finalAmount = "Wrong Input";
        flag = 1;
      } else {
        if ((finalAmount) > 0) {
          finalAmount = (num.length / 2) * mainAmountArray[i];
          flag = 1;
        } else {
          finalAmount = (num.length / 2) * mainAmountArray[i];
          flag = 0;
        }
      }
      // console.log(flag,finalAmount,(finalAmount>0?"ada":"pan"));
      
      // return { number: num, amount1: mainAmountArray[i], amount: finalAmount };
      if (finalAmount > 0 || finalAmount == "Wrong Input") resultArray.push([num, finalAmount]);
    });
    return resultArray;
  };

  const handleWhatsapp = () => {
    if (DigitInputWhastapp === "") {
      message(0, "Please Enter Digit ");
      return;
    }
    const ArrayOutput = parseMessage(DigitInputWhastapp);
    // if (ArrayOutput[0] === 0) {
    //   message(0, ArrayOutput[2]);
    //   setValues([]);
    //   return;
    // }
    setWhatsappDetail(false);
    setValues(ArrayOutput);
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
      bet_numbers: value[0],
      bet_money: value[1],
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
            receiptData: [GameName, formattedDate, (transformedValues), grandTotal,'Copy Paste']
          }
        });
      }, 1000);
      setValues([]);
    });
  },1000);
  };
  const handlers = useSwipeable({
    onSwipedRight: () => navigate("/crossing/" + id),
  });
  return (
    <>
      <GamePageLayout handlers={handlers} id={id} new_message={new_message}>
        <div className="game-list">
          <Link to={"/play/" + id} className="games">
            JODI
          </Link>
          <Link to={"/crossing/" + id} className="games">
            CROSSING
          </Link>
          <Link to={"/copypaste/" + id} className="games active">
            COPY PASTE
          </Link>
        </div>
        {/* bet form design */}
        <div className="CopypASTE-container">
          <label class="custom-field one">
            <input
              type="tel"
              onInput={(event) => {
                // Remove spaces, dots, and non-numeric characters while typing
                setDigitInput(event.target.value.replace(/[^0-9]/g, ""));
              }}
              onPaste={(event) => {
                // Prevent the default paste behavior
                event.preventDefault();

                // Get the pasted content
                const pastedValue = event.clipboardData.getData("text");

                // Remove spaces from the pasted value and then set the cleaned value
                setDigitInput(
                  pastedValue.replace(/\s+/g, "").replace(/[^0-9]/g, "")
                );
              }}
              placeholder="&nbsp;"
              value={DigitInput}
            />

            <span class="placeholder">Enter Digit</span>
            <span class="border"></span>
          </label>
          {WhatsappDetail ? (
            <div className="textareadiv">
              <textarea
                value={DigitInputWhastapp}
                placeholder="Enter data"
                onChange={(e) => setDigitInputWhastapp(e.target.value)}
              ></textarea>
              <div className="buttonCollection">
                <button onClick={handleWhatsapp} className="m-0">
                  <span>Add</span>
                </button>
              </div>
              <div className="buttonCollection">
                <button
                  onClick={() => {
                    setWhatsappDetail(false);
                  }}
                  className="m-0"
                >
                  <span>Close</span>
                </button>
              </div>
            </div>
          ) : (
            ""
          )}
          <label class="custom-field one">
            <input
              type="tel"
              onInput={(event) => {
                setAmountInput(event.target.value);
              }}
              placeholder="&nbsp;"
              value={AmountInput}
            />
            <span class="placeholder">Enter Amount</span>
            <span class="border"></span>
          </label>

          <div className="input_box mt-5">
            <label htmlFor="witpalti">With Palti</label>
            <input
              type="radio"
              id="witpalti"
              name="repetition"
              placeholder="Enter Amount"
              className="width-20cent"
              onClick={(event) => {
                setRepetition(0);
              }}
              value={Repetition}
            />
            <span
              className="WhatsappIcon"
              onClick={() => {
                setWhatsappDetail(true);
              }}
            >
              <FaSquareWhatsapp />
            </span>
          </div>
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

export default CopyPaste;
