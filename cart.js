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
            // Object to store stacked items
            var stackedItems = {};

            // Stack items based on their ID
            cartItems.forEach(item => {
                if (stackedItems[item.id]) {
                    stackedItems[item.id].quantity++;
                } else {
                    stackedItems[item.id] = {
                        quantity: 1,
                        item: item
                    };
                }
            });

            // Generate cart elements for stacked items
            for (var itemId in stackedItems) {
                var stackedItem = stackedItems[itemId];
                var newItem = document.createElement("div");
                newItem.classList.add("cart-item");
                newItem.innerHTML = `
                    <div class="cart">
                        <p class="cart-name">${stackedItem.item.name} (${stackedItem.quantity})</p>
                        <img class="cart-image" src="${stackedItem.item.image}" alt="${stackedItem.item.name}">
                        <p class="cart-price">${stackedItem.item.price}$</p>
                    </div><button class="remove-from-cart" data-quantity="${stackedItem.quantity}" data-item-id="${stackedItem.item.id}">Remove</button>
                `;
                document.getElementById("cart-panel").appendChild(newItem);
            }
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

document.addEventListener("click", function(event) {
    if (event.target.classList.contains("remove-from-cart")) {
        // Get the item ID from the data-item-id attribute
        var itemId = event.target.getAttribute("data-item-id");
        
        // Get the quantity of the item
        var quantity = parseInt(event.target.getAttribute("data-quantity"));

        if (quantity > 1) {
            // Decrease the quantity by 1
            removeFromCart(itemId, quantity - 1);
        } else {
            // Remove the item from the cart completely
            removeFromCart(itemId, 0);
        }
    }
});

function removeFromCart(itemId, newQuantity) {
    // Calculate the updated quantity to be sent to the server
    var updatedQuantity = newQuantity >= 0 ? newQuantity : 0;

    // Send AJAX request to server to update item quantity in cart
    fetch('updateCart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ itemId: itemId, quantity: updatedQuantity })
    })
    .then(response => {
        if (response.ok) {
            // If new quantity is 0, remove the cart item from the DOM
            if (updatedQuantity === 0) {
                var cartItemElement = document.querySelector(`[data-item-id="${itemId}"]`);
                if (cartItemElement) {
                    cartItemElement.remove();
                }
            }
            console.log("Item quantity updated in cart");
        } else {
            throw new Error('Failed to update item quantity in cart');
        }
    })
    .catch(error => {
        console.error('Error updating item quantity in cart:', error);
    });
}

