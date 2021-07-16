@include('basic_layout')
@include('HR/dashboard')

<h1>Create Users</h1>

<form method="post" action={{ action('HrController@store')}} enctype="multipart/form-data">
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
    @if (!empty($bosses) && count($bosses))
    <select  id="boss_name"  name="boss_name" class="form-control">

        <option selected  value=null>select the boss </option>
        @foreach($bosses as $boss)
            <option value="{{ $boss->id}}">{{ $boss->name }}</option>
        @endforeach

    </select>
    @endif

    @if (!empty($designations) && count($designations))
        <select  id="boss_name"  name="designation_id" id="designation_id">

            <option selected  value=null>select the designation </option>
            @foreach($designations as $designation)
                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
            @endforeach

        </select>
    @endif
        <div class="form-group">
            <button style="cursor:pointer" type="submit" class="btn btn-primary">Submit</button>
{{--            <a href="/dashboard" type="button" class="btn btn-warning">Back </a>--}}
        </div>



    </form>

