import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes.js';
import store from '@store/store.js';

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes,
});

router.beforeEach(async (to, from, next) => {
    document.title = to.meta.title || 'CurEx';

    const isLoggedIn = await store.getters.isLoggedIn();

    if (to.meta.requiresAuth) {
        if (isLoggedIn) {
            next();
        } else {
            next('/login');
        }
    } else {
        next();
    }
});

export default router;
