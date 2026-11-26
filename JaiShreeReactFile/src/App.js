import { useState, useContext, useEffect } from 'react';
import { BiRefresh } from "react-icons/bi";
import { FiVolume2 } from "react-icons/fi";
import { IoTrophySharp } from "react-icons/io5";
import { motion } from "framer-motion";
import { AiOutlinePlus, AiOutlineMinus } from "react-icons/ai";
import { IoIosArrowBack, IoIosArrowForward } from "react-icons/io";
import SingleBetSection from './includes/SingleBetSection';
import { TiTick } from "react-icons/ti";
import $ from 'jquery';
import Footer from './includes/footer-tab';
import Preloader from './components/Preloader';
import { useNavigate } from 'react-router-dom';
import { ToastContainer, toast } from 'react-toastify';
import { AuthContext } from './context/AuthContext';

function App() {
  const [multiplier, setmultiplier] = useState(1);
  const [amount, setamount] = useState(10);
  const [selected_join, setselected_join] = useState('blank');
  const [wallet, setwallet] = useState(0.00);
  const [Period, setPeriod] = useState('Loading..');
  const [CountDownTimerLimit, setCountDownTimerLimit] = useState(180);
  const [DelayTimerLimit, setDelayTimerLimit] = useState(30);
  const [tempbg, settempbg] = useState('default-bg');
  const [current_game, setcurrent_game] = useState('Emerd');
  const [new_message, setnew_message] = useState('');
  const [CurrentTimedistance, setCurrentTimedistance] = useState(0);
  const [preloader, setpreloader] = useState(true);
  const [pagenumber, setpagenumber] = useState(1);
  const [WinningMessage] = useState([]);
  const [UserResultRecord, setUserResultRecord] = useState([]);
  const [CountDownTimer, setCountDownTimer] = useState([0, 0, 0, 0]);
  const [results, setresults] = useState([]);
  const { isAuthenticated, loginstatus, Gameresult, Gameinfo, Addbet, UserGameresult } = useContext(AuthContext);
  const navigate = useNavigate();
  useEffect(() => {
    if (!isAuthenticated) {
      navigate('/login');
    }
  }, [isAuthenticated, navigate]);
  useEffect(() => {
    Gameinfo(current_game, (res) => {
      setPeriod(res[2].period);
      setwallet(res[0].balance);
      setpreloader(false);
      Gameresult(current_game, (res) => {
        setresults(res);
        UserGameresult(current_game, 1, (res) => {
          setUserResultRecord(res);
        });
      });
    });
  }, [Gameresult, Gameinfo, UserGameresult, current_game]);

  const totaldigit = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
  const total_color = [
    {
      'name': 'green',
      'colorclass': 'green'
    },
    {
      'name': 'violet',
      'colorclass': 'violet'
    },
    {
      'name': 'red',
      'colorclass': 'red'
    }
  ];
  const recommended_amount = [10, 100, 1000, 10000];
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
  const gamesselection = (event, name, timer, delay) => {
    setpreloader(true);
    $('.games').removeClass('active');
    event.currentTarget.classList.toggle('active');
    setPeriod('Loading..');
    setCountDownTimerLimit(timer);
    setDelayTimerLimit(delay);
    setcurrent_game(name);
    Gameinfo(name, (res) => {
      setPeriod(res[2].period);
      setwallet(res[0].balance);
      setpreloader(false);
      Gameresult(name, (res) => {
        setresults(res);
      });
    });
  }

  const open_bet_form = (btn_id, classes) => {
    if (CurrentTimedistance >= DelayTimerLimit) {
      $(".recommend_amount").removeClass('active');
      $(".recommend_amount:nth-of-type(1)").addClass('active');
      setselected_join(btn_id);
      settempbg(classes);
      setmultiplier(1);
      setamount(10);
      $(".bet-form-container").addClass('active');
    } else {
      message(0, 'Waiting for next round!');
    }
  }
  const close_bet_form = () => {
    $(".bet-form-container").removeClass('active');
  }
  const increament = () => {
    setmultiplier(multiplier + 1);
  }
  const decreament = () => {
    if (multiplier > 1) {
      setmultiplier(multiplier - 1);
    }
  }
  const selectnumber = (event, number) => {
    $(".recommend_amount").removeClass('active');
    event.currentTarget.classList.toggle('active');
    setamount(number);
  };
  const bettingClose = () => {
    $(".bettingButton").attr('disabled', true);
    // $(".join-element").addClass('hide');
    // $(".count-down-element").removeClass('hide');
  }
  const bettingOpen = () => {
    $(".bettingButton").attr('disabled', false);
    // $(".join-element").removeClass('hide');
    // $(".count-down-element").addClass('hide');
  }
  const addbetNow = () => {
    setpreloader(true);
    let data = {
      username: loginstatus,
      period: Period,
      amount: (amount * multiplier),
      ans: selected_join,
      game: current_game
    }
    Addbet(data, (res) => {
      if (res[0].status !== 0) {
        Gameinfo(current_game, (res) => {
          setwallet(res[0].balance);
          setPeriod(res[2].period);
          UserGameresult(current_game, 1, (res) => {
            setUserResultRecord(res);
          });
        });
        // message(1, 'Bet successfully purchased');
        setnew_message("Bet successfully purchased");
        setTimeout(() => {
          setnew_message("");
        }, 2000);
      } else {
        message(0, res[0].message);
      }
      close_bet_form();
      setpreloader(false);
    });
  }
  const back = () => {
    if (pagenumber > 1) {
      UserGameresult(current_game, pagenumber, (res) => {
        setUserResultRecord(res);
      });
      setpagenumber(pagenumber - 1);
    } else {
      setnew_message("Last page");
      setTimeout(() => {
        setnew_message("");
      }, 2000);
    }
  }
  const next = () => {
    UserGameresult(current_game, pagenumber, (res) => {
      setUserResultRecord(res);
    });
    setpagenumber(pagenumber + 1);
  }
  useEffect(() => {
    const intervalId = setInterval(() => {
      var countDownDate = Date.parse(new Date()) / 1e3;
      var distance = CountDownTimerLimit - countDownDate % CountDownTimerLimit;
      setCurrentTimedistance(Math.floor(distance));
      var i = distance / 60,
        n = distance % 60;
      var minutes = Math.floor(i);
      var seconds = Math.floor(n);
      let seconds1, seconds2;
      if (seconds > 9) {
        seconds1 = (seconds - (seconds % 10)) / 10;
        seconds2 = seconds % 10;
      } else {
        seconds1 = 0;
        seconds2 = seconds;
      }
      setCountDownTimer([0, Math.floor(minutes), seconds1, seconds2]);
      if (distance < DelayTimerLimit && distance > 0) {
        bettingClose();
      } else {
        bettingOpen();
      }
      if (distance >= DelayTimerLimit && distance <= CountDownTimerLimit) {
        bettingOpen();
      }
      if (distance > (CountDownTimerLimit - 3) && distance <= CountDownTimerLimit) {
        setpreloader(true);
        Gameinfo(current_game, (res) => {
          setPeriod(res[2].period);
          setwallet(res[0].balance);
          setpreloader(false);
          Gameresult(current_game, (res) => {
            setresults(res);
            UserGameresult(current_game, 1, (res) => {
              setUserResultRecord(res);
            });
          });
        });
      }
    }, 1000);
    return () => {
      clearInterval(intervalId);
    };
  }, [CountDownTimerLimit, DelayTimerLimit, Gameinfo, Gameresult, UserGameresult, current_game])
  const refreshData = () => {
    setpreloader(true);
    Gameinfo(current_game, (res) => {
      setPeriod(res[2].period);
      setwallet(res[0].balance);
      setpreloader(false);
      Gameresult(current_game, (res) => {
        setresults(res);
        UserGameresult(current_game, 1, (res) => {
          setUserResultRecord(res);
        });
      });
    });
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
        <div className="header">
          <div className="item">
            <p className='available-balance'>Available balance: ₹ {(wallet)}</p>
            <div className='header-button-box'>
              <div>
                <button className='btn-1' onClick={() => { navigate('/recharge') }}>Recharge</button>
                <button onClick={() => { window.location.href = '/tend' }}>Trend</button>
              </div>
              <div className="refresh clickable" onClick={() => { refreshData() }}>
                <BiRefresh></BiRefresh>
              </div>
            </div>
          </div>
        </div>
        {new_message !== "" ?
        <div className="message"><span>{new_message}</span></div>
          : ''
        }
        <div className="game-list">
          <div className='games active' onClick={(event) => { gamesselection(event, 'Parity', 180, 30) }}>🎗️ Parity</div>
          <div className='games' onClick={(event) => { gamesselection(event, 'Bcone', 180, 30) }}>🔥 Bcone</div>
          <div className='games' onClick={(event) => { gamesselection(event, 'Fast Emerd', 60, 15) }}>⚡Fast Emerd</div>
        </div>
        <div className="game-header">
          <div className='items-container'>
            <div>
              <p>
                <div className='periodis-icon'><IoTrophySharp /></div>
                <div>Period</div>
              </p>
              <p className='periodid'>{Period}</p>
            </div>
            <div>
              <p className='justify-content-end'>Count Down</p>
              <p className='count-down'><span>{CountDownTimer[0]}</span><span>{CountDownTimer[1]}</span>:<span>{CountDownTimer[2]}</span><span>{CountDownTimer[3]}</span></p>
            </div>
          </div>
          {
            Array.isArray(WinningMessage) && WinningMessage.length > 0 ?
              <div className="new-cashout-news">
                <span className="icon"><FiVolume2></FiVolume2></span>
                <div>
                  {WinningMessage.map((element, i) => (
                    <p key={i}>
                      Congratulations to {element.number}{" "}
                      <span className='text-danger'>Winning {element.amount}</span>
                    </p>
                  ))}
                </div>
              </div>
              : ''}
        </div>
        <div className="count-down-element hide">
          {CurrentTimedistance} <br /> Waiting for result
        </div>
        <div className="join-element color-container">
          {total_color.map((item, i) => {
            return <button key={i} className={item.colorclass + ' bettingButton'} onClick={() => { open_bet_form(item.name, item.colorclass) }}>Join {item.name}</button>
          })}
        </div>
        <div className="join-element number-container">
          {totaldigit.map((item, i) => {
            return <button key={i} className='digit-button bettingButton' value={item} onClick={() => { open_bet_form(item, 'number-default') }}>{item}</button>
          })}
        </div>
        {/* bet form design */}
        <div className="bet-form-container">
          <div className="bet-form">
            <div className={"header " + tempbg}>Join {selected_join}</div>
            <div className="body">
              <p>Contract Money</p>
              <ul className="recommeded-money">
                {recommended_amount.map((item, i) => {
                  if (i === 0) {
                    return <li key={i} className='recommend_amount active' onClick={(event) => { selectnumber(event, item) }}>{item}</li>
                  } else {
                    return <li key={i} className='recommend_amount ' onClick={(event) => { selectnumber(event, item) }}>{item}</li>
                  }
                })}
              </ul>
              <p>Number</p>
              <div className="multiplier-section">
                <button className="mul-btn decreament" onClick={() => { decreament() }}><AiOutlineMinus /></button>
                <input type="number" className="input-multiplier" value={multiplier} readOnly />
                <button className="mul-btn increament" onClick={() => { increament() }}><AiOutlinePlus /></button>
              </div>
              <p className='mt-5'>Total contract money is {multiplier * amount}</p>
              <div className="agree_box">
                <div role="checkbox" tabIndex="0" aria-checked="true" className="van-checkbox">
                  <div className="van-checkbox__icon van-checkbox__icon--square van-checkbox__icon--checked">
                    <i className="van-icon van-icon-success">
                      <TiTick />
                    </i>
                  </div>
                  <span className="van-checkbox__label">I agree <span className="greencolor">PRESALE RULE</span></span>
                </div>
              </div>
              <div className="action-btn">
                <div onClick={() => { close_bet_form() }}>CANCEL</div>
                <div onClick={() => { addbetNow() }}>CONFIRM</div>
              </div>
            </div>
          </div>
        </div>
        <div className="record-header">
          <img src="/icons_asset/win.png" alt="" />
          <div className='font-small'>{current_game == "Emerd"? "Parity" : current_game} record</div>
        </div>
        <table className='record-table'>
          <tr>
            <th>Period</th>
            <th>Price</th>
            <th>Number</th>
            <th>Result</th>
          </tr>
          {
            Array.isArray(results) ?
              results.map((element, i) => (
                <tr className={element.clo + element.res1 + '-ball'}>
                  <td>{element.period}</td>
                  <td>{element.num}</td>
                  <td>{element.ans}</td>
                  <td>
                    <span className="color-ball-container"></span>
                    {element.res1 !== '' ?
                      <span className="color-ball-container"></span>
                      : ''
                    }
                  </td>
                </tr>
              ))
              : ''
          }
        </table>
        <div className="record-header">
          <img src="/icons_asset/record.png" alt="" />
          <div className='font-small'>My Record</div>
        </div>
        <div className="my-record-body mine list-of-options">
          {
            Array.isArray(UserResultRecord) ?
              UserResultRecord.map((element, i) => (
                <SingleBetSection key={i} data={element} />
              ))
              : ''
          }
        </div>
        <div class="pagination">
          <ul class="page_box">
            <li class="page">
              <span>1-10</span>of {pagenumber}
            </li>
            <li class="page_btn">
              <IoIosArrowBack onClick={() => { back() }} />
              <IoIosArrowForward onClick={() => { next() }} />
            </li>
          </ul>
        </div>
      </motion.div>
      <Footer page="win" />
    </>
  );
}

export default App;
