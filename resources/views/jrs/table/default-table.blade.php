@if(!empty($table['head']))
{!! $table['create'] !!}
<hr/>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                @foreach($table['head'] as $key => $value)
                <th>{!! $value !!}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($table['body'][1] as $key2 => $value2)
            <tr>
                @foreach($table['body'][0] as $key3 => $value3)
                <td>
                    {!! $value2[$value3] !!}
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>    
    </table>    
</div>
@else
<blockquote>
    <p>No Data Available</p>
    <footer><cite title="Development Phase">Create some</cite> {!! $table['create'] !!} </footer>
</blockquote>
@endif
