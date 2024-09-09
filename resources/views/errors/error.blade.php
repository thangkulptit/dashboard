@if(Session::has('error'))
<p class="alert alert-danger">{{Session::get('error')}}</p>
@elseif(Session::has('success'))
<p class="alert alert-success">{{Session::get('success')}}</p>
@endif