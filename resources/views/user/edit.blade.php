@include('basic_layout')
@include('HR/dashboard')
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{--{{dd($user->email)}};--}}
<form method="post" action="{{ action('HrController@updateUser', $user)}}" enctype="multipart/form-data">
    {{ csrf_field() }}
{{--    {{ method_field('post') }}--}}

    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control"  value="{{$user->name}}" id="name" name="name">
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control"value="{{$user->email}}" id="email" name="email">
    </div>

    <div class="form-group">
        <label for="password">New Password:</label>
        <input type="password" class="form-control"  id="password" name="password">
    </div>
    <div class="form-group">
        <label for="password">Confirm Password:</label>
        <input type="password" class="form-control"  id="password_confirmation" name="password_confirmation">
    </div>

    <div class="form-group">
        <label for="salary">Salary:</label>
        <input type="salary" class="form-control" value="{{$user->salary}}" id="salary" name="salary">
    </div>

    <div class="form-group">
        <label for="department">department:</label>
        <input type="department" value="{{$user->department}}" class="form-control" id="department" name="department">
    </div>

    <div class="form-check">

        <input class="form-check-input" type="checkbox"  name="is_hr" value={{(bool)$user->is_hr}}  id="is_hr">
        <label class="form-check-label" for="is_hr">
            HR
        </label>
    </div>

    <div class="form-group">
        <label for="profile-pic">Profile Pic</label>
        <input type="file" class="form-control-file" name="profile-pic" id="profile-pic">
    </div>

    @if (!empty($designations) && count($designations))
        <select  id="boss_name"  name="designation_id" id="designation_id">
            <option value="option_select" disabled selected>Select the designation</option>
            @foreach($designations as $designation)
                <option value="{{ $designation->id }}" {{$user->designation_id == $designation->id  ? 'selected' : ''}}>{{ $designation->name }}</option>
            @endforeach

        </select>
    @endif

    <div class="form-group">
        <button style="cursor:pointer" type="submit" class="btn btn-primary">Submit</button>
    </div>

</form>
{{--</html>--}}
