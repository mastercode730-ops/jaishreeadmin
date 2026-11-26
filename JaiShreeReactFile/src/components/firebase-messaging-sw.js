importScripts(
  "https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js"
);
importScripts(
  "https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js"
);

const firebaseConfig = {
  apiKey: "AIzaSyDLhzkc0vYksbojxkrPh9188Hw7kURL9to",
  authDomain: "aghorimatka-2697d.firebaseapp.com",
  projectId: "aghorimatka-2697d",
  storageBucket: "aghorimatka-2697d.firebasestorage.app",
  messagingSenderId: "743507339143",
  appId: "1:743507339143:web:e43e31af0574dd9eb11522",
  measurementId: "G-ZWWLEK1WW6",
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

// messaging.onBackgroundMessage(function (payload) {
//   console.log(
//     "[firebase-messaging-sw.js] Received background message ",
//     payload
//   );
//   const notificationTitle = payload.notification.title;
//   const notificationOptions = {
//     body: payload.notification.body,
//     icon: "/favicon.png",
//   };
//   return self.registration.showNotification(
//     notificationTitle,
//     notificationOptions
//   );
// });


// onBackgroundMessage(messaging, (payload) => {
//   console.log("Received background message:", payload);

//   const notificationTitle = payload.notification?.title || "New Notification";
//   const notificationOptions = {
//     body: payload.notification?.body || "You have a new message.",
//     icon: payload.notification?.icon || "/logo192.png",
//   };

//   self.registration.showNotification(notificationTitle, notificationOptions);
// });

// // Required to avoid issues
// self.addEventListener("notificationclick", (event) => {
//   event.notification.close();
//   event.waitUntil(
//     clients.openWindow("/") // Change to your app's route
//   );
// });
