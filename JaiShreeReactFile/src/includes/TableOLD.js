import React from 'react';

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
    <div>
      <div className="trn-container" key={key}>
        <div className="left" style={{width:'65%'}}>
          <p>{capitalize(data.description)}</p>
          <p>{data.date+" "+data.time}</p>
        </div>
        <div className="right" style={{width:'max-content'}}>
          <p style={{color:(Status ? "green" : "red")}}>{Status ? "+" : "-"}₹ {data.add_amount}</p>
        </div>
      </div>
    </div>
  );
};

export default Table;
