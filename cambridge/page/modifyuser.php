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
        if(isset($_POST['uid']) && isset($_POST['username'])) {
            $uname = htmlspecialchars($_POST['username']);
            $uid = htmlspecialchars($_POST['uid']);
            $rtype = $_POST['role'];
            $noclass = 1;

            include_once('../classes/db.php');
            $DB = new db();
            $sql = "UPDATE Userinfo SET name = '$uname' WHERE uid = $uid";
            $none = $DB::update($sql);
            $sql = "UPDATE userAndRole SET rid = $rtype WHERE uid = $uid";
            $none = $DB::update($sql);
            if (isset($_POST['class'])) {
                $cname = $_POST['class'];
                $sql = "UPDATE userAndClass SET cid = $cname WHERE uid = $uid";
                $none = $DB::update($sql);
            } else {
                $sql = "UPDATE userAndClass SET cid = $noclass WHERE uid = $uid";
                $none = $DB::update($sql);
            }
        } else {
            echo '<script type="text/javascript"> alert("Failed!") </script>';
            header('refresh: 0.5; url=./modifyuser.php');
        }
    }
} catch (Exception $e) {
    echo 'Caught exception: '.$e->getMessage()."\n";
}
?>

<?php
    $html_title = array('zh-tw'=>'修改使用者', 'en'=>'Modify User');
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
        <?php require '../component/header_admin.php';?>
        <?php require '../component/lang.php';?>

        <div class="row justify-content-center">
            <form id="submit" class="form-submit row flex-column px-5 py-4 py-md-5 col-12 col-md-9 wrapper__border bg-white" method="post">
                <h3 class="text-gray mb-5"><?php echo $html_title[$lang]; ?></h3>
                <div class="row justify-content-between mb-4">
                    <div class="col-12 col-md-7">
                        <div class="d-md-flex input-inline flex-wrap">
                            <select class="input-box form-control w-50-md mb-3" id="uid" name="uid" required>
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
                            <select class="input-box form-control w-50-md mb-3 col-12 col-lg-6 flex-basis-auto" id="role" name="role" required>
                                <option value="" selected disabled hidden><?php echo $role[$lang]; ?></option>
                                <?php
                                    include_once('../classes/db.php');
                                    $DB = new db();
                                    $sql = 'SELECT * FROM Role';
                                    $result = $DB::get_record($sql);
                                    foreach ($result as $r) {
                                        $n = simplexml_load_string($r['name']);
                                        echo '<option value="'.$r['rid'].'">'.$n->attributes()->{$lang}.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="d-md-flex input-inline">
                            <input class="input-box form-control w-50-md mb-3 col-12 col-lg-6 flex-basis-auto" type="text" placeholder="<?php echo $username[$lang]; ?>" id="name" name="username" required>
                            <select class="input-box form-control w-50-md mb-3" id="class" name="class">
                                <option value="" selected disabled hidden><?php echo $class[$lang]; ?></option>
                                <?php
                                    include_once('../classes/db.php');
                                    $DB = new db();
                                    $sql = 'SELECT * FROM Class';
                                    $result = $DB::get_record($sql);
                                    foreach ($result as $r) {
                                        $n = simplexml_load_string($r['name']);
                                        echo '<option value="'.$r['cid'].'">'.$n->attributes()->{$lang}.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 row justify-content-center align-items-center p-0">
                        <div class="role-icon text-white rounded-circle text-center align-self-center">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
                <button class="btn submit-btn btn-primary align-self-center mb-4" type="submit"><?php echo $submit[$lang]; ?></button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="../js/user.js"></script>
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