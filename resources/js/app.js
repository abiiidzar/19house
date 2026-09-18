window.trapDialogFocus = (event, dialog) => {
    const focusable = [...dialog.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])')]
        .filter((element) => element.getClientRects().length > 0);

    if (focusable.length === 0) {
        event.preventDefault();
        dialog.focus();
        return;
    }

    const first = focusable[0];
    const last = focusable[focusable.length - 1];
    if (event.shiftKey && (document.activeElement === first || !dialog.contains(document.activeElement))) {
        event.preventDefault();
        last.focus();
    } else if (!event.shiftKey && (document.activeElement === last || !dialog.contains(document.activeElement))) {
        event.preventDefault();
        first.focus();
    }
};

const initialiseHomeMotion = () => {
    const hero = document.querySelector('[data-home-motion]');

    if (!hero || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    document.documentElement.classList.add('motion-enabled');

    requestAnimationFrame(() => hero.classList.add('is-visible'));

    const revealElements = [
        ...document.querySelectorAll('[data-reveal], [data-reveal-item]'),
    ];

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, {
        rootMargin: '0px 0px -10% 0px',
        threshold: 0.12,
    });

    revealElements.forEach((element) => observer.observe(element));
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initialiseHomeMotion, { once: true });
} else {
    initialiseHomeMotion();
}
