import api from './axios.js';

// Admin
export const getAdminOrders = (params) => api.get('/admin/orders', { params });
export const getAdminOrder = (id) => api.get(`/admin/orders/${id}`);
export const updateOrderStatus = (id, status) => api.patch(`/admin/orders/${id}/status`, { status });
export const verifyAdminPayment = (id) => api.patch(`/admin/orders/${id}/verify`);
export const uploadAdminPaymentProof = (id, data) => api.post(`/admin/orders/${id}/payment-proof`, data, {
    headers: { 'Content-Type': 'multipart/form-data' }
});

// Client
export const getClientOrders = (params) => api.get('/client/orders', { params });
export const getClientOrder = (id) => api.get(`/client/orders/${id}`);
export const createOrder = (data) => api.post('/client/orders', data);
export const uploadPaymentProof = (id, data) => api.post(`/client/orders/${id}/payment-proof`, data, {
    headers: { 'Content-Type': 'multipart/form-data' }
});
