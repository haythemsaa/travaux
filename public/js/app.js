// Simple JavaScript for Travaux Pro

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Image preview for file uploads
    const photoInput = document.getElementById('photos');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files.length > 5) {
                alert('Vous ne pouvez télécharger que 5 photos maximum');
                this.value = '';
                return;
            }

            // Optional: Add image preview functionality here
        });
    }

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const passwordField = form.querySelector('input[name="password"]');
            const confirmField = form.querySelector('input[name="password_confirm"]');

            if (passwordField && confirmField) {
                if (passwordField.value !== confirmField.value) {
                    e.preventDefault();
                    alert('Les mots de passe ne correspondent pas');
                    return false;
                }

                if (passwordField.value.length < 6) {
                    e.preventDefault();
                    alert('Le mot de passe doit contenir au moins 6 caractères');
                    return false;
                }
            }
        });
    });

    // Mobile menu toggle (if needed in future)
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Utility function to format numbers as currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(amount);
}

// Utility function to format dates
function formatDate(dateString) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('fr-FR').format(date);
}
