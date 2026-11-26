import React from 'react';
import { MdOutlineDelete } from "react-icons/md";
import styles from '../assets/css/Transaction.module.css'; // Import the SAME CSS module

const Table = ({ key, data,purpose }) => {
  function capitalize(str) {
    if (typeof str !== 'string') return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
  }
  const creditKeywords = [
    "added", "credited", "received", "deposited", "credited to your account", "Kabad Cancel", "earned", "withdrawal request has been Rejected"
  ];
  const isCreditAction = (text) => {
    const containsCreditKeyword = creditKeywords.some(keyword => text.toLowerCase().includes(keyword.toLowerCase()));

    // Both conditions must be true: currency amount and a credit-related keyword
    return containsCreditKeyword;
  }
  if(data.add_amount === "0"){
    return (<></>);
  }
  if(purpose === "win"){
    const textt = data.description;
    const containsCreditKeywordforWin = ["earned"].some(keyword => textt.toLowerCase().includes(keyword.toLowerCase()));
    if(!containsCreditKeywordforWin){
      return (<></>);
    }
  }
  let Status = (isCreditAction(data.description));
  if(data.purpose != ""){
    Status = data.purpose == 'credit' ? true : false;
  }

  return (
    // Use the card style defined in Transaction.module.css
    <div className={styles.historyCard}>
        <div className={styles.cardRow}>
            <span className={styles.gameDetails}>
            {capitalize(data.description)}
            </span>
            <span className={`${styles.amount} ${Status ? styles.credit : styles.debit}`}>
                {Status ? "+" : "-"}₹ {data.add_amount}
            </span>
        </div>
    </div>
  );
};

export default Table;