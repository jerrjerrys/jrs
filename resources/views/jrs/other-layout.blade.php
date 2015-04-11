<html>
    <head></head>
    <body>
        <center><h3>Layout 2</h3></center>        
        <div-form>            
            @if(!empty($form))
                @include($form['sub'],['field'=>$form['field']])
            @endif
        </div-form>        
                
        <div-table>
            @if(!empty($table))
                @include($table)
            @endif
        </div-table>                
    </body>
</html>
