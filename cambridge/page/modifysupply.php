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
        if(isset($_POST['oid']) && isset($_POST['quantity']) && isset($_POST['olimit'])) {
            $id = $_POST['oid'];
            $quantity = (int)$_POST['quantity'];
            $olimit = (int)$_POST['olimit'];

            include_once('../classes/db.php');
            $DB = new db();
            $sql = "UPDATE Inventory SET num = $quantity WHERE oid = $id";
            $none = $DB::update($sql);
            $sql = "UPDATE OLimit SET num = $olimit WHERE oid = $id";
            $none = $DB::update($sql);

            header('Location: ./modifysupply.php');
        } else {
            echo '<script type="text/javascript"> alert("Failed!") </script>';
            header('refresh: 0.5; url=./modifysupply.php');
        }
    }
} catch (Exception $e) {
        echo 'Caught exception: '.$e->getMessage()."\n";
}
?>

<?php
    $html_title = array('zh-tw'=>'修改耗材', 'en'=>'Modify Supply');
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
            <div class="col px-5 py-4 py-md-5 col-12 col-md-8 col-lg-6 wrapper__border bg-white">
                <h3 class="text-gray mb-5 text-md-center"><?php echo $html_title[$lang]; ?></h3>
                <form method="post">
                    <input id="oid" name="oid" type="hidden" value="">
                    <div class="d-flex justify-content-between flex-wrap">
                        <select id="main" class="col-12 col-md-5 input-box form-control mb-4" required>
                                <option value="" selected disabled hidden><?php echo $mc[$lang]; ?></option>
                                <?php
                                    include('../lib/callfunction.php');
                                    $layer1 = getMain();
                                    foreach ($layer1 as $l1) {
                                        $main = simplexml_load_string($l1['name']);
                                        echo "<option value='".$l1['catid']."'>".$main->attributes()->{$lang}."</option>";
                                    }
                                ?>
                        </select>
                        <select id="sub" class="col-12 col-md-5 input-box form-control mb-4" required>
                                <option value="" selected disabled hidden><?php echo $sc[$lang]; ?></option>
                        </select>
                        <select id="item" class="col-12 col-md-5 input-box form-control mb-4" required>
                            <option value="" selected disabled hidden><?php echo $iname[$lang]; ?></option>
                        </select>
                        <input id="brand" class="col-12 col-md-5 input-box form-control mb-4" placeholder="<?php echo $com[$lang]; ?>" readonly>
                        <input type="hidden" id="size" class="col-12 col-md-5 input-box form-control mb-4" placeholder="<?php echo $size[$lang]; ?>" readonly>
                        <input type="hidden" id="color" class="col-12 col-md-5 input-box form-control mb-4" placeholder="<?php echo $color[$lang]; ?>" readonly>
                    </div>
                    <div class="d-flex justify-content-md-center mb-4">
                        <label class="col-form-label"><?php echo $num[$lang]; ?></label>
                        <input type="number" min="0" name="quantity" id="quantity" class="input-box form-control col-4 col-md-5 mx-2" required>
                        <label class="col-form-label fs-noto unit"><?php echo $unit[$lang]; ?></label>
                    </div>
                    <div class="d-flex justify-content-md-center mb-4">
                        <label class="col-form-label"><?php echo $il[$lang]; ?></label>
                        <input type="number" min="0" name="olimit" id="def_limit" class="input-box form-control col-4 mx-2" required>
                        <label class="col-form-label fs-noto unit"><?php echo $unit[$lang]; ?></label>
                    </div>
                    <div class="pt-5">
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
    <script src="../js/ajaxcall.js"></script>
</body>

</html>