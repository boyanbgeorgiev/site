document.getElementById("navigation").innerHTML = `
<div class="topnav">
        <a href="index.html">HOME</a>
        <a href="tees.html">TEES</a>
        <a href="hoodies.html">HOODIES</a>
        <a id="cart-toggle" class="split"><img src="/img/market.png" alt="BAG" class="logo"></a>
        <div id="cart-panel" class="cart-panel">
            <!-- Close button -->
            <span class="close-btn" onclick="closeCart()">&times;</span><br><br>
            <!-- Your cart content goes here -->
            <p class="cart-title">CART<p><br><br>
            <div id="cart-items-container" class="cart-items-container">
                 <!-- Cart items will be dynamically inserted here -->
            </div>
            <div id="cart-total">
                <!-- Total tab content will be dynamically updated here -->
            </div>
            <div id="cart-checkout">
                <!-- Total tab content will be dynamically updated here -->
                <button id="checkout-button">Checkout</button>
            </div>
        </div>
        <a href="/login.html" class="split account"><img src="/img/user.png" alt="USER" class="logo"></a>
    </div>
    <div class="main">
        <img class="logo3" src="/img/new full logo.jpg" alt="logo">
        </div>
`;
