import { useState, useContext, useEffect } from 'react';
import { motion } from "framer-motion";
import Footer from '../includes/footer-tab';
import Dropdown from '../includes/Dropdown';
import { ToastContainer, toast } from 'react-toastify';
import Preloader from './Preloader';
import { useNavigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';
import $ from 'jquery';

function Mine() {
    const [userid, setuserid] = useState('');
    // const preloader = true;
    const [preloader, setpreloader] = useState(true);
    const { isAuthenticated, logout, profile, WhatsappMessage } = useContext(AuthContext);
    const navigate = useNavigate();
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
        profile(function (response) {
            setpreloader(false);
            if (response.status === 0) {
                message(0, response.msg);
            } else {
                setuserid(response.data[0].mobile);
            }
        });
    }, [profile])

    const handleLogout = () => {
        logout();
    };
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
                <div className="mine header">
                    <div className="item">
                        <div className="mine item1">
                            <div className="profile_image"></div>
                            <div>
                                <p>User: &nbsp;&nbsp; {'+91' + userid}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="mine list-of-options">
                    <Dropdown icon="/icons_asset/home.png" url="/dashboard" name="Dashboard" itemclick="" menu={{}} />
                    <Dropdown icon="/icons_asset/wallet.png" url="" name="Wallet" itemclick="" menu={[{ 'name': 'Recharge (पैसा जोड़े)', 'url': '/recharge' }, { 'name': 'Withdrawal (अपना पैसा निकाले )', 'url': '/withdrawal' } ]} />
                    <Dropdown icon="/icons_asset/win_record.png" url="/history/win" name="Win History (अपना जीत देखे)" itemclick="" menu={{}} />
                    <Dropdown icon="/icons_asset/cancel_record.png" url="/history/marketcancel" name="Kabad cancel box" itemclick="" menu={{}} />
                    <Dropdown icon="/icons_asset/history.png" url="/history/market" name="Bid History (अपना खेला हुआ गेम देखे)" itemclick="" menu={{}} />
                    <Dropdown icon="/icons_asset/rate_list.png" url="/ratelist" name="Rate List" itemclick="" menu={{}} />
                    <Dropdown icon="/icons_asset/share.png" url={WhatsappMessage} name="Share App (जो गेम खेलता है उसे शेयर करें)" itemclick="" menu={{}} />
                    <div className="items" onClick={() => { $('.logout-modal.modal').addClass('active') }}>
                        <button className='btn-3 ripple'>{isAuthenticated ? 'Logout' : 'Login'}</button>
                    </div>
                </div>
            </motion.div>
            <section className="logout-modal modal">
                <div className="section">
                    <p>Confirm</p>
                    <p>Do you want to continue?</p>
                    <div className="btn-container">
                        <div className="action-btn">
                            <div onClick={() => { $('.logout-modal.modal').removeClass('active') }}>CANCEL</div>
                            <div onClick={handleLogout}>YES</div>
                        </div>
                    </div>
                </div>
            </section>
            <Footer page='mine' />
        </>
    );
}

export default Mine;
