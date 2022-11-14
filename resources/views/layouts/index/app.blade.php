@inject('bladeService', 'App\Services\BladeService')
        <!DOCTYPE html>
<html lang="en">

<head>
    <base href="">
    <title>Paradoxx Online</title>
    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="" type="image/x-icon">
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200&display=swap" rel="stylesheet">

    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- ------------------------------------------------------------------------------ -->

    <!-- Open Graph (og) предпросмотр (превью) веб-страниц в социальных сетях -->
    <meta property="og:title" content="Paradoxx Online">
    <meta property="og:description"
          content="Инвестируйте в весь крипто-рынок сразу, а не в конкретную криптовалюту. Платформа для покупки и продажи индексных фондов с различной доходностью и степенью риска">
    <meta property="og:type" content="article">
    <meta property="og:image" content="">
    <meta property="og:site_name" content="" />

    <!-- ------------------------------------------------------------------------------ -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="/front/css/zero.css">
    <link rel="stylesheet" href="/front/css/style.css">
    <link rel="stylesheet" href="/front/css/fonts-icon.css">

    <!-- ------------------------------------------------------------------------------ -->
</head>

<body>
@include("layouts.index.header")
@yield('content')
@include("layouts.index.footer")
<script src="{{asset('assets/js/public/app.js')}}" defer></script>
@yield('script')

</body>
</html>