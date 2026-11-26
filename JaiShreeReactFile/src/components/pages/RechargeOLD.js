import { useState, useEffect, useContext } from "react";
import { motion } from "framer-motion";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import Footer from "../../includes/footer-tab";
import Preloader from "../Preloader";
import BackButton from "../../includes/Back_btn";
import { AuthContext } from "../../context/AuthContext";
import { useNavigate, Link } from "react-router-dom";

function Recharge() {
  const navigate = useNavigate();
  const { isAuthenticated, profile, AddMoney } =
    useContext(AuthContext);
  const [preloader, setpreloader] = useState(true);
  const [Balance, setBalance] = useState(0);
  const [Amount, setAmount] = useState("");
  const [Minimum, setMinimum] = useState(100);
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
  const handleRecharge = () => {
    if (Amount >= Minimum) {
        AddMoney(Amount,(res)=>{
            if(res.status === 0){
                message(0,res.msg);
                return;
            }
            window.location.href =(res.url);
        });
    } else {
      message(0, "Please enter amount greater than " + Minimum);
    }
  };
  useEffect(() => {
    profile((data) => {
      setpreloader(false);
      if (data.error) {
        message(0, data.message);
      } else {
        setBalance(data.data[0].wallet);
        setMinimum(data.user_Data[5].minimum_recharge);
      }
    });
  }, [profile]);

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
            <p>Recharge</p>
            <Link to="#/history/recharge" className="historyright">
              {/* <AiOutlineHistory /> */}
            </Link>
          </div>
        </div>
        <div className="recharge recharge_box">
          <img src="/favicon.png" className="recharge_img_logo" alt="Aghori Matka"/>
          <div className="bonus-tag">Balance: ₹{Balance}</div>

          <label class="custom-field one">
            <input
              type="tel"
              onInput={(event) => {
                setAmount(event.target.value!== "" ? parseFloat(event.target.value) : "");
              }}
              placeholder="&nbsp;"
              value={Amount}
            />
            <span class="placeholder">Enter Amount</span>
            <span class="border"></span>
          </label>
          <div className="recommended-amount">
            <button
              className="login_btn ripple"
              onClick={() => {
                setAmount(100);
              }}
            >
              ₹100
            </button>
            <button
              className="login_btn ripple"
              onClick={() => {
                setAmount(200);
              }}
            >
              ₹200
            </button>
            <button
              className="login_btn ripple"
              onClick={() => {
                setAmount(300);
              }}
            >
              ₹300
            </button>
            <button
              className="login_btn ripple"
              onClick={() => {
                setAmount(500);
              }}
            >
              ₹500
            </button>
            <button
              className="login_btn ripple"
              onClick={() => {
                setAmount(1000);
              }}
            >
              ₹1000
            </button>
            <button
              className="login_btn ripple"
              onClick={() => {
                setAmount(2000);
              }}
            >
              ₹2000
            </button>
          </div>
          <div className="input_box_btn">
            <button className="login_btn ripple" onClick={handleRecharge}>
              PAY BY UPI
            </button>
          </div>
        </div>
      </motion.div>
      <Footer page="recharge" />
    </>
  );
}

export default Recharge;
