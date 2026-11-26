import { useState, useEffect, useContext } from 'react';
import { motion } from "framer-motion";
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Footer from '../../includes/footer-tab';
import Preloader from '../Preloader';
import BackButton from '../../includes/Back_btn';
import { AuthContext } from '../../context/AuthContext';
import { Link,useNavigate } from 'react-router-dom';

function BankCard() {
    const navigate = useNavigate();
    const { isAuthenticated, Bankdata } = useContext(AuthContext);
    const [preloader, setpreloader] = useState(true);
    const [bankDeatil, setbankDeatil] = useState([]);
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
    useEffect(() => {
        Bankdata((data) => {
            setbankDeatil(data[0]);
            setpreloader(false);
        });
    }, [Bankdata])

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
                        <p>Bank card</p>
                    </div>
                </div>
                <div className="bank-detail-list">
                    {
                        bankDeatil.account !== '1111111111' && bankDeatil.ifsc !== 'Not Addeed' && bankDeatil.upi !== '' ?
                            <div className="list">
                                <img src="/icons_asset/withdrawal.png" alt="Bank detail" />
                                <div className='data'>
                                    <p>{bankDeatil.bankname}</p>
                                    <p>{bankDeatil.account}</p>
                                    <p>{bankDeatil.ifsc}</p>
                                    <p>{bankDeatil.name}</p>
                                    <p>{bankDeatil.upi}</p>
                                </div>
                                <img src="/icons_asset/info.png" className='info' onClick={()=>{message(0,"Any changes in detail, Please contact admin")}} alt="Bank detail" />
                            </div>
                            :
                            <div className="add-btn">
                                <Link to='/add-bank-card'>
                                    <button>+ Add bank detail</button>
                                </Link>
                            </div>
                    }
                </div>
            </motion.div>
            <Footer page='bank_card' />
        </>
    );
}

export default BankCard;