<?php
    session_start();
    include_once('../classes/user.php');
    $user = new user();
    $user::is_login();
    if(!$user::is_admin()) {
        header('location: ./');
    }
    $lang = $_SESSION['lang'];
?>

<?php
try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['uid'])) {
            $uid = htmlspecialchars($_POST['uid']);

            include_once('../classes/db.php');
            $DB = new db();
            $sql = "SELECT acnt FROM Userinfo WHERE uid = $uid";
            $result = $DB::get_record($sql);
            $acnt = $result[0]['acnt'];
            $sql = "UPDATE Userinfo SET pwd = '".base64_encode($acnt)."' WHERE uid = $uid";
            $none = $DB::update($sql);
            header('Location: ../lib/logout.php');
        } else {
            echo '<script type="text/javascript"> alert("Failed!") </script>';
            header('refresh: 0.5; url=./reset.php');
        }
    }
} catch (Exception $e) {
    echo 'Caught exception: '.$e->getMessage()."\n";
}
?>

<?php
    $html_title = array('zh-tw'=>'重設密碼', 'en'=>'Reset Password');
    require '../component/multilang.php';
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
    <title><?php echo $html_title[$lang]; ?></title>
</head>

<body>
    <?php require '../component/lang.php'; ?>
    <div class="login wrapper">
        <div class="container h-100">
            <div class="row h-100 justify-content-center align-items-center">
                <div class="col-12 col-md-8 col-lg-5 wrapper__border bg-white my-5 pb-5">
                    <div class="items w-100">
                        <h1 class="py-3 mb-4 text-center"><?php echo $html_title[$lang]; ?></h1>
                        <form method="post">
                            <div class="form-group">
                                <select class="input-box form-control mb-3" id="uid" name="uid" required>
                                    <option value="" selected disabled hidden><?php echo $account[$lang]; ?></option>
                                    <?php
                                        include_once('../classes/db.php');
                                        $DB = new db();
                                        $sql = "SELECT u.uid, u.name username, u.acnt, r.name role, c.name class FROM Userinfo u, userAndRole ur, userAndClass uc, Role r, Class c WHERE u.uid = ur.uid AND ur.rid = r.rid AND u.uid = uc.uid AND uc.cid = c.cid";
                                        $result = $DB::get_record($sql);
                                        foreach ($result as $r) {
                                            $rl = simplexml_load_string($r['role']);
                                            $c = simplexml_load_string($r['class']);
                                            echo "<option value='".$r['uid']."'>".$r['acnt']." (".$r['username']."/".$rl->attributes()->{$lang}."/".$c->attributes()->{$lang}.")</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn wrapper_btn w-100 bg-red text-white mt-5"><?php echo $submit[$lang]; ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        <?php
            echo "var lang = '$lang';"; 
        ?>
        lang == 'zh-tw' ? $('#change').prop('checked', false) : $('#change').prop('checked', true);
    </script>
    <script src="../js/changelang.js"></script>
</body>
</html>