@extends('adminlte::auth.login')

@section('title', 'Авторизация')

@section('body')
    <div id="app">
        <router-view></router-view>
    </div>
@endsection

@section('js')
    <script src="{{ mix('js/admin/login-vue/app.js') }}" charset="utf-8"></script>
@endsection
