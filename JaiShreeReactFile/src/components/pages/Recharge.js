import { useState, useEffect, useContext } from "react";
import { motion } from "framer-motion";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import Footer from "../../includes/footer-tab"; // Adjust path
import Preloader from "../Preloader"; // Adjust path
import BackButton from "../../includes/Back_btn"; // Adjust path
import { AuthContext } from "../../context/AuthContext"; // Adjust path
import { useNavigate } from "react-router-dom";
import styles from '../../assets/css/Recharge.module.css'; // Import CSS Module
import { FaQrcode, FaUniversity } from "react-icons/fa"; // QR & Bank Icons

function Recharge() {
  const navigate = useNavigate();
  const { isAuthenticated, profile, AddMoney, getQrDetails, submitQrPayment } = useContext(AuthContext);
  const [preloader, setPreloader] = useState(true);
  const [Balance, setBalance] = useState(0);
  const [Amount, setAmount] = useState("");
  const [Minimum, setMinimum] = useState(100); // Default minimum

  // QR Modal State
  const [showQrModal, setShowQrModal] = useState(false);
  const [qrDetails, setQrDetails] = useState(null);
  const [selectedFile, setSelectedFile] = useState(null);
  const [previewUrl, setPreviewUrl] = useState("");
  const [utrNumber, setUtrNumber] = useState("");
  const [submittingQr, setSubmittingQr] = useState(false);

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

  // Fetch profile data (balance, minimum recharge)
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
                setBalance(response.data[0].wallet || 0);
                // Assuming minimum recharge comes from user_Data array, index 5
                setMinimum(response.user_Data?.[5]?.minimum_recharge || 100);
            } else {
                message(0, "Profile data not found");
            }
        });
    } else {
        setPreloader(false); // Hide preloader if profile function isn't available
    }
    return () => { isMounted = false; }; // Cleanup
  }, [profile]);

  // Handle Recharge Action via UPI Gateway
  const handleRecharge = () => {
    const numericAmount = parseFloat(Amount);
    if (isNaN(numericAmount) || numericAmount < Minimum) {
      message(0, `Minimum recharge amount is ₹${Minimum}`);
      return;
    }
    setPreloader(true); // Show preloader during API call
    if (AddMoney) {
        AddMoney(numericAmount, (res) => {
            setPreloader(false); // Hide preloader after API call
            if (res.status === 0) {
                message(0, res.msg || "Recharge failed");
            } else if (res.url) {
                // Redirect to payment gateway
                window.location.href = res.url;
            } else {
                 message(0, "Invalid response from server.");
            }
        });
    } else {
        setPreloader(false);
        message(0,"Recharge function not available.");
    }
  };

  // Open QR Modal & Fetch QR Details
  const handleOpenQrModal = () => {
    const numericAmount = parseFloat(Amount);
    if (isNaN(numericAmount) || numericAmount < Minimum) {
      message(0, `Minimum recharge amount is ₹${Minimum}`);
      return;
    }
    setPreloader(true);
    if (getQrDetails) {
      getQrDetails((res) => {
        setPreloader(false);
        if (res && res.status === 1 && res.data) {
          setQrDetails(res.data);
          setShowQrModal(true);
        } else {
          message(0, res?.msg || "QR Code payment is currently unavailable");
        }
      });
    } else {
      setPreloader(false);
      message(0, "QR Code service not available.");
    }
  };

  // Handle File Change
  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setSelectedFile(file);
      setPreviewUrl(URL.createObjectURL(file));
    }
  };

  // Handle Copy UPI
  const handleCopyUpi = () => {
    if (qrDetails && qrDetails.upi_num) {
      navigator.clipboard.writeText(qrDetails.upi_num);
      message(1, "UPI ID copied to clipboard!");
    }
  };

  // Handle Copy Bank Details
  const handleCopyText = (text, label) => {
    if (text) {
      navigator.clipboard.writeText(text);
      message(1, `${label} copied to clipboard!`);
    }
  };

  // Submit QR Payment Request
  const handleSubmitQrPayment = () => {
    if (!selectedFile) {
      message(0, "Please select and upload the payment screenshot image.");
      return;
    }
    setSubmittingQr(true);
    setPreloader(true);

    const formData = new FormData();
    formData.append("amount", Amount);
    formData.append("transaction_id", utrNumber);
    formData.append("transaction_image", selectedFile);

    submitQrPayment(formData, (res) => {
      setPreloader(false);
      setSubmittingQr(false);
      if (res && res.status === 1) {
        message(1, res.msg || "Payment request submitted successfully!");
        setShowQrModal(false);
        setSelectedFile(null);
        setPreviewUrl("");
        setUtrNumber("");
        setAmount("");
      } else {
        message(0, res?.msg || "Failed to submit payment request.");
      }
    });
  };

  // Framer Motion variants
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { staggerChildren: 0.1 } },
  };
  const itemVariants = {
    hidden: { y: 20, opacity: 0 },
    visible: { y: 0, opacity: 1, transition: { type: 'spring', stiffness: 100 } },
  };

  return (
    <>
      <Preloader status={preloader} />
      <ToastContainer /* Props remain same */ />

      {/* Use the Guruji theme container */}
      <div className={styles.rechargeContainer}>
        {!preloader && (
            <motion.div
                variants={containerVariants}
                initial="hidden"
                animate="visible"
                exit={{ opacity: 0 }}
            >
                {/* --- Header --- */}
                <motion.div className={styles.rechargeHeader} variants={itemVariants}>
                    <BackButton className={styles.backButton} />
                    <p className={styles.headerTitle}>Recharge</p>
                </motion.div>

                {/* --- Recharge Box --- */}
                <motion.div className={styles.rechargeBox} variants={itemVariants}>
                    <div className={styles.balanceDisplay}>
                        Available Balance: ₹{Balance}
                    </div>

                    <div className={styles.inputLabel}>Enter Amount (Min: ₹{Minimum})</div>
                    <div className={styles.inputContainer}>
                        <span className={styles.currencySymbol}>₹</span>
                        <input
                            type="number"
                            inputMode="decimal"
                            className={styles.amountInput}
                            onInput={(event) => {
                                const value = event.target.value.replace(/[^0-9]/g, '');
                                setAmount(value !== "" ? value : "");
                            }}
                            placeholder="0"
                            value={Amount}
                        />
                    </div>

                    <div className={styles.recommendedAmount}>
                        {[Minimum, 200, 500, 1000, 2000, 5000].map((value) => (
                            (value >= Minimum || value === Minimum) &&
                            <button
                                key={value}
                                className={styles.amountButton}
                                onClick={() => setAmount(String(value))}
                            >
                                ₹{value}
                            </button>
                        ))}
                    </div>

                    <div className={styles.payButtonContainer}>
                        <button className={styles.payButton} onClick={handleRecharge}>
                            PAY BY UPI
                        </button>
                        <button className={`${styles.payButton} ${styles.qrButton}`} onClick={handleOpenQrModal}>
                            <FaQrcode size={18} /> PAY BY QR
                        </button>
                    </div>
                </motion.div>
            </motion.div>
        )}
      </div>

      {/* --- QR Code Payment Modal --- */}
      {showQrModal && qrDetails && (
        <div className={styles.qrModalOverlay}>
          <div className={styles.qrModal}>
            <div className={styles.modalHeader}>
              <h3 className={styles.modalTitle}>Scan & Pay by QR</h3>
              <button className={styles.closeButton} onClick={() => setShowQrModal(false)}>&times;</button>
            </div>
            <div className={styles.qrCardBody}>
              {qrDetails.name && <span className={styles.qrTagBadge}>{qrDetails.name}</span>}

              {qrDetails.qr_img ? (
                <div className={styles.qrImageWrapper}>
                  <img src={qrDetails.qr_img} alt="Payment QR Code" className={styles.qrImage} />
                </div>
              ) : (
                <div style={{ color: "#ffc107", fontStyle: "italic" }}>No QR image uploaded</div>
              )}

              <div className={styles.balanceDisplay} style={{ marginBottom: 0 }}>
                Paying Amount: ₹{Amount}
              </div>

              {qrDetails.upi_num && (
                <div className={styles.upiDetailsBox}>
                  <span className={styles.upiIdText}>UPI: {qrDetails.upi_num}</span>
                  <button className={styles.copyBtn} onClick={handleCopyUpi}>Copy</button>
                </div>
              )}

              {/* Direct Bank Transfer Details */}
              {(qrDetails.account_number || qrDetails.ifsc_code) && (
                <div className={styles.bankTransferSection}>
                  <div className={styles.bankSectionHeader}>
                    <FaUniversity className={styles.bankIcon} />
                    <span>Direct Bank Transfer Details</span>
                  </div>

                  {qrDetails.bank_name && (
                    <div className={styles.bankRow}>
                      <span className={styles.bankLabel}>Bank Name</span>
                      <span className={styles.bankVal}>{qrDetails.bank_name}</span>
                    </div>
                  )}

                  {qrDetails.account_holder_name && (
                    <div className={styles.bankRow}>
                      <span className={styles.bankLabel}>A/C Holder</span>
                      <span className={styles.bankVal}>{qrDetails.account_holder_name}</span>
                    </div>
                  )}

                  {qrDetails.account_number && (
                    <div className={styles.bankRow}>
                      <span className={styles.bankLabel}>A/C Number</span>
                      <div className={styles.bankValWithCopy}>
                        <span className={styles.bankValMono}>{qrDetails.account_number}</span>
                        <button 
                          type="button"
                          className={styles.copyBtnSmall} 
                          onClick={() => handleCopyText(qrDetails.account_number, "Account Number")}
                        >
                          Copy
                        </button>
                      </div>
                    </div>
                  )}

                  {qrDetails.ifsc_code && (
                    <div className={styles.bankRow}>
                      <span className={styles.bankLabel}>IFSC Code</span>
                      <div className={styles.bankValWithCopy}>
                        <span className={styles.bankValMono}>{qrDetails.ifsc_code}</span>
                        <button 
                          type="button"
                          className={styles.copyBtnSmall} 
                          onClick={() => handleCopyText(qrDetails.ifsc_code, "IFSC Code")}
                        >
                          Copy
                        </button>
                      </div>
                    </div>
                  )}
                </div>
              )}

              <div className={styles.uploadSection}>
                <label className={styles.uploadLabel}>Upload Payment Screenshot <span style={{ color: "red" }}>*</span></label>
                <input 
                  type="file" 
                  accept="image/*" 
                  className={styles.fileInput} 
                  onChange={handleFileChange} 
                />
                {previewUrl && (
                  <div className={styles.ssPreviewBox}>
                    <img src={previewUrl} alt="Preview" className={styles.ssPreviewImg} />
                  </div>
                )}

                <label className={styles.uploadLabel} style={{ marginTop: "6px" }}>UTR / Reference Number (Optional)</label>
                <input 
                  type="text" 
                  placeholder="Enter 12-digit UTR Number" 
                  className={styles.utrInput} 
                  value={utrNumber} 
                  onChange={(e) => setUtrNumber(e.target.value)} 
                />

                <button 
                  className={styles.submitQrBtn} 
                  onClick={handleSubmitQrPayment} 
                  disabled={submittingQr}
                >
                  {submittingQr ? "Submitting..." : "Submit Payment Request"}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* --- Footer --- */}
      <Footer />
    </>
  );
}

export default Recharge;
