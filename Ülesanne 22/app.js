function setTheme(theme) {
    document.body.className = theme;
    localStorage.setItem('theme', theme);
}

function loadTheme() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        setTheme(savedTheme);
    } else {
        setTheme('light');
    }
}

document.querySelector('.theme-toggle').addEventListener('click', () => {
    const currentTheme = document.body.className;
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    setTheme(newTheme);
});

loadTheme();
