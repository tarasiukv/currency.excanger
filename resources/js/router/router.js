import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes.js';

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes,
});

router.beforeEach(async (to, from, next) => {
    document.title = to.meta.title || 'CurEx';

    if (to.meta.requiresAuth) {
        if (store.state.is_logged_in) {
            next();
        } else {
            next('/login');
        }
    } else {
        next();
    }
});

export default router;
