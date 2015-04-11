<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Table</h3>
        <div class="box-tools pull-right">
            @if(array_key_exists('create', $table))            
            <a href='{!! url($table["create"][0]) !!}' class='btn btn-default btn-sm'><i class="fa fa-plus"></i></a>
            @endif            
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>                        
                        @foreach($table['show'] as $key => $value)
                        <th><center>{!! $key !!}</center></th>
                        @endforeach

                        @if(array_key_exists('update', $table))
                        <th><center>Update</center></th>
                        @endif

                        @if(array_key_exists('delete', $table))
                        <th><center>Remove</center></th>
                        @endif
                    </tr>
                </thead>
                <tbody>            
                    {!! $table['body'] !!}
                </tbody>    
            </table>                
        </div>
    </div>
    <div class="box-footer clearfix">
        {!! $table['query']->paginate($table['paginate'])->render() !!}
    </div>
</div>