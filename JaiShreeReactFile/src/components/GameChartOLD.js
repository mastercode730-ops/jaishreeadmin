import { useState, useContext, useEffect } from "react";
import { motion } from "framer-motion";
import Footer from "../includes/footer-tab";
import Preloader from "./Preloader";
import BackButton from "../includes/Back_btn";
import { useParams, useNavigate } from "react-router-dom";
import { IoIosArrowBack, IoIosArrowForward } from "react-icons/io";
import { AuthContext } from "../context/AuthContext";
function GameChart() {
  const { id } = useParams();
  const [preloader, setpreloader] = useState(true);
  const [data, setdate] = useState([]);
  const [pagenumber, setpagenumber] = useState(1);
  const [Name, setName] = useState("");
  const [new_message, setnew_message] = useState("");
  const { isAuthenticated, GameChart } = useContext(AuthContext);
  const navigate = useNavigate();
  if (!isAuthenticated) {
    navigate("/login");
  }
  function capitalize(str) {
    if (typeof str !== "string") return "";
    return str.charAt(0).toUpperCase() + str.slice(1);
  }
  const back = () => {
    // if (pagenumber > 1) {
    //   Transactiondata(id, pagenumber, (res) => {
    //     setdate(res);
    //     setpreloader(false);
    //   });
    //   setpagenumber(pagenumber - 1);
    // } else {
    //   setnew_message("Last page");
    //   setTimeout(() => {
    //     setnew_message("");
    //   }, 2000);
    // }
  };
  const next = () => {
    // Transactiondata(id, pagenumber, (res) => {
    //   setdate(res);
    //   setpreloader(false);
    // });
    // setpagenumber(pagenumber + 1);
  };
  useEffect(() => {
    setpreloader(false);
    GameChart(id, (res) => {
      if (res.status === 0) {
        setnew_message(res.msg);
        setTimeout(() => {
          setnew_message("");
        }, 2000);
        return;
      }
      setdate(res.chart);
      setName(res.name);
      setpreloader(false);
    });
  }, [GameChart, id]);

  return (
    <>
      <Preloader status={preloader} />
      <motion.div
        initial={{ translateX: -100 }}
        animate={{ translateX: 0 }}
        exit={{ translateX: 0 }}
        transition={{ duration: 0.2 }}
      >
        {new_message !== "" ? (
          <div className="message">
            <span>{new_message}</span>
          </div>
        ) : (
          ""
        )}
        <div className="login header">
          <div className="item">
            <p style={{width:"100%",fontSize:"17px"}}>{capitalize(Name)} RESULT CHART</p>
          </div>
        </div>
          <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Number</th>
            </tr>
          </thead>
          <tbody style={{fontWeight:900}}>
            {data.map((element, i) => (
              <tr key={i}>
                <td>{element.date}</td>
                <td>{element.number}</td>
              </tr>
            ))}
          </tbody>
        </table>
        <div class="pagination">
          <ul class="page_box">
            <li class="page">
              <span>1-10</span>of {pagenumber}
            </li>
            <li class="page_btn">
              <IoIosArrowBack
                onClick={() => {
                  back();
                }}
              />
              <IoIosArrowForward
                onClick={() => {
                  next();
                }}
              />
            </li>
          </ul>
        </div>
      </motion.div>
      <Footer page="transaction" />
    </>
  );
}

export default GameChart;
