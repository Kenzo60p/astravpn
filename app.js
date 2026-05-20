document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            themeToggle.textContent = document.documentElement.classList.contains('dark') ? 'Light Mode' : 'Dark Mode';
        });
    }

    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            const formData = new FormData(loginForm);
            const response = await fetch('/login', {
                method: 'POST',
                body: formData,
            });
            const data = await response.json();
            if (response.ok) {
                showToast(data.message || 'Login successful');
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                showToast(data.error || 'Unable to login', true);
            }
        });
    }

    const configForm = document.getElementById('configForm');
    if (configForm) {
        configForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            const formData = new FormData(configForm);
            const token = localStorage.getItem('astravpn_token');
            const response = await fetch('/api/configs/generate', {
                method: 'POST',
                headers: {
                    Authorization: token ? 'Bearer ' + token : '',
                },
                body: formData,
            });
            const data = await response.json();
            if (response.ok) {
                showToast('Configuration generated successfully');
                console.log(data.config);
            } else {
                showToast(data.error || 'Unable to generate config', true);
            }
        });
    }
});

function showToast(message, error = false) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    toast.style.border = error ? '1px solid rgba(248,113,113,0.5)' : '1px solid rgba(34,211,238,0.5)';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4500);
}
