const navToggle = document.querySelector('.nav-toggle');
const siteNav = document.querySelector('.site-nav');
const liveTicker = document.getElementById('liveTicker');

let relativeTimeFormatter;

if (typeof Intl !== 'undefined' && typeof Intl.RelativeTimeFormat === 'function') {
    relativeTimeFormatter = new Intl.RelativeTimeFormat('tr', { numeric: 'auto' });
}

const relativeTimeThresholds = [
    { limit: 30, divisor: 1, unit: 'second' },
    { limit: 90, divisor: 60, unit: 'minute' },
    { limit: 3600, divisor: 60, unit: 'minute' },
    { limit: 86400, divisor: 3600, unit: 'hour' },
    { limit: 604800, divisor: 86400, unit: 'day' },
    { limit: 2592000, divisor: 604800, unit: 'week' },
    { limit: 31536000, divisor: 2592000, unit: 'month' },
    { limit: Number.POSITIVE_INFINITY, divisor: 31536000, unit: 'year' }
];

const formatRelativeTime = (isoString) => {
    if (!relativeTimeFormatter) {
        return '';
    }

    const timestamp = Date.parse(isoString);

    if (Number.isNaN(timestamp)) {
        return '';
    }

    const now = Date.now();
    const secondsDifference = Math.round((timestamp - now) / 1000);
    const absoluteSeconds = Math.abs(secondsDifference);

    if (absoluteSeconds <= relativeTimeThresholds[0].limit) {
        return 'Şimdi';
    }

    for (const threshold of relativeTimeThresholds.slice(1)) {
        if (absoluteSeconds < threshold.limit) {
            const value = Math.round(secondsDifference / threshold.divisor);
            return relativeTimeFormatter.format(value, threshold.unit);
        }
    }

    return '';
};

const updateRelativeTimes = (root = document) => {
    if (!relativeTimeFormatter) {
        return;
    }

    const timeElements = root.querySelectorAll('[data-relative-time]');

    timeElements.forEach((timeElement) => {
        const isoValue = timeElement.getAttribute('datetime');

        if (!isoValue) {
            return;
        }

        const relativeLabel = formatRelativeTime(isoValue);

        if (relativeLabel) {
            timeElement.textContent = relativeLabel;
        }
    });
};

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

const setupBreakingNews = () => {
    const breakingList = document.querySelector('.breaking-news__list');

    if (!breakingList) {
        return null;
    }

    const moreButton = document.querySelector('.breaking-news__more');
    const baseMoreLabel = moreButton ? moreButton.textContent.trim() : 'Daha Fazla Haber Yükle';
    const articleUrlBase = breakingList.dataset.articleUrl || '/article.php';
    const queue = [];
    const queueSlugs = new Set();
    const displayedSlugs = new Set();
    const articleRegistry = new Map();

    const registerArticle = (item) => {
        if (!item || !item.slug) {
            return;
        }

        articleRegistry.set(item.slug, item);
    };

    const pushToQueue = (item, toFront = false) => {
        if (!item || !item.slug) {
            return;
        }

        registerArticle(item);

        if (displayedSlugs.has(item.slug) || queueSlugs.has(item.slug)) {
            return;
        }

        if (toFront) {
            queue.unshift(item);
        } else {
            queue.push(item);
        }

        queueSlugs.add(item.slug);
    };

    const updateMoreButtonState = () => {
        if (!moreButton) {
            return;
        }

        if (!queue.length) {
            moreButton.disabled = true;
            moreButton.setAttribute('aria-disabled', 'true');
            moreButton.textContent = 'Tüm haberler görüntülendi';
            return;
        }

        moreButton.disabled = false;
        moreButton.removeAttribute('aria-disabled');
        moreButton.textContent = `${baseMoreLabel} (${queue.length})`;
    };

    const enforceLimit = (limit = 12) => {
        while (breakingList.children.length > limit) {
            const lastItem = breakingList.lastElementChild;

            if (!lastItem) {
                break;
            }

            const slug = lastItem.dataset.article;
            breakingList.removeChild(lastItem);

            if (slug) {
                displayedSlugs.delete(slug);
                const stored = articleRegistry.get(slug);
                pushToQueue(stored);
            }
        }
    };

    const createBreakingItem = (item) => {
        const article = document.createElement('article');
        article.className = 'breaking-news__item';
        article.setAttribute('role', 'listitem');
        article.dataset.article = item.slug;

        const badge = document.createElement('span');
        badge.className = 'badge badge--ghost';
        badge.textContent = item.category_name || item.category || '';

        const heading = document.createElement('h3');
        const link = document.createElement('a');
        link.href = `${articleUrlBase}?slug=${encodeURIComponent(item.slug)}`;
        link.textContent = item.title;
        heading.appendChild(link);

        const timeElement = document.createElement('time');
        timeElement.setAttribute('datetime', item.published_at || '');
        timeElement.dataset.relativeTime = 'true';
        const relativeLabel = item.published_at ? formatRelativeTime(item.published_at) : '';

        if (relativeLabel) {
            timeElement.textContent = relativeLabel;
        }

        article.append(badge, heading, timeElement);
        return article;
    };

    const appendItems = (items, position = 'end') => {
        if (!Array.isArray(items) || !items.length) {
            return;
        }

        const nodes = [];

        items.forEach((item) => {
            if (!item || !item.slug) {
                return;
            }

            registerArticle(item);

            if (queueSlugs.has(item.slug)) {
                queueSlugs.delete(item.slug);
                const queueIndex = queue.findIndex((queuedItem) => queuedItem.slug === item.slug);

                if (queueIndex >= 0) {
                    queue.splice(queueIndex, 1);
                }
            }

            if (displayedSlugs.has(item.slug)) {
                return;
            }

            displayedSlugs.add(item.slug);
            nodes.push(createBreakingItem(item));
        });

        if (!nodes.length) {
            return;
        }

        if (position === 'start') {
            for (let index = nodes.length - 1; index >= 0; index -= 1) {
                breakingList.insertBefore(nodes[index], breakingList.firstChild);
            }
            enforceLimit();
        } else {
            const fragment = document.createDocumentFragment();
            nodes.forEach((node) => fragment.appendChild(node));
            breakingList.appendChild(fragment);
        }

        updateRelativeTimes(breakingList);
    };

    const loadMoreChunk = (chunkSize = 4) => {
        if (!queue.length) {
            updateMoreButtonState();
            return;
        }

        const chunk = queue.splice(0, chunkSize);
        chunk.forEach((item) => queueSlugs.delete(item.slug));
        appendItems(chunk, 'end');
        updateMoreButtonState();
    };

    const appendFromFeed = (items) => {
        if (!Array.isArray(items) || !items.length) {
            return;
        }

        appendItems(items, 'start');
        items.forEach((item) => pushToQueue(item));
        updateMoreButtonState();
    };

    breakingList.querySelectorAll('[data-article]').forEach((articleElement) => {
        const slug = articleElement.dataset.article;

        if (!slug) {
            return;
        }

        displayedSlugs.add(slug);

        const badge = articleElement.querySelector('.badge');
        const link = articleElement.querySelector('h3 a');
        const timeElement = articleElement.querySelector('time');

        registerArticle({
            slug,
            title: link ? link.textContent.trim() : '',
            category_name: badge ? badge.textContent.trim() : '',
            published_at: timeElement ? timeElement.getAttribute('datetime') || '' : ''
        });
    });

    let initialQueue = [];

    try {
        initialQueue = JSON.parse(breakingList.dataset.feed || '[]');
    } catch (error) {
        initialQueue = [];
    }

    if (Array.isArray(initialQueue)) {
        initialQueue.forEach((item) => pushToQueue(item));
    }

    updateMoreButtonState();
    updateRelativeTimes(breakingList);

    if (moreButton) {
        moreButton.addEventListener('click', () => {
            loadMoreChunk();
        });
    }

    return {
        appendFromFeed,
        enqueue: (items) => {
            if (!Array.isArray(items)) {
                return;
            }

            items.forEach((item) => pushToQueue(item));
            updateMoreButtonState();
        }
    };
};

const setupHomeFeedRefresh = (breakingNewsController) => {
    const siteMain = document.querySelector('.site-main');

    if (!siteMain) {
        return null;
    }

    const feedUrl = siteMain.dataset.homeFeed;

    if (!feedUrl) {
        return null;
    }

    const liveList = document.querySelector('[data-live-updates-list]');
    const agendaList = document.querySelector('[data-agenda-list]');
    const breakingList = document.querySelector('.breaking-news__list');
    const articleUrlBase = breakingList ? breakingList.dataset.articleUrl || '/article.php' : '/article.php';
    let lastAgendaSignature = '';
    let lastLiveSignature = '';
    let lastGeneratedAt = '';
    let isFetching = false;

    const renderLiveUpdates = (messages) => {
        if (!liveList || !Array.isArray(messages)) {
            return;
        }

        const signature = JSON.stringify(messages);

        if (signature === lastLiveSignature) {
            return;
        }

        lastLiveSignature = signature;
        liveList.innerHTML = '';

        messages.slice(0, 6).forEach((message) => {
            const item = document.createElement('li');
            const badge = document.createElement('span');
            badge.className = 'news-digest__time';
            badge.textContent = 'Canlı';
            const paragraph = document.createElement('p');
            paragraph.textContent = message;
            item.append(badge, paragraph);
            liveList.appendChild(item);
        });
    };

    const renderAgenda = (items) => {
        if (!agendaList || !Array.isArray(items)) {
            return;
        }

        const signature = JSON.stringify(items.map((item) => [item.slug, item.published_at]));

        if (signature === lastAgendaSignature) {
            return;
        }

        lastAgendaSignature = signature;
        agendaList.innerHTML = '';

        items.slice(0, 8).forEach((item) => {
            if (!item || !item.slug) {
                return;
            }

            const listItem = document.createElement('li');
            const timeElement = document.createElement('time');
            timeElement.className = 'news-digest__time';
            timeElement.setAttribute('datetime', item.published_at || '');
            timeElement.dataset.relativeTime = 'true';

            const relativeLabel = item.published_at ? formatRelativeTime(item.published_at) : '';

            if (relativeLabel) {
                timeElement.textContent = relativeLabel;
            }

            const paragraph = document.createElement('p');
            const link = document.createElement('a');
            link.href = `${articleUrlBase}?slug=${encodeURIComponent(item.slug)}`;
            link.textContent = item.title;
            paragraph.appendChild(link);

            listItem.append(timeElement, paragraph);
            agendaList.appendChild(listItem);
        });

        updateRelativeTimes(agendaList);
    };

    const applyLatestArticles = (items) => {
        if (!breakingNewsController || !Array.isArray(items)) {
            return;
        }

        breakingNewsController.appendFromFeed(items);
    };

    const fetchFeed = async () => {
        if (isFetching) {
            return;
        }

        isFetching = true;

        try {
            const response = await fetch(feedUrl, {
                headers: {
                    Accept: 'application/json'
                }
            });

            if (!response.ok) {
                return;
            }

            const payload = await response.json();

            if (!payload || typeof payload !== 'object') {
                return;
            }

            if (payload.generated_at === lastGeneratedAt) {
                return;
            }

            lastGeneratedAt = payload.generated_at;

            applyLatestArticles(payload.latest);
            renderLiveUpdates(payload.live);
            renderAgenda(payload.agenda);
        } catch (error) {
            // Silently ignore network errors to avoid interrupting the experience.
        } finally {
            isFetching = false;
        }
    };

    fetchFeed();

    const refreshInterval = window.setInterval(() => {
        if (!document.hidden) {
            fetchFeed();
        }
    }, 60000);

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            fetchFeed();
        }
    });

    return () => {
        window.clearInterval(refreshInterval);
    };
};

updateRelativeTimes();

if (relativeTimeFormatter) {
    window.setInterval(() => {
        updateRelativeTimes();
    }, 60000);
}

const breakingNewsController = setupBreakingNews();
setupHomeFeedRefresh(breakingNewsController);
