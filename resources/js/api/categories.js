import api from './axios.js';

// Public
export const getCategories = () => api.get('/categories');

// Admin
export const getAdminCategories = (params) => api.get('/admin/categories', { params });
export const getAdminCategory = (id) => api.get(`/admin/categories/${id}`);
export const createCategory = (formData) => api.post('/admin/categories', formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
});
export const updateCategory = (id, formData) => {
    formData.append('_method', 'PUT');
    return api.post(`/admin/categories/${id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    });
};
export const deleteCategory = (id) => api.delete(`/admin/categories/${id}`);
