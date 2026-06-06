import api from './axios.js';

// Admin - Users
export const getUsers = (params) => api.get('/admin/users', { params });
export const getUser = (id) => api.get(`/admin/users/${id}`);
export const createUser = (data) => api.post('/admin/users', data);
export const getAdminDashboard = () => api.get('/admin/dashboard');

// Client - Profile
export const getProfile = () => api.get('/client/profile');
export const updateProfile = (data) => api.put('/client/profile', data);

// Public - Home
export const getHomeData = () => api.get('/home-data');
