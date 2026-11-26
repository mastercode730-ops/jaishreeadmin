import { useContext } from 'react';
import { Link } from 'react-router-dom';
import { FaHistory,FaShareAlt,FaPlay } from "react-icons/fa";
import { MdHome } from "react-icons/md";
import { AuthContext } from '../context/AuthContext';
function Footer({ page }) {
  const { WhastappMessage } = useContext(AuthContext);
  const handleShare = async () => {
    const messageUrl = await WhastappMessage();
    if (messageUrl) {
      window.open(messageUrl, "_blank", "noopener,noreferrer"); // Opens the WhatsApp share link
    }
};

  return (
    <>
      <div className="footer-tab">
        <Link to='/dashboard' className='footer-btn-container'>
          <MdHome />
          <p>Home</p>
        </Link>
        {/* <Link to='/playground' className='footer-btn-container'>
          <FaPlay />
          <p>Play</p>
        </Link> */}
        {localStorage.getItem('login') && localStorage.getItem('login') !== '' ?
        <Link to='/history/all' className='footer-btn-container'>
          <FaHistory />
          <p>History</p>
        </Link>
        : '' }
        {localStorage.getItem('login') && localStorage.getItem('login') !== '' ?
        <Link onClick={handleShare} className='footer-btn-container'>
          <FaShareAlt/>
          <p>Share</p>
        </Link>
        : '' }
        <Link to={localStorage.getItem('login') && localStorage.getItem('login') !== '' ? '/help' : '/login' } className='footer-btn-container'>
          <img src={'/icons_asset/support.png'} alt="Help" />
          <p>Help</p>
        </Link>
      </div>
    </>
  )
}

export default Footer;
