@extends('basic_layout')
{{--@extends('flash-messages')--}}

{{--<h3>Hi, <strong>{{ \Illuminate\Support\Facades\Auth::user()->name }}</strong> welcome to side</h3>--}}
<nav class="navbar flex-column">

    <a href="{{url('/markattandenceView')}}" class="btn btn-warning nav-link">Mark attendance </a>
    <a href="{{url('user/add')}}" class="btn btn-primary nav-link">Add employee </a>
    <a href="{{url('user/show')}}" class="btn btn-info nav-link">Show employees </a>
    <a href="{{url('/daily_report')}}" class="btn btn-success nav-link">Today's report</a>
    <a href="{{url('/report')}}" class="btn btn-info nav-link">Monthly Report</a>
   <a href= "{{url('/logout')}}" class="btn btn-danger ml-3 nav-link">Sign Out of Your Account</a>
</nav>
