<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        /* Header Styling */
        header {
            background-color: #2a9d8f;
            color: white;
            padding: 0.3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-img {
            height: 40px;
        }

        .navbar {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .navbar-brand {
            color: white;
            font-size: 1.8rem;
            text-decoration: none;
            margin-left: 1rem;
            font-family: 'Lobster', cursive;
        }

        .header-icons {
            font-size: 1.5rem;
            display: flex;
            gap: 1rem;
        }

        .header-icons i {
            color: white;
            cursor: pointer;
            transition: color 0.3s;
        }

        .header-icons i:hover {
            color: #ffffff;
        }

        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }

        .search-bar {
            display: flex;
            gap: 0.5rem;
            padding: 0.2rem;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 500px;
        }

        .search-bar input {
            padding: 0.3rem;
            font-size: 1rem;
            border: none;
            outline: none;
            background-color: transparent;
            color: #555;
            width: 250px;
        }

        .search-bar input::placeholder {
            color: #888;
        }

        .search-button {
            background: transparent;
            border: none;
            cursor: pointer;
            color: #888;
            font-size: 1.2rem;
            margin-left: auto;
        }

        .search-button:hover {
            color: #2a9d8f;
        }

        /* Product Card Styling */
        .item-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            margin: 1rem auto;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            position: relative;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            transition: transform 0.3s ease-in-out;
        }

        .item-container:hover {
            transform: scale(1.02);
        }

        .zoom-effect {
            overflow: hidden;
            position: relative;
            width: 300px;
            height: 300px;
        }

        .zoom-effect img {
            width: 100%;
            height: 100%;
            transition: transform 0.3s ease-in-out;
            border-radius: 8px;
        }

        .zoom-effect:hover img {
            transform: scale(1.1);
        }

        .text {
            max-width: 3300px;
            flex: 1;
            margin-left: 1.5rem;
        }

        .text h4 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .text p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 1rem;
        }

        .price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2a9d8f;
            margin-bottom: 0.5rem;
        }

        .wishlist-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            color: #aaa;
            cursor: pointer;
            transition: color 0.3s;
        }

        .wishlist-icon.filled {
            color: #e63946;
        }

        .add-to-cart {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: #2a9d8f;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart:hover {
            background-color: #21867a;
        }

        .add-to-cart.added {
            background-color: #21867a;
            color: #fff;
            cursor: default;
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar">
        <a href="HomePage.html"><img src="photos/logo.png" alt="Website Logo" class="logo-img"></a>
        <a class="navbar-brand" href="HomePage.html">Artiva</a>
        <div class="search-container">
            <form class="search-bar" method="GET" action="">
                <input type="text" name="q" placeholder="Search for products..." required>
                <button class="search-button" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="header-icons">
            <a href="Categories/wishlist.html" style="text-decoration: none;">
                <i class="fas fa-heart"></i>
            </a>
            <a href="Categories/cart.html" style="text-decoration: none;">
                <i class="fas fa-shopping-cart"></i>
            </a>
        </div>
    </nav>
</header>

<div class="container mt-4">
    <h2>Search Results</h2>
    <?php
    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $query = htmlspecialchars($_GET['q']);

        // Database connection
        $conn = new mysqli("localhost", "root", "", "artiva");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM product WHERE productname LIKE ? OR description LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%" . $query . "%";
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='item-container' data-product-id='" . htmlspecialchars($row['id']) . "' data-price='" . htmlspecialchars($row['price']) . "'>";
                echo "<i class='fas fa-heart wishlist-icon'></i>";
                echo "<div class='zoom-effect'>";
                echo "<img src='". htmlspecialchars($row['image']) . "' alt='Product Image'>";
                echo "</div>";
                echo "<div class='text'>";
                echo "<h4>" . htmlspecialchars($row['productname']) . "</h4>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "<div class='price'>₹" . htmlspecialchars($row['price']) . "</div>";
                echo "<button class='add-to-cart'>Add to Cart</button>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No results found for '<b>" . $query . "</b>'.</p>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<p>Please enter a search term.</p>";
    }
    ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Wishlist functionality
        const wishlistButtons = document.querySelectorAll('.wishlist-icon');
        let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

        wishlist.forEach(item => {
            const productElement = document.querySelector(`.item-container[data-product-id="${item.id}"]`);
            if (productElement) {
                const wishlistIcon = productElement.querySelector('.wishlist-icon');
                wishlistIcon.classList.add('filled');
            }
        });

        wishlistButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const productElement = e.target.closest('.item-container');
                const productId = productElement.getAttribute('data-product-id');
                const productName = productElement.querySelector('h4').textContent;
                const productPrice = parseFloat(productElement.querySelector('.price').textContent.replace('₹', ''));
                const productImage = productElement.querySelector('img').src;

                const existingWishlistItem = wishlist.find(item => item.id === productId);

                if (existingWishlistItem) {
                    wishlist = wishlist.filter(item => item.id !== productId);
                } else {
                    wishlist.push({ id: productId, name: productName, price: productPrice, image: productImage });
                }

                localStorage.setItem('wishlist', JSON.stringify(wishlist));
                e.target.classList.toggle('filled');
            });
        });

        // Add to cart functionality
        const addToCartButtons = document.querySelectorAll('.add-to-cart');

        addToCartButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const productElement = e.target.closest('.item-container');
                const productId = productElement.getAttribute('data-product-id');
                const productName = productElement.querySelector('h4').textContent;
                const productPrice = parseFloat(productElement.getAttribute('data-price'));
                const productImage = productElement.querySelector('img').src;

                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                const existingProduct = cart.find(item => item.id === productId);

                if (existingProduct) {
                    existingProduct.quantity++;
                } else {
                    cart.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        image: productImage,
                        quantity: 1
                    });
                }

                localStorage.setItem('cart', JSON.stringify(cart));

                e.target.innerHTML = '<i class="fas fa-check"></i> Added';
                e.target.classList.add('added');
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
