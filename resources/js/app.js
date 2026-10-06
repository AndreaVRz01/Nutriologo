document.addEventListener('DOMContentLoaded', () => {
    // Modo Claro / Oscuro
    const themeBtn = document.getElementById('theme-toggle');
    if (themeBtn) {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);

        themeBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    }

    // Modal Carga Masiva
    const openModalBtn = document.getElementById('open-masivo-modal');
    const closeModalBtn = document.getElementById('close-masivo-modal');
    const modal = document.getElementById('masivo-modal');

    if (openModalBtn && modal) {
        openModalBtn.addEventListener('click', () => modal.style.display = 'flex');
        if (closeModalBtn) closeModalBtn.addEventListener('click', () => modal.style.display = 'none');
    }
});