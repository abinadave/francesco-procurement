import Vue from 'vue'
import Vuex from 'vuex'


export const store = new Vuex.Store({
	state: {
		divisions: [],
		purchase_orders: [],
		po_items: []
	},
	mutations: {
		FETCH_PURCHASE_ORDERS(state){
			let self = this;
			Vue.http.get('/po').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                    for (var i = json.po_items.length - 1; i >= 0; i--) {
                        state.po_items.unshift(json.po_items[i]);
                    };
                    for (var i = json.purchase_orders.length - 1; i >= 0; i--) {
                        state.purchase_orders.unshift(json.purchase_orders[i]);
                    };
                }
			}, (resp) => {
				console.log(resp);
			});
		},
		FETCH_APPROVED_DATES(state){
			let self = this;
			state.divisions = [];
			Vue.http.get('/division').then((resp) => {
				if (resp.status === 200) {
					let json = resp.body;
					for (var i = json.length - 1; i >= 0; i--) {
						state.divisions.unshift(json[i]);
					};
				}
			}, (resp) => {
				if (resp.status === 422) {
					let json = resp.body;
					console.log(json);
				}
			});
		},
		PUSH_DIVISION(state, payload){
			let self = this;

			state.divisions.unshift(payload.division);
		}
	},
	getters: {
		divisions(state){
			return state.divisions;
		},
		purchase_orders(state){
			return state.purchase_orders;
		},
		po_items(state){
			return state.po_items;
		}
	}
});