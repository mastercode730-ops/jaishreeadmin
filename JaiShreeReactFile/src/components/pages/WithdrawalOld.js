import { useState, useEffect, useContext } from "react";
import { motion } from "framer-motion";
import { ToastContainer, toast } from "react-toastify";
import Footer from "../../includes/footer-tab";
import Preloader from "../Preloader";
import BackButton from "../../includes/Back_btn";
import { AuthContext } from "../../context/AuthContext";
import { useNavigate, Link } from "react-router-dom";

function Withdrawal() {
  const navigate = useNavigate();
  const { isAuthenticated, profile, WithdrawMoney } = useContext(AuthContext);
  const [preloader, setpreloader] = useState(true);
  const [Balance, setBalance] = useState("Loading..");
  const [Password, setPassword] = useState("");
  const [StartTime, setStartTime] = useState("");
  const [EndTime, setEndTime] = useState("");
  const [currentTime, setCurrentTime] = useState("");
  const [Amount, setAmount] = useState("");
  const [BankCard, setBankCard] = useState("");
  const [Minimum, setMinimum] = useState("Loading...");
  const [AccountHolder, setAccountHolder] = useState("");
  const [Account, setAccount] = useState("");
  const [Ifsc, setIfsc] = useState("");
  const [BankName, setBankName] = useState("");
  const [WithdrawalStatus, setWithdrawalStatus] = useState(false);
  const [shake, setShake] = useState(false);
  if (!isAuthenticated) {
    navigate("/login");
  }
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
  const handleRecharge = () => {
    if (Amount === "") {
      message(0, "Please enter Amount");
    } else if (BankCard === "") {
      message(0, "Please select Payment Mode");
    } else if (Amount > Balance) {
      message(0, "Insufficient Wallet balance");
    } else if (Amount < Minimum) {
      message(0, "Please enter amount greater than " + Minimum);
    } else {
      setpreloader(true);
      WithdrawMoney(
        Amount,
        Password,
        BankCard,
        Account,
        Ifsc,
        AccountHolder,
        BankName,
        (data) => {
          setpreloader(false);
          if (data.error) {
            message(0, data.message);
          } else {
            message(1, data.message);
            setTimeout(() => {
              navigate("/mine");
            }, 1000);
          }
        }
      );
    }
  };
  const inputAmount = (event) => {
    let amount = parseInt(event.target.value);
    setAmount(amount);
  };
  useEffect(() => {
    profile((data) => {
      setpreloader(false);
      if (data.error) {
        message(0, data.message);
      } else {
        setBalance(data.data[0].wallet);
        setMinimum(data.data[0].setting.withdrawal_amount);
        setStartTime(data.data[0].setting.startwith);
        setEndTime(data.data[0].setting.endwith);
      }
    });
  }, [profile]);

  useEffect(() => {
    const updateCurrentTime = () => {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, "0");
      const minutes = String(now.getMinutes()).padStart(2, "0");
      const seconds = String(now.getSeconds()).padStart(2, "0");
      setCurrentTime(`${hours}:${minutes}:${seconds}`);
    };

    // Update current time every second
    const intervalId = setInterval(updateCurrentTime, 1000);

    // Initial update
    updateCurrentTime();

    // Cleanup the interval on component unmount
    return () => clearInterval(intervalId);
  }, []);

  const isTimeBetween = () => {
    if (!currentTime || !StartTime || !EndTime) {
      return false; // Or handle the case where times are not yet available
    }

    const [currentHour, currentMinute, currentSecond] = currentTime
      .split(":")
      .map(Number);
    const [startHour, startMinute, startSecond] =
      StartTime.split(":").map(Number);
    const [endHour, endMinute, endSecond] = EndTime.split(":").map(Number);

    const currentDate = new Date();
    const startDate = new Date(currentDate);
    startDate.setHours(startHour, startMinute, startSecond);

    const endDate = new Date(currentDate);
    endDate.setHours(endHour, endMinute, endSecond);

    const checkDate = new Date(currentDate);
    checkDate.setHours(currentHour, currentMinute, currentSecond);

    return checkDate >= startDate && checkDate <= endDate;
  };

  useEffect(() => {
    if (isTimeBetween()) {
      // Do something if the current time is between start and end time
      setWithdrawalStatus(true);
      // Example: Call a function, update state, etc.
    } else {
      setWithdrawalStatus(false);
    }
  }, [currentTime, StartTime, EndTime]);

  const messageStyle = {
    padding: '15px',
    border: '2px solid #f44336', // Red border for closed state
    backgroundColor: '#ffebee', // Light red background
    borderRadius: '8px',
    textAlign: 'center',
    fontSize: '1.2em',
    fontWeight: 'bold',
    transition: 'background-color 0.3s ease, border-color 0.3s ease',
    ...(shake ? { animation: 'shake 0.5s' } : {}), // Apply shake animation
    ...(WithdrawalStatus ? { borderColor: '#4caf50', backgroundColor: '#e8f5e9' } : {}), // Green for open
  };

  const timeSpanStyle = {
    fontWeight: 'normal',
    color: '#777',
  };

  const shakeAnimation = `
    @keyframes shake {
      0% { transform: translateX(0); }
      25% { transform: translateX(-5px) rotate(-2deg); }
      50% { transform: translateX(5px) rotate(2deg); }
      75% { transform: translateX(-5px) rotate(-2deg); }
      100% { transform: translateX(0); }
    }
  `;

  return (
    <>
      <Preloader status={preloader} />
      <ToastContainer
        position="top-right"
        autoClose={5000}
        hideProgressBar={false}
        newestOnTop={false}
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
        theme="colored"
      />
      <motion.div
        initial={{ translateX: -100 }}
        animate={{ translateX: 0 }}
        exit={{ translateX: 0 }}
        transition={{ duration: 0.2 }}
      >
        <div className="login header">
          <div className="item">
            <BackButton />
            <p>Withdrawal</p>
            <Link to="/history/withdrawal" className="historyright">
              {/* <AiOutlineHistory /> */}
            </Link>
          </div>
        </div>
        <div className="recharge recharge_box">
          <div className="bonus-tag">Balance:₹ {Balance}</div>
          {WithdrawalStatus ? (
            <>
              <div className="WithdrawalBox">
                <img
                  src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR363BO6LXUKGXXHiOTaHnqqDXgwpw4qOeOoA&s"
                  alt=""
                />
                <input
                  type="radio"
                  name="PAYMENTMETHOD"
                  value="Paytm"
                  onClick={(event) => {
                    setBankCard("Paytm");
                  }}
                />
              </div>
              <div className="WithdrawalBox">
                <img
                  style={{ width: "135px" }}
                  src="https://cdn.worldvectorlogo.com/logos/phonepe-1.svg"
                  alt=""
                />
                <input
                  type="radio"
                  name="PAYMENTMETHOD"
                  value="Phonepe"
                  onClick={(event) => {
                    setBankCard("Phonepe");
                  }}
                />
              </div>
              <div className="WithdrawalBox">
                <img
                  style={{ width: "135px" }}
                  src="https://cdn.worldvectorlogo.com/logos/google-pay-2.svg"
                  alt=""
                />
                <input
                  type="radio"
                  name="PAYMENTMETHOD"
                  value="GPay"
                  onClick={(event) => {
                    setBankCard("GPay");
                  }}
                />
              </div>
              <div className="WithdrawalBox">
                <img
                  style={{ width: "80px" }}
                  src="https://static.vecteezy.com/system/resources/thumbnails/020/716/209/small/flat-icon-bank-bank-icon-where-to-keep-money-illustration-of-saving-in-the-bank-free-png.png"
                  alt=""
                />
                <input
                  type="radio"
                  name="PAYMENTMETHOD"
                  value="Bank"
                  onClick={(event) => {
                    setBankCard("Bank");
                  }}
                />
              </div>

              {BankCard == "Bank" ? (
                <>
                  <label class="custom-field one">
                    <input
                      type="tel"
                      onInput={(event) => {
                        setAccountHolder(event.target.value);
                      }}
                      value={AccountHolder}
                      placeholder="&nbsp;"
                    />
                    <span class="placeholder">Enter Account Holder Name</span>
                    <span class="border"></span>
                  </label>
                  <label class="custom-field one">
                    <input
                      type="tel"
                      onInput={(event) => {
                        setAccount(event.target.value);
                      }}
                      value={Account}
                      placeholder="&nbsp;"
                    />
                    <span class="placeholder">Enter Account Number</span>
                    <span class="border"></span>
                  </label>
                  <label class="custom-field one">
                    <input
                      type="tel"
                      onInput={(event) => {
                        setBankName(event.target.value);
                      }}
                      value={BankName}
                      placeholder="&nbsp;"
                    />
                    <span class="placeholder">Enter Bank Name</span>
                    <span class="border"></span>
                  </label>
                  <label class="custom-field one">
                    <input
                      type="tel"
                      onInput={(event) => {
                        setIfsc(event.target.value);
                      }}
                      value={Ifsc}
                      placeholder="&nbsp;"
                    />
                    <span class="placeholder">Enter IFSC Code</span>
                    <span class="border"></span>
                  </label>
                </>
              ) : (
                <label class="custom-field one">
                  <input
                    type="tel"
                    onInput={(event) => {
                      setPassword(event.target.value);
                    }}
                    value={Password}
                    placeholder="&nbsp;"
                  />
                  <span class="placeholder">Enter Mobile Number</span>
                  <span class="border"></span>
                </label>
              )}
              <label class="custom-field one">
                <input
                  type="tel"
                  onInput={(event) => {
                    inputAmount(event);
                  }}
                  value={Amount}
                  placeholder="&nbsp;"
                />
                <span class="placeholder">Enter Amount</span>
                <span class="border"></span>
              </label>
              <p className="">
                <b>Note:</b> Minimum Withdrawal amount is ₹{Minimum}
              </p>
              <div className="input_box_btn">
                <button className="login_btn ripple" onClick={handleRecharge}>
                  Withdrawal
                </button>
              </div>
            </>
          ) : (
            <div style={messageStyle}>
              WITHDRAWAL TIME CLOSED, PLEASE TRY AGAIN BETWEEN <span style={timeSpanStyle}>{StartTime}</span> and <span style={timeSpanStyle}>{EndTime}</span>
            <br />
            <br />
            <br />
            <br />
            निकासी का समय समाप्त हो गया है, कृपया <span style={timeSpanStyle}>{StartTime}</span> और <span style={timeSpanStyle}>{EndTime}</span> के बीच पुनः प्रयास करें।
            </div>
          )}
        </div>
      </motion.div>
      <Footer page="recharge" />
    </>
  );
}

export default Withdrawal;
