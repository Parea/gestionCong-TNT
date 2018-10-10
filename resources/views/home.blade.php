@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            <div class="card">
                <div class="card-header" style="text-align:center;">Bienvenue sur votre gestionnaire de congés {{ Auth::user()->lastname }} {{ Auth::user()->firstname}}</div>
                 <div class="card-body">
                    Vous êtes bien connecter 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif --}}