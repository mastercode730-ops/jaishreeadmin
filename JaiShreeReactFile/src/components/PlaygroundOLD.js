import { React, useState, useContext, useEffect } from "react";
import "react-toastify/dist/ReactToastify.css";
import { useNavigate, Link, useParams } from "react-router-dom";
import { toast } from "react-toastify";
import { AuthContext } from "../context/AuthContext";
import { useSwipeable } from "react-swipeable";
import GamePageLayout from "./GamePageLayout";

function Playground() {
  const { id } = useParams();
  const [values, setValues] = useState(Array(100).fill(""));
  const [Harufvalues, HarufsetValues] = useState(Array(10).fill(""));
  const [Baharvalues, BaharsetValues] = useState(Array(10).fill(""));
  const { isAuthenticated, Addbet, Gameinfo } = useContext(AuthContext);
  const [new_message, setnew_message] = useState("");
  const navigate = useNavigate();
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState(null); // Optional: for error handling
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
    return alert(text);
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
  //Generate Rows of jodi matka
  const rows = [];
  const anderHarufrows = [];
  const baharHarufrows = [];

  // Generate rows up to 100
  for (let i = 0; i < 10; i++) {
    const headers = [];
    const headersHaruf = [];
    const inputs = [];
    const Anderinputs = [];
    const Baharinputs = [];
    for (let j = 1; j <= 10; j++) {
      const number = i * 10 + j;
      const index = i * 10 + j - 1;
      headers.push(
        <th key={`th-${number}`}>{number < 10 ? `0${number}` : number}</th>
      );
      inputs.push(
        <td key={`td-${number}`}>
          <input
            type="number"
            min="1"
            step="1"
            value={values[index]}
            onChange={(e) => handleInputChange(index, e.target.value, "jodi")}
          />
        </td>
      );
      if (i === 0) {
        headersHaruf.push(<th key={`th-${number - 1}`}>{number - 1}</th>);
        Anderinputs.push(
          <td key={`td-${number}`}>
            <input
              type="number"
              min="1"
              step="1"
              value={Harufvalues[index]}
              onChange={(e) =>
                handleInputChange(index, e.target.value, "ander")
              }
            />
          </td>
        );
        Baharinputs.push(
          <td key={`td-${number}`}>
            <input
              type="number"
              min="1"
              step="1"
              value={Baharvalues[index]}
              onChange={(e) =>
                handleInputChange(index, e.target.value, "bahar")
              }
            />
          </td>
        );
      }
    }
    if (i === 0) {
      anderHarufrows.push(
        <>
          <tr>{headersHaruf}</tr>
          <tr>{Anderinputs}</tr>
        </>
      );
      baharHarufrows.push(
        <>
          <tr>{headersHaruf}</tr>
          <tr>{Baharinputs}</tr>
        </>
      );
    }
    rows.push(
      <>
        <tr>{headers}</tr>
        <tr>{inputs}</tr>
      </>
    );
  }
  // Handle input change
  const handleInputChange = (index, newValue, purpose) => {
    const updatedValues = [...values];
    const AnderupdatedValues = [...Harufvalues];
    const BaharupdatedValues = [...Baharvalues];
    if (purpose === "jodi") {
      updatedValues[index] = newValue ? parseInt(newValue, 10) : ""; // Ensure values are integers or empty
      setValues(updatedValues);
    }

    if (purpose === "ander") {
      AnderupdatedValues[index] = newValue ? parseInt(newValue, 10) : ""; // Ensure values are integers or empty
      HarufsetValues(AnderupdatedValues);
    }

    if (purpose === "bahar") {
      BaharupdatedValues[index] = newValue ? parseInt(newValue, 10) : ""; // Ensure values are integers or empty
      BaharsetValues(BaharupdatedValues);
    }
  };

  // Calculate the sum of all values
  const calculateSum = () => {
    let Sum = values.reduce((sum, value) => sum + (value || 0), 0); // Add non-empty values
    Sum += Harufvalues.reduce((sum, value) => sum + (value || 0), 0); // Add non-empty values
    Sum += Baharvalues.reduce((sum, value) => sum + (value || 0), 0); // Add non-empty values

    return Sum;
  };
  const calculateSu = (purpose) => {
    if(purpose == 'all'){
      let d = values.reduce((sum, value) => sum + (value || 0), 0);
      d += Harufvalues.reduce((sum, value) => sum + (value || 0), 0);
      d += Baharvalues.reduce((sum, value) => sum + (value || 0), 0);
      return d;
    }
    if (purpose === 1) {
      return values.reduce((sum, value) => sum + (value || 0), 0);
    }
    if (purpose === 2) {
      return Harufvalues.reduce((sum, value) => sum + (value || 0), 0);
    }
    if (purpose === 3) {
      return Baharvalues.reduce((sum, value) => sum + (value || 0), 0);
    }
  };
//   const handleBetting = () => {
//     if (values.length === 0) {
//       message(0, "Empty Response");
//       return;
//     }
//     let AllGameCount = 0;

//     const transformedValuesJodi = values
//   .map((value, index) => ({
//     bet_numbers: index + 1,
//     bet_money: value,
//     type: 1, // Assuming 'values' corresponds to type 1
//   }))
//   .filter((entry) => entry.bet_money !== "");

// const transformedValuesAnderHaruf = Harufvalues.map((value, index) => ({
//   bet_numbers: index + 1,
//   bet_money: value,
//   type: 2, // Assuming 'Harufvalues' corresponds to type 2
// })).filter((entry) => entry.bet_money !== "");

// const transformedValuesBaharHaruf = Baharvalues.map((value, index) => ({
//   bet_numbers: index + 1,
//   bet_money: value,
//   type: 3, // Assuming 'Baharvalues' corresponds to type 3
// })).filter((entry) => entry.bet_money !== "");

// // Merge all three arrays into a single array
// const mergedArray = [
//   ...transformedValuesJodi,
//   ...transformedValuesAnderHaruf,
//   ...transformedValuesBaharHaruf,
// ];

// // console.log(mergedArray);
//     if (transformedValuesJodi.length > 0) {
//       AllGameCount++;
//     }
//     if (transformedValuesAnderHaruf.length > 0) {
//       AllGameCount++;
//     }
//     if (transformedValuesBaharHaruf.length > 0) {
//       AllGameCount++;
//     }

//     let grandTotal1 = calculateSum('all');

//     Addbet(
//       id,
//       1,
//       JSON.stringify(mergedArray),
//       ((grandTotal1)),
//       (res) => {
//         if (res.status === 0) {
//           message(0, res.msg);
//           return;
//         }
//         // message(1, res.msg);
//         setnew_message(res.msg);
//         setTimeout(() => {
//           navigate("/playground");
//         }, 1000);
//         BaharsetValues([]);
//         setValues([]);
//         HarufsetValues([]);
//       }
//     );
//   };
  

  // Your function to call the API
  // const placeBetAPI = async (betData) => {
  //   // Simulate API call delay
  //   await new Promise(resolve => setTimeout(resolve, 1500));
  //   // --- Replace with your actual fetch/axios call ---
  //   console.log("Sending bet data to server:", betData);
  // };

  const handleBetting = async () => {
    const today = new Date();
  const day = String(today.getDate()).padStart(2, '0');
  const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
  const year = today.getFullYear();
  const formattedDate = `${day}-${month}-${year}`;
    // 1. Check if already loading
    if (isLoading) {
      // console.log("Request already in progress, skipping.");
      return; // Do nothing if already submitting
    }
    
    // 2. Set loading state to true
    setIsLoading(true);
    setTimeout(() => {
    setError(null); // Reset error on new attempt
    console.log("Setting loading to true, making request...");

    try {
      
    if (values.length === 0) {
      message(0, "Empty Response");
      return;
    }
    let AllGameCount = 0;

    const transformedValuesJodi = values
  .map((value, index) => ({
    bet_numbers: index + 1,
    bet_money: value,
    type: 1, // Assuming 'values' corresponds to type 1
  }))
  .filter((entry) => entry.bet_money !== "");

const transformedValuesAnderHaruf = Harufvalues.map((value, index) => ({
  bet_numbers: index + 1,
  bet_money: value,
  type: 2, // Assuming 'Harufvalues' corresponds to type 2
})).filter((entry) => entry.bet_money !== "");

const transformedValuesBaharHaruf = Baharvalues.map((value, index) => ({
  bet_numbers: index + 1,
  bet_money: value,
  type: 3, // Assuming 'Baharvalues' corresponds to type 3
})).filter((entry) => entry.bet_money !== "");

// Merge all three arrays into a single array
const mergedArray = [
  ...transformedValuesJodi,
  ...transformedValuesAnderHaruf,
  ...transformedValuesBaharHaruf,
];

// console.log(mergedArray);
    if (transformedValuesJodi.length > 0) {
      AllGameCount++;
    }
    if (transformedValuesAnderHaruf.length > 0) {
      AllGameCount++;
    }
    if (transformedValuesBaharHaruf.length > 0) {
      AllGameCount++;
    }

    let grandTotal1 = calculateSum('all');

    Addbet(
      id,
      1,
      JSON.stringify(mergedArray),
      ((grandTotal1)),
      (res) => {
        if (res.status === 0) {
          message(0, res.msg);
          return;
        }
        // message(1, res.msg);
        setnew_message(res.msg);
        setTimeout(() => {
          navigate('/receipt', {
            state: {
              // You can name the property anything, e.g., 'receiptDetails'
              receiptData: [
                GameName,
                formattedDate,
                (mergedArray),
                (grandTotal1),'Jodi']
            }
          });
        }, 1000);
        BaharsetValues([]);
        setValues([]);
        HarufsetValues([]);
      }
    );

    } catch (err) {
      // Handle errors (e.g., show error message)
      console.error("Request failed:", err);
      setError(err.message || 'An error occurred.');

    } finally {
      // 5. CRUCIAL: Set loading state back to false in finally block
      // This ensures it resets even if there's an error
      setIsLoading(false);
      console.log("Setting loading back to false.");
    }
  }, 1000);
  };
  const handlers = useSwipeable({
    onSwipedLeft: () => navigate("/crossing/" + id),
  });
  return (
    <>
      <GamePageLayout handlers={handlers} id={id} new_message={new_message}>
        <div className="game-list">
          <Link to={"/play/" + id} className="games active">
            JODI
          </Link>
          <Link to={"/crossing/" + id} className="games">
            CROSSING
          </Link>
          <Link to={"/copypaste/" + id} className="games">
            COPY PASTE
          </Link>
        </div>
        {/* bet form design */}
        <table className="JODITable">
          <tbody>{rows}</tbody>
        </table>
        <p className="HarufHeading">Ander Haruf</p>
        <table className="JODITable">
          <tbody>{anderHarufrows}</tbody>
        </table>
        <p className="HarufHeading">Bahar Haruf</p>
        <table className="JODITable">
          <tbody>{baharHarufrows}</tbody>
        </table>
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

export default Playground;
