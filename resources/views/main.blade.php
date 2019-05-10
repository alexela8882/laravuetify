<!DOCTYPE html>
<html>
<head>
	<title>Laravel & Vuetify</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet">
  <!-- <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
</head>
<body>

  <div id="app">
    <app></app>
  </div>

  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>