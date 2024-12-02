<?php
include('conex.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Location: signup.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Harvest - Fresh Farm Produce</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="landing.css">
</head>
<body class="bg-soft-beige">
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-logo">
                <img src="https://i.pinimg.com/736x/99/f6/f0/99f6f0d107c9734961cb9259b332a23f.jpg" alt="Green Harvest Logo">
                <h1>Green Harvest</h1>
            </a>
            
            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
    
            <div class="nav-links">
                <a href="#home" class="nav-link">Home</a>
                <a href="#products" class="nav-link">Products</a>
                <a href="#services" class="nav-link">Services</a>
                <a href="#about" class="nav-link">About</a>
                <form method="POST">
                    <button type="submit" class="signup-btn">Sign Up</button>
                </form>
            </div>
        </div>
    </nav>

    <section class="hero-section" id="home">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h2 class="hero-title">Fresh Organic Produce From Farm to Table</h2>
            <p class="hero-subtitle">Experience the finest selection of locally grown, organic produce delivered straight to your doorstep.</p>
            <a href="#products" class="hero-cta">Shop Now</a>
        </div>
    </section>
    
    <section id="products" class="container mx-auto py-16">
        <h2 class="text-4xl text-center mb-12 text-green-800">Most Popular Products</h2>
        <form class="search-box">
            <input type="text" placeholder="Search products...">
            <button type="reset"></button>
        </form>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div class="product-card bg-white rounded-lg p-6 text-center shadow-md">
                <img src="https://i.pinimg.com/736x/33/eb/a5/33eba579d76a096f19e4e20630daeb28.jpg" alt="Tomatoes" class="mb-4">
                <h3 class="text-xl font-semibold flex-grow">Fresh Tomatoes</h3>
                <p class="text-green-600 font-bold">$2.99/kg</p>
                <button class="farm-btn text-white px-4 py-2 rounded-full mt-4">Add to Cart</button>
            </div>
            <div class="product-card bg-white rounded-lg p-6 text-center shadow-md">
                <img src="https://i.pinimg.com/736x/5f/0b/42/5f0b42bd132f603bad5748a27bdad933.jpg" alt="Potatoes" class="mb-4">
                <h3 class="text-xl font-semibold flex-grow">Fresh Potatoes</h3>
                <p class="text-green-600 font-bold">$4.99/kg</p>
                <button class="farm-btn text-white px-4 py-2 rounded-full mt-4">Add to Cart</button>
            </div>
            <div class="product-card bg-white rounded-lg p-6 text-center shadow-md">
                <img src="https://i.pinimg.com/736x/d1/a5/c3/d1a5c3640aea92a324c910d9ce5a754c.jpg" alt="Carrots" class="mb-4">
                <h3 class="text-xl font-semibold flex-grow">Fresh Carrots</h3>
                <p class="text-green-600 font-bold">$2.99/kg</p>
                <button class="farm-btn text-white px-4 py-2 rounded-full mt-4">Add to Cart</button>
            </div>
            <div class="product-card bg-white rounded-lg p-6 text-center shadow-md">
                <img src="https://i.pinimg.com/736x/25/59/c2/2559c2d91a561a4a8dd669e134c1db7e.jpg" alt="Spinach" class="mb-4">
                <h3 class="text-xl font-semibold flex-grow">Fresh Spinach</h3>
                <p class="text-green-600 font-bold">$3.49/kg</p>
                <button class="farm-btn text-white px-4 py-2 rounded-full mt-4">Add to Cart</button>
            </div>
        </div>
    </section>

    <section id="services" class="bg-green-100 py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl mb-12 text-green-800">Our Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <i class="fas fa-truck text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold">Free Delivery</h3>
                    <p>Local delivery for orders over $50</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <i class="fas fa-leaf text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold">Organic Certification</h3>
                    <p>100% Certified Organic Produce</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <i class="fas fa-hand-holding-heart text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold">Farm Support</h3>
                    <p>Supporting Local Farmers Directly</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="container mx-auto py-16">
        <div class="flex items-center">
            <div class="w-1/2 pr-12">
                <h2 class="text-4xl mb-6 text-green-800">About Green Harvest</h2>
                <p>We are a local farm committed to delivering the freshest, most sustainable produce directly to your table...</p>
            </div>
            <div class="w-1/2">
                <img src="https://i.pinimg.com/736x/b9/7d/8c/b97d8c21c5c4617f58f567ed52358a3e.jpg" alt="Our Farm" class="rounded-lg shadow-md">
            </div>
        </div>
    </section>

    <footer class="bg-green-800 text-white py-12">
        <div class="container mx-auto grid grid-cols-3 gap-8">
            <div>
                <h4 class="text-xl mb-4">Contact Us</h4>
                <p>Email: contact@greenharvest.com</p>
                <p>Phone: (555) 123-4567</p>
            </div>
            <div>
                <h4 class="text-xl mb-4">Follow Us</h4>
                <div class="flex space-x-4">
                    <a href="#" class="text-2xl hover:text-green-300"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-2xl hover:text-green-300"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-2xl hover:text-green-300"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-xl mb-4">Location</h4>
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12345.67890!2d-0.1276!3d51.5074!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTHCsDMwJzI2LjciTiAwMCcwNy40Ilc!5e0!3m2!1sen!2sus!4v1234567890"
                    width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
        <div class="text-center mt-8 border-t border-green-700 pt-4">
            © 2024 Green Harvest. All Rights Reserved.
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const mobileNav = document.querySelector('.mobile-nav');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            mobileNav.classList.toggle('active');
            menuBtn.innerHTML = mobileNav.classList.contains('active') 
                ? '<i class="fas fa-times"></i>' 
                : '<i class="fas fa-bars"></i>';
        }
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navLinks = document.querySelector('.nav-links');
        const navLinksItems = document.querySelectorAll('.nav-link');

        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            document.body.style.overflow = navLinks.classList.contains('active') ? 'hidden' : '';
            mobileMenuBtn.innerHTML = navLinks.classList.contains('active') 
                ? '<i class="fas fa-times"></i>' 
                : '<i class="fas fa-bars"></i>';
        });

        navLinksItems.forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                document.body.style.overflow = '';
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });

        document.addEventListener('click', (e) => {
            if (!navLinks.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                navLinks.classList.remove('active');
                document.body.style.overflow = '';
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });

       document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.querySelector('.search-box input[type="text"]');
            const resetButton = document.querySelector('.search-box button[type="reset"]');
            const productCards = document.querySelectorAll('.product-card');

            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                productCards.forEach(card => {
                    const productName = card.querySelector('h3').textContent.toLowerCase();
                    card.style.display = productName.includes(searchTerm) ? 'flex' : 'none';
                });
            });

            resetButton.addEventListener('click', () => {
                searchInput.value = '';
                productCards.forEach(card => {
                    card.style.display = 'flex';
                });
            });

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>
</html>