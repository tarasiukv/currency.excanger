import {ref, reactive, inject, computed, watch} from "vue";
import {useRouter} from "vue-router";
import axios from "axios";

export default function useExchangeRates() {
    const exchange_rates = ref([]);
    const exchange_rate = ref({});
    const sort_option = ref('title-asc');
    const exchange_rate_page = ref(1);
    const exchange_rate_page_count = ref(1);
    const router = useRouter();
    const store = inject('store');

    /**
     * @param service
     * @returns {Promise<void>}
     */
    const fetchExchangeRates = async (service) => {
        try {
            let request_config = {
                service: service
            }
            const response = await axios.get('/api/exchange_rates/fetch', request_config)

            exchange_rate.value = response.data.data
        } catch (e) {
            console.log(e)
        }
        return false;
    }

    /**
     * @returns {Promise<void>}
     */
    const getExchangeRates = async () => {
        try {
            const response = await axios.get('/api/exchange_rates',
                {
                    params: {
                        page: exchange_rate_page.value
                    }
                })
            exchange_rates.value = response.data.data
            exchange_rate_page_count.value = response.data?.meta?.last_page

        } catch (e) {
            console.log(e)
        }

        return false;
    }

    /**
     * @param id
     * @returns {Promise<void>}
     */
    const getExchangeRate = async (id) => {
        try {
            let request_config = {}
            const response = await axios.get('/api/exchange_rates/' + id, request_config)

            exchange_rate.value = response.data.data
        } catch (e) {
            console.log(e)
        }
        return false;
    }

    /**
     * @param form_data
     * @returns {Promise<boolean>}
     */
    const storeExchangeRate = async (form_data) => {
        try {
            let request_config = {
                headers: {
                    'authorization': 'Bearer ' + localStorage.getItem('access_token')
                }
            };
            const response = await axios.post('/api/exchange_rates', form_data, request_config)
            await router.push('/admin/exchange_rates');

            return response.data;
        } catch (e) {
            if (e.response && e.response.status === 401) {
                // Вивести повідомлення про помилку "Unauthenticated"
                window.alert("Помилка: Ви не автентифіковані");
            } else {
                // Інша обробка помилки
                console.error(e);
            }
        }
    };


    /**
     * @param id
     * @param form_data
     * @returns {Promise<boolean>}
     */
    const updateExchangeRate = async (id, form_data) => {
        try {
            let request_config = {
                headers: {
                    'authorization': 'Bearer ' + localStorage.getItem('access_token'),
                }
            }
            const response = await axios.put('/api/exchange_rates/' + id, form_data, request_config)
            await router.push('/admin/exchange_rates');

        } catch (e) {
            if (e.response && e.response.status === 401) {
                window.alert("Помилка: Ви не автентифіковані");
            } else {
                console.error(e);
                window.alert("Помилка: Під час оновлення прайс листа");
            }
        }

        return false;
    }

    /**
     * @param id
     * @returns {Promise<boolean>}
     */
    const destroyExchangeRate = async (id) => {
        if (confirm("Ви впевнені, що хочете видалити цей прайс лист?")) {
            if (id !== undefined) {
                try {
                    let request_config = {
                        headers: {
                            'authorization': 'Bearer ' + localStorage.getItem('access_token')
                        }
                    }
                    await axios.delete('/api/exchange_rates/' + id, request_config)
                    await getExchangeRates();
                } catch (e) {
                    if (e.response && e.response.status === 401) {
                        // Вивести повідомлення про помилку "Unauthenticated"
                        window.alert("Помилка: Ви не автентифіковані");
                    } else {
                        // Інша обробка помилки
                        console.error(e);
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param search_params
     * @returns {Promise<void>}
     */
    const searchExchangeRates = async (search_params) => {
        try {
            const response = await axios.post('/api/exchange_rates/search', {
                search_text: search_params.search_text,
                category_ids: search_params.category_ids,
                packaging_ids: search_params.packaging_ids,
                consistence_ids: search_params.consistence_ids,
                producing_country_ids: search_params.producing_country_ids,
                manufacturer_ids: search_params.manufacturer_ids,
                min_exchange_rate: search_params.min_exchange_rate,
                max_exchange_rate: search_params.max_exchange_rate,
            });
            exchange_rates.value = response.data.data;
        } catch (e) {
            console.error(e);
        }
    };

    /**
     * Sort exchange_rate title and exchange_rate
     */
    const sortExchangeRates = () => {
        const sorted_exchange_rates = [...exchange_rates.value];
        if (sort_option.value === 'title-asc') {
            sorted_exchange_rates.sort((a, b) => a.product.title.localeCompare(b.product.title));
        } else if (sort_option.value === 'title-desc') {
            sorted_exchange_rates.sort((a, b) => b.product.title.localeCompare(a.product.title));
        } else if (sort_option.value === 'exchange_rate-asc') {
            sorted_exchange_rates.sort((a, b) => a.exchange_rate - b.exchange_rate);
        } else if (sort_option.value === 'exchange_rate-desc') {
            sorted_exchange_rates.sort((a, b) => b.exchange_rate - a.exchange_rate);
        }
        exchange_rates.value = sorted_exchange_rates;
    };

    const changePage = async (page) => {
        exchange_rate_page.value = page;
        await getExchangeRates();
    };

    /**
     * Next page for pagination
     */
    const nextPage = async () => {
        if (exchange_rate_page.value < exchange_rate_page_count.value) {
            exchange_rate_page.value++;
            await getExchangeRates();
        }
    };

    /**
     * Preview page for pagination
     */
    const prevPage = async () => {
        if (exchange_rate_page.value > 1) {
            exchange_rate_page.value--;
            await getExchangeRates();
        }
    };

    watch(sort_option, () => {
        sortExchangeRates();
    });

    return {
        fetchExchangeRates,
        getExchangeRate,
        getExchangeRates,
        storeExchangeRate,
        updateExchangeRate,
        destroyExchangeRate,
        searchExchangeRates,
        changePage,
        nextPage,
        prevPage,
        exchange_rates,
        exchange_rate,
        sort_option,
        exchange_rate_page,
        exchange_rate_page_count
    }
}
