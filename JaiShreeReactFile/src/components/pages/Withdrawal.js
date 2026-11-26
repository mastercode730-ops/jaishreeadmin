import { useState, useEffect, useContext } from "react";
import { motion } from "framer-motion";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import Footer from "../../includes/footer-tab"; // Adjust path
import Preloader from "../Preloader"; // Adjust path
import BackButton from "../../includes/Back_btn"; // Adjust path
import { AuthContext } from "../../context/AuthContext"; // Adjust path
import { useNavigate } from "react-router-dom";
import styles from '../../assets/css/Withdrawal.module.css'; // Import CSS Module
import { FaUniversity } from "react-icons/fa"; // Icons for payment methods

// Payment method images (replace with your actual paths or import if needed)
const paytmLogo = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR363BO6LXUKGXXHiOTaHnqqDXgwpw4qOeOoA&s";
const phonepeLogo = "https://cdn.worldvectorlogo.com/logos/phonepe-1.svg";
const gpayLogo = "https://cdn.worldvectorlogo.com/logos/google-pay-2.svg";
const bankLogo = "https://static.vecteezy.com/system/resources/thumbnails/020/716/209/small/flat-icon-bank-bank-icon-where-to-keep-money-illustration-of-saving-in-the-bank-free-png.png";


function Withdrawal() {
  const navigate = useNavigate();
  const { isAuthenticated, profile, WithdrawMoney } = useContext(AuthContext);
  const [preloader, setPreloader] = useState(true);
  const [Balance, setBalance] = useState(0); // Default to 0
  const [Password, setPassword] = useState(""); // Mobile number for UPI
  const [StartTime, setStartTime] = useState("");
  const [EndTime, setEndTime] = useState("");
  const [currentTime, setCurrentTime] = useState("");
  const [Amount, setAmount] = useState("");
  const [BankCard, setBankCard] = useState(""); // Selected payment method
  const [Minimum, setMinimum] = useState(100); // Default minimum
  const [AccountHolder, setAccountHolder] = useState("");
  const [Account, setAccount] = useState("");
  const [ConfirmAccount, setConfirmAccount] = useState("");
  const [Ifsc, setIfsc] = useState("");
  const [BankName, setBankName] = useState("");
  const [WithdrawalStatus, setWithdrawalStatus] = useState(false); // Withdrawal open/closed status
  // const [shake, setShake] = useState(false); // Shake effect removed for now

  // Redirect if not authenticated
  useEffect(() => {
    if (!isAuthenticated) {
      navigate("/login");
    }
  }, [isAuthenticated, navigate]);

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

  // Fetch profile data
  useEffect(() => {
    let isMounted = true;
    setPreloader(true);
    if (profile) {
        profile((response) => {
            if (!isMounted) return;
            setPreloader(false);
            if (response.status === 0) {
                message(0, response.msg || "Failed to load profile");
            } else if (response.data && response.data.length > 0) {
                const uData = response.data[0];
                setBalance(uData.wallet || 0);
                setMinimum(uData.setting?.withdrawal_amount || 100);
                setStartTime(uData.setting?.startwith || "");
                setEndTime(uData.setting?.endwith || "");

                // Auto-fill saved bank details from profile
                if (uData.account && (uData.account.account_no || uData.account.ifsc_code)) {
                    if (uData.account.holder_name) setAccountHolder(uData.account.holder_name);
                    if (uData.account.account_no) {
                        setAccount(uData.account.account_no);
                        setConfirmAccount(uData.account.account_no);
                    }
                    if (uData.account.ifsc_code) setIfsc(uData.account.ifsc_code);
                    if (uData.account.bank_name) setBankName(uData.account.bank_name);
                } else {
                    // Check local storage fallback
                    const saved = localStorage.getItem("saved_bank_details");
                    if (saved) {
                        try {
                            const parsed = JSON.parse(saved);
                            if (parsed.holder_name) setAccountHolder(parsed.holder_name);
                            if (parsed.account_no) {
                                setAccount(parsed.account_no);
                                setConfirmAccount(parsed.account_no);
                            }
                            if (parsed.ifsc_code) setIfsc(parsed.ifsc_code);
                            if (parsed.bank_name) setBankName(parsed.bank_name);
                        } catch(e) {}
                    }
                }
            } else {
                message(0, "Profile data not found");
            }
        });
    } else {
        setPreloader(false);
    }
    return () => { isMounted = false; };
  }, [profile]);

  // Update current time and check withdrawal window
  useEffect(() => {
    const updateCurrentTime = () => {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, "0");
      const minutes = String(now.getMinutes()).padStart(2, "0");
      const seconds = String(now.getSeconds()).padStart(2, "0");
      setCurrentTime(`${hours}:${minutes}:${seconds}`);
    };

    const intervalId = setInterval(updateCurrentTime, 1000);
    updateCurrentTime(); // Initial call

    return () => clearInterval(intervalId); // Cleanup
  }, []);

  // Check if withdrawal is allowed based on time
  useEffect(() => {
    if (!currentTime || !StartTime || !EndTime) {
      setWithdrawalStatus(false); // Default to closed if times are missing
      return;
    }

    try {
        const [startHour, startMinute] = StartTime.split(":").map(Number);
        const [endHour, endMinute] = EndTime.split(":").map(Number);

        const now = new Date(); // Use today's date for comparison
        const start = new Date(now);
        start.setHours(startHour, startMinute, 0, 0); // Set start time today

        const end = new Date(now);
        end.setHours(endHour, endMinute, 0, 0); // Set end time today

        // Handle overnight case (e.g., 21:00 to 06:00)
        if (end < start) {
            // If current time is after start OR before end (on the next day concept)
            if (now >= start || now <= end) {
                setWithdrawalStatus(true);
            } else {
                setWithdrawalStatus(false);
            }
        } else {
            // Normal case (e.g., 09:00 to 17:00)
            if (now >= start && now <= end) {
                setWithdrawalStatus(true);
            } else {
                setWithdrawalStatus(false);
            }
        }
    } catch (error) {
        console.error("Error parsing time:", error);
        setWithdrawalStatus(false); // Default to closed on error
    }

  }, [currentTime, StartTime, EndTime]);


  // Handle Withdrawal Submission
  const handleWithdrawalSubmit = () => {
    const numericAmount = parseFloat(Amount);

    if (!BankCard) {
      message(0, "Please select a withdrawal method"); return;
    }
    if (isNaN(numericAmount) || Amount === "") {
      message(0, "Please enter a valid amount"); return;
    }
    if (numericAmount < Minimum) {
      message(0, `Minimum withdrawal amount is ₹${Minimum}`); return;
    }
    if (numericAmount > Balance) {
      message(0, "Insufficient wallet balance"); return;
    }

    // UPI Validation (Mobile Number)
    if (BankCard !== "Bank" && (Password === "" || !/^\d{10}$/.test(Password))) {
        message(0, "Please enter a valid 10-digit mobile number for UPI"); return;
    }

    // Bank Validation
    if (BankCard === "Bank") {
        if (!AccountHolder.trim()) { message(0, "Please enter Account Holder Name"); return; }
        if (!BankName.trim()) { message(0, "Please enter Bank Name"); return; }
        if (!Account.trim() || !/^\d{6,24}$/.test(Account.trim())) { message(0, "Please enter a valid Bank Account Number (digits only)"); return; }
        if (ConfirmAccount.trim() !== Account.trim()) { message(0, "Account Number and Confirm Account Number do not match"); return; }
        if (!Ifsc.trim() || Ifsc.trim().length < 4) { message(0, "Please enter a valid IFSC Code"); return; }

        // Cache in localStorage for easy future reuse
        try {
            localStorage.setItem("saved_bank_details", JSON.stringify({
                holder_name: AccountHolder.trim(),
                account_no: Account.trim(),
                ifsc_code: Ifsc.trim().toUpperCase(),
                bank_name: BankName.trim()
            }));
        } catch(e) {}
    }

    setPreloader(true);
    if (WithdrawMoney) {
      WithdrawMoney(
        numericAmount,
        Password, // This is used as Mobile for UPI
        BankCard,
        Account,
        Ifsc,
        AccountHolder,
        BankName,
        (data) => {
          setPreloader(false);
          if (data.status === 0) { // Check API response status
            message(0, data.msg || data.message || "Withdrawal failed");
          } else {
            message(1, data.msg || data.message || "Withdrawal successful");
            // Reset form and navigate after success
            setAmount("");
            setPassword("");
            setBankCard("");
            setAccountHolder("");
            setAccount("");
            setIfsc("");
            setBankName("");
            setTimeout(() => {
              navigate("/mine"); // Navigate after a short delay
            }, 1500);
          }
        }
      );
    } else {
        setPreloader(false);
        message(0, "Withdrawal function not available.");
    }
  };

  // Framer Motion variants
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { staggerChildren: 0.08 } },
  };
  const itemVariants = {
    hidden: { y: 15, opacity: 0 },
    visible: { y: 0, opacity: 1, transition: { type: 'spring', stiffness: 100 } },
  };


  return (
    <>
      <Preloader status={preloader} />
      <ToastContainer /* Props remain same */ />

      {/* Use the Guruji theme container */}
      <div className={styles.withdrawContainer}>
        {!preloader && (
            <motion.div
                variants={containerVariants}
                initial="hidden"
                animate="visible"
                exit={{ opacity: 0 }}
            >
                {/* --- Header --- */}
                <motion.div className={styles.withdrawHeader} variants={itemVariants}>
                    <BackButton className={styles.backButton} />
                    <p className={styles.headerTitle}>Withdrawal</p>
                    {/* <Link to="/history/withdrawal" className={styles.historyLink} aria-label="Withdrawal History">
                        <AiOutlineHistory />
                    </Link> */}
                </motion.div>

                {/* --- Main Withdrawal Box --- */}
                <motion.div className={styles.withdrawBox} variants={itemVariants}>
                    <div className={styles.balanceDisplayWithdraw}>
                        Available Balance: ₹{Balance}
                    </div>

                    {/* --- Withdrawal Time Status --- */}
                    {!WithdrawalStatus ? (
                        <div className={styles.timeClosedMessage}>
                            <p>WITHDRAWAL TIME CLOSED</p>
                            <p className={styles.timeRange}>
                                Please try again between {StartTime || '--:--'} and {EndTime || '--:--'}
                            </p>
                            <p className={styles.hindiMessage}>
                                निकासी का समय समाप्त हो गया है, कृपया {StartTime || '--:--'} और {EndTime || '--:--'} के बीच पुनः प्रयास करें।
                            </p>
                        </div>
                    ) : (
                        /* --- Withdrawal Form (Shown when open) --- */
                        <div className={styles.withdrawForm}>
                            <p className={styles.sectionTitle}>Select Withdrawal Method</p>
                            <div className={styles.paymentMethodSelection}>
                                {/* UPI Options */}
                                <label className={`${styles.paymentOption} ${BankCard === 'Paytm' ? styles.selected : ''}`}>
                                    <input type="radio" name="PAYMENTMETHOD" value="Paytm" checked={BankCard === 'Paytm'} onChange={(e) => setBankCard(e.target.value)} />
                                    <img src={paytmLogo} alt="Paytm" />
                                    <span>Paytm</span>
                                </label>
                                <label className={`${styles.paymentOption} ${BankCard === 'Phonepe' ? styles.selected : ''}`}>
                                    <input type="radio" name="PAYMENTMETHOD" value="Phonepe" checked={BankCard === 'Phonepe'} onChange={(e) => setBankCard(e.target.value)} />
                                    <img src={phonepeLogo} alt="PhonePe" />
                                    <span>PhonePe</span>
                                </label>
                                <label className={`${styles.paymentOption} ${BankCard === 'GPay' ? styles.selected : ''}`}>
                                    <input type="radio" name="PAYMENTMETHOD" value="GPay" checked={BankCard === 'GPay'} onChange={(e) => setBankCard(e.target.value)} />
                                    <img src={gpayLogo} alt="Google Pay" />
                                    <span>GPay</span>
                                </label>
                                {/* Bank Option */}
                                <label className={`${styles.paymentOption} ${BankCard === 'Bank' ? styles.selected : ''}`}>
                                    <input type="radio" name="PAYMENTMETHOD" value="Bank" checked={BankCard === 'Bank'} onChange={(e) => setBankCard(e.target.value)} />
                                    <img src={bankLogo} alt="Bank Transfer" />
                                    {/* <FaUniversity className={styles.bankIcon}/> */}
                                    <span>Bank</span>
                                </label>
                            </div>

                            {/* --- Conditional Inputs --- */}
                            {BankCard && BankCard !== 'Bank' && (
                                <div className={styles.inputGroup}>
                                    <label className={styles.inputLabelWithdraw}>Enter UPI Mobile Number</label>
                                    <input
                                        type="tel" // Use tel for number pads
                                        inputMode="numeric" // Hint numeric keyboard
                                        maxLength={10}
                                        className={styles.formInput}
                                        value={Password} // Reusing Password state for mobile number
                                        onInput={(e) => setPassword(e.target.value.replace(/[^0-9]/g, ''))}
                                        placeholder="10 Digit Mobile Number"
                                    />
                                </div>
                            )}

                            {BankCard === 'Bank' && (
                                <div className={styles.bankFormCard}>
                                    <div className={styles.bankCardTitle}>
                                        <FaUniversity className={styles.bankCardTitleIcon} />
                                        <span>Beneficiary Bank Account Details</span>
                                    </div>
                                    <p className={styles.bankCardSubtitle}>
                                        Enter the bank details where you want to receive your withdrawal amount. The admin will directly transfer the funds to this account.
                                    </p>

                                    <div className={styles.inputGroup}>
                                        <label className={styles.inputLabelWithdraw}>Account Holder Name <span className={styles.reqStar}>*</span></label>
                                        <input 
                                            type="text" 
                                            className={styles.formInput} 
                                            value={AccountHolder} 
                                            onChange={(e) => setAccountHolder(e.target.value)} 
                                            placeholder="Enter Full Name as on Bank Account" 
                                        />
                                    </div>

                                    <div className={styles.inputGroup}>
                                        <label className={styles.inputLabelWithdraw}>Bank Name <span className={styles.reqStar}>*</span></label>
                                        <input 
                                            type="text" 
                                            className={styles.formInput} 
                                            value={BankName} 
                                            onChange={(e) => setBankName(e.target.value)} 
                                            placeholder="e.g. State Bank of India / HDFC Bank / ICICI" 
                                        />
                                    </div>

                                    <div className={styles.inputGroup}>
                                        <label className={styles.inputLabelWithdraw}>Bank Account Number <span className={styles.reqStar}>*</span></label>
                                        <input 
                                            type="text" 
                                            inputMode="numeric" 
                                            className={styles.formInput} 
                                            value={Account} 
                                            onInput={(e) => setAccount(e.target.value.replace(/[^0-9]/g, ''))} 
                                            placeholder="Enter Bank Account Number" 
                                        />
                                    </div>

                                    <div className={styles.inputGroup}>
                                        <label className={styles.inputLabelWithdraw}>Confirm Account Number <span className={styles.reqStar}>*</span></label>
                                        <input 
                                            type="text" 
                                            inputMode="numeric" 
                                            className={styles.formInput} 
                                            value={ConfirmAccount} 
                                            onInput={(e) => setConfirmAccount(e.target.value.replace(/[^0-9]/g, ''))} 
                                            placeholder="Re-enter Bank Account Number" 
                                        />
                                    </div>

                                    <div className={styles.inputGroup}>
                                        <label className={styles.inputLabelWithdraw}>IFSC Code <span className={styles.reqStar}>*</span></label>
                                        <input 
                                            type="text" 
                                            className={styles.formInput} 
                                            value={Ifsc} 
                                            onChange={(e) => setIfsc(e.target.value.toUpperCase())} 
                                            placeholder="e.g. SBIN0001234" 
                                            style={{ textTransform: "uppercase" }}
                                        />
                                    </div>
                                </div>
                            )}

                            {/* --- Amount Input --- */}
                            {BankCard && ( // Only show amount input if a method is selected
                                <div className={styles.inputGroup}>
                                    <label className={styles.inputLabelWithdraw}>Enter Amount (Min: ₹{Minimum})</label>
                                    <div className={styles.amountInputContainer}>
                                        <span className={styles.currencySymbolWithdraw}>₹</span>
                                        <input
                                            type="number"
                                            inputMode="decimal"
                                            className={styles.amountInputWithdraw}
                                            value={Amount}
                                            onInput={(e) => setAmount(e.target.value.replace(/[^0-9]/g, ''))}
                                            placeholder="0"
                                        />
                                    </div>
                                    <p className={styles.minimumNote}>Minimum Withdrawal: ₹{Minimum}</p>
                                </div>
                            )}

                            {/* --- Submit Button --- */}
                            {BankCard && ( // Only show button if method selected
                                <div className={styles.submitButtonContainer}>
                                    <button className={styles.submitButton} onClick={handleWithdrawalSubmit}>
                                        Withdraw
                                    </button>
                                </div>
                            )}
                        </div>
                    )}
                </motion.div>
            </motion.div>
        )}
      </div>
      {/* --- Footer --- */}
      <Footer />
    </>
  );
}

export default Withdrawal;
