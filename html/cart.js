document.addEventListener("DOMContentLoaded", function() {
    var cartToggle = document.getElementById("cart-toggle");
    var cartPanel = document.getElementById("cart-panel");
    var addToCartBtn = document.querySelector(".add-to-cart");

    // Container for cart items
    var cartItemsContainer = document.getElementById("cart-items-container");

    // Container for cart total
    var cartTotalContainer = document.getElementById("cart-total");

    // Initialize total price
    var totalPrice = 0;

    // Function to fetch cart items from the server and update UI
    function loadCartItems() {
        fetch('getCartItems.php')
            .then(response => response.json())
            .then(cartItems => {
                var stackedItems = {};
                cartItemsContainer.innerHTML = '';

                // Reset total price
                totalPrice = 0;

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

                    // Update total price
                    totalPrice += stackedItem.item.price * stackedItem.quantity;
                }

                // Display total price
                displayTotalPrice(totalPrice);
            })
            .catch(error => {
                console.error('Error fetching cart items:', error);
            });
    }

    // Load cart items on page load
    loadCartItems();

    // Toggle cart panel visibility
    cartToggle.addEventListener("click", function() {
        // Check login status before opening the cart
        checkLoginStatus();
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
                    
                    // Recalculate and display total price
                    totalPrice -= parseFloat(event.target.previousSibling.textContent); // Deduct price of removed item
                    displayTotalPrice(totalPrice);
                } else {
                    throw new Error('Failed to update item quantity in cart');
                }
            })
            .catch(error => {
                console.error('Error updating item quantity in cart:', error);
            });
        }
    });

    // Function to update the total quantity displayed in the cart icon
    function updateCartItemCount() {
        var cartItemCount = document.querySelectorAll('.cart-item').length;
        document.getElementById('cart-item-count').textContent = cartItemCount;
    }

    // Function to display total price
    function displayTotalPrice(totalPrice) {
        cartTotalContainer.textContent = "Total Price: $" + totalPrice.toFixed(2);
    }
});

// Function to check login status and display appropriate message
function checkLoginStatus() {
    fetch('check_login.php')
        .then(response => response.json())
        .then(data => {
            if (data.logged_in) {
                // User is logged in, open the cart panel
                document.getElementById("cart-panel").classList.toggle("active");
            } else {
                // User is not logged in, display message and login button
                var cartPanel = document.getElementById("cart-panel");
                cartPanel.innerHTML = "";
                var loginMessage = document.createElement("p");
                loginMessage.textContent = "You are not logged in";
                loginMessage.classList.add("login");
                var loginButton = document.createElement("button");
                loginButton.textContent = "Login";
                loginButton.classList.add("login-button");
                loginButton.addEventListener("click", function() {
                    window.location.href = "login.html";
                });
                cartPanel.appendChild(loginMessage);
                cartPanel.appendChild(loginButton);
                cartPanel.classList.add("active");
            }
        })
        .catch(error => {
            console.error('Error checking login status:', error);
        });
}

// Function to close the cart panel
function closeCart() {
    var cartPanel = document.getElementById("cart-panel");
    cartPanel.classList.remove("active");
}

document.addEventListener("DOMContentLoaded", function() {
    var checkoutButton = document.getElementById("checkout-button");

    // Add event listener to the checkout button
    checkoutButton.addEventListener("click", function() {
        // Call a function to handle the checkout process
        handleCheckout();
    });

    // Function to handle the checkout process
    function handleCheckout() {
        // Perform any necessary validation or processing before checkout
        // For example, you can check if the cart is empty before proceeding to checkout
        if (cartIsEmpty()) {
            alert("Your cart is empty. Please add items to your cart before proceeding to checkout.");
            return; // Stop execution if cart is empty
        }

        // If cart is not empty, redirect the user to the checkout page
        window.location.href = "checkout.html";
    }

    // Function to check if the cart is empty
    function cartIsEmpty() {
        // Check if the cart items container is empty
        var cartItemsContainer = document.getElementById("cart-items-container");
        return cartItemsContainer.children.length === 0;
    }
});
document.addEventListener("click", function(event) {
    // Check if the clicked element has the class "remove-from-cart"
    if (event.target.classList.contains("remove-from-cart")) {
        // Get the price of the item being removed
        var itemPrice = parseFloat(event.target.previousElementSibling.querySelector(".cart-price").textContent.replace('$', ''));
        
        // Get the current total price
        var totalPrice = parseFloat(document.getElementById("cart-total").textContent.replace('Total Price: $', ''));
        
        // Calculate the new total price after removing the item
        var newTotalPrice = totalPrice - itemPrice;
        
        // Update the total price displayed in the cart
        document.getElementById("cart-total").textContent = 'Total Price: $' + newTotalPrice.toFixed(2);
    }
});