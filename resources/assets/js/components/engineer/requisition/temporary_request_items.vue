<template>
    <div>
    	<a @click="$emit('additem')" style="cursor: pointer" class="pull-right">Add <i class="glyphicon glyphicon-plus"></i></a>
        <table class="table table-hover table-bordered table-condensed table-temporary-items">
        	<thead>
        		<tr>
        			<th class="text-center" width="80">Qty</th>
        			<th class="text-center" width="150">Unit</th>
        			<th class="text-center">Description</th>
        			<th class="text-center" width="100">Unit Price</th>
        			<th class="text-right" width="200">TOTAL</th>
        			<th width="1"></th>
        		</tr>
        	</thead>
        	<tbody>
        		<tr v-for="(item, index) in items">
        			<td><input style="width: 80px; text-align: center" type="text" v-model="item.qty"></td>
        			<td><input style="width: 150px; text-align: center" type="text" v-model="item.unit"></td>
        			<td><input style="width: 350px; text-align: center" type="text" v-model="item.description"></td>
        			<td><input style="width: 100px; text-align: center" type="text" v-model="item.unit_price"></td>
        			<td class="text-right"><b>{{ getTotalPerItem(item) }}</b></td>
        			<td>
        				<i @click="$emit('removeitem', index)" class="glyphicon glyphicon-remove text-primary" style="cursor: pointer"></i>
        			</td>
        		</tr>
        	</tbody>
        	<tfoot>
        		<tr>
        			<th colspan="4" class="text-center">Total</th>
        			<th class="text-right"> {{ getTotalAllItems }} </th>
        		</tr>
        	</tfoot>
        </table>
    </div>
</template>
<style type="text/css">
	.table-temporary-items {
		font-size: 12px;
		padding: 20px;
	}
</style>
<script>
	import accounting from 'accounting'
    export default {
        mounted() {
            // console.log('Component mounted.')
        },
        props: {
        	items: {
        		type: Array
        	}
        },
        methods: {

        	getTotalPerItem(item){
        		let self = this;
        		let total = Number(item.qty) * Number(item.unit_price);
        		return accounting.formatNumber(total, 2);
        	}
        },
        computed: {
        	getTotalAllItems(){
        		let self = this;
        		let item = {}, total = 0.0;
        		for (var i = self.items.length - 1; i >= 0; i--) {
        			item = self.items[i];
        			total += Number(item.qty) * Number(item.unit_price);
        		}
        		return accounting.formatNumber(total, 2);
        	}
        }
    }
</script>
