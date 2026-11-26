import React from 'react';
import { MdOutlineDelete } from "react-icons/md";

const MarketTable = ({ key, data, purpose, Oncancelfun }) => {
  return (
    <div>
      <div className="trn-container" key={key}>
        <div className="left" style={{ width: '65%' }}>
          <p>{data.game_name} - {data.type === "1" ? "JODI" : (data.type === "2" ? " ANDER H" : "BAHAR H")} - {data.game_number}</p>
          <p>{data.created_at}</p>
        </div>
        <div className="right" style={{ width: 'max-content',textAlign:'right'}}>
          <p style={{color:'red'}}>-₹ {data.money}</p>
          {
            purpose == "market" && data.isResult == "0" ?
            <p className='cancelbtn' onClick={() => Oncancelfun(data.id)}><MdOutlineDelete /></p>
            : ""
          }
        </div>
      </div>
    </div>
  );
};

export default MarketTable;
