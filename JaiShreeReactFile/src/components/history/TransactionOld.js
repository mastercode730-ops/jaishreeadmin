import { useState, useContext, useEffect } from "react";
import { motion } from "framer-motion";
import Footer from "../../includes/footer-tab";
import Preloader from "../Preloader";
import BackButton from "../../includes/Back_btn";
import Table from "../../includes/Table";
import MarketTable from "../../includes/MarketTable";
import { useParams, useNavigate } from "react-router-dom";
import { IoIosArrowBack, IoIosArrowForward } from "react-icons/io";
import { AuthContext } from "../../context/AuthContext";
function Transaction() {
  const { id } = useParams();
  const [preloader, setpreloader] = useState(true);
  const [data, setdate] = useState([]);
  const [pagenumber, setpagenumber] = useState(1);
  const [new_message, setnew_message] = useState("");
  const { isAuthenticated, Transactiondata, CancelBet } =
    useContext(AuthContext);
  const navigate = useNavigate();
  if (!isAuthenticated) {
    navigate("/login");
  }
  function capitalize(str) {
    if (typeof str !== "string") return "";
    return str.charAt(0).toUpperCase() + str.slice(1);
  }
  const back = () => {
    if (pagenumber > 1) {
      Transactiondata(id, pagenumber, (res) => {
        setdate(res);
        setpreloader(false);
      });
      setpagenumber(pagenumber - 1);
    } else {
      setnew_message("Last page");
      setTimeout(() => {
        setnew_message("");
      }, 2000);
    }
  };
  const next = () => {
    Transactiondata(id, pagenumber, (res) => {
      setdate(res);
      setpreloader(false);
    });
    setpagenumber(pagenumber + 1);
  };
  useEffect(() => {
    setpreloader(false);
    Transactiondata(id, 1, (res) => {
      if (res.status === 0) {
        setnew_message(res.msg);
        setTimeout(() => {
          setnew_message("");
        }, 2000);
        return;
      }
      setdate(res.data);
      setpreloader(false);
    });
  }, [Transactiondata, id]);
  const deleteBet = (gameId) => {
    CancelBet(gameId, (res) => {
      if (res.status === 1) {
        window.location.reload();
        setnew_message(res.msg);
        setTimeout(() => {
          setnew_message("");
        }, 2000);
      }
    });
  };

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
            <BackButton />
            <p>{capitalize(id)} history</p>
          </div>
        </div>
        {id === "market" || id === "marketcancel"
          ? data.map((element, i) => (
              <MarketTable
                key={i}
                data={element}
                purpose={id}
                Oncancelfun={deleteBet}
              />
            ))
          : data.map((element, i) => (
              <Table key={i} data={element} purpose={id} />
            ))}
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

export default Transaction;
