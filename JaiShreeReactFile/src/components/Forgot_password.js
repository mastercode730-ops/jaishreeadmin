import { useState,useContext } from 'react';
import { motion } from "framer-motion";
import Footer from '../includes/footer-tab';
import Preloader from './Preloader';
import BackButton from '../includes/Back_btn';
import { Link, useNavigate } from 'react-router-dom';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { AuthContext } from '../context/AuthContext';

function ForgotPassword() {
    const [preloader, setpreloader] = useState(false);
    const [mobile, setmobile] = useState('');
    const [otp, setotp] = useState('');
    const [password, setpassword] = useState('');
    const [OtpBtn, setOtpBtn] = useState(false);
    const [otpbtntext, setotpbtntext] = useState('OTP');
    const { ForgotPassword, sendOTP } = useContext(AuthContext);
    const navigate = useNavigate();
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
        if (mobile !== '') {
            setpreloader(true);
            sendOTP(mobile, (res) => {
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
        if (mobile !== '' && otp !== '' && password !== '') {
            setpreloader(true);
            let data = {
                otp:otp,
                username:mobile,
                password:password
            }
            ForgotPassword(data, (res) => {
                setpreloader(false);
                if (!res[0].error) {
                    message(1, 'Password successfully updated!');
                    setTimeout(() => {
                        navigate('/login');
                    }, 1000);
                }else{
                    message(0, res[0].status);
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
                        <p>Forgot Password</p>
                    </div>
                </div>
                <div className="recharge_box">
                <div className="input_box">
                        <img
                            src='/icons/mobile.png' alt=""
                        />
                        <input type="text" placeholder="Mobile Number" value={mobile} onInput={(event) => { setmobile(event.target.value) }} />
                    </div>
                    <div className="input_box">
                        <img
                            src="/icons/otp.png" alt=""
                        />
                        <input type="password" placeholder="Verification Code" value={otp} onInput={(event) => { setotp(event.target.value) }} />
                        <button className="login_btn ripple" disabled={OtpBtn} onClick={() => { sendOTPs() }}>
                            {otpbtntext}
                        </button>
                    </div>
                    <div className="input_box">
                        <img
                            src="/icons/key.png" alt=""
                        />
                        <input type="password" placeholder="Password" value={password} onInput={(event) => { setpassword(event.target.value) }} />
                    </div>
                    <div className="input_box_btn">
                        <button className="login_btn ripple" onClick={()=>{handleRegister()}}>
                            Submit
                        </button>
                    </div>
                    <div className="input_box_btn">
                        <div className="two_btn">
                            <Link to="/login">
                                <button className="ripplegrey">
                                    Login
                                </button>
                            </Link>
                            <Link to="/register">
                                <button className="ripplegrey">
                                    Register?
                                </button>
                            </Link>
                        </div>
                    </div>
                </div>
            </motion.div>
            <Footer page='login' />
        </>
    );
}

export default ForgotPassword;
