<html>
      <body>
         <form action='' method='post'>
              @csrf
                      @if($errors->any())
              <ul>
             @foreach($errors->all() as $error)
            <li> {{ $error }} </li>
             @endforeach
        @endif

        @if( session( 'success' ) )
             {{ session( 'success' ) }}
        @endif
            <label>Bill Amount: </label>
             {{$amount}}
             <br>

             <label> Input Mpesa Number to pay </label>
             <input type='text' name='mpesa_number' />
             <br>

            <button type='submit'>Send!</button>
      </form>
    </body>
</html>