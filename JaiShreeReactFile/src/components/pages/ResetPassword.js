import { useState, useEffect, useContext } from 'react';
import { motion } from "framer-motion";
import { ToastContainer, toast } from 'react-toastify';
import { TiTick } from "react-icons/ti";
import 'react-toastify/dist/ReactToastify.css';
import Footer from '../../includes/footer-tab';
import Preloader from '../Preloader';
import BackButton from '../../includes/Back_btn';
import { AuthContext } from '../../context/AuthContext';
import { useNavigate } from 'react-router-dom';

function ResetPassword() {
    const navigate = useNavigate();
    const { isAuthenticated, profile,base_url,loginstatus } = useContext(AuthContext);
    const [preloader, setpreloader] = useState(true);
    const [Balance, setBalance] = useState(0);
    const [Amount, setAmount] = useState();
    const [Minimum, setMinimum] = useState(100);
    useEffect(() => {
        if (!isAuthenticated) {
            navigate('/login');
        }
    }, [isAuthenticated, navigate]);
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
    const handleRecharge = () => {
        if (Amount >= Minimum) {
            window.open(base_url+'trova/src/api/pay.php?user='+loginstatus+'&am='+Amount, '_blank');
        }else{
            message(0,'Please enter amount greater than '+Minimum);
        }
    }
    useEffect(() => {
        profile((data) => {
            setpreloader(false);
            if (data.error) {
                message(0, data.message);
            } else {
                setBalance(data.user_Data[0].balance);
                setMinimum(data.user_Data[5].minimum_recharge);
            }
        });
    }, [profile])

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
                        <p>Reset Password</p>
                    </div>
                </div>
                <div className="recharge recharge_box">
                    <div className='bonus-tag'>Balance:₹ {Balance}</div>
                    <div className="input_box">
                        <img
                            src='/icons_asset/wallet.png' alt=""
                        />
                        <input type="text" placeholder="Enter Amount" onInput={(event) => { setAmount(parseInt(event.target.value)) }} value={Amount} />
                    </div>
                    <div className='recommended-amount'>
                        <button className="login_btn ripple" onClick={() => { setAmount(500) }}>
                            ₹500
                        </button>
                        <button className="login_btn ripple" onClick={() => { setAmount(1000) }}>
                            ₹1000
                        </button>
                        <button className="login_btn ripple" onClick={() => { setAmount(2000) }}>
                            ₹2000
                        </button>
                        <button className="login_btn ripple" onClick={() => { setAmount(5000) }}>
                            ₹5000
                        </button>
                        <button className="login_btn ripple" onClick={() => { setAmount(10000) }}>
                            ₹10000
                        </button>
                        <button className="login_btn ripple" onClick={() => { setAmount(50000) }}>
                            ₹50000
                        </button>
                    </div>
                    <p className='notice'>Payment Method:</p>
                    <div className="agree_box">
                        <div role="checkbox" tabIndex="0" aria-checked="true" className="van-checkbox">
                            <div className="">
                                <i className="van-icon van-icon-success">
                                    <TiTick />
                                </i>
                            </div>
                            <span className="van-checkbox__label">EKPay</span>
                        </div>
                    </div>
                    <p className='notice'>Note: Minimum recharge amount is ₹{Minimum}</p>
                    <div className="input_box_btn">
                        <button className="login_btn ripple" onClick={handleRecharge}>
                            Recharge
                        </button>
                    </div>
                </div>
            </motion.div>
            <Footer page='recharge' />
        </>
    );
}

export default ResetPassword;