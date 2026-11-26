import { useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';

const CustomBackHandler = () => {
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    const handleBackNavigation = () => {
      const currentPath = location.pathname;

      if (
        currentPath.startsWith('/play/') ||
        currentPath.startsWith('/copypaste/') ||
        currentPath.startsWith('/crossing/')
      ) {
        navigate('/', { replace: true }); // Redirect to `/`
        return true; // Prevent default back navigation
      }
    };

    // Listen to the popstate event for back button navigation
    const handlePopState = () => handleBackNavigation();

    window.addEventListener('popstate', handlePopState);

    return () => {
      window.removeEventListener('popstate', handlePopState); // Cleanup
    };
  }, [location, navigate]);

  return null;
};

export default CustomBackHandler;
