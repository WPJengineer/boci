<?php

session_start();

require(__DIR__ . '/components/messages.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boci</title>
    <link rel="icon" href="/student014/boci/assets/images/logo.svg" type="svg">
    <link rel="stylesheet" href="/student014/boci/css/common.css">
    <link rel="stylesheet" href="/student014/boci/css/backend_style.css">
  </head>
<body>
    <header>
        <div class="logo-header">
            <a href="/student014/boci/index.html">
                <img class="logo" src="/student014/boci/assets/images/logo.svg" alt="logo">
            </a>
                <nav class="header-icons">
                <!-- <img class="icon" src="/boci/assets/icons/profile-icon-black.svg" alt="profile-icon"> -->
                <!-- <div class="search-bar">
                    <img class="icon" src="./assets/icons/search-icon-black.svg" alt="search-icon">
                </div> -->
            </nav>
        </div>
        <nav class="header-menu">
            <ul>
                <li id="btnHome"><a href="/student014/boci/index.html">INICIO</a></li>
                <li id="btnProducts"><a href="/student014/boci/views/products.html">JUGUETES</a></li>
                <li id="btnAboutUs"><a href="/student014/boci/views/about.html">SOBRE NOSOTROS</a></li>
                <li id="btnBlog"><a href="/student014/boci/views/blogs.html">BLOG</a></li>
            </ul>
        </nav>
    </header>