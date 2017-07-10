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
		opened_requests: [],
		quotation_forms: [],
		quotation_items: [],
		pr_estimated_costs: [],
		/* approved dates of quotation*/
		approved_prs: [],
		users: [],
		notifications: [],
		read_notifications: [],

		/* data for po receipt */
		po_receipt_purchase_orders: [],
		po_receipt_po_items: [],

		/* for modal po receipt */
		currentPo: {},
		currentPurchaseForm: [],
		rsPurchaseItem: [],
		cboQfApproved: 'all'
	},
	
	mutations: {
		FETCH_READ_NOTIF(state){
			state.read_notifications = [];
			Vue.http.get('/read_notification').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                    state.read_notifications = json;
                };
			}, (resp) => {
				console.log(resp);
			});
		},
		FETCH_MY_NOTIFICATIONS(state){
			state.notifications = [];
			Vue.http.get('/my_notification').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                    state.notifications = json;
                    console.log(json)
                };
			}, (resp) => {
				console.log(resp);
			});
		},
		FETCH_USERS(state){
			state.users = [];
			Vue.http.get('/user').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                    state.users = json;
                };
			}, (resp) => {
				console.log(resp);
			});
		},
		FETCH_REQUISITION_ESTIMATED_COSTS(state){
			state.pr_estimated_costs = [];
			Vue.http.get('/pr_estimated_costs').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                    state.pr_estimated_costs = json;
                };
			}, (resp) => {
				console.log(resp);
			});
		},
		FETCH_APPROVED_QUOTATIONS(state){
			state.approved_prs = [];
			Vue.http.get('/approved_pr').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                   	state.approved_prs = json.approved_dates;
                };
			}, (resp) => {
				console.log(resp);
			});
		},
		FETCH_QUOTATIONS_AND_ITEMS(state){
			state.quotation_forms = [];
			state.quotation_items = [];
			Vue.http.get('/quotations').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                    state.quotation_forms = json.quotation_forms;
                    state.quotation_items = json.quotation_items;
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
		FETCH_OPENED_REQUESTS(state){
			Vue.http.get('/opened_request').then((resp) => {
				if (resp.status === 200) {
                    let json = resp.body;
                    state.opened_requests = json;
                };
			}, (resp) => {
				console.log(resp);
			});
		},
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
			state.divisions.unshift(payload.division);
		},
		PUSH_OPENED_REQUESTS(state, payload){
			let or = payload.opened_request;
			$.each(or, function(index, val) {
				console.log(index + ': ' + val);
			});
			state.opened_requests.push(payload.opened_request);
		},
		PUSH_READ_NOTIFS(state, payload){
			let arr = payload.json;
			for (var i = arr.length - 1; i >= 0; i--) {
				state.read_notifications.push(arr[i]);
			};
		},
		UPDATE_PO_FORMS_ITEMS(state, payload){
			let json = payload.json;
			state.purchase_orders = json.purchase_orders;
			state.po_items = json.po_items;
		},

		/* FIlters */
		FILTER_APPROVED_QUOTATIONS(state, payload){
			let selected = payload.selected;
			Vue.http.post('/quotation_approved_or_not', {
				selected: selected
			}).then((resp) => {
				if (resp.status === 200) {
					let json = resp.body;
					state.quotation_forms = json.quotation_forms;
					console.log(json.quotation_forms)
				}
			}, (resp) => {
				if (resp.status === 422) {
					let json = resp.body;
					console.log(json);
				}
			});
		}
	},
	getters: {
		
		overall_total_po(state){
			let total = 0, item = {};
			for (var i = state.po_items.length - 1; i >= 0; i--) {
				item = state.po_items[i];
				
				total += Number(item.qty) * Number(item.unit_price);
			};
			return total;
		},
		read_notifications(state){
			return state.read_notifications;
		},
		notifications(state){
			return state.notifications;
		},
		users(state){
			return state.users;
		},
		pr_estimated_costs(state){
			return state.pr_estimated_costs;
		},
		quotation_forms(state){
			return state.quotation_forms;
		},
		quotation_items(state){
			return state.quotation_items;
		},
		opened_requests(state){
			return state.opened_requests;
		},
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
		},
		currentPoItems(state){
			let self = this;
			return _.filter(state.po_items, {po_id: Number(state.currentPo.id)});
		}
	}
});

