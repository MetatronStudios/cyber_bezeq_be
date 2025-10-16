<?php
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
if (time() > mktime(0, 0, 1, 11, 3, 2025)) {
    header('location: /index.html');
    exit();
}?><!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-789WG8EQTS"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-789WG8EQTS');
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>האתר בבניה</title>
    <style>
        body {
            direction: rtl;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }

        h1 {
            color: #ff5733;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .construction-icon {
            font-size: 100px;
            color: #ff5733;
        }
    </style>
</head>

<body lang="he" dir="rtl">
    <i class="construction-icon">🚧</i>
    <h1>Bezeq Cyber Game<br>
        בעקבות חמיצר ואוצרות הסייבר הישראלי</h1>
    <p>יעלה להתמודדות ביום ב' ה- 3 בנובמבר</p>
    <p>בהצלחה!<br>
<img src="dan_sign.png" alt="דן חמיצר"></p>
</body>

</html>
