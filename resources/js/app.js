import { auth } from "./firebase";
import {
  GoogleAuthProvider,
  setPersistence,
  browserSessionPersistence,
  signInWithPopup,
} from "firebase/auth";

const provider = new GoogleAuthProvider();
const button = document.getElementById("firebase-google-login");

if (button) {
  button.addEventListener("click", async () => {
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
      alert("Login Google gagal: " + error.message);
    }
  });
}