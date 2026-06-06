import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    withCredentials: false,
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    }
});

// Interceptor para agregar el Bearer token de Sanctum
api.interceptors.request.use(config => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
});

// Interceptor para manejar errores globalmente
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            // Token inválido o expirado: limpiar y redirigir
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');
            const path = window.location.pathname;
            if (path.startsWith('/cliente') || path.startsWith('/admin')) {
                window.location.href = '/login';
            }
        }
        return Promise.reject(error);
    }
);

export default api;
