@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            <div class="card">
                <div class="card-header" style="text-align:center;">{{ Auth::user()->lastname }} {{ Auth::user()->firstname}} voici les information pour vos congées</div>
                @foreach ($employees as $employee)
                    <div class="card-body">
                        <h1>Congées acquis: {{ $employee->timeoff_granted }}</h1>
                        <h1>Congées restant: {{ $employee->total_timeoff }}</h1>
                        <h1>UserId: {{ $employee->user_id }}</h1>
                        <h1>ServiceId: {{ $employee->service_id }}</h1>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
