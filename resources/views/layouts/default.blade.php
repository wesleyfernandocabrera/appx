<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE v4 | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="AdminLTE"/>
    <meta name="keywords" content="admin dashboard"/>
      <!--Adcionar app.scss-->
      @vite('resources/scss/app.scss')
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <!--adicionar header-->
      @include('parts.header')
      <!-- /.navbar -->
      @include('parts.sidebar')
      <main class="app-main">
      <!--adicionar content-header-->  
      @include('parts.content-header')
      <div class="app-content">
      <div class="container-fluid">
      <!--adicionar content-->
      @yield('content')
      </div>
      </div>
      </main>
      <!--adicionar footer-->
      @include('parts.footer')
      </div>
      <!--adicionar app.js-->
      @vite('resources/js/app.js')
  </body>
</html>
