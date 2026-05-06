const observerOptions = {
    threshold: 0.1,
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('reveal-active');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

export const vReveal = {
    mounted(el, binding) {
        el.classList.add('reveal-base');

        const options = typeof binding.value === 'string' ? { direction: binding.value } : (binding.value || {});
        const direction = options.direction || 'bottom';
        const duration = options.duration || '1s';
        const delay = options.delay || '0s';
        const distance = options.distance || '40px';

        let offsetX = '0', offsetY = '0';
        if (direction === 'top') offsetY = `-${distance}`;
        if (direction === 'bottom') offsetY = distance;
        if (direction === 'left') offsetX = `-${distance}`;
        if (direction === 'right') offsetX = distance;

        el.style.setProperty('--reveal-x', offsetX);
        el.style.setProperty('--reveal-y', offsetY);
        el.style.setProperty('--reveal-duration', duration);
        el.style.setProperty('--reveal-delay', delay);

        observer.observe(el);
    },

    unmounted(el) {
        observer.unobserve(el);
    }
};