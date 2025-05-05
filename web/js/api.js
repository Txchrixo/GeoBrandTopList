const API_BASE = '/api/v1';
//endpoints with method and path
const API_ENDPOINTS = {
  LOGIN: { method: 'POST', path: '/login' },
  LOGOUT: { method: 'POST', path: '/logout' },
  BRANDS: { method: 'GET', path: '/brands' },
}

export const Auth = (() => {
    let userData = sessionStorage.getItem("userData") || null;

    return {
        setUserData(data) {
            userData = data;
            sessionStorage.setItem("userData", JSON.stringify(data));
        },
        clearUserData() {
            userData = null;
            sessionStorage.removeItem("userData");
        },
        getUserData() {
            return userData;
        },
        checkAuth() {
            const userData = this.getUserData();
            if (!userData) {
                return false;
            }
            return true;
        }
    };
})();

export async function request(path, options = {}) {
    const headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        ...options.headers,
    };

    const res = await fetch(`${API_BASE}${path}`, {
        ...options,
        headers,
        credentials: 'include',
    });

    if (!res.ok) {
        const err = await res.json(); 
        const error = new Error(err.message || 'API error');
        if (err.errors) error.errors = err.errors;
        throw error;
    }
    const response = await res.json();
    return response.data;
}

export async function login(email, password) {
  const data = await request(API_ENDPOINTS.LOGIN.path, {
    method: API_ENDPOINTS.LOGIN.method,
    body: JSON.stringify({ email, password }),
  });
  Auth.setUserData(data.user);
  return data.user;
}

export function logout() {
    Auth.clearUserData();
}

export async function fetchTopList(page = 1) {
  const path = `${API_ENDPOINTS.BRANDS.path}?page=${page}`;
  return request(path, {
    method: API_ENDPOINTS.BRANDS.method,
  });
}