document.addEventListener("DOMContentLoaded", () => {
    const productList = document.getElementById("product-list");
    async function loadProducts() {
        try {
            const response = await fetch("https://dummyjson.com/products");
            const data = await response.json();
            displayProducts(data.products);
        } catch (error) {
            console.error("Viga toodete laadimisel:", error);
            productList.innerHTML = `<p class="text-danger text-center">Toodete laadimine ebaõnnestus!</p>`;
        }
    }
    function displayProducts(products) {
        products.forEach(product => {
            const card = `
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <img src="${product.thumbnail}" class="card-img-top" alt="${product.title}">
                        <div class="card-body text-dark">
                            <h5 class="card-title">${product.title}</h5>
                            <p class="card-text"><strong>${product.price}€</strong></p>
                        </div>
                        <div class="card-footer bg-white">
                            <button class="btn btn-primary w-100">Add to cart</button>
                        </div>
                    </div>
                </div>
            `;
            productList.innerHTML += card;
        });
    }
    loadProducts();
});
