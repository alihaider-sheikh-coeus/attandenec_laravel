@extends('basic_layout')
<meta charset="utf-8">
<title>jQuery UI Datepicker - Default functionality</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<h1> in report page</h1>
<form method="post" >
    {{csrf_field()}}
<label for="month">Month: </label>
<input type="text" id="month" name="month" class="monthPicker" />
 <button style="cursor:pointer" type="submit" class="btn btn-success">Submit</button>
</form>
@if (!empty($users) && count($users))
    <table class="table table-sm">
        <thead>
        <tr>
            <th>Status</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->status }}</td>
                <td>{{ $user->total }}</td>

            </tr>
        @endforeach
        </tbody>
    </table>


@endif

<script>

    $(document).ready(function()
    {
        $(".monthPicker").datepicker({
            dateFormat: 'MM yy',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,

            onClose: function(dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).val($.datepicker.formatDate('MM yy', new Date(year, month, 1)));
            }
        });

        $(".monthPicker").focus(function () {
            $(".ui-datepicker-calendar").hide();
            $("#ui-datepicker-div").position({
                my: "center top",
                at: "center bottom",
                of: $(this)
            });
        });
    });
</script>
