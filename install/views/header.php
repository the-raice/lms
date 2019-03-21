<!DOCTYPE HTML>
<html>
<head>
    <title>Установка The Raice LMS</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/public/assets/css/default.css">
    <link rel="stylesheet" type="text/css" href="/public/assets/css/pagePreloadEffect.css" />
    <script src="/public/assets/js/modernizr.custom.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="/public/favicon.ico" type="image/x-icon">
</head>
<body>
    <div id="ip-container" class="ip-container">
                <header class="ip-header">
                    <div class="ip-loader">
                        <svg class="ip-inner" width="60px" height="60px" viewBox="0 0 80 80">
                            <path class="ip-loader-circlebg" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,40.5,10z"/>
                            <path id="ip-loader-circle" class="ip-loader-circle" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,40.5,10z"/>
                        </svg>
                    </div>
                </header>
        <div class="install step<?=$step ?>">
            <div class="content__install">
                <nav class="install__nav">
                    <ul class="nav__ul">
                        <a class="nav__link" href="/install/install?step=0"><li class="nav__li it1">Добро пожаловать</li></a>
                        <a class="nav__link" href="/install/install?step=1"><li class="nav__li it2">Настройка базы данных</li></a>
                        <a class="nav__link" href="#"><li class="nav__li it3">Регистрация</li></a>
                        <a class="nav__link" href="#"><li class="nav__li it4">Готово</li></a>
                    </ul>
                </nav>
            </div>
            <header class="content__header">
