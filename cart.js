document.addEventListener("DOMContentLoaded", function() {
    var cartToggle = document.getElementById("cart-toggle");
    var cartPanel = document.getElementById("cart-panel");
    var addToCartBtn = document.querySelector(".add-to-cart");

    // Container for cart items
    var cartItemsContainer = document.getElementById("cart-items-container");

    // Function to fetch cart items from the server and update UI
    function loadCartItems() {
        fetch('getCartItems.php')
            .then(response => response.json())
            .then(cartItems => {
                var stackedItems = {};
                cartItemsContainer.innerHTML = '';

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
                    var cartItem = document.createElement("div");
                    cartItem.classList.add("cart-item");
                    cartItem.innerHTML = `
                        <div class="cart">
                            <p class="cart-name">${stackedItem.item.name} (${stackedItem.quantity})</p>
                            <img class="cart-image" src="${stackedItem.item.image}" alt="${stackedItem.item.name}">
                            <p class="cart-price">${stackedItem.item.price}$</p>
                        </div>
                        <button class="remove-from-cart" data-item-id="${stackedItem.item.id}" data-quantity="${stackedItem.quantity}">Remove</button>
                    `;
                    cartItemsContainer.appendChild(cartItem);
                }
            })
            .catch(error => {
                console.error('Error fetching cart items:', error);
            });
    }

    // Load cart items on page load
    loadCartItems();

    // Toggle cart panel visibility
    cartToggle.addEventListener("click", function() {
        cartPanel.classList.toggle("active");
    });

    // Handle adding an item to the cart
    addToCartBtn.addEventListener("click", function() {
        // Get the item ID from the item-id element
        var itemId = document.querySelector(".item-id").textContent.trim();

        // Send AJAX request to add item to cart
        fetch('addToCart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ itemId: itemId })
        })
        .then(response => {
            if (response.ok) {
                // If item added successfully, reload cart items
                loadCartItems();
                console.log('Item added to cart');
            } else {
                throw new Error('Failed to add item to cart');
            }
        })
        .catch(error => {
            console.error('Error adding item to cart:', error);
        });
    });

    // Handle removing an item from the cart
document.addEventListener("click", function(event) {
    if (event.target.classList.contains("remove-from-cart")) {
        var itemId = event.target.getAttribute("data-item-id");
        var newQuantity = parseInt(event.target.getAttribute("data-quantity")) - 1;

        // Send AJAX request to update item quantity in cart
        fetch('updateCart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ itemId: itemId, quantity: newQuantity })
        })
        .then(response => {
            if (response.ok) {
                // If new quantity is 0, remove the cart item from the DOM
                if (newQuantity === 0) {
                    var cartItem = event.target.closest(".cart-item");
                    cartItem.parentNode.removeChild(cartItem); // Remove the cart item from the DOM
                } else {
                    // Update the displayed quantity in the cart item
                    var cartItem = event.target.closest(".cart-item");
                    cartItem.innerHTML = `
                        <div class="cart">
                            <p class="cart-name">${cartItem.querySelector('.cart-name').textContent.split('(')[0].trim()} (${newQuantity})</p>
                            ${cartItem.querySelector('.cart-image').outerHTML}
                            <p class="cart-price">${cartItem.querySelector('.cart-price').textContent}</p>
                        </div>
                        <button class="remove-from-cart" data-item-id="${itemId}" data-quantity="${newQuantity}">Remove</button>
                    `;
                }
                // Update the total quantity displayed in the cart icon
                updateCartItemCount();
                console.log("Item quantity updated in cart");
            } else {
                throw new Error('Failed to update item quantity in cart');
            }
        })
        .catch(error => {
            console.error('Error updating item quantity in cart:', error);
        });
    }
});
});

// Function to update the total quantity displayed in the cart icon
function updateCartItemCount() {
    var cartItemCount = document.querySelectorAll('.cart-item').length;
    document.getElementById('cart-item-count').textContent = cartItemCount;
}

function closeCart() {
    var cartPanel = document.getElementById("cart-panel");
    cartPanel.classList.remove("active");
}
