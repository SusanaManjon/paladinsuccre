import api from './axios.js';
import axios from 'axios';

// Usamos axios puro (sin baseURL '/api') para obtener el cookie de CSRF
export const getCsrfCookie = () => axios.get('/sanctum/csrf-cookie', {
    baseURL: window.location.origin
});

export const login = (credentials) => api.post('/login', credentials);
export const register = (data) => api.post('/register', data);
export const logout = () => api.post('/logout');
export const getUser = () => api.get('/user');
