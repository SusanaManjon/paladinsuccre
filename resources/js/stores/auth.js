import { reactive, computed } from 'vue';
import { getUser, getCsrfCookie } from '../api/auth.js';

const state = reactive({
    user: null,
    isLoading: true,
    isAuthenticated: false,
});

const fetchUser = async () => {
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
    try {
        await getCsrfCookie();
        await fetchUser();
    } catch (error) {
        state.isLoading = false;
    }
};

const setUser = (user) => {
    state.user = user;
    state.isAuthenticated = true;
};

const clearUser = () => {
    state.user = null;
    state.isAuthenticated = false;
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
