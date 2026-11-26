import {React} from "react";
import ReactDOM from 'react-dom/client';
import Playground from './components/Playground';
import CopyPaste from './components/CopyPaste';
import Crossing from './components/Crossing';
import Mine from './components/mine';
import Login from './components/Login';
import Dashboard from './components/Dashboard';
import KhaiwalPage from './components/KhaiwalPage';
import GameChart from './components/GameChart';
import Privacypolicy from './components/pages/Privacy_policy';
import RateList from './components/pages/RateList';
import Riskaggrement from './components/pages/Riskaggrement';
import Register from './components/Register';
import BankCard from './components/pages/bank_card';
import Promotion from './components/pages/Promotion';
import Recharge from './components/pages/Recharge';
import ResetPassword from './components/pages/ResetPassword';
import P2PTransfer from './components/pages/P2PTransfer';
import Transaction from './components/history/Transaction';
import Withdrawal from './components/pages/Withdrawal';
import Receipt from "./components/Receipt";
import ForgotPassword from './components/Forgot_password';
import AddBankCard from './components/pages/AddBankCard';
import CustomBackHandler from './CustomBackHandler';
import './assets/css/App.css';
import './assets/css/responsive.css';
import './assets/css/AdarshCustomization.css';
import 'react-toastify/dist/ReactToastify.css';
import reportWebVitals from './reportWebVitals';
import { Route, BrowserRouter as Router, Routes } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import { AnimatePresence } from "framer-motion";
const App = () => {
  if ("serviceWorker" in navigator) {
    navigator.serviceWorker
      .register("/firebase-messaging-sw.js")
      .then((registration) => {
        console.log("Service Worker registered:", registration);
      })
      .catch((error) => console.log("Service Worker registration failed:", error));
  }


  return (
    <AuthProvider>
      <Router>
        <CustomBackHandler />
        <AnimatePresence mode="wait">
          <Routes>
            <Route path="/" exact element={<Login />} />
            <Route path="/login" exact element={<Login />} />
            <Route path="/receipt" exact element={<Receipt />} />
            <Route path="/register/:refcode?" exact element={<Register />} />
            <Route path="/forgot-password" exact element={<ForgotPassword />} />
            {/* <Route path="/dashboard" exact element={<KhaiwalPage />} /> */}
            <Route path="/dashboard" exact element={<Dashboard />} />
            <Route path="/playground" exact element={<Dashboard />} />
            <Route path="/play/:id" exact element={<Playground />} />
            <Route path="/copypaste/:id" exact element={<CopyPaste />} />
            <Route path="/crossing/:id" exact element={<Crossing />} />
            <Route path="/mine" exact element={<Mine />} />
            <Route path="/history/:id" exact element={<Transaction />} />
            <Route path="/gamechart/:id" exact element={<GameChart />} />
            <Route path="/p2p" exact element={<P2PTransfer />} />
            <Route path="/recharge" exact element={<Recharge />} />
            <Route path="/withdrawal" exact element={<Withdrawal />} />
            <Route path="/promotion" exact element={<Promotion />} />
            <Route path="/bank-card" exact element={<BankCard />} />
            <Route path="/add-bank-card" exact element={<AddBankCard />} />
            <Route path="/reset-password" exact element={<ResetPassword />} />
            <Route path="/ratelist" exact element={<RateList />} />
            <Route path="/help" exact element={<Privacypolicy />} />
            <Route path="/risk-disclouser-aggrement" exact element={<Riskaggrement />} />
          </Routes>
        </AnimatePresence>
      </Router>
    </AuthProvider>
  );
};

// Render App
const root = ReactDOM.createRoot(document.getElementById("root"));
root.render(<App />);

reportWebVitals();