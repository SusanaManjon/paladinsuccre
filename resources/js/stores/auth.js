import { reactive, computed } from 'vue';
import { getUser } from '../api/auth.js';

// Restaurar usuario desde localStorage al cargar
const storedUser = localStorage.getItem('auth_user');

const state = reactive({
    user: storedUser ? JSON.parse(storedUser) : null,
    isLoading: true,
    isAuthenticated: !!localStorage.getItem('auth_token'),
});

const fetchUser = async () => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        state.isLoading = false;
        state.isAuthenticated = false;
        state.user = null;
        return;
    }

    try {
        const { data } = await getUser();
        setUser(data);
    } catch (error) {
        clearUser();
    } finally {
        state.isLoading = false;
    }
};

const initAuth = async () => {
    state.isLoading = true;
    await fetchUser();
};

const setUser = (user, token = null) => {
    state.user = user;
    state.isAuthenticated = true;
    localStorage.setItem('auth_user', JSON.stringify(user));
    if (token) {
        localStorage.setItem('auth_token', token);
    }
};

const clearUser = () => {
    state.user = null;
    state.isAuthenticated = false;
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
};

const isAdmin = computed(() => state.user?.role === 'admin');
const isClient = computed(() => state.user?.role === 'client');

export function useAuth() {
    return {
        state,
        fetchUser,
        initAuth,
        setUser,
        clearUser,
        isAdmin,
        isClient,
        isLoading: computed(() => state.isLoading),
        isAuthenticated: computed(() => state.isAuthenticated),
    };
}
