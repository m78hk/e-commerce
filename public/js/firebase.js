// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/8.0.0/firebase-app.js";
import { getAnalytics } from "firebase/analytics";
import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/8.0.0/firebase-auth.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyAaHo422K_N-JmZ6Ziq8ur-6a2sZ3_OFRQ",
  authDomain: "abcshop-web.firebaseapp.com",
  projectId: "abcshop-web",
  storageBucket: "abcshop-web.appspot.com",
  messagingSenderId: "293629346772",
  appId: "1:293629346772:web:30e0219f215ab1e2bdcc41",
  measurementId: "G-RNF3QDCT8F"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const auth = getAuth(app);
const provider = new FacebookAuthProvider();


document.addEventListener ("DOMContentLoaded", function() {
  const facebookLogin = document.getElementById("facebook-login-btn");
  facebookLogin.addEventListener("click", function(event) {
    event.preventDefault();
    alert(55)
  });
})

