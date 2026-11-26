import React, { createContext, useState } from "react";
import axios from "axios";
export const AuthContext = createContext();
export const base_url = process.env.REACT_APP_API_URL;
export const app_url = process.env.REACT_APP_APP_URL;
export const app_name = process.env.REACT_APP_APP_NAME;
export const TelegramLink = "https://t.me/+447426400980";
export const WhatsappMessage = `https://api.whatsapp.com/send?text=${encodeURIComponent(
    `नमस्कार दोस्तों,  इस एप्लीकेशन में आपको जो सबसे ज्यादा रेट मिलता है ₹10 के 980 और सबसे ज्यादा टाइमिंग।
and support https://user.${app_url}
मोबाइल एप्लीकेशन लिंक ये नीचे है, इस लिंक पर क्लिक करके डाउनलोड करो 👇🏼👇🏼👇🏼 👇🏼
https://${app_url}`)}`;
export const WhatsappMessageSupport = "https://wa.me/+447426400980";
export const AuthProvider = ({ children }) => {
  const loginstatus = localStorage.getItem("login");
  const TokenName = "90XBLACKDOG";
  const TokenVal = "08b657cc840fcbe8bb7d9ed4499ce18c";
  const [isAuthenticated, setIsAuthenticated] = useState(
    loginstatus && loginstatus !== "" ? true : false
  );

//   const WhastappMessage = () => {
//     let data = new FormData();
//     data.append(TokenName, TokenVal);
//     data.append("user_id", loginstatus);
//     axios({
//       method: "POST",
//       url: base_url + "Webservices/profile",
//       data: data,
//     }).then((response) => {
//         let reffercode = response.data.data[0].mobile
//         return `https://api.whatsapp.com/send?text=${encodeURIComponent(
//             `नमस्कार दोस्तों,  इस एप्लीकेशन में आपको जो सबसे ज्यादा रेट मिलता है ₹10 के 980 और सबसे ज्यादा टाइमिंग।
//             and support https://user.${app_url}
//             मोबाइल एप्लीकेशन लिंक ये नीचे है, इस लिंक पर क्लिक करके डाउनलोड करो 👇🏼👇🏼👇🏼 👇🏼
//             https://${app_url}
//             रेफर कोड मांगे तो ये लगा देना: My Refer Code: ${reffercode}`
//           )}`;
//       })
//       .catch((error) => {
//         // callback(error, false);
//         console.error("Error fetching data:", error);
//       });
//   }

const WhastappMessage = async () => {
    try {
        let data = new FormData();
        data.append(TokenName, TokenVal);
        data.append("user_id", loginstatus);

        const response = await axios({
            method: "POST",
            url: base_url + "Webservices/profile",
            data: data,
        });

        let reffercode = response.data.data[0].mobile;

        return `https://api.whatsapp.com/send?text=${encodeURIComponent(
            `नमस्कार दोस्तों,  इस एप्लीकेशन में आपको जो सबसे ज्यादा रेट मिलता है ₹10 के 950 और सबसे ज्यादा टाइमिंग। and support https://user.${app_url}  
            मोबाइल एप्लीकेशन लिंक ये नीचे है, इस लिंक पर क्लिक करके डाउनलोड करो 👇🏼👇🏼👇🏼👇🏼  
            https://${app_url}  

            रेफर कोड मांगे तो ये लगा देना: My Refer Code: ${reffercode}`
        )}`;
    } catch (error) {
        console.error("Error fetching data:", error);
        return null; // Return null in case of an error
    }
};



  const login = (datas, callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    Object.entries(datas).forEach(([key, value]) => {
      data.append(key, value);
    });
    axios({
      method: "POST",
      url: base_url + "Webservices/login",
      data: data,
    })
      .then((response) => {
        if (
          response.data.status === 1 &&
          response.data.id &&
          response.data.id !== ""
        ) {
          localStorage.setItem("login", response.data.id);
          setIsAuthenticated(true);
        }
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const getMarkets = (callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    axios({
      method: "POST",
      url: base_url + "Webservices/game_list",
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const profile = (callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    data.append("user_id", loginstatus);
    axios({
      method: "POST",
      url: base_url + "Webservices/profile",
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const FSC_KEY_UPDATE = (token, callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    data.append("user_id", loginstatus);
    data.append("token", token);
    axios({
      method: "POST",
      url: base_url + "Webservices/updatefcm",
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const Gameinfo = (id, callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    data.append("game_id", id);
    axios({
      method: "POST",
      url: base_url + "Webservices/game_details",
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const Addbet = (id, type, arrval, grandTotal, callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    data.append("user_id", loginstatus);
    data.append("game_id", id);
    data.append("game_type", type);
    data.append("bet_numbers", arrval);
    data.append("grand_total", grandTotal);

    axios({
      method: "POST",
      url: base_url + "Webservices/game_bet",
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const CancelBet = (id, callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    data.append("user_id", loginstatus);
    data.append("game_id", id);
    axios({
      method: "POST",
      url: base_url + "Webservices/game_bet_cancel",
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const Transactiondata = (category, pageNo, callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    data.append("user_id", loginstatus);
    let URL = "Webservices/transaction_history";
    if (category === "market" || category === "marketcancel") {
      URL = "Webservices/my_bets";
    }
    if (category === "marketcancel") {
      URL = "Webservices/my_bets_cancel";
    }
    axios({
      method: "POST",
      url: base_url + URL,
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const GameRule = (callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    data.append("user_id", loginstatus);
    axios({
      method: "POST",
      url: base_url + "Webservices/game_rule",
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const AddMoney = (grandTotal, callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    data.append("user_id", loginstatus);
    data.append("amount", grandTotal);
    axios({
      method: "POST",
      url: base_url + "Webservices/add_money_gateway",
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const WithdrawMoney = (
    Amount,
    Password,
    BankCard,
    Account,
    Ifsc,
    AccountHolder,
    BankName,
    callback
  ) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    data.append("user_id", loginstatus);
    data.append("amount", Amount);
    data.append("number", Password);
    data.append("type", BankCard);
    data.append("bank_ac_no", Account);
    data.append("ifsc_code", Ifsc);
    data.append("ac_holder_name", AccountHolder);
    data.append("bank_name", BankName);
    axios({
      method: "POST",
      url: base_url + "Webservices/withdraw_money",
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const GameChart = (game_id, callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    data.append("user_id", loginstatus);
    data.append("game_id", game_id);
    let URL = "Webservices/game_chart";
    axios({
      method: "POST",
      url: base_url + URL,
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching data:", error);
      });
  };
  const PromotionData = (callback) => {
    axios
      .get(
        base_url + "api/promotion.php?action=getuserinfo&user=" + loginstatus,
        {
          headers: {
            Accept: "application/json",
          },
        }
      )
      .then((response) => {
        callback(response.data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };
  const BankCardData = (callback) => {
    axios
      .get(base_url + "api/bankcard.php?action=bankcard&user=" + loginstatus, {
        headers: {
          Accept: "application/json",
        },
      })
      .then((response) => {
        callback(response.data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };
  const WithdrawalRequest = (data, callback) => {
    axios
      .post(
        base_url + "api/bankcard.php?action=withdrawal&user=" + loginstatus,
        data
      )
      .then((response) => {
        callback(response.data[0]);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };
  const AddBanksCard = (data, callback) => {
    axios
      .post(
        base_url + "api/game.php?action=addbankcard&user=" + loginstatus,
        data
      )
      .then((response) => {
        callback(response.data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };

  const Gameresult = (gamename, callback) => {
    axios
      .get(
        base_url + "api/game.php?game=" + gamename + "&action=resultrec&page=1",
        {
          headers: {
            Accept: "application/json",
          },
        }
      )
      .then((response) => {
        callback(response.data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };
  const UserGameresult = (gamename, page, callback) => {
    axios
      .get(
        base_url +
          "api/game.php?game=" +
          gamename +
          "&action=betrec&page=" +
          page +
          "&user=" +
          loginstatus,
        {
          headers: {
            Accept: "application/json",
          },
        }
      )
      .then((response) => {
        callback(response.data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };
  const LevelDetail = (level, callback) => {
    axios
      .get(
        base_url +
          "api/game.php?level=" +
          level +
          "&action=inviterecord&page=1&user=" +
          loginstatus,
        {
          headers: {
            Accept: "application/json",
          },
        }
      )
      .then((response) => {
        callback(response.data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };
  const Bankdata = (callback) => {
    axios
      .get(base_url + "api/game.php?action=bankcard&user=" + loginstatus, {
        headers: {
          Accept: "application/json",
        },
      })
      .then((response) => {
        callback(response.data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };
  const sendOTP = (mobile, callback) => {
    axios
      .get(base_url + "api/otp.php?num=" + mobile, {
        headers: {
          Accept: "application/json",
        },
      })
      .then((response) => {
        callback(response.data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };

  const P2PTransferAmount = (data, callback) => {
    axios
      .post(
        base_url + "api/game.php?action=p2ptransfer&user=" + loginstatus,
        data,
        {
          headers: {
            Accept: "application/json",
          },
        }
      )
      .then((response) => {
        callback(response.data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };
  const Register = (data, callback) => {
    axios
      .post(base_url + "api/auth.php?action=addusers", data, {
        headers: {
          Accept: "application/json",
        },
      })
      .then((response) => {
        callback(response.data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };
  const ForgotPassword = (data, callback) => {
    axios
      .post(base_url + "api/auth.php?action=resetpassword", data, {
        headers: {
          Accept: "application/json",
        },
      })
      .then((response) => {
        callback(response.data);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  };
  const logout = () => {
    localStorage.removeItem("login");
    setIsAuthenticated(false);
  };

  const getQrDetails = (callback) => {
    let data = new FormData();
    data.append(TokenName, TokenVal);
    axios({
      method: "POST",
      url: base_url + "Webservices/get_qr_details",
      data: data,
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error fetching QR details:", error);
      });
  };

  const submitQrPayment = (formData, callback) => {
    formData.append(TokenName, TokenVal);
    formData.append("user_id", loginstatus);
    axios({
      method: "POST",
      url: base_url + "Webservices/submit_qr_payment",
      data: formData,
      headers: {
        "Content-Type": "multipart/form-data",
      },
    })
      .then((response) => {
        callback(response.data, true);
      })
      .catch((error) => {
        callback(error, false);
        console.error("Error submitting QR payment request:", error);
      });
  };

  return (
    <AuthContext.Provider
      value={{
        WhatsappMessageSupport,
        isAuthenticated,
        FSC_KEY_UPDATE,
        WhatsappMessage,
        login,
        Register,
        ForgotPassword,
        GameRule,
        logout,
        base_url,
        app_url,
        app_name,
        TelegramLink,
        getMarkets,
        Bankdata,
        GameChart,
        profile,
        AddMoney,
        WithdrawMoney,
        PromotionData,
        WithdrawalRequest,
        LevelDetail,
        AddBanksCard,
        CancelBet,
        P2PTransferAmount,
        BankCardData,
        WhastappMessage,
        loginstatus,
        Gameresult,
        Gameinfo,
        Addbet,
        UserGameresult,
        Transactiondata,
        sendOTP,
        getQrDetails,
        submitQrPayment,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};
