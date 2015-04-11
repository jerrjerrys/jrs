@if(Session::has('save-error'))            
<div class="alert alert-danger" role="alert">
    <b>{!! Session::get('save-error') !!}</b>
</div>
@endif

@if(Session::has('error'))            
<div class="alert alert-danger" role="alert">
    <b>{!! Session::get('error') !!}</b>
</div>
@endif

@if(Session::has('save-success'))
<div class="alert alert-success" role="alert">
    <b>{!! Session::get('save-success') !!}</b>
</div>            
@endif