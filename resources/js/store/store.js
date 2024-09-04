import { reactive } from 'vue';

const state = reactive({
    access_token: localStorage.getItem('access_token') || null,
    refresh_token: localStorage.getItem('refresh_token') || null,
    is_logged_in: localStorage.getItem('access_token') !== null,
    user: {
        id: localStorage.getItem('user_id') || null,
        name: localStorage.getItem('user_name') || null,
        role: localStorage.getItem('user_role') || null
    },
    is_loading_page: true
});

const methods = {};

const getters = {
    async isLoggedIn() {
        return state.is_logged_in;
    }
};

const setters = {
    async setIsLogged(value) {
        state.is_logged_in = value;
        if (!value) {
            localStorage.removeItem('access_token');
            localStorage.removeItem('refresh_token');
            localStorage.removeItem('user_id');
            localStorage.removeItem('user_name');
            localStorage.removeItem('user_role');
        }
    },
    async setIsLoadingPage(value) {
        state.is_loading_page = value;
    },
    async setUser(user) {
        state.user.id = user.id;
        state.user.name = user.name;
        state.user.role = user.role;
        localStorage.setItem('user_id', user.id);
        localStorage.setItem('user_name', user.name);
        localStorage.setItem('user_role', user.role);
    },
};

export default {
    state,
    methods,
    getters,
    setters,
};
