@extends('basic_layout')
<table class="table table-sm">
    <thead>
    <tr>
        <th>User name</th>
        <th>User email</th>
        <th>User picture</th>
        <th colspan="2">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td><img class="card-img-top" src="{{url('uploads/'.$user->profile_pic)}}" height="80" width="80" alt="{{$user->profile_pic}}"></td>
            <td><a href="edit/{{ $user->id }}" class="btn btn-xs btn-info pull-right">Edit</a></td>
            <td><a href="delete/{{ $user->id }}" class="btn btn-xs btn-info pull-right">Delete</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</html>
