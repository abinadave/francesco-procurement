
<template>
  <div class="modal fade" id="modal-po-receipt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Modal title</h4>
        </div>
        <div class="modal-body">
            <br>
            <!-- <img src=""> -->
            <p class="text-center">INTEGRATED, PEOPLE-DRIVEN COMMUNITY PROJECT IN POST YOLANDA</p>
            <p class="text-center" style="margin-top: -12px; font-size: 11px" >BRGY. 99 DIIT, TACLOBAN CITY</p>
            <h5 class="text-center" style="margin-top: 5px; font-weight: bolder">PURCHASE REQUEST</h5>
            <div class="pull-right" style="margin-top: -10px">
                <ul>
                  <li><b>Date: &nbsp;&nbsp;</b></li>
                  <li><b>PR No.</b></li>
                </ul>
            </div>
            <div style="margin-top: -5px">
                <label>Name </label>

            </div><br>
            
            <div class="panel panel-default">
                <ul>
                    <li>Location &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                      <span>________________________</span>

                    </li>
                    <li>Block No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                      <span>________________________</span>

                    </li>
                    <li>House Model&nbsp;
                      <span>________________________</span>

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
                            
                    </tbody>
                    <tfoot>
                        <tr>
                           <th colspan="4" style="font-size: 16px">ESTIMATED COST</th>
                           <th style="text-align: right; font-size: 16px" class="text-danger">P</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
    export default {
        mounted() {

        },
        computed: {
            
        }
    }
</script>