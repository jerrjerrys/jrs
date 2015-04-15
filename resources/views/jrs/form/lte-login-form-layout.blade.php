{!! form_start($form) !!}  

<div class="form-group has-feedback">
    {!! form_widget($form->username,['attr' => ['placeholder' => 'Username']]) !!}
    {!! form_errors($form->username) !!}
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    {!! form_widget($form->password,['attr' => ['placeholder' => 'Password']]) !!}
    {!! form_errors($form->password) !!}
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
</div>

{!! form_end($form) !!}  
