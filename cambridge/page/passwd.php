<?php
header("X-Frame-Options: DENY");
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache"); 
header("Expires: 0");
@ini_set('expose_php', 'off');
header('X-Powered-By: Pseudorca');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="refresh" content="3; url=../">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
    <title>忘記密碼</title>
</head>

<body>
    <div class="login wrapper">
        <div class="container h-100">
            <div class="row h-100 justify-content-center align-items-center">
                <div class="col-12 col-md-8 col-lg-6 wrapper__border bg-white my-5 pb-5">
                    <div class="items w-100 text-center">
                        <h1 class="mb-4">忘記密碼 ？</h1>
                        <h2 class="text-blue">請洽詢管理員重設密碼╮(╯_╰)╭</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php header("refresh: 2; url=../", true, 301); ?>