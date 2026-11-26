import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { AiOutlineDown } from "react-icons/ai";
import { AiOutlineUp } from "react-icons/ai";

const Dropdown = ({ icon, name, itemclick, menu, url }) => {
    const [isOpen, setIsOpen] = useState(false);

    const toggleMenu = () => {
        if (typeof menu == 'object' && menu.length > 0) {
            setIsOpen(!isOpen);
        }
    };
    return (
        <div className='items ripple' onClick={itemclick === '' ? () => { } : itemclick}>
            <Link to={url} className='sub-item' onClick={toggleMenu}>
                <div className='first-item'>
                    <img src={icon} alt={name} />
                    <p>{name}</p>
                </div>
                {isOpen ?
                    <span className='moreicon'><AiOutlineUp /></span>
                    :
                    <span className='moreicon'><AiOutlineDown /></span>
                }
            </Link>
            {isOpen ?
                <ul className="dropdown-menu">
                    {
                        menu.map((element, index) => (
                            <Link onClick={element.click} to={element.url} key={index}>
                                <li>{element.name}</li>
                            </Link>
                        ))
                    }
                </ul>
                : ''
            }
        </div>
    );
};

export default Dropdown;
