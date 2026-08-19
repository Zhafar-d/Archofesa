import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

import { auth } from "./firebase";
import {
  GoogleAuthProvider,
  setPersistence,
  browserSessionPersistence,
  signInWithPopup,
} from "firebase/auth";

const provider = new GoogleAuthProvider();
const button = document.getElementById("firebase-google-login");

let isLoggingIn = false;

if (button) {
  button.addEventListener("click", async () => {
    if (isLoggingIn) return;
    isLoggingIn = true;
    button.disabled = true;

    try {
      await setPersistence(auth, browserSessionPersistence);
      const result = await signInWithPopup(auth, provider);
      const user = result.user;
      const idToken = await user.getIdToken();

      const response = await fetch('/firebase-login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ idToken }),
      });

      if (! response.ok) {
        const error = await response.json().catch(() => null);
        throw new Error(error?.message || 'Firebase login failed.');
      }

      const data = await response.json();
      window.location.href = data.redirect || '/dashboard';
    } catch (error) {
      console.error(error);
      
      const errorCode = error?.code || error?.error?.code || "";
      const errorMessage = error?.message || String(error);

      // Ignore user closing or cancelling popup
      if (
        errorCode === "auth/cancelled-popup-request" ||
        errorCode === "auth/popup-closed-by-user" ||
        errorMessage.includes("auth/popup-closed-by-user") ||
        errorMessage.includes("auth/cancelled-popup-request")
      ) {
        return;
      }

      if (errorCode === "auth/unauthorized-domain" || errorMessage.includes("auth/unauthorized-domain")) {
        alert("Domain ini (" + window.location.hostname + ") belum ditambahkan ke Authorized Domains di Firebase Console. Silakan tambahkan domain ini di Firebase Console -> Authentication -> Settings -> Authorized domains.");
        return;
      }

      alert("Login Google gagal: " + errorMessage);
    } finally {
      isLoggingIn = false;
      button.disabled = false;
    }
  });
}