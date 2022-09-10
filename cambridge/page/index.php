<?php
    session_start();
    include_once('../classes/user.php');
    $user = new user();
    $user::is_login();
    $lang = $_SESSION['lang'];
?>

<?php
    $html_title = array('zh-tw'=>'首頁', 'en'=>'Home');
    require '../component/multilang.php';
?>

<!DOCTYPE HTML>
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
            if($user::is_admin()) {
                require '../component/header_admin.php';
                echo '<div class="row row-cols-1 row-cols-md-3 text-center mx-2">
                        <div class="col mb-4">
                            <div class="card br-radius h-100">
                                <div class="card-body py-5">
                                    <h4 class="card-title border-bottom pb-3 mb-3 border-dark">'.$multistr1[$lang].'</h4>
                                    <ul class="pl-0">
                                        <li class="mb-3"><a href="remaining.php" class="text-black">'.$multistr2[$lang].'</a></li>
                                        <li class="mb-3"><a href="condition.php" class="text-black">'.$multistr3[$lang].'</a></li>
                                        <li class="mb-3"><a href="applying.php" class="text-black">'.$multistr4[$lang].'</a></li>
                                        <li><a href="purchase.php" class="text-black">'.$multistr5[$lang].'</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <div class="card br-radius bg-red h-100">
                                <div class="card-body py-5">
                                    <h4 class="card-title text-white border-bottom pb-3 mb-3 border-white">'.$multistr6[$lang].'</h4>
                                    <ul class="pl-0">
                                        <li class="mb-3"><a href="supply.php" class="text-whitee">'.$multistr7[$lang].'</a></li>
                                        <li class="mb-3"><a href="modifysupply.php" class="text-whitee">'.$multistr8[$lang].'</a></li>
                                        <li class="mb-3"><a href="get.php" class="text-whitee">'.$multistr17[$lang].'</a></li>
                                        <li class="mb-3"><a href="return.php" class="text-whitee">'.$multistr18[$lang].'</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <div class="card br-radius h-100">
                                <div class="card-body py-5">
                                    <h4 class="card-title border-bottom pb-3 mb-3 border-dark">'.$multistr9[$lang].'</h4>
                                    <ul class="pl-0">
                                        <li class="mb-3"><a href="createuser.php" class="text-black">'.$multistr10[$lang].'</a></li>
                                        <li class="mb-3"><a href="modifyuser.php" class="text-black">'.$multistr11[$lang].'</a></li>
                                        <li class="mb-3"><a href="limit.php" class="text-black">'.$multistr15[$lang].'</a></li>
                                        <li class="mb-3"><a href="reset.php" class="text-black">'.$multistr20[$lang].'</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>';
            } else {
                header('Location: ./record.php');
            }
        ?>
        <?php require '../component/lang.php';?>
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