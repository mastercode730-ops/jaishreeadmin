import React, { useState } from 'react';
import { AiOutlineDown } from "react-icons/ai";
import { AiOutlineUp } from "react-icons/ai";

const SingleBetSection = ({ data }) => {
    const [isOpen, setIsOpen] = useState(false);

    const toggleMenu = () => {
        setIsOpen(!isOpen);
    };
    return (
        <div className='items ripple'>
            <div className='sub-item' onClick={toggleMenu}>
                <div className='first-item'>
                    <p>{data.period}</p>
                    <p className={data.res}>{data.res}</p>
                    <p className={data.res}>{data.res === 'success' ? '+' : '-'}{data.res === 'success' ? data.amount : data.amount - (data.amount / 100 * data.tax)}</p>
                    <p>{data.game == "Emerd" ? "Parity" : data.game}</p>
                </div>
                {isOpen ?
                    <span className='moreicon'><AiOutlineUp /></span>
                    :
                    <span className='moreicon'><AiOutlineDown /></span>
                }
            </div>
            {isOpen ?
                <ul className="dropdown-menu">
                    <p className='title'>Period Detail</p>
                    <table>
                        <tr>
                            <td>Period</td>
                            <td>{data.period}</td>
                        </tr>
                        <tr>
                            <td>Contract Money</td>
                            <td>{data.bamount}</td>
                        </tr>
                        <tr>
                            <td>Delivery</td>
                            <td>{data.bamount / 100 * data.tax}</td>
                        </tr>
                        <tr>
                            <td>Open Price</td>
                            <td>{data.price}</td>
                        </tr>
                        <tr>
                            <td>Result</td>
                            <td>{data.number} <span style={{ color: data.color }}>{data.color}</span> <span style={{ color: data.color1 }}>{data.color1}</span></td>
                        </tr>
                        <tr>
                            <td>Select</td>
                            <td>{data.ans}</td>
                        </tr>
                        <tr>
                            <td>Amount</td>
                            <td className={data.res}>{data.res === 'success' ? '+' : '-'}{data.amount - (data.amount / 100 * data.tax)}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td className={data.res}>{data.res}</td>
                        </tr>
                        <tr>
                            <td>Create Time</td>
                            <td>{data.time}</td>
                        </tr>
                    </table>
                </ul>
                : ''
            }
        </div>
    );
};

export default SingleBetSection;
