const navToggle = document.querySelector('.nav-toggle');
const siteNav = document.querySelector('.site-nav');
const liveTicker = document.getElementById('liveTicker');

if (navToggle && siteNav) {
    navToggle.addEventListener('click', () => {
        const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';
        navToggle.setAttribute('aria-expanded', String(!isExpanded));
        siteNav.classList.toggle('active');
    });
}

if (liveTicker) {
    const messages = JSON.parse(liveTicker.dataset.messages || '[]');
    let index = 0;

    const renderMessage = () => {
        if (!messages.length) {
            liveTicker.textContent = '';
            return;
        }

        liveTicker.classList.remove('ticker-animate');
        void liveTicker.offsetWidth; // restart animation
        liveTicker.classList.add('ticker-animate');
        liveTicker.textContent = messages[index];
        index = (index + 1) % messages.length;
    };

    renderMessage();
    setInterval(renderMessage, 5000);
}
