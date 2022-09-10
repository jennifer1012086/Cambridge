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
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit;
}
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token'];
$lang = 'zh-tw';
if(isset($_GET['lang']) && $_GET['lang'] == 'en') {
    $lang = htmlspecialchars($_GET['lang']);
}
$_SESSION['lang'] = $lang;

$html_title = array('zh-tw'=>'登入', 'en'=>'Login');
require './component/multilang.php';
?>

<!DOCTYPE HTML>
<html lang="zh-TW">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <title><?php echo $html_title[$lang]; ?></title>
</head>

<body>
    <div class="login wrapper">
        <div class="container h-100">
            <?php require './component/lang.php'; ?>
            <div class="row h-100 justify-content-center align-items-center">
                <div class="col-12 col-md-8 col-lg-6 wrapper__border bg-white my-5">
                    <div class="items w-100">
                        <?php
                            if ($lang == 'zh-tw') {
                                echo '<h1 class="logo mb-4"><img class="w-100" src="img/logo.png" alt="康橋國際學校幼兒園（林口校區）耗材管理系統"></h1>';
                            } else if ($lang == 'en') {
                                echo '<div class="title fs-en text-center mb-4">
                                    <p class="fz-1 mb-2">Kang Chiao International School Preschool (Linkou Campus)</p>
                                    <h1 class="fz-2">Supplies Management System</h1>
                                </div>';
                            }

                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                if (!empty($_POST['token'])) {
                                    if (hash_equals($_SESSION['token'], $_POST['token'])) {
                                        if(isset($_POST['account']) && isset($_POST['passwd'])) {
                                            $acnt = htmlspecialchars($_POST['account']);
                                            $pwd = htmlspecialchars($_POST['passwd']);

                                            include_once('./classes/db.php');
                                            $DB = new db();
                                            $sql = "SELECT * FROM Userinfo u, userAndRole ur WHERE u.uid = ur.uid";
                                            $result = $DB::get_record($sql);

                                            if(!$result) {
                                                exit();
                                            }

                                            $isuser = 0;
                                            foreach ($result as $r) {
                                                if($acnt == $r['acnt'] && $pwd == base64_decode($r['pwd'])) {
                                                    $isuser = 1;
                                                    $_SESSION['valid_user'] = $r['uid'];
                                                    $_SESSION['username'] = $r['name'];
                                                    $_SESSION['role'] = $r['rid'];
                                                    $_SESSION['session'] = session_id();
                                                }
                                            }
                                            if($isuser)
                                                header('Location: ./page/');
                                            else
                                                echo "<h3 class='text-center'>".$login_failed[$lang]."</h3>";
                                        }
                                    } else {
                                        echo "<h3 class='text-center'>[Warning]: Illegal attempts!</h3>";
                                    }
                                }
                            } 
                        ?>
                        <form method="post">
                            <input type="hidden" id="token" name="token" value="<?php echo $token; ?>" required>
                            <div class="form-group">
                                <label for="account"><?php echo $account[$lang]; ?></label>
                                <input type="text" class="form-control bg-gray" id="account" name="account" required>
                            </div>
                            <div class="form-group">
                                <label for="password"><?php echo $password[$lang]; ?></label>
                                <input type="password" class="form-control bg-gray" id="password" name="passwd" required>
                            </div>
                            <button id="submit" type="submit" class="btn wrapper_btn w-100 bg-red text-white"><?php echo $login[$lang]; ?></button>
                            <div class="form-group text-center mt-4">
                                <a href="./page/passwd.php" class="text-blue"><?php echo $forgot_password[$lang]; ?></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        <?php
            echo "let lang = '$lang';"; 
        ?>
        let change = document.getElementById('change');
        lang == 'zh-tw' ? change.checked = false : change.checked = true;
        change.onclick = function changeLang() {
            if (change.checked) {
                window.location.replace('./?lang=en');
            } else {
                window.location.replace('./');
            }
        }
    </script>
</body>

</html>