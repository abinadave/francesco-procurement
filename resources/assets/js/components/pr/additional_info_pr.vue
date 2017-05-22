<template>
    <div>
        <!-- This is the template, pr_no: {{ requestForm.id }} -->
        <div class="col-md-10">
            <div class="form-group col-md-4">
              <label class="control-label" for="inputError1">House model</label>
              <input type="text" :value="getHouseModel" class="form-control input-sm">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label"  for="inputError1">Location</label>
              <input type="text" class="form-control input-sm" :value="requestForm.location">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label" for="inputError1">Block no.</label>
              <input type="text" :value="requestForm.block_no" class="form-control input-sm">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label" for="inputError1">Charging</label>
              <input type="text" class="form-control input-sm" :value="requestForm.charging">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label" for="inputError1">Date / time</label>
              <input type="text" class="form-control input-sm" :value="formatDate(requestForm.datetime)">
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    export default {
        mounted() {
            // console.log('Component mounted.')
        },
        props: {
            houseModels: {
                type: Array
            },
            requestForm: {
                type: Object
            }
        },
        methods: {
            formatDate(datetime){
                let self = this;
                return moment(datetime).format('MMMM DD, YYYY, HH:mm a');
            }
        },
        computed: {
            getHouseModel(){
                let self = this;
                let hmi = Number(self.requestForm.house_model);
                let rs = _.filter(self.houseModels, {id: hmi});
                if (rs.length) {
                    let houseModel = _.first(rs);
                    return houseModel.model;
                }
            }
        },
        watch: {
            'requestForm': function(newVal){
                // $.each(newVal, function(index, val) {
                //     console.log(index + ': ' + val);
                // });
            }
        }
    }
</script>
