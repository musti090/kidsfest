<!DOCTYPE html>
<html lang="az">
<head>
    <title>@yield('title','İdarə Paneli') | İdarə Paneli</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   {{-- <meta name="csrf-token" content="{{ csrf_token() }}" />--}}
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/fontawesome-free/css/all.min.css') }}">
     @stack('categoriesCss')
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/myCustom/css/myCustom.css') }}">
    @stack('customCss')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed {{--layout-footer-fixed--}}">
<div class="gel d-flex align-items-center justify-content-center" style="height: 100vh;font-size: 20px">
        <button class="btn btn-info p-3">
        <span class="spinner-grow spinner-grow-sm"></span>
        Məlumatlar yüklənir..
        </button>
</div>
<div class="wrapper">

    <div class="umumi-getir d-flex align-items-center justify-content-center" style="height: 100vh;font-size: 20px">
        <button class="btn btn-info p-3">
            <span class="spinner-grow spinner-grow-sm"></span>
            Məlumatlar yüklənir..
        </button>
    </div>