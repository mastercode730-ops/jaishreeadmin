import { useState, useContext, useEffect } from 'react';
import { motion } from "framer-motion";
import Footer from '../../includes/footer-tab';
import Preloader from '../Preloader';
import BackButton from '../../includes/Back_btn';
import { AuthContext } from '../../context/AuthContext';

function RateList() {
    const [preloader, setpreloader] = useState(true);
    const [Data, setData] = useState("");
    const [jodirate, setjodirate] = useState("");
    const [hurfrate, sethurfrate] = useState("");
    const { GameRule } = useContext(AuthContext);
    useEffect(() => {
        GameRule(function (response) {
            setpreloader(false);
            if (response.status === 0) {
                // message(0, response.msg);
            } else {
                setjodirate(response.jodirate);
                sethurfrate(response.hurfrate);
                setData(response.data.description);
            }
        });
    }, [GameRule])
    return (
        <>
            <Preloader status={preloader} />
            <motion.div
                initial={{ translateX: -100 }}
                animate={{ translateX: 0 }}
                exit={{ translateX: 0 }}
                transition={{ duration: 0.2 }}
            >
                <div className="login header">
                    <div className="item">
                        <BackButton />
                        <p>Rate List</p>
                    </div>
                </div>
                <div className="rate-card">
  <h2>Aghori Matka Rate List</h2>
  <div className="rate-item">
    <span className="type">Jodi:</span>
    <span className="win">100 ke {jodirate}</span>
  </div>
  <div className="rate-item">
    <span className="type">Harf:</span>
    <span className="win">100 ke {hurfrate}</span>
  </div>
  <p className="app-mention">Experience the game on the Aghori Matka App.</p>
</div>
            </motion.div>
            <Footer page='login' />
        </>
    );
}

export default RateList;