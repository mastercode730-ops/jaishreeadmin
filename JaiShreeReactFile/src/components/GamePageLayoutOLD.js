import { React, useState, useContext, useEffect } from "react";
import { motion } from "framer-motion";
import Preloader from "../components/Preloader";
import "react-toastify/dist/ReactToastify.css";
import { useNavigate } from "react-router-dom";
import { ToastContainer, toast } from "react-toastify";
import { AuthContext } from "../context/AuthContext";

const GamePageLayout = ({ children, handlers, id, new_message }) => {
  const [GameName, setGameName] = useState("");
  const [preloader, setpreloader] = useState(true);
  const { isAuthenticated, Gameinfo } = useContext(AuthContext);
  const [timeLeft, setTimeLeft] = useState("");
  const [targetDateTime, settargetDateTime] = useState("");
  const [targetDateTimeMotiJodi, settargetDateTimeMotiJodi] = useState("");
  const [targetDateMotiEnd, settargetDateMotiEnd] = useState("");
  const navigate = useNavigate();
  if (!isAuthenticated) {
    navigate("/login");
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
  };
  useEffect(() => {
    Gameinfo(id, (res) => {
      setpreloader(false);
      if (res.status === 0) {
        message(0, res.msg);
        return;
      }
      let daa = res.data[0];
      setGameName(daa.game_name);
      settargetDateTime(daa.end_date + " " + daa.end_time);
      settargetDateTimeMotiJodi(daa.result_date + " " + daa.result_time);
    });
  }, [id,Gameinfo]);

  useEffect(() => {
    function exporthms(targetDatew){
      const now = new Date();
      const difference = targetDatew - now;
      if (difference > 0) {
        const hours = Math.floor(difference / (1000 * 60 * 60));
        const minutes = Math.floor((difference / (1000 * 60)) % 60);
        const seconds = Math.floor((difference / 1000) % 60);
        return (hours + ":" + minutes + ":" + seconds);
      } else {
        return ("00:00:00"); // Set to 0 if the time has passed
      }
    }
    const calculateTimeLeft = () => {
      const now = new Date();
      const targetDate = new Date(targetDateTime);
      setTimeLeft(exporthms(targetDate)); // Set to 0 if the time has passed

      const targetDateMotiEnd = new Date(targetDateTimeMotiJodi);
      settargetDateMotiEnd(exporthms(targetDateMotiEnd)); // Set to 0 if the time has passed
    };

    // Initial call to calculate time left
    calculateTimeLeft();

    // Update every second
    const interval = setInterval(calculateTimeLeft, 1000);

    // Cleanup interval on component unmount
    return () => clearInterval(interval);
  }, [targetDateTime,targetDateMotiEnd]);
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
      <motion.div {...handlers}>
        {new_message !== "" ? (
          <div className="messageAp">
            <span>{new_message}</span>
          </div>
        ) : (
          ""
        )}
        <div className="header">
          <div className="item">
            <p>{GameName}</p>
            <div className="TimingCont">
              <p>Game Last Time</p>
              <p>{timeLeft}</p>
            </div>
          </div>
        </div>
        <div className="game-list">
          <div className="games activeMessgae">
            Moti Jodi Last Time : {targetDateMotiEnd}
          </div>
        </div>
        {children}
      </motion.div>
    </>
  );
};

export default GamePageLayout;
