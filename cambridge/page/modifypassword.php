<?php
    session_start();
    include_once('../classes/user.php');
    $user = new user();
    $user::is_login();
    $lang = $_SESSION['lang'];
?>

<?php
try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['new']) && isset($_POST['old'])) {
            $old = htmlspecialchars($_POST['old']);
            $new = htmlspecialchars($_POST['new']);
            $id = $_POST['uid'];

            include_once('../classes/db.php');
            $DB = new db();
            $sql = "SELECT pwd FROM Userinfo WHERE uid = $id";
            $result = $DB::get_record($sql)[0];
            $tmp = base64_decode($result['pwd']);
            if ($old == $tmp) {
                    $new = base64_encode($new);
                    $sql = "UPDATE Userinfo SET pwd = '$new' WHERE uid = $id";
                    $none = $DB::update($sql);
            } else {
                echo '<script type="text/javascript"> alert("Wrong Old Password!") </script>';
                header('refresh: 0.5; url=./modifypassword.php');
            }
        } else {
            echo '<script type="text/javascript"> alert("Failed!") </script>';
            header('refresh: 0.5; url=./modifypassword.php');
        }
    }
} catch (Exception $e) {
    echo 'Caught exception: '.$e->getMessage()."\n";
}
?>

<?php
    $html_title = array('zh-tw'=>'修改密碼', 'en'=>'Change Password');
    require '../component/multilang.php';
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <title><?php echo $html_title[$lang]; ?></title>
</head>

<body>
    <div class="container px-0">
        <?php
            if(!$user::is_admin()) {
                require '../component/header_user.php';
            } else {
                require '../component/header_admin.php';
            }
        ?>
        <?php require '../component/lang.php';?>
        <div class="row justify-content-center">
            <form id="submit" class="row flex-column px-5 py-4 py-md-5 col-12 col-md-9 col-lg-9 wrapper__border bg-white" method="post">
                <h3 class="text-gray mb-5"><?php echo $html_title[$lang]; ?></h3>
                <div class="row justify-content-between mb-4">
                    <div class="col-12 col-md-6">
                        <input type="hidden" name="uid" value="<?php echo $_SESSION['valid_user']; ?>">
                        <input class="input-box form-control mb-3" type="password" placeholder="<?php echo $oldPassword[$lang]; ?>" id="oldPassword" name="old" required>
                        <input class="input-box form-control mb-3" type="password" placeholder="<?php echo $newPassword[$lang]; ?>" id="newPassword" name="new" required>
                        <input class="input-box form-control mb-3" type="password" placeholder="<?php echo $passwordAgain[$lang]; ?>" id="passwordAgain" required>
                    </div>
                    <div class="col-md-6 row justify-content-center align-items-center p-0">
                        <div class="lock">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>
                <button class="btn submit-btn btn-primary align-self-center mb-4" type="submit" onclick="return exam();"><?php echo $submit[$lang]; ?></button>
            </form>
        </div>        
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script type="text/javascript">
        <?php
            echo "var lang = '$lang';"; 
        ?>
        lang == 'zh-tw' ? $('#change').prop('checked', false) : $('#change').prop('checked', true);
        
        function exam() {
            var new1 = $('#newPassword').prop('value');
            var new2 = $('#passwordAgain').prop('value');
            if (new1.localeCompare(new2) != 0) {
                alert("[錯誤]: 請再次確認新密碼!");
                return false;
            } else {
                return true;
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="../js/changelang.js"></script>
</body>
</html>