document.addEventListener("DOMContentLoaded", function() {
  var cartToggle = document.getElementById("cart-toggle");
  var cartPanel = document.getElementById("cart-panel");

  cartToggle.addEventListener("click", function() {
      cartPanel.classList.toggle("active");
  });
});

function closeCart() {
  var cartPanel = document.getElementById("cart-panel");
  cartPanel.classList.remove("active");
}