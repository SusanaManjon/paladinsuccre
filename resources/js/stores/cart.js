import { reactive, computed, watch } from 'vue';

const CART_KEY = 'paladin_cart';

const loadCart = () => {
    const saved = localStorage.getItem(CART_KEY);
    return saved ? JSON.parse(saved) : [];
};

const state = reactive({
    items: loadCart(),
});

watch(() => state.items, (newItems) => {
    localStorage.setItem(CART_KEY, JSON.stringify(newItems));
}, { deep: true });

const addItem = (product, quantity = 1) => {
    const existing = state.items.find(item => item.product_id === product.id);
    if (existing) {
        existing.quantity += quantity;
    } else {
        state.items.push({
            product_id: product.id,
            name: product.name,
            price: product.price,
            weight: product.weight,
            image_url: product.image_url,
            quantity: quantity,
        });
    }
};

const removeItem = (productId) => {
    state.items = state.items.filter(item => item.product_id !== productId);
};

const updateQuantity = (productId, quantity) => {
    const item = state.items.find(item => item.product_id === productId);
    if (item) {
        item.quantity = quantity > 0 ? quantity : 1;
    }
};

const clearCart = () => {
    state.items = [];
};

const totalItems = computed(() => state.items.reduce((acc, item) => acc + item.quantity, 0));
const totalPrice = computed(() => state.items.reduce((acc, item) => acc + (item.price * item.quantity), 0));

export function useCart() {
    return {
        state,
        addItem,
        removeItem,
        updateQuantity,
        clearCart,
        totalItems,
        totalPrice,
    };
}
