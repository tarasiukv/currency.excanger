import {inject, ref} from 'vue';
import {useRouter} from 'vue-router';
import axios from 'axios';

export default function useAuth() {
    const router = useRouter()
    const user = ref({})
    const email = ref('')
    const password = ref('')
    const store = inject('store')

    const register = async () => {
        try {
            let request_config = {}
            user.value.user_role_id = 2;

            console.log(user.value)
            const response = await axios.post('/api/register', user.value, request_config)

            console.log(response)
            await clearLocalStorage();

            localStorage.setItem('access_token', response.access_token)
            localStorage.setItem('refresh_token', response.refresh_token)
            localStorage.setItem('user_id', response.user?.id)
            localStorage.setItem('user_name', response.user?.name)
            localStorage.setItem('user_role', response.user?.role)

            await store.setters.setIsLogged(true)
            await store.setters.setUser({
                id: response.user?.id,
                name: response.user?.name,
                role: response.user?.role
            });

            await router.push('/dashboard');
        } catch (e) {
            console.log(e)
        }

        return false;
    }

    const login = async () => {
        if (store.state.is_logged_in) return true
        else {
            try {
                const {data} = await axios.post('/api/login', {
                    email: email.value,
                    password: password.value
                }, {
                    headers: {
                        'Accept': 'application/json',
                    }
                })
                await clearLocalStorage();

                localStorage.setItem('access_token', data.access_token)
                localStorage.setItem('refresh_token', data.refresh_token)
                localStorage.setItem('user_id', data.user?.id)
                localStorage.setItem('user_name', data.user?.name)
                localStorage.setItem('user_role', data.user?.role)

                await store.setters.setIsLogged(true)
                await store.setters.setUser({
                    id: data.user?.id,
                    name: data.user?.name,
                    role: data.user?.role
                });

                await router.push('/dashboard')
            } catch (e) {
                console.log('!!!Some error in login', e)
            }
        }

        return false;
    }

    const logout = async () => {
        try {
            let request_config = {
                headers: {
                    'authorization': 'Bearer ' + localStorage.getItem('access_token')
                }
            }

            const response = await axios.post('/api/auth/logout', null, request_config)
                .then(response => {
                    clearLocalStorage();
                    store.setters.setIsLogged(false)
                    router.push('/').then(() => {
                        window.location.reload()
                    })
                })
        } catch (e) {
            console.log('!!!Some error in logout', e)
        }

        return false;
    }

    const checkAuthStatus = async () => {
        const access_token = localStorage.getItem('access_token');

        if (access_token) {
            const decodedToken = parseToken(access_token);

            if (decodedToken && decodedToken.exp * 1000 < Date.now()) {
                await clearLocalStorage();
                store.state.is_logged_in = false;
            } else {
                store.state.is_logged_in = true;
            }
        } else {
            store.state.is_logged_in = false;
        }
    };

    function parseToken(token) {
        try {
            return JSON.parse(atob(token.split(".")[1]));
        } catch (e) {
            return null;
        }
    }

    const clearLocalStorage = () => {
        const keysToDelete = [
            'access_token',
            'refresh_token',
            'user_id',
            'user_name',
            'user_role',
        ];

        keysToDelete.forEach(key => {
            if (localStorage.getItem(key)) {
                localStorage.removeItem(key);
            }
        });
    };

    return {
        register,
        login,
        logout,
        checkAuthStatus,
        clearLocalStorage,
        user,
        email,
        password
    }
}
