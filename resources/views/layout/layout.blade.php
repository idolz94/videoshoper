<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Dashboard</title>
  <base href="{{asset('')}}" target="">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!-- Bootstrap 3.3.7 -->
  @include('layout.__layout.bottom')
    @include('layout.__layout.script')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

@include('layout.__layout.header')
@include('layout.__layout.sidebar')

<!-- Left side column. contains the logo and sidebar -->

@yield('content')
</div>
</body>
</html>
