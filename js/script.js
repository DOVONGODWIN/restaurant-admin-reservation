document.addEventListener('DOMContentLoaded', function() {
    // Sélecteurs
    const menuToggle = document.querySelector('.menu-toggle');
    const navbar = document.querySelector('.navbar');
    const header = document.querySelector('header');

    // Fonctions
    function toggleMenu() {
        navbar?.classList.toggle('active');
        menuToggle?.classList.toggle('active');
    }

    function toggleStickyHeader() {
        header?.classList.toggle('sticky', window.scrollY > 0);
    }

     // Event Listeners
     menuToggle?.addEventListener('click', toggleMenu);
     window.addEventListener('scroll', toggleStickyHeader);
     
    function smoothScroll(e) {
        e.preventDefault();
        const href = this.getAttribute('href');
        if (href !== '#') {
            const targetElement = document.querySelector(href);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        }
    }

    async function addToCart(itemId) {
        try {
            const response = await fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `item_id=${itemId}`
            });
            const data = await response.json();
            if (data.success) {
                alert('Produit ajouté au panier');
                updateCartCount();
            } else {
                throw new Error('Erreur lors de l\'ajout au panier');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert(error.message);
        }
    }

    async function updateCartItemQuantity(itemId, quantity) {
        try {
            const response = await fetch('update_cart_quantity.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `item_id=${itemId}&quantity=${quantity}`
            });
            const data = await response.json();
            if (data.success) {
                updateCartTotal();
            } else {
                throw new Error('Erreur lors de la mise à jour de la quantité');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert(error.message);
        }
    }

    async function removeCartItem(itemId) {
        try {
            const response = await fetch('remove_cart_item.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `item_id=${itemId}`
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            } else {
                throw new Error('Erreur lors de la suppression de l\'article');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert(error.message);
        }
    }

    async function updateCartCount() {
        try {
            const response = await fetch('get_cart_count.php');
            const data = await response.json();
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = data.count;
            }
        } catch (error) {
            console.error('Erreur lors de la mise à jour du compteur du panier:', error);
        }
    }

    async function updateCartTotal() {
        try {
            const response = await fetch('get_cart_total.php');
            const data = await response.json();
            const cartTotalElement = document.getElementById('cart-total');
            if (cartTotalElement) {
                cartTotalElement.textContent = data.total;
            }
        } catch (error) {
            console.error('Erreur lors de la mise à jour du total du panier:', error);
        }
    }

    // Event Listeners
    menuToggle?.addEventListener('click', toggleMenu);
    window.addEventListener('scroll', toggleStickyHeader);
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', smoothScroll);
    });

    document.querySelectorAll('.acheter').forEach(button => {
        button.addEventListener('click', () => addToCart(button.dataset.id));
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', () => updateCartItemQuantity(input.dataset.id, input.value));
    });

    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', () => removeCartItem(button.dataset.id));
    });

    // Validation du formulaire de réservation
    const reservationForm = document.querySelector('#reservation form');
    if (reservationForm) {
        reservationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Ajoutez ici la logique de validation du formulaire
        });
    }

    // Gestion du bouton de paiement PayPal
    const checkoutBtn = document.getElementById('checkout-btn');
    const paypalContainer = document.getElementById('paypal-button-container');

    if (checkoutBtn && paypalContainer) {
        checkoutBtn.addEventListener('click', function() {
            checkoutBtn.style.display = 'none';
            paypalContainer.style.display = 'block';
        });
    }

    // Initialisation
    updateCartCount();
});