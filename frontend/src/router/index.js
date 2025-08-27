import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth.js';
import routes from './routes';

const Router = createRouter({
    history: createWebHistory('/identia/'),
    routes,
});

// AuthGuard
Router.beforeEach(async (to) => {
    const publicPages = ['/login', '/sso/login'];
    const authRequired = !publicPages.includes(to.path);
    const authStore = useAuthStore();
    if (authRequired && !authStore.token) {
        authStore.returnUrl = to.fullPath;
        return '/login';
    }
    if (authStore.token && to.name === 'login') {
        return '/dashboard';
    }
});

// PermissionGuard
Router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    if (to.matched.some((record) => record.meta.permission) && to.path != from.path) {
        if (authStore.authPermissions.includes(to.meta.permission)) {
            next();
        } else {
            next({ path: '/unauthorized' });
        }
    } else {
        next();
    }
});

export default Router;

