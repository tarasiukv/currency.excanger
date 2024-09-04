import {inject, ref} from 'vue';
import {useRouter} from 'vue-router';
import axios from 'axios';

export default function useExchangeRates() {
    const exchange_rates = ref([])
    const exchange_rate = ref({})
    const router = useRouter()
    const store = inject('store')


    /**
     * @returns {Promise<void>}
     */
    const getExchangeRates = async () => {
        try {
            let request_config = {}
            const response = await axios.get('/api/exchange-rates', request_config)
            exchange_rates.value = response.data
        } catch (e) { await console.log(e) }

        return false;
    }

    /**
     * @param id
     * @param way
     * @returns {Promise<void>}
     */
    const getExchangeRate = async (id) => {
        try {
            let request_config = {
            }
            const response = await axios.get('/api/exchange-rates/' + id, request_config)
            exchange_rate.value = response.data.data
        } catch (e) { await console.log(e) }

        return false;
    }

    /**
     * @param data
     * @returns {Promise<boolean>}
     */
    const storeExchangeRate = async () => {
        try {
            let request_config = {
                headers: {
                    'authorization': 'Bearer ' + localStorage.getItem('access_token')
                }
            }
            const response = await axios.post('/api/exchange-rates', exchange_rate.value, request_config)
            await getExchangeRates();
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
     * @param exchange_rate
     * @returns {Promise<boolean>}
     */
    const updateExchangeRate = async (exchange_rate) => {
        try {
            let request_config = {
                headers: {
                    'authorization': 'Bearer ' + localStorage.getItem('access_token')
                }
            }
            const response = await axios.put('/api/exchange-rates/' + exchange_rate.id, {
                exchange_rate_type: exchange_rate.exchange_rate_type,
            }, request_config)
            await getExchangeRates();
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
    const destroyExchangeRate = async (id) => {
        if (confirm('You`re sure?')) {
            if (id !== undefined) {
                try {
                    let request_config = {
                        headers: {
                            'authorization': 'Bearer ' + localStorage.getItem('access_token')
                        }
                    }
                    await axios.delete('/api/exchange-rates/' + id, request_config);
                    await getExchangeRates();
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

    /**
     * @param {number} from_currency_id
     * @param {number} to_currency_id
     * @returns {Promise<void>}
     */
    const searchExchangeRates = async (from_currency_id, to_currency_id) => {
        try {
            let data = {
                from_currency_id: from_currency_id,
                to_currency_id: to_currency_id,
            }
            const response = await axios.post(`/api/exchange-rates/search`, data);
            exchange_rates.value = response.data;
        } catch (e) {
            console.error(e);
        }
    };

    return {
        getExchangeRate,
        getExchangeRates,
        storeExchangeRate,
        updateExchangeRate,
        destroyExchangeRate,
        searchExchangeRates,
        exchange_rates,
        exchange_rate,
    }
}
