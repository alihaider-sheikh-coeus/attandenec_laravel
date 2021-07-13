@extends('basic_layout')
@extends('flash-messages')

<form method="post" action={{ action('UserController@login')}}>
    {{csrf_field()}}


    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
<div class="form-group">
    <button style="cursor:pointer" type="submit" class="btn btn-primary">Submit</button>
</div>
</form>

