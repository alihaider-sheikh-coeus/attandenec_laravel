@include('basic_layout')
{{--@include('HR/dashboard')--}}
<h1>On AttendancePage</h1>
<!DOCTYPE html>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<html>
<body>
<h3>Hi, <strong>{{ \Illuminate\Support\Facades\Auth::user()->name }}</strong> mark your attendance</h3>
@php
    $time_in_value=false;
    $time_out_value=false;
@endphp
@if (!empty($attandence_check) && count($attandence_check))
    @if(($attandence_check[0]->time_in!==null))
        @php
            $time_in_value = true;
        @endphp
    @endif
    @if(($attandence_check[0]->time_out!==null))
        @php
            $time_out_value = true;
        @endphp
    @endif
@endif
<div class="container">

    <form method="post" action="{{ action('AttandenceController@markAttandence')}}">
    {{csrf_field()}}
        <input type="text" id="TimeInField" style="height: 30px; width: 180px; border-collapse: collapse;margin-top: 120px" value="{{ ($time_in_value)? $attandence_check[0]->time_in:"" }}" readonly name="TimeIn">
        @if( $time_in_value)
            <button  type="button" id="TimeIn"  disabled  class="btn btn-primary" style="font-size: small"> Time In</button>
        @else
            <button  type="button" id="TimeIn"   class="btn btn-primary" style="font-size: small"> Time In</button>
        @endif
        <input type="text" style="height: 30px; width: 180px;" id="TimeOutField" value="{{ ($time_out_value)? $attandence_check[0]->time_out:"" }}" readonly name="TimeOut">
        @if( $time_out_value)
            <button type="button" disabled  id="TimeOut" class="btn btn-warning" style="font-size: small"  >Time Out</button>
        @else
            <button type="button"   id="TimeOut" class="btn btn-warning" style="font-size: small"  >Time Out</button>
        @endif
        <div class="form-group">
            <button style="cursor:pointer" type="submit" class="btn btn-success">Submit</button>

            @if( \Illuminate\Support\Facades\Auth::user()->is_hr!=1)
            <a href="/logout" class="btn btn-danger ml-3">Sign Out of Your Account</a>
            @else
                <a href="/dashboard" type="button" class="btn btn-warning">DashBoard </a>
            @endif
        </div>
    </form>
    </div>

<script>
    $(document).ready(function()
    {
        $("#TimeIn").click(function()
        {
            new Date($.now());
            var dateObj = new Date();
            var time = dateObj.getHours() + ":" + dateObj.getMinutes() + ":" + dateObj.getSeconds();
            $('#TimeInField').val(time);
         });

        $("#TimeOut").click(function()
        {
            if( !$('#TimeInField').val() ) {
                alert("please first time_in and then time out");
            }
            else
            {
                new Date($.now());
                var dateObj = new Date();
                var time = dateObj.getHours() + ":" + dateObj.getMinutes() + ":" + dateObj.getSeconds();
                $('#TimeOutField').val(time);
            }
        });
    });
</script>
</body>
</html>
