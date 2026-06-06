import { createRouter, createWebHistory } from 'vue-router';
import { useAuth } from '../stores/auth.js';

// Layouts
const PublicLayout = () => import('../layouts/PublicLayout.vue');
const AdminLayout = () => import('../layouts/AdminLayout.vue');
const ClientLayout = () => import('../layouts/ClientLayout.vue');

// Public Pages
const HomePage = () => import('../Pages/public/HomePage.vue');
const CatalogPage = () => import('../Pages/public/CatalogPage.vue');
const ProductDetailPage = () => import('../Pages/public/ProductDetailPage.vue');

// Auth Pages
const LoginPage = () => import('../Pages/auth/LoginPage.vue');
const RegisterPage = () => import('../Pages/auth/RegisterPage.vue');

// Admin Pages
const AdminDashboard = () => import('../Pages/admin/AdminDashboard.vue');
const ProductsList = () => import('../Pages/admin/ProductsList.vue');
const ProductFormPage = () => import('../Pages/admin/ProductFormPage.vue');
const CategoriesList = () => import('../Pages/admin/CategoriesList.vue');
const CategoryFormPage = () => import('../Pages/admin/CategoryFormPage.vue');
const UsersList = () => import('../Pages/admin/UsersList.vue');
const UserFormPage = () => import('../Pages/admin/UserFormPage.vue');
const OrdersList = () => import('../Pages/admin/OrdersList.vue');

// Client Pages
const ClientDashboard = () => import('../Pages/client/ClientDashboard.vue');
const CartPage = () => import('../Pages/client/CartPage.vue');
const CheckoutPage = () => import('../Pages/client/CheckoutPage.vue');
const OrdersPage = () => import('../Pages/client/OrdersPage.vue');
const ProfilePage = () => import('../Pages/client/ProfilePage.vue');

export const routes = [
    {
        path: '/',
        component: PublicLayout,
        children: [
            { 
                path: '', 
                name: 'home', 
                component: HomePage, 
                meta: { 
                    layout: 'public',
                    title: 'Paladín Sucre — Embutidos Artesanales Premium',
                    description: 'Descubre los embutidos artesanales de más alta calidad en Sucre, Bolivia. Chorizos, salchichas y jamones elaborados con la receta tradicional desde 2009.'
                } 
            },
            { 
                path: 'catalogo', 
                name: 'catalog', 
                component: CatalogPage, 
                meta: { 
                    layout: 'public',
                    title: 'Catálogo de Embutidos — Paladín Sucre',
                    description: 'Explora nuestra variedad premium de embutidos artesanales. Chorizo criollo, salchichas de primera, jamón curado y combos familiares con envíos a domicilio.'
                } 
            },
            { 
                path: 'catalogo/:slug', 
                name: 'product-detail', 
                component: ProductDetailPage, 
                meta: { 
                    layout: 'public',
                    title: 'Detalle de Producto — Paladín Sucre',
                    description: 'Conoce los ingredientes, precio, peso y descripción de nuestros mejores productos cárnicos artesanales en Sucre.'
                } 
            },
            { 
                path: 'login', 
                name: 'login', 
                component: LoginPage, 
                meta: { 
                    layout: 'public', 
                    guest: true,
                    title: 'Iniciar Sesión — Paladín Sucre',
                    description: 'Accede a tu cuenta de cliente para realizar pedidos en línea, ver tu carrito de compras y administrar tus direcciones de entrega.'
                } 
            },
            { 
                path: 'register', 
                name: 'register', 
                component: RegisterPage, 
                meta: { 
                    layout: 'public', 
                    guest: true,
                    title: 'Crear Cuenta — Paladín Sucre',
                    description: 'Regístrate hoy mismo y sé parte de la familia Paladín Sucre. Disfruta de la compra rápida de embutidos artesanales.'
                } 
            },
        ]
    },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true, role: 'admin' },
        children: [
            { path: '', name: 'admin.dashboard', component: AdminDashboard },
            { path: 'productos', name: 'admin.products', component: ProductsList },
            { path: 'productos/crear', name: 'admin.products.create', component: ProductFormPage },
            { path: 'productos/:id/editar', name: 'admin.products.edit', component: ProductFormPage },
            { path: 'categorias', name: 'admin.categories', component: CategoriesList },
            { path: 'categorias/crear', name: 'admin.categories.create', component: CategoryFormPage },
            { path: 'categorias/:id/editar', name: 'admin.categories.edit', component: CategoryFormPage },
            { path: 'usuarios', name: 'admin.users', component: UsersList },
            { path: 'usuarios/crear', name: 'admin.users.create', component: UserFormPage },
            { path: 'pedidos', name: 'admin.orders', component: OrdersList },
        ]
    },
    {
        path: '/cliente',
        component: ClientLayout,
        meta: { requiresAuth: true, role: 'client' },
        children: [
            { path: '', name: 'client.dashboard', component: ClientDashboard },
            { path: 'carrito', name: 'client.cart', component: CartPage },
            { path: 'checkout', name: 'client.checkout', component: CheckoutPage },
            { path: 'pedidos', name: 'client.orders', component: OrdersPage },
            { path: 'perfil', name: 'client.profile', component: ProfilePage },
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/'
    }
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { top: 0 };
        }
    }
});

router.beforeEach(async (to, from, next) => {
    const { state, initAuth, isAdmin, isClient } = useAuth();

    if (!state.initialized) {
        await initAuth();
    }

    if (to.meta.requiresAuth && !state.isAuthenticated) {
        return next({ name: 'login' });
    }

    if (to.meta.guest && state.isAuthenticated) {
        if (isAdmin.value) return next({ name: 'admin.dashboard' });
        if (isClient.value) return next({ name: 'client.dashboard' });
        return next({ name: 'home' });
    }

    if (to.meta.role && to.meta.role === 'admin' && !isAdmin.value) {
        return next({ name: 'home' });
    }

    if (to.meta.role && to.meta.role === 'client' && !isClient.value) {
        return next({ name: 'home' });
    }

    next();
});

router.afterEach((to) => {
    // 1. Google Analytics
    if (typeof window.gtag === 'function' && window.GOOGLE_ANALYTICS_ID) {
        window.gtag('config', window.GOOGLE_ANALYTICS_ID, {
            page_path: to.fullPath,
            page_title: to.meta.title || to.name || to.path,
            page_location: window.location.href
        });
    }

    // 2. SEO Title
    const siteTitle = to.meta.title || 'Paladín Sucre — Embutidos Artesanales';
    document.title = siteTitle;

    // 3. SEO Meta Description
    const defaultDesc = 'Embutidos artesanales de la más alta calidad, manteniendo la tradición y el sabor auténtico de Chuquisaca desde 2009.';
    const metaDescription = document.querySelector('meta[name="description"]');
    if (metaDescription) {
        metaDescription.setAttribute('content', to.meta.description || defaultDesc);
    }
});

export default router;