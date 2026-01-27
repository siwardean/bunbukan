/**
 * Cookie Consent Management
 * Gère le pop-up de consentement aux cookies et la création des cookies
 */
(function() {
    'use strict';
    
    const banner = document.getElementById('cookie-consent-banner');
    const acceptBtn = document.getElementById('cookie-accept');
    const declineBtn = document.getElementById('cookie-decline');
    
    if (!banner) return;
    
    // Durée du cookie : 1 an (365 jours)
    const cookieExpiry = 365;
    
    /**
     * Crée un cookie avec expiration
     */
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/;SameSite=Lax";
    }
    
    /**
     * Masque le banner avec animation
     */
    function hideBanner() {
        banner.style.opacity = '0';
        banner.style.transform = 'translateY(100%)';
        setTimeout(() => {
            banner.style.display = 'none';
        }, 300);
    }
    
    /**
     * Gestion du clic sur "Accept"
     */
    if (acceptBtn) {
        acceptBtn.addEventListener('click', function() {
            setCookie('bunbukan_cookie_consent', 'accepted', cookieExpiry);
            hideBanner();
        });
    }
    
    /**
     * Gestion du clic sur "Decline"
     */
    if (declineBtn) {
        declineBtn.addEventListener('click', function() {
            setCookie('bunbukan_cookie_consent', 'declined', cookieExpiry);
            hideBanner();
        });
    }
})();
