import { login as apiLogin, logout as apiLogout } from './api.js';

const form = document.getElementById('loginForm');

if (form) {
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    try {
      await apiLogin(email, password);
      window.location.href = 'dashboard.html';
    } catch (err) {
      alert(err.message);
    }
  });
}

const logoutBtn = document.getElementById('logoutBtn');
if (logoutBtn) {
  logoutBtn.addEventListener('click', () => {
    apiLogout();
    window.location.href = 'login.html';
  });
}