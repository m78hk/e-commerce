// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/8.0.0/firebase-app.js";
import { getAnalytics } from "firebase/analytics";
import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/8.0.0/firebase-auth.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: 
  authDomain: 
  projectId: 
  storageBucket: 
  messagingSenderId: 
  appId: 
  measurementId: 
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

