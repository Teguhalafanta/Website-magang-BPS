function switchTab(tabName) {
    // Update active tab
    tabs.forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.tab === tabName) {
            tab.classList.add('active');
        }
    });

    // Switch forms
    if (tabName === 'login') {
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        dividerText.textContent = 'Belum punya akun?';
        toggleLink.textContent = 'Sign Up';
    } else {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        dividerText.textContent = 'Sudah punya akun?';
        toggleLink.textContent = 'Sign In';
    }
}

// Tab click handlers
tabs.forEach(tab => {
    tab.addEventListener('click', (e) => {
        e.preventDefault();
        const tabName = tab.dataset.tab;
        switchTab(tabName);
    });
});

// Toggle link handler
toggleLink.addEventListener('click', (e) => {
    e.preventDefault();
    const currentTab = document.querySelector('.nav-tab.active').dataset.tab;
    switchTab(currentTab === 'login' ? 'register' : 'login');
});

// Form submission handlers
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function (e) {
        const submitBtn = form.querySelector('.btn-primary');
        const originalText = submitBtn.textContent.trim();

        // Animasi tombol
        submitBtn.classList.add('loading');
        submitBtn.innerHTML =
            `${originalText.includes('Create') ? 'Creating...' : 'Signing In...'} <div class="loading"></div>`;

        // Simulate process
        setTimeout(() => {
            submitBtn.classList.remove('loading');
            submitBtn.style.background = 'linear-gradient(135deg, #00b894, #00a085)';
            submitBtn.textContent = originalText.includes('Create') ? 'Account Created!' :
                'Welcome!';

            setTimeout(() => {
                submitBtn.style.background =
                    'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                submitBtn.textContent = originalText;
            }, 2000);
        }, 2000);
    });
});

// Ripple effect
function createRipple(event) {
    const button = event.currentTarget;
    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;

    const ripple = document.createElement('span');
    ripple.style.cssText = `
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    width: ${size}px;
    height: ${size}px;
    left: ${x}px;
    top: ${y}px;
    pointer-events: none;
    `;

    button.appendChild(ripple);
    setTimeout(() => ripple.remove(), 600);
}

// Add ripple to buttons
document.querySelectorAll('.btn-primary, .btn-sso, .nav-tab').forEach(button => {
    button.style.position = 'relative';
    button.style.overflow = 'hidden';
    button.addEventListener('click', createRipple);
});

// Enhanced input animations
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('focus', function () {
        this.parentElement.style.transform = 'scale(1.02)';
    });

    input.addEventListener('blur', function () {
        this.parentElement.style.transform = 'scale(1)';
    });
});

// Parallax effect
document.addEventListener('mousemove', function (e) {
    const shapes = document.querySelectorAll('.shape');
    const x = e.clientX / window.innerWidth;
    const y = e.clientY / window.innerHeight;

    shapes.forEach((shape, index) => {
        const speed = (index + 1) * 0.3;
        const xOffset = (x - 0.5) * speed * 15;
        const yOffset = (y - 0.5) * speed * 15;

        shape.style.transform =
            `translate(${xOffset}px, ${yOffset}px) rotate(${shape.style.animationDelay ? parseInt(shape.style.animationDelay) * 10 : 0}deg)`;
    });
});

// Particle system
function createParticles() {
    const particleContainer = document.querySelector('.floating-shapes');

    for (let i = 0; i < 30; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.cssText = `
    position: absolute;
    width: 2px;
    height: 2px;
    background: rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    left: ${Math.random() * 100}%;
    animation: particle-float ${10 + Math.random() * 20}s infinite linear;
    animation-delay: ${Math.random() * 20}s;
    `;
        particleContainer.appendChild(particle);
    }
}

// Initialize
createParticles();
