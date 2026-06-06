import api from './axios.js';

// Public
export const getProducts = (params) => api.get('/products', { params });
export const getProduct = (slug) => api.get(`/products/${slug}`);

// Admin
export const getAdminProducts = (params) => api.get('/admin/products', { params });
export const getAdminProduct = (id) => api.get(`/admin/products/${id}`);
export const createProduct = (formData) => api.post('/admin/products', formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
});
export const updateProduct = (id, formData) => {
    formData.append('_method', 'PUT'); // Para enviar archivos en una actualización
    return api.post(`/admin/products/${id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    });
};
export const deleteProduct = (id) => api.delete(`/admin/products/${id}`);
