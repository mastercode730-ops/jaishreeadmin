import { useState, useEffect, useContext } from 'react';
import { motion } from "framer-motion";
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Footer from '../../includes/footer-tab';
import Preloader from '../Preloader';
import { AiOutlineHistory } from "react-icons/ai";
import BackButton from '../../includes/Back_btn';
import { AuthContext } from '../../context/AuthContext';
import { useNavigate,Link } from 'react-router-dom';

function P2PTransfer() {
    const navigate = useNavigate();
    const { isAuthenticated, profile,P2PTransferAmount } = useContext(AuthContext);
    const [preloader, setpreloader] = useState(true);
    const [Balance, setBalance] = useState(0);
    const [Amount, setAmount] = useState('');
    const [Username, setUsername] = useState('');
    const [Maximum, setMaximum] = useState(0);
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
    const handleP2p = () => {
        if (Username !== '' && Amount !== '') {
            let data = {
                username: Username,
                amount: Amount
            }
            setpreloader(true);
            P2PTransferAmount(data,(res)=>{
                setpreloader(false);
                if (res[0].status > 0) {
                    setBalance(res[0].wallet);
                    message(1,'Amount transfer request successfully sent.');
                } else {
                    message(0, res[0].message);
                }
            });
        }else{
            message(0,'Please fill all required fields.');
        }
    }
    useEffect(() => {
        profile((data) => {
            setpreloader(false);
            if (data.error) {
                message(0, data.message);
            } else {
                setBalance(data.user_Data[0].balance);
                setMaximum(data.user_Data[5].maximum_p2p)
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
                        <p>P2P Transfer</p>
                        <Link to="/history/p2p" className="historyright"><AiOutlineHistory /></Link>
                    </div>
                </div>

                <div className="recharge recharge_box">
                    <div className='bonus-tag'>Balance:₹ {Balance}</div>
                    <div className="input_box">
                        <img
                            src='/icons_asset/wallet.png' alt=""
                        />
                        <input type="number" placeholder="Enter Amount" onInput={(event) => { setAmount(parseInt(event.target.value)) }} value={Amount} />
                    </div>
                    <div className="input_box">
                        <img
                            src='/icons_asset/mine.png' alt=""
                        />
                        <input type="text" placeholder="Enter Friend Mobile Number" onInput={(event) => { setUsername(event.target.value) }} value={Username} />
                    </div>
                    <p className='notice'>Note: Maximum transfer amount is ₹{Maximum}</p>
                    <div className="input_box_btn">
                        <button className="login_btn ripple" onClick={handleP2p}>
                            Transfer
                        </button>
                    </div>
                </div>

            </motion.div>
            <Footer page='login' />
        </>
    );
}

export default P2PTransfer;