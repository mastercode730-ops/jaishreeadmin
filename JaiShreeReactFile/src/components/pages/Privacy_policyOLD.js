import { useState,useContext } from "react";
import { motion } from "framer-motion";
import Footer from "../../includes/footer-tab";
import Preloader from "../Preloader";
import BackButton from "../../includes/Back_btn";
import { AuthContext } from "../../context/AuthContext";

function Privacypolicy() {
  const [preloader] = useState(false);
  const { WhatsappMessageSupport } = useContext(AuthContext);
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
            <p>Contact Us</p>
          </div>
        </div>
        <div data-v-4af9315c="" className="content">
          <h1 data-v-4af9315c="">
            सब भाई ये फ़ोन पेश से पेमेंट किया करो ऑटोमैटिक जुड़ जाता है अपलोड
            करने के बाद
          </h1>
          <h2
            data-v-4af9315c=""
            style={{ marginTop: "100px", textAlign: "center" }}
          >
            Need Help (मदद की जरूरत है)
          </h2>
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

          {/* <div className="input_box_btn">
            <button
              className="login_btn ripple"
              onClick={() => {
                window.open("tel:+919979068000");
              }}
            >
              Call Now
            </button>
          </div> */}
        </div>
      </motion.div>
      <Footer page="login" />
    </>
  );
}

export default Privacypolicy;
