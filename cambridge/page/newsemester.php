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
        if(isset($_POST['sem-ch']) && isset($_POST['sem-ch'])){
            include_once('../classes/db.php');
            $DB = new db();
            $semch = htmlspecialchars($_POST['sem-ch']);
            $semen = htmlspecialchars($_POST['sem-en']);
            $sem_combine = "<div zh-tw=\'$semch\' en=\'$semen\'></div>";
            $sql = "INSERT INTO Semester (name) VALUES ('$sem_combine')";
            $none = $DB::insert($sql);
            echo '<script type="text/javascript"> alert("Success!") </script>';
        } else {
            echo '<script type="text/javascript"> alert("Failed!") </script>';
            header('refresh: 0.5; url=./newsemester.php');
        }
    }
} catch (Exception $e) {
        echo 'Caught exception: '.$e->getMessage()."\n";
}
?>

<?php
    $html_title = array('zh-tw'=>'開始新學期', 'en'=>'New Semester');
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

        <div class="row justify-content-center pb-5">
            <div class="col px-5 py-4 py-md-5 col-12 col-md-9 col-lg-7 wrapper__border bg-white">
                <h3 class="text-gray mb-5 text-md-center"><?php echo $html_title[$lang]; ?></h3>
                <form method="post">
                    <div class="mb-4">
                        <h5 class="mb-3 require"><?php echo $semester[$lang]; ?>：</h5>
                        <div class="form-row">
                            <div class="col-md-6 mb-4">
                                <input type="text" id="sem-ch" placeholder="<?php echo $chinese[$lang]; ?>" class="input-box form-control" name="sem-ch" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <input type="text" id="sem-en" placeholder="<?php echo $english[$lang]; ?>" class="input-box form-control" name="sem-en" required>
                            </div>
                        </div>
                        <small class="text-muted"><?php echo $nse1[$lang]; ?></small>
                        <button type="submit" class="btn wrapper_btn w-100 bg-red text-white"><?php echo $submit[$lang]; ?></button>
                    </div>
                </form>
            </div>
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