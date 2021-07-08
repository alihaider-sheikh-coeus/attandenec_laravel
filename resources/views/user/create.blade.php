@extends('basic_layout')
<h1>Create Users</h1>
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
<form method="post" action={{ action('UserController@store')}} enctype="multipart/form-data">
        {{csrf_field()}}

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="salary">Salary:</label>
            <input type="salary" class="form-control" id="salary" name="salary">
        </div>

        <div class="form-group">
            <label for="department">department:</label>
            <input type="department" class="form-control" id="department" name="department">
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox"  name="is_hr" value=true id="is_hr">
            <label class="form-check-label" for="is_hr">
                HR
            </label>
        </div>

        <div class="form-group">
            <label for="profile-pic">Profile Pic</label>
            <input type="file" class="form-control-file" name="profile-pic" id="profile-pic">
        </div>
        <select class="form-select" aria-label="Default select example" name="designation_id" id="designation_id">
{{--            <label for="">designation Pic</label>--}}
            <option selected>select designation</option>
            <option value="1">manager</option>
            <option value="2">hr</option>
            <option value="3">CEO</option>
            <option value="4">developer</option>
        </select>

        <div class="form-group">
            <button style="cursor:pointer" type="submit" class="btn btn-primary">Submit</button>
        </div>
{{--        @include('partials.formerrors')--}}
    </form>

{{--@endsection--}}


