document.addEventListener("DOMContentLoaded", function() {
    var cartToggle = document.getElementById("cart-toggle");
    var cartPanel = document.getElementById("cart-panel");
    var addToCartBtn = document.querySelector(".add-to-cart");

    // Function to fetch cart items from the server
    function loadCartItems() {
        // Fetch cart items from the server using user_id
        fetch('getCartItems.php')
            .then(response => response.json())
            .then(cartItems => {
                // Generate cart elements for each item
                cartItems.forEach(item => {
                    var newItem = document.createElement("div");
                    newItem.classList.add("cart-item");
                    newItem.innerHTML = `
                        <div class="cart">
                            <p class="cart-name">${item.name}</p>
                            <img class="cart-image" src="${item.image}" alt="${item.name}">
                            <p class="cart-price">${item.price}$</p>
                        </div>
                    `;
                    document.getElementById("cart-panel").appendChild(newItem);
                });
            })
            .catch(error => {
                console.error('Error fetching cart items:', error);
            });
    }

    // Load cart items on page load
    loadCartItems();

    cartToggle.addEventListener("click", function() {
        cartPanel.classList.toggle("active");
    });

    addToCartBtn.addEventListener("click", function() {
        // Fetch JSON data
        fetch('items.json')
            .then(response => response.json())
            .then(data => {
                // Get the ID from the item-id element
                var id = document.querySelector(".item-id").textContent.trim();

                // Find the item with the retrieved ID
                var item = data.find(item => item.id === parseInt(id));

                if (item) {
                    // Send AJAX request to add item to cart table in database
                    addToCart(item.id);
                } else {
                    console.error("Item with ID " + id + " not found in JSON data.");
                }
            })
            .catch(error => {
                console.error('Error fetching JSON:', error);
            });
    });
});

function addToCart(itemId) {
    // Send AJAX request to server to add item to cart
    fetch('addToCart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ itemId: itemId })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to add item to cart');
        }
        // Item added to cart successfully
        console.log('Item added to cart');
    })
    .catch(error => {
        console.error('Error adding item to cart:', error);
    });
}

function closeCart() {
    var cartPanel = document.getElementById("cart-panel");
    cartPanel.classList.remove("active");
}
