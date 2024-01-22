@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('resources')

@endsection
@vite(['resources/js/components/dashboard.js'])
@section('content')
        <div class="main-title">
            <p class="font-weight-bold">DASHBOARD</p>
        </div>

        <div class="main-cards">
            <div class="card">
                <div class="card-inner">
                    <p class="text-primary">VENUES</p>
                    <span class="material-icons-outlined text-blue">account_balance</span>
                </div>
                <span class="text-primary font-weight-bold">{{$venuesCount}}</span>
            </div>

            <div class="card">
                <div class="card-inner">
                    <p class="text-primary">EVENTS</p>
                    <span class="material-icons-outlined text-orange">theater_comedy</span>
                </div>
                <span class="text-primary font-weight-bold">{{$eventsCount}}</span>
            </div>

            <div class="card">
                <div class="card-inner">
                    <p class="text-primary">USERS</p>
                    <span class="material-icons-outlined text-green">group</span>
                </div>
                <span class="text-primary font-weight-bold">{{$usersCount}}</span>
            </div>

            <div class="card">
                <div class="card-inner">
                    <p class="text-primary">TICKETS</p>
                    <span class="material-icons-outlined text-red">confirmation_number</span>
                </div>
                <span class="text-primary font-weight-bold">{{$ticketsCount}}</span>
            </div>
        </div>

        <div class="charts">
            <div class="charts-card">
                <p class="chart-title">Top 5 Events</p>
                <div id="bar-chart"></div>
            </div>

            <div class="charts-card">
                <p class="chart-title">Ticket Purchase</p>
                <div id="area-chart"></div>
            </div>
        </div>
@endsection
