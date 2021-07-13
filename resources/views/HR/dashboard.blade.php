@extends('basic_layout')
@extends('flash-messages')
@push('css')
    <link rel="stylesheet" href="path to your css">
@endpush
<h3>Hi, <strong>{{ \Illuminate\Support\Facades\Auth::user()->name }}</strong> welcome to side</h3>
<div class="links" >
    <a href="/markattandenceView" class="btn btn-warning">Mark attendance </a>
    <a href="user/add" class="btn btn-primary">Add employee </a>
    <a href="user/show" class="btn btn-info">Show employees </a>
    <a href="/daily_report" class="btn btn-success">Today's report</a>
    <a href="/report" class="btn btn-info">Monthly Report</a>

    <a href="/logout" class="btn btn-danger ml-3">Sign Out of Your Account</a>

</div>
