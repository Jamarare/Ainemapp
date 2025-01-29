// Firebase'i seadistamine
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
import { getFirestore, collection, addDoc, getDocs, deleteDoc, doc } from "https://www.gstatic.com/firebasejs/9.17.1/firebase-firestore.js";

const firebaseConfig = {
    apiKey: "AIzaSyDJwUC0L7zLaMW_KAGROpL9Z9Ql9ygDkkw",
    authDomain: "ylesanne-23.firebaseapp.com",
    projectId: "ylesanne-23",
    storageBucket: "ylesanne-23.firebasestorage.app",
    messagingSenderId: "240529986675",
    appId: "1:240529986675:web:8adb57275d2e3476934606",
    measurementId: "G-H6GFCER887"
  };

initializeApp(firebaseConfig);

const db = getFirestore();
const kogumik = collection(db, "postitused");

window.app = app;
console.log("Firebase app name:", app.name);

const ratingInput = document.getElementById("rating");
const commentInput = document.getElementById("comment");
const submitButton = document.getElementById("submitFeedback");
const adminPanel = document.getElementById("adminPanel");
const toggleAdminButton = document.getElementById("toggleAdmin");
const averageRatingElem = document.getElementById("averageRating");
const feedbackCountElem = document.getElementById("feedbackCount");
const feedbackList = document.getElementById("feedbackList");

submitButton.addEventListener("click", async () => {
    const rating = parseInt(ratingInput.value);
    const comment = commentInput.value.trim();

    if (rating >= 1 && rating <= 5 && comment) {
        await addDoc(collection(db, "feedback"), { rating, comment, createdAt: new Date() });
        alert("Tagasiside saadetud!");
        ratingInput.value = "";
        commentInput.value = "";
    } else {
        alert("Palun täida kõik väljad õigesti!");
    }
});

toggleAdminButton.addEventListener("click", async () => {
    adminPanel.style.display = adminPanel.style.display === "none" ? "block" : "none";
    if (adminPanel.style.display === "block") {
        const feedbackSnapshot = await getDocs(collection(db, "feedback"));
        const feedbackData = feedbackSnapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));

        const totalRatings = feedbackData.reduce((sum, fb) => sum + fb.rating, 0);
        const feedbackCount = feedbackData.length;
        const averageRating = feedbackCount > 0 ? (totalRatings / feedbackCount).toFixed(2) : 0;

        averageRatingElem.textContent = averageRating;
        feedbackCountElem.textContent = feedbackCount;

        feedbackList.innerHTML = "";
        feedbackData.forEach(feedback => {
            const li = document.createElement("li");
            li.textContent = `${feedback.rating} tärni: ${feedback.comment}`;
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Kustuta";
            deleteButton.addEventListener("click", async () => {
                await deleteDoc(doc(db, "feedback", feedback.id));
                alert("Kommentaar kustutatud!");
                toggleAdminButton.click();
            });
            li.appendChild(deleteButton);
            feedbackList.appendChild(li);
            window.location.href = "admin.html";
        });
    }
});