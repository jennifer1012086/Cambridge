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
        if(isset($_POST['username']) && isset($_POST['account'])) {
            $uname = htmlspecialchars($_POST['username']);
            $acnt = htmlspecialchars($_POST['account']);
            $rtype = $_POST['role'];
            if (isset($_POST['class'])) {
                $cname = $_POST['class'];
            }
            include('../lib/callfunction.php');
            if (!isAccRept($acnt)) {
                include_once('../classes/db.php');
                $DB = new db();
                $sql = "INSERT INTO Userinfo(name,acnt,pwd) VALUES ('$uname','$acnt','".base64_encode($acnt)."')";
                $id = $DB::insert($sql);
                $sql = "INSERT INTO userAndRole (uid,rid) VALUES ($id,$rtype)";
                $none = $DB::insert($sql);
                if ($rtype == 1) {
                    $sql = "INSERT INTO userAndClass(uid,cid) VALUES ($id,$cname)";
                } else {
                    $sql = "INSERT INTO userAndClass(uid,cid) VALUES ($id,1)";
                }
                $none = $DB::insert($sql);
                $sql = "SELECT * FROM Userinfo WHERE acnt = '$acnt'";
                $r = $DB::count_record($sql);
                if ($r > 0) {
                    echo '<script type="text/javascript"> alert("Success!") </script>';
                } else {
                    echo '<script type="text/javascript"> alert("Failed!") </script>';
                }
                header("refresh: 0.5; url=./createuser.php", true, 301);
            } else {
                echo '<script type="text/javascript"> alert("Failed!") </script>';
                header('refresh: 0.5; url=./createuser.php');
            }
        }
    }
} catch (Exception $e) {
    echo 'Caught exception: '.$e->getMessage()."\n";
}
?>

<?php
    $html_title = array('zh-tw'=>'新增使用者', 'en'=>'Create User');
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
            <form id="submit" class="row flex-column px-5 py-4 py-md-5 col-12 col-md-9 wrapper__border bg-white" method="post">
                <h3 class="text-gray mb-5"><?php echo $html_title[$lang]; ?></h3>
                <div class="row justify-content-between mb-4">
                    <div class="col-12 col-md-7">
                        <div class="d-flex input-inline flex-wrap-reverse flex-lg-nowrap">
                            <input class="input-box form-control w-50-md mb-3 col-12 col-lg-6 flex-basis-auto" type="text" placeholder="<?php echo $username[$lang]; ?>" id="name" name="username" required>
                            <!-- modified -->
                            <select class="input-box form-control w-50-md mb-3" id="role" name="role" required>
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
                            <!-- modified end -->
                        </div>
                        <div class="d-md-flex input-inline">
                            <input class="input-box form-control w-50-md mb-3 mr-2" type="text" placeholder="<?php echo $account[$lang]; ?>" id="account" name="account" required>
                            <select class="input-box form-control w-50-md mb-3" name="class" id="class">
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
    <script src="../js/createuser.js"></script>
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