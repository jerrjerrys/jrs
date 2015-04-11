<style>

    a {
        text-decoration:underline;
        color:#00F;
        cursor:pointer;
    }

    .controls div, .controls div input {
        float:left;    
        margin-right: 10px;
    }

</style>

<script type="text/javascript" src="{!! asset('assets/js/jquery.sheepItPlugin.js') !!}"></script>
<script type="text/javascript">

    $(document).ready(function() {


        var addressesForm = $("#address").sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowAdd: true,
            // Limits
            maxFormsCount: 10,
            minFormsCount: 1,
            iniFormsCount: 1,
        });


    });

</script>

<div id="address">      
    <hr/>
    <div class="form-group"  >
        <!-- Controls -->            
        <div id="address_controls" class="">
            <div class="col-sm-2"></div>
            <div class="col-sm-3">
                <div id="address_add"><a><span>Add address</span></a></div>
            </div>
            <div class="col-sm-5">
                <div id="address_remove_last"><a><span>Remove</span></a></div>            
            </div>            
        </div>        
        <!-- /Controls -->
    </div>
    
    <!-- Form template-->
    <div id="address_template">
        <div class="form-group"  >
            <div class="col-sm-2"></div>
            <label class="control-label col-sm-2">Address</label>    

            <div class="col-sm-6">
                <input id="address_#index#" class="form-control" name="address[#index#]" type="text" size="50" maxlength="100" />
            </div>
            <div class="col-sm-2">
                <button id="address_remove_current" class="btn btn-xs btn-danger">Remove</button>                                    
            </div>
        </div>
        <div class="form-group"  >
            <div class="col-sm-2"></div>
            <label class="control-label col-sm-2">Name</label>    

            <div class="col-sm-6">
                <input id="address_#index#" class="form-control" name="name[#index#]" type="text" size="50" maxlength="100" />
            </div>            
        </div>
        <hr/>
    </div>
    <!-- /Form template -->

    <!-- No forms template -->
    <div id="address_noforms_template">No addresses</div>
    <!-- /No forms template -->     
    <hr/>
</div>
<!-- /Main sheepIt Form -->
