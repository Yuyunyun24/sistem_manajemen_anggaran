@extends('layouts.loginregister.app')
@section('content')
    
    <div class="wrapper">
        <div class="logo">
            <img src="{{URL::asset('images/logo.png')}}" alt="">
        </div>
        <div class="text-center mt-4 name">
            Login
        </div>
        <br>
        <form class="p-3 mt-3" action="{{ route('actionlogin') }}" method="post">
        @csrf
            <div class="form-field d-flex align-items-center">
                <span class="far fa-user"></span>
                <input type="email" name="email" id="userName" placeholder="Email">
            </div>
            <div class="form-field d-flex align-items-center">
                <span class="fas fa-key"></span>
                <input type="password" name="password" id="pwd" placeholder="Password">
            </div>
            <button type="submit" class="btn mt-3">Login</button>
        </form>
        
    </div>

@endsection