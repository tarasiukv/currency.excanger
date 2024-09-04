<script setup>
import HeaderComponent from "@components/features/HeaderComponent.vue";

</script>

<template>
    <HeaderComponent />
    <div class="main-content">

        <div id="layout-wrapper">
            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body border-bottom">
                                    <div class="float-end dropdown ms-2">
                                        <a class="text-muted" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="mb-4 me-3">
                                            <i class="mdi mdi-account-circle text-primary h1"></i>
                                        </div>

                                        <div>
                                            <h5 class="">Admin</h5>
                                            <p class="text-muted mb-1">admin@mail.com</p>
                                            <p class="text-muted mb-0">Id no: #1</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body border-bottom">
                                    <div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div>
                                                    <p class="text-muted mb-2">Available Balance</p>
                                                    <h5>$ 0.00</h5>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="text-sm-end mt-4 mt-sm-0">
                                                    <p class="text-muted mb-2">Get exchange rate</p>
                                                    <h5><span class="badge bg-success ms-1 align-bottom">Get</span></h5>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
</template>

<style scoped>

</style>
