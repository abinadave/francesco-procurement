import Vue from 'vue'
import Vuex from 'vuex'


export const store = new Vuex.Store({
	state: {
		request_forms:[],
		request_items: [],
		suppliers:[],

		divisions: [],
		purchase_orders: [],
		po_items: [],
		house_models: [],

		/* data for po receipt */
		po_receipt_purchase_orders: [],
		po_receipt_po_items: [],

		/* for modal po receipt */
		currentPo: {},
		currentPurchaseForm: [],
		rsPurchaseItem: []
	},
	mutations: {
		FETCH_HOUSE_MODELS(state){
			Vue.http.get('/house_model').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                   	state.house_models = json;
                };
			}, (resp) => {
				console.log(resp);
			});
		},
		SET_CURRENT_PO(state, payload){
			let self = this;
			let po = payload.po;
			state.currentPo = po;
			state.currentPurchaseForm = _.filter(state.request_forms, { id: po.pr_no})[0];
            state.rsPurchaseItem = _.filter(state.request_items, { request_form_id: po.pr_no });
		},
		FETCH_SUPPLIERS(state){
			let self = this;
			Vue.http.get('/supplier').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                    state.suppliers = json;
                };
			}, (resp) => {
				console.log(resp);
			});
		},
		FETCH_REQUEST_FORMS_ITEMS(state){
			let self = this;
			state.request_forms = [];
			state.request_items = [];
			Vue.http.get('/request_form_items').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                    state.request_forms = json.request_forms;
                    state.request_items = json.request_items;
                };
			}, (resp) => {
				console.log(resp);
			});
		},
		CURRENT_PO(state, payload){
			let self = this;
			let po = payload.po;
			$.each(po, function(index, val) {
				console.log(index + ': ' + val);
			});
		},
		FETCH_PURCHASE_ORDERS(state){
			let self = this;
			state.purchase_orders = [];
			state.po_items = [];
			Vue.http.get('/po').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                    for (var i = json.po_items.length - 1; i >= 0; i--) {
                        state.po_items.unshift(json.po_items[i]);
                    };
                    for (var i = json.purchase_orders.length - 1; i >= 0; i--) {
                        state.purchase_orders.unshift(json.purchase_orders[i]);
                    };
                };
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
		request_forms(state){
			return state.request_forms;
		},
		request_items(state){
			return state.request_items;
		},
		divisions(state){
			return state.divisions;
		},
		purchase_orders(state){
			return state.purchase_orders;
		},
		po_items(state){
			return state.po_items;
		},
		suppliers(state){
			return state.suppliers;
		},
		currentPo(state){
			return state.currentPo;
		},
		currentPurchaseForm(state){
			return state.currentPurchaseForm;
		},
		rsPurchaseItem(state){
			return state.rsPurchaseItem;
		},
		house_models(state){
			return state.house_models;
		}
	}
});