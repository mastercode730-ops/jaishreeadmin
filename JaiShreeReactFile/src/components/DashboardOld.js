import { React,useState, useContext, useEffect } from "react";
import { initializeFirebase, requestNotificationPermission, getFirebaseMessaging } from "../firebase";
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
import "react-responsive-carousel/lib/styles/carousel.min.css"; // requires a loader
import { Carousel } from "react-responsive-carousel";
import { onMessage } from 'firebase/messaging';

function Dashboard() {
  const [preloader, setpreloader] = useState(true);
  const [MarketData, setMarketData] = useState([]);
  const [UpcommingMarketData, setUpcommingMarketData] = useState([]);
  const [Walletd, setWalletd] = useState(0);
  const [banner, setbanner] = useState([]);
  const [LastResult, setLastResult] = useState();
  const [NoticeData, setNoticeData] = useState("");
  const [NoticeStatus, setNoticeStatus] = useState(true);
  const [LastResultNumber, setLastResultNumber] = useState();
  const { app_name, getMarkets,isAuthenticated, profile, WhatsappMessageSupport, base_url, FSC_KEY_UPDATE } = useContext(AuthContext);
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
    initializeFirebase();
    requestNotificationPermission()
      .then((token) => {
        if (token) {
          FSC_KEY_UPDATE(token,(d)=>{
            // alert(token);
            console.log(d);
          });
        }
      })
      .catch((error) => {
        console.error("Error getting FCM token:", error);
      });

      // const messaging = getFirebaseMessaging(); // Get the messaging instance

    // if (messaging) {
    //   onMessage(messaging, (payload) => {
    //     console.log("Message received in foreground:", payload);

    //     new Notification(payload.notification.title, {
    //       body: payload.notification.body,
    //       icon: payload.notification.icon || "/favicon.png",
    //     });
    //   });
    // } else {
    //   console.log("Firebase Messaging is not initialized.");
    // }
  }, []);
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
      const Latdata = response.LastResult[0] ?? 'XX';
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
          {/* <Link to="" className="download">
            <MdNotificationsActive />
          </Link> */}
        </div>
        <Carousel
          showThumbs={false}
          showStatus={false}
          infiniteLoop={true}
          showArrows={false}
          autoPlay={true}
        >
          {banner.map((market) => (
          <div>
              <img src={base_url+market.banner_img} alt="help"/>
          </div>
            ))}
        </Carousel>
        <div className="buttonCollection">
        <button onClick={()=>{window.open(WhatsappMessageSupport)}}>
            <FaWhatsapp /> <span>WHATSAPP</span>
        </button>
        <button 
          onClick={() => {
            navigate('/recharge');
          }}
        >
            <MdOutlineAddCard />
            <span>ADD FUND</span>
          </button>
          <button 
          onClick={() => {
            navigate('/withdrawal');
          }}
        >
            <BiMoneyWithdraw />
            <span>WITHDRAW</span>
          </button>
        </div>
        <div className="buttonCollection">
          <button className="m-0 topResult" onClick={()=>{window.location.href='#'}}>
            HOW TO PLAY
          </button>
        </div>
        {/* <div className="buttonCollection">
          <button className="m-0 topResult">
            <p> 🔱 {LastResult} <span className="bgredwithwhite">{LastResultNumber}</span>🔱 </p>
            <p>🔥सबसे पहले रिजल्ट देखने के लिए क्लिक करे🔥 </p>
            <Link onClick={()=>{window.location.href='https://aghorimatka.com/'}}>  क्लिक लिंक</Link>
          </button>
        </div> */}
        <p className="notice">
          <GrAnnounce />
          <marquee>
          WELCOME TO MAHADEV MATKA
          </marquee>
        </p>
        <div className="bazar">
          {NoticeStatus ?
          <div className="NoticeDiv">
            <p className="mainp">
            <img className="mainimg" onClick={handleCloseNotice} src="https://static.vecteezy.com/system/resources/previews/018/887/462/non_2x/signs-close-icon-png.png" />
            <span dangerouslySetInnerHTML={{ __html: NoticeData }}></span>
            </p>
          </div>
          : ""}
          {UpcommingMarketData.map((market) => (
            <div className="item" key={market.game_id}>
              <div className="part partA">
                <p className="name">{market.game_name.toUpperCase()}</p>
                <Link to={"/gamechart/"+market.match_id} className="gameChartResult">
                  <p className="ResultNumber">{market.result}</p>
                  <FaChartLine />
                  <span>Game Chart</span>
                </Link>
                {
                  market.active ?
                  <Link to={"/play/"+market.game_id} className="PlayTimeIn">
                PLAY <FaPlay />
              </Link> : 
                <Link className="PlayTimeOut">TIME OUT</Link>
                }
              </div>
              <div className="part partB">
                <p>OPEN: {market.start_time}</p>
                <p>CLOSE: {market.end_time}</p>
              </div>
            </div>
          ))}
          {MarketData.map((market) => (
            <div className="item" key={market.game_id}>
              <div className="part partA">
                <p className="name">{market.game_name.toUpperCase()}</p>
                <Link to={"/gamechart/"+market.game_id} className="gameChartResult">
                  <p className="ResultNumber">{market.result}</p>
                  <FaChartLine />
                  <span>Game Chart</span>
                </Link>
                <Link to={"/play/"+market.game_id} className="PlayTimeIn">
                PLAY <FaPlay />
              </Link>
              </div>
              <div className="part partB">
                <p>OPEN: {market.start_time}</p>
                <p>CLOSE: {market.end_time}</p>
              </div>
            </div>
          ))}
        </div>
      </motion.div>
      <Footer page="home" />
    </div>
  );
}

export default Dashboard;
