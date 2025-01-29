
const firebaseConfig = {
    apiKey: "AIzaSyDJwUC0L7zLaMW_KAGROpL9Z9Ql9ygDkkw",
    authDomain: "ylesanne-23.firebaseapp.com",
    projectId: "ylesanne-23",
    storageBucket: "ylesanne-23.firebasestorage.app",
    messagingSenderId: "240529986675",
    appId: "1:240529986675:web:8adb57275d2e3476934606",
    measurementId: "G-H6GFCER887"
  };

const app = firebase.initializeApp(firebaseConfig);
const db = firebase.firestore();

const feedbackTable = document.getElementById("feedback-table");
const totalReviewers = document.getElementById("total-reviewers");
const averageRating = document.getElementById("average-rating");

async function loadFeedback() {
    const snapshot = await db.collection("feedback").get();
    let totalRating = 0;
    let totalCount = 0;

    feedbackTable.innerHTML = "";

    snapshot.forEach(doc => {
        const data = doc.data();
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${data.rating}</td>
            <td>${data.comment}</td>
            <td><button class="btn btn-danger btn-sm" onclick="deleteFeedback('${doc.id}')">Kustuta</button></td>
        `;
        feedbackTable.appendChild(row);

        totalRating += data.rating;
        totalCount++;
    });

    totalReviewers.textContent = totalCount;
    averageRating.textContent = totalCount > 0 ? (totalRating / totalCount).toFixed(1) : 0;
}

async function deleteFeedback(id) {
    if (confirm("Kas oled kindel, et soovid selle tagasiside kustutada?")) {
        await db.collection("feedback").doc(id).delete();
        loadFeedback();
    }
}

loadFeedback();
