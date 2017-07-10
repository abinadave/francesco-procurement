<template>
        <div class="col-md-12" style="font-size: 12px">
            <div class="panel panel-primary">
                <div class="panel-heading" style="font-weight: bolder">
                    Quotations <br><i>(Row with bold letters are approved with purchase orders...)</i><br>
                </div>
                <div class="panel-body">
                    <quotation-list></quotation-list>
                </div>
            </div>
            <modal-po></modal-po>
        </div>
</template>

<script>
    import QuotationListComponent from './quotation_list.vue'
    import PrintablePo from '../po/po_modal_printable.vue'
    export default {
        mounted() {
            this.$store.commit('FETCH_QUOTATIONS_AND_ITEMS');
            this.$store.commit('FETCH_REQUEST_FORMS_ITEMS');
            this.$store.commit('FETCH_SUPPLIERS');
            this.$store.commit('FETCH_APPROVED_QUOTATIONS');
            this.$store.commit('FETCH_USERS');
            this.$store.commit('FETCH_PURCHASE_ORDERS');
        },
        components: {
            'quotation-list': QuotationListComponent,
            'modal-po': PrintablePo
        }

    }
</script>
