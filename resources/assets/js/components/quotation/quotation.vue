<template>
    <div>
        <div>
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-primary">
                    <div class="panel-heading">Quotations Panel</div>
                    <div class="panel-body">
                        <quotation-list></quotation-list>
                    </div>
                </div>
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
        },
        components: {
            'quotation-list': QuotationListComponent
        }

    }
</script>
