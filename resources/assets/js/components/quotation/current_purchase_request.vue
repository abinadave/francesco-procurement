<template>
    <div class="col-md-9 panel panel-default" style="margin-top: 20px">  
          
            <p class="text-center">INTEGRATED, PEOPLE-DRIVEN COMMUNITY PROJECT IN POST YOLANDA</p>
            <p class="text-center" style="margin-top: -12px; font-size: 11px" >BRGY. 99 DIIT, TACLOBAN CITY</p>
            <h5 class="text-center" style="margin-top: 5px; font-weight: bolder">PURCHASE REQUEST</h5>
            <div class="pull-right" style="margin-top: -10px">
                <ul>
                  <li><b>Date: &nbsp;&nbsp;</b> {{ getDateAndTime }}</li>
                  <li><b>PR No.</b> {{ form.id }}</li>
                </ul>
            </div>
            <div style="margin-top: -5px">
                <label>Name </label>
                <p style="display: inline-block;"> &nbsp;{{ getRequestersName }}</p>
            </div><br>
            
            <div class="panel panel-default">
                <ul>
                    <li>Location &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                      <span>________________________</span>
                      <span style="margin-left: -190px">{{ form.location }}</span>
                    </li>
                    <li>Block No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                      <span>________________________</span>
                      <span style="margin-left: -190px">{{ form.block_no }}</span>
                    </li>
                    <li>House Model&nbsp;
                      <span>________________________</span>
                      <span style="margin-left: -190px">{{ getHouseModel }}</span>
                    </li>
                </ul>
            </div>
            <div>
                <table id="tbl-requested-items" class="table table-bordered table-condensed table-hover">
                    <thead>
                        <tr>
                            <th width="70">QTY</th>
                            <th width="90">UNIT</th>
                            <th>DESCRIPTION</th>
                            <th width="100" style="text-align: right">UNIT PRICE</th>
                            <th width="120" style="text-align: right">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody id="tr-items">
                        <tr v-for="item in items">
                            <td>{{ item.qty }}</td>
                            <td>{{ getString(item.unit) }}</td>
                            <td class="text-primary" style="font-weight: bolder">
                                {{ getString(item.description) }}
                            </th>
                            <td style="text-align: right; font-weight: bolder">{{ formatNumber(item.unit_price) }}</td>
                            <td style="text-align: right">{{ getTotal(item) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                           <th colspan="4" style="font-size: 16px">ESTIMATED COST</th>
                           <th style="text-align: right; font-size: 16px" class="text-danger">P {{ getTotalAllItems }}</th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
</template>

<script>
    import moment from 'moment'
    import accounting from 'accounting'
    export default {
        mounted() {
            this.fetchUsers();
            this.fetchHouseModels();
        },
        props: {
            items: {
                type: Array
            },
            form: {
                type: Object
            }
        },
        data(){
            return {
                users: [],
                house_models: []
            }
        },
        computed: {
            getHouseModel(){
                let self = this;
                let rs = _.filter(self.house_models, {id: Number(self.form.house_model)});
                // return rs.length;
                if (rs.length) {
                    return rs[0].model.toUpperCase();
                }else { return '-'; };
            },
            getTotalAllItems(){
                let self = this;
                let item = {}, total = 0.0;
                for (var i = self.items.length - 1; i >= 0; i--) {
                  item = self.items[i];
                  total += Number(item.qty) * Number(item.unit_price);
                }
                return accounting.formatNumber(total, 2);
            },
            getDateAndTime(){
                let self = this;
                let formated = moment(self.form.datetime).format('MMMM DD, YYYY, ddd,  hh:mm a');
                return formated;
            },
            getRequestersName(){
                let self = this;
                let rs = _.filter(self.users, {id: self.form.requested_by});
                if (rs.length) {
                    return rs[0].name.toUpperCase();
                }else {
                    return 'Requesters name not found';
                }
            }
        },
        methods: {
            getString(str){
                let self = this;
                if (typeof str === 'string') {
                    return str.toUpperCase();
                }else {
                    return 'not found';
                }
            },
            fetchHouseModels(){
                let self = this;
                self.$http.get('/house_model').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        for (var i = json.length - 1; i >= 0; i--) {
                            self.house_models.push(json[i]);
                        };
                    }
                }, (resp) => {
                    console.log(resp);
                });
            },
            fetchUsers(){
                let self = this;
                self.$http.get('/user').then((resp) => {
                    if (resp.status === 200) {
                        let users = resp.body;
                        for (var i = users.length - 1; i >= 0; i--) {
                             self.users.push(users[i]);
                        }
                    }
                }, (resp) => {
                    console.log(resp);
                });
            },
            formatNumber(n){
                return accounting.formatNumber(n, 2);
            },
            getTotal(item){
                let self = this;
                let total = Number(item.qty) * Number(item.unit_price);
                return accounting.formatNumber(total, 2);
            }
        },
        watch: {
            'form': function(newVal){
                // $.each(newVal, function(index, val) {
                //     console.log(index + ': ' + val);
                // });
            },
            'items': function(items){
                let self = this;
                let $tr = $('#tr-items'),
                total = 10;
                setTimeout(function(){
                    total -= self.items.length;
                    let html = '';
                    for (var i = total - 1; i >= 0; i--) {
                        html += '<tr >';
                        html += '<td class="td-extension">-</td>';
                        html += '<td class="td-extension">-</td>';
                        html += '<td class="td-extension">-</td>';
                        html += '<td class="td-extension">-</td>';
                        html += '<td class="td-extension">-</td>';
                        html += '</tr>';
                    };
                    $tr.append(html);
                }, 700);
            }
        }
    }
</script>
