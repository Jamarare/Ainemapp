const quotesContainer = document.getElementById("quotesContainer");
const loadingIndicator = document.getElementById("loading");
const errorMessage = document.getElementById("errorMessage");
const filterInput = document.getElementById("filter");

async function fetchAndDisplayQuotes() {
  try {
    loadingIndicator.style.display = "block";
    quotesContainer.innerHTML = "";

    const response = await fetch("https://dummyjson.com/quotes");
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    const data = await response.json();
    const quotes = data.quotes;

    displayQuotes(quotes);

    filterInput.addEventListener("input", () => {
      const filterValue = filterInput.value.toLowerCase();
      const filteredQuotes = quotes.filter((quote) =>
        quote.tags.some((tag) => tag.toLowerCase().includes(filterValue))
      );
      displayQuotes(filteredQuotes);
    });
  } catch (error) {
    console.error("Error fetching quotes:", error);
    errorMessage.classList.remove("d-none");
  } finally {
    loadingIndicator.style.display = "none";
  }
}

function displayQuotes(quotes) {
  quotesContainer.innerHTML = "";
  quotes.forEach((quote) => {
    const card = document.createElement("div");
    card.className = "col-md-4";
    card.innerHTML = `
      <div class="card">
        <div class="card-header">${quote.author}</div>
        <div class="card-body">
          <p class="card-text">${quote.quote}</p>
        </div>
        <div class="card-footer">${quote.tags.join(", ")}</div>
      </div>
    `;
    quotesContainer.appendChild(card);
  });
}

fetchAndDisplayQuotes();
