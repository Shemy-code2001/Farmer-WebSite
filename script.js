document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-product');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.closest('.product-card').dataset.productId;
            const confirmModal = document.createElement('div');
            confirmModal.innerHTML = `
                <div class="modal-overlay">
                    <div class="modal-content">
                        <h2>Confirmer la suppression</h2>
                        <p>Êtes-vous sûr de vouloir supprimer ce produit ?</p>
                        <div class="modal-actions">
                            <button class="btn btn-cancel">Annuler</button>
                            <button class="btn btn-delete confirm-delete">Supprimer</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(confirmModal);
            const cancelBtn = confirmModal.querySelector('.btn-cancel');
            const confirmBtn = confirmModal.querySelector('.confirm-delete');

            cancelBtn.addEventListener('click', () => {
                document.body.removeChild(confirmModal);
            });

            confirmBtn.addEventListener('click', () => {
                window.location.href = `dashboard.php?delete_product=${productId}`;
            });
        });
    });
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-15px)';
            this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.15)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
        });
    });
    const messages = document.querySelectorAll('.message');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 500);
        }, 5000);
    });
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) { 
                    alert('L\'image est trop volumineuse. Taille maximale: 5MB');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.querySelector('.image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.className = 'image-preview';
                        imageInput.parentNode.insertBefore(preview, imageInput.nextSibling);
                    }
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Aperçu de l'image" style="max-width: 200px; margin: 10px 0;">
                        <p>Aperçu de l'image</p>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    const searchInput = document.getElementById('product-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');
            
            productCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    const sortSelect = document.getElementById('product-sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortBy = this.value;
            const productsGrid = document.querySelector('.products-grid');
            const productCards = Array.from(document.querySelectorAll('.product-card'));

            productCards.sort((a, b) => {
                const aValue = a.querySelector(`.sort-${sortBy}`).textContent;
                const bValue = b.querySelector(`.sort-${sortBy}`).textContent;
                
                return sortBy === 'price' 
                    ? parseFloat(aValue) - parseFloat(bValue)
                    : aValue.localeCompare(bValue);
            });
            productsGrid.innerHTML = '';
            productCards.forEach(card => productsGrid.appendChild(card));
        });
    }
    function checkNotifications() {
        fetch('get_notifications.php')
            .then(response => response.json())
            .then(notifications => {
                const notificationContainer = document.getElementById('notifications');
                if (notifications.length > 0) {
                    notificationContainer.innerHTML = '';
                    notifications.forEach(notification => {
                        const notificationElement = document.createElement('div');
                        notificationElement.className = 'notification';
                        notificationElement.innerHTML = `
                            <span class="notification-icon">${notification.icon}</span>
                            <span class="notification-message">${notification.message}</span>
                        `;
                        notificationContainer.appendChild(notificationElement);
                    });
                    notificationContainer.classList.add('has-notifications');
                } else {
                    notificationContainer.classList.remove('has-notifications');
                }
            })
            .catch(error => console.error('Erreur de récupération des notifications:', error));
    }

    setInterval(checkNotifications, 60000);
    checkNotifications(); 
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            const isDarkMode = document.body.classList.contains('dark-mode');
            localStorage.setItem('dark-mode', isDarkMode);
        });

        if (localStorage.getItem('dark-mode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    }
});
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }, 10);
}
function loadContent(url, targetSelector) {
    const target = document.querySelector(targetSelector);
    target.innerHTML = '<div class="loader">Chargement...</div>';

    fetch(url)
        .then(response => response.text())
        .then(html => {
            target.innerHTML = html;
        })
        .catch(error => {
            target.innerHTML = 'Erreur de chargement du contenu';
            console.error('Erreur:', error);
        });
}

document.addEventListener("DOMContentLoaded", () => {
    const links = document.querySelectorAll(".sidebar-menu a"); 
    const contentSection = document.getElementById("products-section"); 

    links.forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault(); 
            const url = link.getAttribute("href"); 

            if (url) {
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Erreur lors du chargement de la page");
                        }
                        return response.text();
                    })
                    .then(data => {
                        contentSection.innerHTML = data;
                    })
                    .catch(error => {
                        console.error("Erreur :", error);
                        contentSection.innerHTML = "<p>Impossible de charger la page.</p>";
                    });
            }
        });
    });
});
contentSection.classList.add("loading");
fetch(url)
    .then(response => {
        contentSection.classList.remove("loading");
        return response.text();
    });
