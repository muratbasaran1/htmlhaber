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

const newsletterForm = document.querySelector('.newsletter-form');

if (newsletterForm) {
    const feedback = newsletterForm.querySelector('.form-feedback');
    const emailInput = newsletterForm.querySelector('input[type="email"]');

    const setFeedback = (message, type) => {
        if (!feedback) {
            return;
        }

        feedback.textContent = message;
        feedback.classList.remove('form-feedback--error', 'form-feedback--success');

        if (type) {
            feedback.classList.add(`form-feedback--${type}`);
        }
    };

    newsletterForm.addEventListener('submit', async (event) => {
        if (!emailInput) {
            return;
        }

        event.preventDefault();
        const email = emailInput.value.trim();

        if (email === '') {
            setFeedback('Lütfen e-posta adresinizi girin.', 'error');
            emailInput.focus();
            return;
        }

        setFeedback('Kaydınız oluşturuluyor…');
        newsletterForm.classList.add('is-loading');

        try {
            const response = await fetch(newsletterForm.action || '/subscribe.php', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
                body: new FormData(newsletterForm),
            });

            const payload = await response.json();

            if (!response.ok || payload.status !== 'success') {
                const message = payload && payload.message ? payload.message : 'Abonelik oluşturulamadı.';
                setFeedback(message, 'error');
                return;
            }

            setFeedback(payload.message || 'Aboneliğiniz oluşturuldu.', 'success');
            newsletterForm.reset();
        } catch (error) {
            setFeedback('Bağlantı hatası oluştu. Lütfen tekrar deneyin.', 'error');
        } finally {
            newsletterForm.classList.remove('is-loading');
        }
    });
}
