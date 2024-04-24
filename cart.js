document.addEventListener("DOMContentLoaded", function() {
  var cartToggle = document.getElementById("cart-toggle");
  var cartPanel = document.getElementById("cart-panel");
  var addToCartBtn = document.querySelector(".add-to-cart");

  cartToggle.addEventListener("click", function() {
      cartPanel.classList.toggle("active");
  });

  addToCartBtn.addEventListener("click", function() {
      // Extract item details
      var itemName = document.querySelector(".item-name").innerText;
      var itemDescription = document.querySelector(".item-description").innerText;

      // Create a new cart item element
      var newItem = document.createElement("p");
      newItem.textContent = itemName + ": " + itemDescription;

      // Append the new cart item to the cart panel
      document.getElementById("cart-panel").appendChild(newItem);

      // Close the cart panel
      closeCart();
  });
});

function closeCart() {
  var cartPanel = document.getElementById("cart-panel");
  cartPanel.classList.remove("active");
}
