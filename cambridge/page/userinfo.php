<?php
    session_start();
    include_once('../classes/user.php');
    $user = new user();
    $user::is_login();
    $lang = $_SESSION['lang'];
?>

<?php
    $html_title = array('zh-tw'=>'個人資料', 'en'=>'Personal Information');
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
            <form id="submit" class="row flex-column px-5 py-4 py-md-5 col-12 col-md-9 col-lg-9 wrapper__border bg-white">
                <h3 class="text-gray mb-5"><?php echo $html_title[$lang]; ?></h3>
                <div class="row justify-content-between mb-4">
                    <div class="col-12 col-md-7">
                        <?php
                            $r = $user::get_userinfo();
                            $id = $r[0];
                            $name = $r[1];
                            $role = $r[2];
                            echo "<input class='input-box form-control w-50-md mb-3' id='name' placeholder='姓名' value='$name' readonly>";
                            echo "<div class='d-md-flex input-inline'>";
                            include_once('../classes/db.php');
                            $DB = new db();
                            $sql = "SELECT u.acnt, c.name FROM Userinfo u, Class c, userAndClass uc WHERE u.uid = uc.uid AND uc.cid = c.cid AND u.uid = $id";
                            $result = $DB::get_record($sql)[0];
                            $account = $result['acnt'];
                            $n = simplexml_load_string($result['name']);
                            echo "<input class='input-box form-control w-50-md mb-3 mr-2' type='text' placeholder='帳號' id='account' value='$account' readonly>";
                            echo "<input class='input-box form-control w-50-md mb-3' id='class' placeholder='班級' value='".$n->attributes()->{$lang}."' readonly>";
                            echo "</div>";
                        ?>
                    </div>
                    <div class="col-md-5 row justify-content-center align-items-center p-0">
                        <?php
                        if(!$user::is_admin()) {
                            echo '<div class="role-icon bg-lgray text-white rounded-circle text-center align-self-center">
                                    <i class="fas fa-user"></i>
                                </div>';
                        } else {
                            echo '<div class="role-icon bg-red text-white rounded-circle text-center align-self-center">
                                    <i class="fas fa-user"></i>
                                </div>';
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
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