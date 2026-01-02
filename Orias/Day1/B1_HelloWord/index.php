<?php
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hello, World!</title>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f6f8fa;
            margin: 0;
        }
        .gradient-text {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(90deg, #2f81f7, #6e40c9, #2ca07dff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>
<body>
    <span class="gradient-text">Hello, World!</span>
</body>
</html>';
?>