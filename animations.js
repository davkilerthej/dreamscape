// ===== ANIMATIONS JAVASCRIPT FILE =====

// Animation configuration
const ANIMATION_CONFIG = {
    duration: 800,
    easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
    stagger: 100,
    threshold: 0.1
};

// Initialize animations when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
    initHoverAnimations();
    initPageTransitions();
    initParticleEffects();
    initLoadingAnimations();
});

// ===== SCROLL ANIMATIONS =====
function initScrollAnimations() {
    const observerOptions = {
        threshold: ANIMATION_CONFIG.threshold,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateElementOnScroll(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements for scroll animations
    const animatedElements = document.querySelectorAll(`
        .feature-card,
        .gamemode-card,
        .server-card,
        .gamemode-detail,
        .hero-content > *,
        .community-content > *
    `);
    
    animatedElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `all ${ANIMATION_CONFIG.duration}ms ${ANIMATION_CONFIG.easing}`;
        el.style.transitionDelay = `${index * ANIMATION_CONFIG.stagger}ms`;
        
        observer.observe(el);
    });
}

// Animate element when it comes into view
function animateElementOnScroll(element) {
    element.style.opacity = '1';
    element.style.transform = 'translateY(0)';
    
    // Add entrance animation class
    element.classList.add('animated-in');
    
    // Trigger child animations if any
    const children = element.querySelectorAll('[data-animate]');
    children.forEach((child, index) => {
        setTimeout(() => {
            animateChildElement(child);
        }, index * 100);
    });
}

// Animate child elements
function animateChildElement(element) {
    const animationType = element.dataset.animate;
    
    switch (animationType) {
        case 'fade-in':
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, 100);
            break;
            
        case 'slide-in-left':
            element.style.opacity = '0';
            element.style.transform = 'translateX(-30px)';
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateX(0)';
            }, 100);
            break;
            
        case 'slide-in-right':
            element.style.opacity = '0';
            element.style.transform = 'translateX(30px)';
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateX(0)';
            }, 100);
            break;
            
        case 'scale-in':
            element.style.opacity = '0';
            element.style.transform = 'scale(0.8)';
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'scale(1)';
            }, 100);
            break;
    }
}

// ===== HOVER ANIMATIONS =====
function initHoverAnimations() {
    // Card hover effects
    const cards = document.querySelectorAll('.feature-card, .gamemode-card, .server-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            animateCardHover(this, 'enter');
        });
        
        card.addEventListener('mouseleave', function() {
            animateCardHover(this, 'leave');
        });
    });
    
    // Button hover effects
    const buttons = document.querySelectorAll('.btn');
    
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            animateButtonHover(this, 'enter');
        });
        
        button.addEventListener('mouseleave', function() {
            animateButtonHover(this, 'leave');
        });
    });
    
    // Navigation link hover effects
    const navLinks = document.querySelectorAll('.nav-menu a');
    
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            animateNavLinkHover(this, 'enter');
        });
        
        link.addEventListener('mouseleave', function() {
            animateNavLinkHover(this, 'leave');
        });
    });
}

// Animate card hover
function animateCardHover(card, action) {
    const icon = card.querySelector('.feature-icon, .gamemode-icon, .server-icon');
    const content = card.querySelector('.gamemode-content, .feature-content');
    
    if (action === 'enter') {
        // Lift card
        card.style.transform = 'translateY(-10px) scale(1.02)';
        card.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.3)';
        
        // Animate icon
        if (icon) {
            icon.style.transform = 'scale(1.1) rotate(5deg)';
            icon.style.boxShadow = '0 15px 35px rgba(102, 126, 234, 0.4)';
        }
        
        // Animate content
        if (content) {
            content.style.transform = 'translateY(-5px)';
        }
        
        // Add glow effect
        card.style.borderColor = 'rgba(102, 126, 234, 0.3)';
        
    } else {
        // Reset card
        card.style.transform = 'translateY(0) scale(1)';
        card.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.1)';
        
        // Reset icon
        if (icon) {
            icon.style.transform = 'scale(1) rotate(0deg)';
            icon.style.boxShadow = '0 10px 25px rgba(102, 126, 234, 0.3)';
        }
        
        // Reset content
        if (content) {
            content.style.transform = 'translateY(0)';
        }
        
        // Remove glow effect
        card.style.borderColor = 'rgba(255, 255, 255, 0.1)';
    }
}

// Animate button hover
function animateButtonHover(button, action) {
    if (action === 'enter') {
        button.style.transform = 'translateY(-2px) scale(1.05)';
        button.style.boxShadow = '0 8px 25px rgba(102, 126, 234, 0.6)';
    } else {
        button.style.transform = 'translateY(0) scale(1)';
        button.style.boxShadow = '0 4px 15px rgba(102, 126, 234, 0.4)';
    }
}

// Animate navigation link hover
function animateNavLinkHover(link, action) {
    const underline = link.querySelector('::after') || link;
    
    if (action === 'enter') {
        link.style.transform = 'translateY(-2px)';
        link.style.background = 'rgba(255, 255, 255, 0.15)';
    } else {
        link.style.transform = 'translateY(0)';
        link.style.background = 'rgba(255, 255, 255, 0.1)';
    }
}

// ===== PAGE TRANSITIONS =====
function initPageTransitions() {
    // Smooth page transitions
    const links = document.querySelectorAll('a[href]:not([href^="#"]):not([href^="mailto:"]):not([href^="tel:"])');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            // Only handle internal links
            if (this.hostname === window.location.hostname) {
                e.preventDefault();
                
                const targetUrl = this.href;
                
                // Add exit animation
                document.body.classList.add('page-exit');
                
                // Navigate after animation
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 300);
            }
        });
    });
    
    // Page entrance animation
    window.addEventListener('load', function() {
        document.body.classList.add('page-enter');
    });
}

// ===== PARTICLE EFFECTS =====
function initParticleEffects() {
    // Hero section particles
    const heroParticles = document.querySelector('.hero-particles');
    if (heroParticles) {
        createParticles(heroParticles, 50);
    }
    
    // Background particles
    createBackgroundParticles();
}

// Create particle system
function createParticles(container, count) {
    for (let i = 0; i < count; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        // Random properties
        const size = Math.random() * 3 + 1;
        const x = Math.random() * 100;
        const y = Math.random() * 100;
        const duration = Math.random() * 20 + 10;
        const delay = Math.random() * 5;
        
        particle.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            left: ${x}%;
            top: ${y}%;
            animation: float-particle ${duration}s ease-in-out infinite;
            animation-delay: ${delay}s;
        `;
        
        container.appendChild(particle);
    }
}

// Create background particles
function createBackgroundParticles() {
    const body = document.body;
    
    // Add CSS for background particles
    const style = document.createElement('style');
    style.textContent = `
        .bg-particle {
            position: fixed;
            width: 2px;
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            pointer-events: none;
            z-index: -1;
        }
        
        @keyframes float-particle {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 0.8; }
        }
        
        @keyframes bg-float {
            0%, 100% { transform: translateY(0px) translateX(0px); opacity: 0.1; }
            50% { transform: translateY(-30px) translateX(10px); opacity: 0.3; }
        }
    `;
    
    document.head.appendChild(style);
    
    // Create background particles
    for (let i = 0; i < 30; i++) {
        const particle = document.createElement('div');
        particle.className = 'bg-particle';
        
        const x = Math.random() * 100;
        const y = Math.random() * 100;
        const duration = Math.random() * 30 + 20;
        const delay = Math.random() * 10;
        
        particle.style.cssText = `
            left: ${x}%;
            top: ${y}%;
            animation: bg-float ${duration}s ease-in-out infinite;
            animation-delay: ${delay}s;
        `;
        
        body.appendChild(particle);
    }
}

// ===== LOADING ANIMATIONS =====
function initLoadingAnimations() {
    // Page loading animation
    const loader = document.querySelector('.page-loader');
    if (loader) {
        window.addEventListener('load', function() {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        });
    }
    
    // Content loading animations
    const contentSections = document.querySelectorAll('.content-section');
    contentSections.forEach((section, index) => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            section.style.transition = 'all 0.8s ease-out';
            section.style.opacity = '1';
            section.style.transform = 'translateY(0)';
        }, index * 200);
    });
}

// ===== ADVANCED ANIMATIONS =====

// Parallax scrolling effect
function initParallaxEffect() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    
    window.addEventListener('scroll', throttle(function() {
        const scrolled = window.pageYOffset;
        
        parallaxElements.forEach(element => {
            const speed = parseFloat(element.dataset.parallax) || 0.5;
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    }, 16));
}

// Text reveal animation
function initTextReveal() {
    const textElements = document.querySelectorAll('[data-text-reveal]');
    
    const textObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                revealText(entry.target);
                textObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    textElements.forEach(el => textObserver.observe(el));
}

// Reveal text character by character
function revealText(element) {
    const text = element.textContent;
    element.textContent = '';
    
    for (let i = 0; i < text.length; i++) {
        const span = document.createElement('span');
        span.textContent = text[i];
        span.style.opacity = '0';
        span.style.animation = `revealChar 0.1s ease-out ${i * 0.05}s forwards`;
        element.appendChild(span);
    }
}

// Add reveal animation CSS
const revealStyle = document.createElement('style');
revealStyle.textContent = `
    @keyframes revealChar {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(revealStyle);

// ===== PERFORMANCE OPTIMIZATION =====

// Throttle function for scroll events
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Debounce function for resize events
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ===== ANIMATION CONTROLS =====

// Pause animations when page is not visible
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        document.body.classList.add('animations-paused');
    } else {
        document.body.classList.remove('animations-paused');
    }
});

// Reduce motion for users who prefer it
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.body.classList.add('reduced-motion');
}

// ===== UTILITY FUNCTIONS =====

// Check if element is in viewport
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Get random number between min and max
function randomBetween(min, max) {
    return Math.random() * (max - min) + min;
}

// Format number with commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// ===== INITIALIZATION COMPLETE =====
console.log('Animations system initialized! ✨'); 