/*
 * Welcome to your app's main JavaScript file!
 *
 * This file is included via Webpack Encore in base.html.twig.
 */
import './styles/app.scss';
import $ from 'jquery'; // Required for Bootstrap collapse and alert functionality

// Log to confirm Webpack Encore is working
console.log('This log comes from assets/app.js - welcome to Webpack Encore! 🎉');

// Sticky Navbar, Smooth Scrolling, Collapse, Parallax, Card Flip, and Flash Message Auto-Close
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.querySelector('.navbar');
    const navLinks = document.querySelectorAll('.nav-link');
    const parallax = document.querySelector('.parallax-bg');
    const bookCards = document.querySelectorAll('.book-card');

    // Sticky Navbar
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('sticky', 'shadow-lg', 'bg-white');
        } else {
            navbar.classList.remove('sticky', 'shadow-lg', 'bg-white');
        }
    });

    // Smooth Scrolling for Nav Links
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // Ensure navbar collapse works with jQuery
    $('.navbar-toggler').on('click', () => {
        $('#navbarNav').collapse('toggle');
        console.log('Navbar toggler clicked'); // Debug log
    });

    // Parallax Effect (for index.html.twig)
    if (parallax) {
        window.addEventListener('scroll', () => {
            const scrollPosition = window.pageYOffset;
            parallax.style.transform = `translateY(${scrollPosition * 0.5}px)`;
            console.log('Parallax transform applied:', parallax.style.transform); // Debug log
        });
    }

    // Card Flip Effect (for index.html.twig)
    bookCards.forEach(card => {
        const cardInner = card.querySelector('.card-inner');
        if (cardInner) {
            card.addEventListener('mouseenter', () => {
                cardInner.classList.add('rotate-y-180');
                console.log('Card flipped to back:', card.querySelector('.card-title')?.textContent); // Debug log
            });
            card.addEventListener('mouseleave', () => {
                cardInner.classList.remove('rotate-y-180');
                console.log('Card flipped to front:', card.querySelector('.card-title')?.textContent); // Debug log
            });
        }
    });

    // Auto-close flash messages after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
            console.log('Flash message closed:', alert.textContent.trim()); // Debug log
        }, 5000);
    });

    // Debug: Log nav link visibility
    navLinks.forEach(link => {
        console.log(`Nav link: ${link.textContent}, Visible: ${link.offsetParent !== null}`);
    });

    // Debug: Log book card initialization
    console.log(`Initialized ${bookCards.length} book cards for flip effect`);
});