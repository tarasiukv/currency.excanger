import HomeComponent from "@components/HomeComponent.vue";
import LoginComponent from "@components/auth/LoginComponent.vue";
import RegisterComponent from "@components/auth/RegisterComponent.vue";
import ExchangeComponent from "@components/ExchangeComponent.vue";
import DashboardComponent from "@components/DashboardComponent.vue";

const routes = [
    {
        path: '/',
        component: HomeComponent,
        meta: {title: 'Home | CurEx'}
    },
    {
        path: '/login',
        component: LoginComponent,
        meta: {title: 'Login | CurEx'}
    },
    {
        path: '/register',
        component: RegisterComponent,
        meta: {title: 'Register | CurEx'}
    },
    {
        path: '/dashboard',
        component: DashboardComponent,
        meta: {
        title: 'Dashboard | CurEx',
            requiresAuth: true,
        }
    },
    {
        path: '/exchange',
        component: ExchangeComponent,
        meta: {title: 'Exchange | CurEx',}
    },
];

export default routes;
