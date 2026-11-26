import { useState, useContext } from 'react';
import { motion } from "framer-motion";
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Footer from '../../includes/footer-tab';
import Preloader from '../Preloader';
import BackButton from '../../includes/Back_btn';
import { AuthContext } from '../../context/AuthContext';
import { useNavigate } from 'react-router-dom';

function AddBankCard() {
    const navigate = useNavigate();
    const [preloader, setpreloader] = useState(false);
    const [name, setname] = useState('');
    const [ifsc, setifsc] = useState('');
    const [bankname, setbankname] = useState('');
    const [upi, setupi] = useState('');
    const [account, setaccount] = useState('');
    const [state, setstate] = useState('');
    const [city, setcity] = useState('');
    const [otp, setotp] = useState('');
    const { isAuthenticated, AddBanksCard,loginstatus, sendOTP } = useContext(AuthContext);
    const [mobile, setmobile] = useState(loginstatus);
    const [OtpBtn, setOtpBtn] = useState(false);
    const [otpbtntext, setotpbtntext] = useState('OTP');
    if (!isAuthenticated) {
        navigate('/login')
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
    }
    const sendOTPs = () => {
        if (loginstatus !== '') {
            setpreloader(true);
            sendOTP(loginstatus, (res) => {
                if (res.return) {
                    message(1, 'OTP send successfully!');
                    setOtpBtn(true);
                    setpreloader(false);
                    let cc = 60;
                    let countdown = setInterval(() => {
                        if (cc <= 0) {
                            clearInterval(countdown);
                            setOtpBtn(false);
                            setotpbtntext('OTP');
                        } else {
                            setotpbtntext('WAIT ' + cc + 's');
                            cc--;
                        }
                    }, 1000);
                }
            });
        } else {
            message(0, 'Please enter mobile number');
        }
    }
    const handleRegister = () => {
        if (account !== '' && ifsc !== '' && upi !== '' && bankname !== '') {
            setpreloader(true);
            let data = {
                name: name,
                bankname: bankname,
                account: account,
                ifsc: ifsc,
                upi: upi,
                mobile: mobile,
                otp: otp,
            }
            AddBanksCard(data, (res) => {
                setpreloader(false);
                if (res.status === "success") {
                    message(1, 'Bank card add successfully!');
                    setTimeout(() => {
                        navigate('/mine');
                    }, 1000);
                } else {
                    message(0, res.status);
                }
            });
        } else {
            message(0, 'Enter required fields.');
        }
    }

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
                        <p>Add Bank card</p>
                    </div>
                </div>
                <div className="recharge_box">
                    <div className="input_box">
                        <img
                            src='/icons_asset/mine.png' alt=""
                        />
                        <input type="text" placeholder="Actual Name" value={name} onInput={(event) => { setname(event.target.value) }} required/>
                    </div>
                    <div className="input_box">
                        <img
                            src='/icons_asset/mobile.png' alt=""
                        />
                        <input type="text" placeholder="Ifsc code" value={ifsc} onInput={(event) => { setifsc(event.target.value) }} required/>
                    </div>
                    <div className="input_box">
                        <img
                            src='/icons_asset/mobile.png' alt=""
                        />
                        <input type="text" placeholder="Bank Name" value={bankname} onInput={(event) => { setbankname(event.target.value) }} required/>
                    </div>
                    <div className="input_box">
                        <img
                            src='/icons_asset/mobile.png' alt=""
                        />
                        <input type="text" placeholder="Bank Number" value={account} onInput={(event) => { setaccount(event.target.value) }} required/>
                    </div>
                    <div className="input_box">
                        <img
                            src='/icons_asset/mobile.png' alt=""
                        />
                        <input type="text" placeholder="Upi Id" value={upi} onInput={(event) => { setupi(event.target.value) }} required/>
                    </div>
                    <div className="input_box">
                        <img
                            src='/icons_asset/mobile.png' alt=""
                        />
                        <input type="text" placeholder="City" value={city} onInput={(event) => { setcity(event.target.value) }} required/>
                    </div>
                    <div className="input_box">
                        <img
                            src='/icons_asset/mobile.png' alt=""
                        />
                        <input type="text" placeholder="State" value={state} onInput={(event) => { setstate(event.target.value) }} required/>
                    </div>
                    <div className="input_box">
                        <img
                            src='/icons_asset/mobile.png' alt=""
                        />
                        <input type="text" placeholder="Mobile No." value={mobile} onInput={(event) => { setmobile(event.target.value) }} readOnly/>
                    </div>
                    <div className="input_box">
                        <img
                            src="/icons_asset/otp.png" alt=""
                        />
                        <input type="password" placeholder="Verification Code" value={otp} onInput={(event) => { setotp(event.target.value) }} required/>
                        <button className="login_btn ripple" disabled={OtpBtn} onClick={() => { sendOTPs() }}>
                            {otpbtntext}
                        </button>
                    </div>
                    <div className="input_box_btn">
                        <button className="login_btn ripple" onClick={() => { handleRegister() }}>
                            Submit
                        </button>
                    </div>
                </div>
            </motion.div>
            <Footer page='bank_card' />
        </>
    );
}

export default AddBankCard;