import { reactive } from 'vue';

const state = reactive({
    access_token: null,
    refresh_token: null,
    is_logged_in: false,
    user: {
        id: null,
        name: null,
        role: null
    },
    is_loading_page: true
});

const methods = {};

const getters = {
    async isLoggedIn() {
        return typeof state.access_token !== "undefined" && state.access_token !== null;
    }
};

const setters = {
    async setIsLogged(value) {
        state.is_logged_in = value;
    },
    async setIsLoadingPage(value) {
        state.is_loading_page = value;
    },
    async setUser(user) {
        state.user.id = user.id;
        state.user.name = user.name;
        state.user.role = user.role;
    },
};

export default {
    state,
    methods,
    getters,
    setters,
};
