import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage, isSupported } from "firebase/messaging";

// Your Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyDLhzkc0vYksbojxkrPh9188Hw7kURL9to",
  authDomain: "aghorimatka-2697d.firebaseapp.com",
  projectId: "aghorimatka-2697d",
  storageBucket: "aghorimatka-2697d.firebasestorage.app",
  messagingSenderId: "743507339143",
  appId: "1:743507339143:web:e43e31af0574dd9eb11522",
  measurementId: "G-ZWWLEK1WW6",
};

let firebaseApp;
let messaging;
let messagingInstance;

export const initializeFirebase = async () => {
  if (!firebaseApp) {
    try {
      firebaseApp = initializeApp(firebaseConfig);
    } catch (e) {
      console.warn("Firebase initializeApp error:", e);
    }
  }
  if (!messaging && firebaseApp) {
    try {
      // Firebase Messaging/ServiceWorker is ONLY allowed by browsers in Secure Contexts (HTTPS/localhost)
      if (typeof window !== "undefined" && window.isSecureContext && navigator && "serviceWorker" in navigator && navigator.serviceWorker) {
        const supported = await isSupported().catch(() => false);
        if (supported) {
          messaging = getMessaging(firebaseApp);
        }
      }
    } catch (e) {
      console.warn("Firebase messaging not supported (requires HTTPS):", e);
    }
  }
  return { firebaseApp, messaging };
};

export const requestNotificationPermission = () => {
  return new Promise(async (resolve, reject) => {
    try {
      if (typeof window === "undefined" || !window.isSecureContext || !("Notification" in window) || !navigator || !("serviceWorker" in navigator) || !navigator.serviceWorker) {
        return resolve(null);
      }
      if (!messaging) {
        await initializeFirebase();
      }
      if (!messaging) {
        return resolve(null);
      }
      const permission = await Notification.requestPermission();
      console.log("Notification permission status:", permission);

      if (permission === "granted") {
        try {
          const token = await getToken(messaging, {
            vapidKey:
              "BKr5u9F4FD1a4tqkcTuffJxYwq-ekxeElx60WdSbvr6ziuM-QO_o38pO1HZs1xANIwCEnqm6vggcKYHdlE9EXKs", // Your Firebase VAPID key
          });

          if (token) {
            console.log("FCM Token:", token);
            resolve(token); // Resolve with the token
          } else {
            console.log("No FCM token available.");
            resolve(null); // Resolve with null if no token
          }
        } catch (error) {
          console.error("Error getting FCM token:", error);
          resolve(null);
        }
      } else {
        console.log("Notification permission denied.");
        resolve(null); // Resolve with null if permission denied
      }
    } catch (error) {
      console.error("Error getting notification permission:", error);
      resolve(null);
    }
  });
};

export const getFirebaseMessaging = () => {
  return messaging;
};