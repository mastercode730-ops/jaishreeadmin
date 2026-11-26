import { useState, useContext, useEffect } from "react";
import { motion } from "framer-motion";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { useNavigate } from "react-router-dom";
import Preloader from "./Preloader";
import { AuthContext } from "../context/AuthContext";
import { useLocation } from 'react-router-dom';
function useQuery() {
  return new URLSearchParams(useLocation().search);
}
function Login() {
  const [preloader, setpreloader] = useState(false);
  const [username, setusername] = useState("");
  const [password, setpassword] = useState("");
  const [refferal_code, setrefferal_code] = useState("");
  const [ButtonName, setButtonName] = useState("Send OTP");
  const [OtpContainer, setOtpContainer] = useState(false);
  const [RefferalContainer, setRefferalContainer] = useState(false);
  const query = useQuery();
  const agentId = query.get('agent');
  const { isAuthenticated, login, WhatsappMessageSupport } =
    useContext(AuthContext);
  const navigate = useNavigate();

  useEffect(() => {
    if (isAuthenticated) {
      navigate("/dashboard");
    }
  }, [isAuthenticated]);
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
  const handleLogin = () => {
    if (username === "") {
      message(0, "Please enter Mobile Number.");
      return;
    }
    if (!OtpContainer) {
      let data = {
        mobile: username,
      };
      setpreloader(true);
      login(data, function (response) {
        if (response.status === 0) {
          setpreloader(false);
          message(0, response.msg);
        } else {
          setOtpContainer(true);
          setButtonName("Verify OTP & Login");
          setpreloader(false);
          message(1, response.msg);
        }
        if (response.purpose == 1) {
          setRefferalContainer(true);
        }
      });
      return;
    } else {
      if (password === "") {
        message(0, "Please enter OTP.");
        return;
      } else {
        let data = {
          mobile: username,
          otp: password,
          refferal: refferal_code
        };
        if(agentId){
          data['agentId'] = agentId
        }
        setpreloader(true);
        login(data, function (response) {
          if (response.status === 0) {
            setpreloader(false);
            message(0, response.msg);
          } else {
            setpreloader(false);
            message(1, response.msg);
          }
        });
        return;
      }
    }
  };

  if (isAuthenticated) {
    navigate("/dashboard");
  }
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
        <div className="aghorilogocontainer">
          <img src="/favicon.png" className="aghorilogo" alt="Logo" />
        </div>
        <p className="texttitle">AGHORI MATKA</p>
        <div className="recharge_box">
          <label class="custom-field one">
            <input
              type="tel"
              onInput={(event) => {
                setusername(event.target.value);
              }}
              placeholder="&nbsp;"
            />
            <span class="placeholder">Enter Phone Number</span>
            <span class="border"></span>
          </label>
          {RefferalContainer && (
            <label class="custom-field one">
              <input
                type="tel"
                onInput={(event) => {
                  setrefferal_code(event.target.value);
                }}
                placeholder="&nbsp;"
              />
              <span class="placeholder">Refferal Code</span>
              <span class="border"></span>
            </label>
          )}
          {OtpContainer ? (
            <label class="custom-field one">
            <input
              type="tel"
              onInput={(event) => {
                setpassword(event.target.value);
              }}
              placeholder="&nbsp;"
            />
            <span class="placeholder">OTP</span>
            <span class="border"></span>
          </label>
          ) : (
            ""
          )}
          <div className="input_box_btn">
            <button className="login_btn ripple" onClick={handleLogin}>
              {ButtonName}
            </button>
          </div>
          <div className="input_box_btn">
            <button
              className="login_btn ripple"
              onClick={() => {
                window.open(WhatsappMessageSupport);
              }}
            >
              हेल्प चाहिए WHATSAPP करो
            </button>
          </div>
          <div className="input_box_btn">
            <button
              className="login_btn ripple"
              onClick={() => {
                window.open("https://t.me/aghoribabamatka");
              }}
            >
              हेल्प चाहिए TELEGRAM करो
            </button>
          </div>
        </div>
      </motion.div>
    </>
  );
}

export default Login;
