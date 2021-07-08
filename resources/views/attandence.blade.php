@extends('basic_layout')
<h1>in attandencePage</h1>
<!DOCTYPE html>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<html>
<body>

<div class="container">
    <form method="post" action="{{ action('AttandenceController@markAttandence')}}">
    {{csrf_field()}}
        <input type="text" id="TimeInField" style="height: 30px; width: 180px; border-collapse: collapse;margin-top: 120px" value="" readonly name="TimeIn">
    <button  type="button" id="TimeIn"class="btn btn-primary"style="font-size: small"  >Time In</button>
    <input type="text" style="height: 30px; width: 180px;" id="TimeOutField" value="" readonly name="TimeOut">
    <button type="button" id="TimeOut"class="btn btn-primary"style="font-size: small"  >Time Out</button>
        <div class="form-group">
            <button style="cursor:pointer" type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>
    </div>

<script>
    $(document).ready(function()
    {  $("#TimeIn").click(function()
        {
            new Date($.now());
            var dateObj = new Date();
            var time = dateObj.getHours() + ":" + dateObj.getMinutes() + ":" + dateObj.getSeconds();
            $('#TimeInField').val(time);
         });

        $("#TimeOut").click(function()
        {
            new Date($.now());
            var dateObj = new Date();
            var time = dateObj.getHours() + ":" + dateObj.getMinutes() + ":" + dateObj.getSeconds();
            $('#TimeOutField').val(time);
        });
    });
</script>
</body>
</html>
