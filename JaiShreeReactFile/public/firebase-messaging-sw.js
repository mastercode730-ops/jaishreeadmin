importScripts("https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js");

const firebaseConfig = {
    apiKey: "AIzaSyDLhzkc0vYksbojxkrPh9188Hw7kURL9to",
    authDomain: "aghorimatka-2697d.firebaseapp.com",
    projectId: "aghorimatka-2697d",
    storageBucket: "aghorimatka-2697d.firebasestorage.app",
    messagingSenderId: "743507339143",
    appId: "1:743507339143:web:e43e31af0574dd9eb11522",
    measurementId: "G-ZWWLEK1WW6",
  };

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// Background notification handling
messaging.onBackgroundMessage((payload) => {
  console.log("Received background message:", payload);
  self.registration.showNotification(payload.notification.title, {
    body: payload.notification.body,
    icon: "https://aghorimatka.com/images/logo.jpg",
    requireInteraction: true
  });
});