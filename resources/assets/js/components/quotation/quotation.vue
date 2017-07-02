<template>
        <div class="col-md-12" style="font-size: 12px">
            <div class="panel panel-primary">
                <div class="panel-heading">Quotations</div>
                <div class="panel-body">
                    <quotation-list></quotation-list>
                </div>
            </div>
        </div>
</template>

<script>
    import QuotationListComponent from './quotation_list.vue'
    export default {
        mounted() {
            this.$store.commit('FETCH_QUOTATIONS_AND_ITEMS');
            this.$store.commit('FETCH_REQUEST_FORMS_ITEMS');
            this.$store.commit('FETCH_SUPPLIERS');
            this.$store.commit('FETCH_APPROVED_QUOTATIONS');
            this.$store.commit('FETCH_USERS');
        },
        components: {
            'quotation-list': QuotationListComponent
        }

    }
</script>
