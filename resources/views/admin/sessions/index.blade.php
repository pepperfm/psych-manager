@extends('adminlte::page')

@section('title', 'Сессии')

@section('content_header')
@endsection

@section('content')
    <div id="app">
        <router-view></router-view>
    </div>
@endsection

@section('css')
    {{--    <link rel="stylesheet" href="/css/admin_custom.css">--}}
@endsection

@section('js')
    <script src="{{ mix('js/admin/sessions-vue/app.js') }}" charset="utf-8"></script>
@endsection
