
@extends('basic_layout')
<h1>Report Page</h1>
<h3>Here we generate daily report!</h3>
<a href="/dashboard" class="btn btn-warning">Back </a>


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
@else

<h2> No record for today!</h2>

@endif
