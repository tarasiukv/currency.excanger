<script setup>
import HeaderComponent from "@components/features/HeaderComponent.vue";
import {onMounted, ref, watch} from "vue";
import useCurrency from "@composables/currency.js";
import useExchangeRates from "@composables/exchange-rate.js";

const { searchExchangeRates, exchange_rates } = useExchangeRates();
const { currencies, getCurrencies } = useCurrency();

const from_currency_id = ref(null);
const to_currency_id = ref(null);
const amount = ref(0);
const converted_amount = ref(0);

onMounted(async () => {
    await getCurrencies();
});

watch([from_currency_id, to_currency_id], async ([newFromCurrencyId, newToCurrencyId]) => {
    if (newFromCurrencyId && newToCurrencyId) {
        await searchExchangeRates(newFromCurrencyId, newToCurrencyId);
        calculateConvertedAmount();
    }
});

watch(amount, () => {
    calculateConvertedAmount();
});

function calculateConvertedAmount() {
    if (exchange_rates.value.length > 0 && amount.value > 0) {
        converted_amount.value = amount.value * exchange_rates.value[0].rate;
    } else {
        converted_amount.value = 0;
    }
}

</script>

<template>
    <HeaderComponent />
    <div class="main-content">

        <div class="page-content">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Buy/Sell currency</h4>
                                <div class="currency-buy-sell-nav">
                                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#buy" role="tab">
                                                Buy
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content currency-buy-sell-nav-content p-4">
                                        <div class="tab-pane active" id="buy" role="tabpanel">
                                            <form>
                                                    <label>Payment method :</label>
                                                    <div class="row">
                                                        <div class="col-xl-2 col-sm-4">
                                                            <label class="card-radio-label mb-3">
                                                                <input type="radio" name="pay-method" id="pay-methodoption1" class="card-radio-input">

                                                                <div class="card-radio">
                                                                    <i class="fab fa-cc-mastercard font-size-24 text-primary align-middle me-2"></i>

                                                                    <span>Credit / Debit Card</span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                <div class="mb-3">
                                                    <label>Add value :</label>

                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="input-group mb-2 currency-value">
                                                                <select class="form-select" v-model="from_currency_id">
                                                                    <option
                                                                        v-for="currency in currencies"
                                                                        :key="currency.id"
                                                                        :value="currency.id"
                                                                    >
                                                                        {{ currency.symbol }} - {{ currency.name }}
                                                                    </option>
                                                                </select>
                                                                <input type="text" class="form-control" placeholder="Amount" v-model="amount">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6">
                                                            <div class="input-group mb-2">
                                                                <input type="text" class="form-control" placeholder="Converted Amount" :value="converted_amount" disabled>

                                                                <select class="form-select" v-model="to_currency_id">
                                                                    <option
                                                                        v-for="currency in currencies"
                                                                        :key="currency.id"
                                                                        :value="currency.id"
                                                                    >
                                                                        {{ currency.symbol }} - {{ currency.name }}
                                                                    </option>
                                                                </select>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Card number :</label>
                                                    <input type="text" class="form-control" placeholder="Wallet Address">
                                                </div>
                                                <div class="text-center mt-4">
                                                    <button type="button" class="btn btn-success">Buy currency</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    </div>
</template>

<style scoped>
</style>
