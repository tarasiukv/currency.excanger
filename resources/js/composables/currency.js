import {inject, ref} from 'vue';
import {useRouter} from 'vue-router';
import axios from 'axios';

export default function useCurrency() {
    const currencies = ref([])
    const currency = ref({})
    const router = useRouter()
    const store = inject('store')


    /**
     * @returns {Promise<void>}
     */
    const getCurrencies = async () => {
        try {
            let request_config = {}
            const response = await axios.get('/api/currencies', request_config)
            currencies.value = response.data
        } catch (e) { await console.log(e) }

        return false;
    }

    /**
     * @param id
     * @param way
     * @returns {Promise<void>}
     */
    const getCurrency = async (id) => {
        try {
            let request_config = {
            }
            const response = await axios.get('/api/currencies/' + id, request_config)
            currency.value = response.data.data
        } catch (e) { await console.log(e) }

        return false;
    }

    /**
     * @param data
     * @returns {Promise<boolean>}
     */
    const storeCurrency = async () => {
        try {
            let request_config = {
                headers: {
                    'authorization': 'Bearer ' + localStorage.getItem('access_token')
                }
            }
            const response = await axios.post('/api/currencies', currency.value, request_config)
            await getCurrencies();
        } catch (e) {
            if (e.response && e.response.status === 401) {
                window.alert('Error: Unauthenticated');
            } else {
                console.error(e);
            }
        }

        return false;
    }

    /**
     * @param id
     * @returns {Promise<boolean>}
     */
    const destroyCurrency = async (id) => {
        if (confirm('You`re sure?')) {
            if (id !== undefined) {
                try {
                    let request_config = {
                        headers: {
                            'authorization': 'Bearer ' + localStorage.getItem('access_token')
                        }
                    }
                    await axios.delete('/api/currencies/' + id, request_config);
                    await getCurrencies();
                } catch (e) {
                    if (e.response && e.response.status === 401) {
                        window.alert('Error: Unauthenticated');
                    } else {
                        console.error(e);
                    }
                }
            }
        }
    }

    return {
        getCurrency,
        getCurrencies,
        storeCurrency,
        destroyCurrency,
        currencies,
        currency,
    }
}
