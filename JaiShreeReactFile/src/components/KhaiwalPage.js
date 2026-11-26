import { React,useState, useContext, useEffect } from "react";
import { motion } from "framer-motion";
import { Link, useNavigate } from "react-router-dom";
import Footer from "../includes/footer-tab";
import { FaWhatsapp } from "react-icons/fa";
import { MdOutlineAddCard } from "react-icons/md";
import { GrAnnounce } from "react-icons/gr";
import { BiMoneyWithdraw } from "react-icons/bi";
import { FaChartLine, FaPlay } from "react-icons/fa";
import { AuthContext } from "../context/AuthContext";
import { ToastContainer, toast } from "react-toastify";
import Preloader from "./Preloader";
import { GrMenu } from "react-icons/gr";
import { MdNotificationsActive } from "react-icons/md";
import "react-responsive-carousel/lib/styles/carousel.min.css"; // requires a loader
import { Carousel } from "react-responsive-carousel";

function KhaiwalPage() {
  const [preloader, setpreloader] = useState(true);
  const [MarketData, setMarketData] = useState([]);
  const [UpcommingMarketData, setUpcommingMarketData] = useState([]);
  const [Walletd, setWalletd] = useState(0);
  const [banner, setbanner] = useState([]);
  const [LastResult, setLastResult] = useState();
  const [NoticeData, setNoticeData] = useState("");
  const [NoticeStatus, setNoticeStatus] = useState(true);
  const [LastResultNumber, setLastResultNumber] = useState();
  const { app_name, getMarkets,isAuthenticated, profile, WhatsappMessageSupport, base_url } = useContext(AuthContext);
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
  };
  useEffect(() => {
    const today = new Date().toDateString(); // Get today's date as a string
    const savedDate = localStorage.getItem("noticeDate");
    const savedStatus = localStorage.getItem("noticeStatus");

    // Check if the notice is already dismissed today
    if (savedDate === today && savedStatus === "false") {
      setNoticeStatus(false);
    } else {
      // Reset for a new day
      localStorage.setItem("noticeDate", today);
      localStorage.setItem("noticeStatus", "true");
      setNoticeStatus(true);
    }
  }, []);
  const handleCloseNotice = () => {
    setNoticeStatus(false);
    localStorage.setItem("noticeStatus", "false"); // Save the updated status in localStorage
  };

  useEffect(() => {
    getMarkets(function (response, boolRes) {
      setpreloader(false);
      const Latdata = response.LastResult[0];
      let LastResultName = Latdata.game_name;
      let LastResultNumber = Latdata.number>9?Latdata.number:'0'+Latdata.number;
      setLastResult(LastResultName+" रिजल्ट ");
      setLastResultNumber(LastResultNumber);
      if (!boolRes && response.response.status !== 200) {
        message(0, response.response.data.error);
        // return;
      } else if (response.status === 0) {
        message(0, response.msg);
      } else {
        setMarketData(response.running_game);
        const sortedData = response.upcomming_game
    .sort((a, b) => {
        // First, compare by sequence in ascending order
        if (a.sequence !== b.sequence) {
            return a.sequence - b.sequence;
        }

        // If sequences are equal, sort by datetime in ascending order
        const dateA = new Date(a.datetime);
        const dateB = new Date(b.datetime);
        return dateA - dateB;
    });
        setUpcommingMarketData(sortedData);
        setbanner(response.banner);
        setNoticeData(response.notice[0].text_msg)
      }
    });
    profile(function (response) {
      setpreloader(false);
      if (response.status === 0) {
        message(0, response.msg);
      } else {
        let walletdata = response.data[0].wallet;
        setWalletd(walletdata);
      }
    });
  }, [getMarkets,profile]);

  return (
    <div className="welcome">
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
        <div className="welcome header">
          <Link to="/mine" className="HeaderMenuIcon">
            <GrMenu />
          </Link>
          <div className="part1">
            <p className="HeaderText text">{app_name}</p>
          </div>
          <Link to="" className="WalletInHeader">
            {Walletd} ₹
          </Link>
          <Link to="" className="download">
            <MdNotificationsActive />
          </Link>
        </div>
<iframe className="KhaiwalIframe" src="https://aghorimatka.in/"></iframe>

      </motion.div>
      <Footer page="home" />
    </div>
  );
}

export default KhaiwalPage;
