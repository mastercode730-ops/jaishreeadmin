import { useState, useEffect, useContext } from 'react';
import { motion } from "framer-motion";
import $ from 'jquery';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Footer from '../../includes/footer-tab';
import Preloader from '../Preloader';
import BackButton from '../../includes/Back_btn';
import { AuthContext } from '../../context/AuthContext';
import { useNavigate } from 'react-router-dom';

function Promotion() {
    const navigate = useNavigate();
    const { isAuthenticated, PromotionData, LevelDetail } = useContext(AuthContext);
    const [preloader, setpreloader] = useState(true);
    const [refferal_code, setrefferal_code] = useState();
    const [level1user, setLevel1user] = useState(10);
    const [level2user, setLevel2user] = useState(10);
    const [level3user, setLevel3user] = useState(10);
    const [Tpeople, setTpeople] = useState(0);
    const [Contribution, setContribution] = useState(0);
    const [Promotions, setPromotions] = useState([]);
    const [Bonus, setBonus] = useState(0);
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
    const copyurl = (textToCopy) => {
        const textarea = document.createElement('textarea');
        textarea.value = textToCopy;
        document.body.appendChild(textarea);

        textarea.select();
        document.execCommand('copy');

        textarea.remove();

        message(1, 'Refferal link copied successfully');
    }
    const handlelevel = (level, event) => {
        $('.van-tab').removeClass('active');
        event.currentTarget.classList.add('active');
        setpreloader(true);
        LevelDetail(level, (res) => {
            setpreloader(false);
            setPromotions(res);
        });
    }
    function maskPhoneNumber(phoneNumber) {
        if (phoneNumber.length < 4) {
            return phoneNumber; // Not enough characters to mask
        }

        const firstTwo = phoneNumber.substring(0, 2);
        const lastTwo = phoneNumber.substring(phoneNumber.length - 2);

        const maskedPart = '*'.repeat(phoneNumber.length - 4);

        return firstTwo + maskedPart + lastTwo;
    }
    useEffect(() => {
        setpreloader(true);
        LevelDetail(1, (res) => {
            setpreloader(false);
            setPromotions(res);
        });
        PromotionData((data) => {
            setpreloader(false);
            if (data.error) {
                message(0, data.message);
            } else {
                setLevel1user(data.data[0].level1);
                setLevel2user(data.data[0].level2);
                setLevel3user(data.data[0].level3);
                setTpeople(data.data[0].people);
                setContribution(data.data[0].con);
                setBonus(data.data[0].bonus);
                setrefferal_code(data.data[0].usercode);
            }
        });
    }, [LevelDetail, PromotionData])

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
                        <p>Promotion</p>
                    </div>
                </div>
                <div className='bonus-tag'>Bonus:₹ {Bonus}</div>
                <ul className="promotion layout">
                    <li>
                        <ol className='pp'> Total People </ol>
                        <ol className="two_ol">{Tpeople}</ol>
                    </li>
                    <li>
                        <ol className='pp'> Contribution </ol>
                        <ol className="two_ol"> ₹ {Contribution}</ol>
                    </li>
                </ul>
                <div className="layout_bot">
                    <div className="bot_list">
                        <p className="titles">My Promotion Code</p>
                        <p className="answer">{refferal_code}</p>
                    </div>
                    <div className="bot_list">
                        <p className="titles">My Promotion Link</p>
                        <p className="answer heights">{process.env.REACT_APP_APP_URL}register/{refferal_code}</p>
                    </div>
                </div>
                <div className="openlink">
                    <button onClick={() => { copyurl(process.env.REACT_APP_APP_URL + 'register/' + refferal_code) }} className="tag-read ripplegrey"> Copy Link </button>
                </div>
                <div
                    data-v-aa6d4f0a=""
                    className="van-tabs van-tabs--line"
                    style={{ marginBottom: 50 }}
                >
                    <div className="level-list">
                        <div role="tablist" className="van-tabs__nav van-tabs__nav--line">
                            <div className="van-tab active" onClick={(event) => { handlelevel(1, event) }}>
                                <span className="van-tab__text van-tab__text--ellipsis">
                                    Level 1 ({level1user})
                                </span>
                            </div>
                            <div id="eve2" role="tab" className="van-tab" onClick={(event) => { handlelevel(2, event) }}>
                                <span className="van-tab__text van-tab__text--ellipsis">
                                    Level 2（{level2user}）
                                </span>
                            </div>
                            <div id="level3" role="tab" className="van-tab" onClick={(event) => { handlelevel(3, event) }}>
                                <span className="van-tab__text van-tab__text--ellipsis">
                                    Level 3（{level3user}）
                                </span>
                            </div>
                            <div
                                className="van-tabs__line"
                                style={{
                                    width: 0,
                                    transform: "translateX(69px) translateX(-50%)",
                                    height: 0,
                                    borderRadius: 0,
                                    transitionDuration: "0.3s"
                                }}
                            />
                        </div>
                    </div>
                    <table className='promotion-table'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Phone</th>
                                <th>Water reward</th>
                                <th>First reward</th>
                            </tr>
                        </thead>
                        <tbody>
                            {Promotions.map((element, i) => (
                                <tr key={i}>
                                    <td>{i + 1}</td>
                                    <td>{maskPhoneNumber(element.username)}</td>
                                    <td>{element.water_reward}</td>
                                    <td>{element.first_reward}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

            </motion.div>
            <Footer page='promotion' />
        </>
    );
}

export default Promotion;