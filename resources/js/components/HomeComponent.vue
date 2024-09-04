<script setup>
import HeaderComponent from "@components/features/HeaderComponent.vue";
import useExchangeRates from "@composables/exchange-rate.js";
import {onMounted} from "vue";

const { getExchangeRates, exchange_rates } = useExchangeRates();

onMounted(async () => {
    await getExchangeRates();
})
</script>

<template>
    <HeaderComponent />
    <div class="main-content">

        <div id="layout-wrapper">
        <div class="page-content">
            <div class="container-fluid_center">

                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Exchange Rates</h4>

                                <div class="table-responsive">
                                    <table class="table table-nowrap align-middle mb-0">
                                        <thead>
                                        <tr>
                                            <th scope="col">Currency from</th>
                                            <th scope="col">Currency to</th>
                                            <th scope="col">Price</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="exchange_rate in exchange_rates" :key="exchange_rate.id">
                                            <th scope="row">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-3">
                                                                    <span class="avatar-title rounded-circle bg-warning-subtle text-warning font-size-18">
                                                                        <i class="mdi mdi-bitcoin"></i>
                                                                    </span>
                                                    </div>
                                                    <span>{{ exchange_rate.from_currency.symbol }} - {{ exchange_rate.from_currency.name }}</span>
                                                </div>
                                            </th>
                                            <th scope="row">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-3">
                                                                    <span class="avatar-title rounded-circle bg-warning-subtle text-warning font-size-18">
                                                                        <i class="mdi mdi-bitcoin"></i>
                                                                    </span>
                                                    </div>
                                                    <span>{{ exchange_rate.to_currency.symbol }} - {{ exchange_rate.to_currency.name }}</span>
                                                </div>
                                            </th>
                                            <td>
                                                <div class="text-muted">
                                                    {{ exchange_rate.rate }}
                                                </div>
                                            </td>
                                            <td style="width: 120px;">
                                                <a href="/exchange" class="btn btn-primary btn-sm w-xs">View</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
    </div>
    </div>

</template>

<style scoped>

</style>
