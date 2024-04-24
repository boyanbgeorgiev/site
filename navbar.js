document.getElementById("navigation").innerHTML = `
<div class="topnav">
        <a href="index.html">HOME</a>
        <a href="#news">HOODIES</a>
        <a href="#contact">TEES</a>
        <a href="#contact">E GIFT CARDS</a>
        <a href="#" id="cart-toggle" class="split"><img src="market.png" alt="BAG" class="logo"></a>
        <div id="cart-panel" class="cart-panel">
            <!-- Close button -->
            <span class="close-btn" onclick="closeCart()">&times;</span><br><br>
            <!-- Your cart content goes here -->
            <p>Your cart items will appear here.<p>
        </div>
        <a href="/login.php" class="split account"><img src="user.png" alt="USER" class="logo"></a>
    </div>
    <div class="main">
        <img class="logo3" src="full logo.jpg" alt="logo">
        </div>
`;
