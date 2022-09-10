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
        if(isset($_POST['oid']) && isset($_POST['uid']) && isset($_POST['quantity'])) {
            $id = (int)$_POST['oid'];
            $user = (int)$_POST['uid'];
            $quantity = (int)$_POST['quantity'];

            include('../lib/callfunction.php');
            $semester = (int)getSemesterID();
            $action = 3;           
            $event = 19;           
            $get = 0;              
            $return = 0;           
            $review = 0;           
            if (is_int($quantity) && ($quantity > 0)) {
                include_once('../classes/db.php');
                $DB = new db();
                $sql = "INSERT INTO inAndOut(sid,uid,aid,oid,eid,num,get,ret,review,dt,reason) VALUES($semester,$user,$action,$id,$event,$quantity,$get,$return,$review,curdate(),'')";
                $none = $DB::insert($sql);
                $sql = "UPDATE Inventory SET num = (num+$quantity) WHERE oid = $id";
                $none = $DB::update($sql);
                header('Location: ./supply.php');
            } else {
                echo '<script type="text/javascript"> alert("Please enter a number which is larger than 0!") </script>';
            }
        } else {
            echo '<script type="text/javascript"> alert("Failed!") </script>';
            header('refresh: 0.5; url=./supply.php');
        }
    }
} catch (Exception $e) {
        echo 'Caught exception: '.$e->getMessage()."\n";
}
?>

<?php
    $html_title = array('zh-tw'=>'進貨', 'en'=>'Increase Supply');
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
                    <input type="hidden" name="uid" value="<?php echo $_SESSION['valid_user']; ?>">
                    <div class="d-flex justify-content-between flex-wrap">
                        <select name="main" id="main" class="col-12 col-md-5 input-box form-control mb-4" required>
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
                        <select name="sub" id="sub" class="col-12 col-md-5 input-box form-control mb-4" required>
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
                        <input type="number" min="1" name="quantity" id="number" placeholder="<?php echo $an[$lang]; ?>" class="input-box form-control col-10 col-md-5 mr-2" required>
                        <label class="col-form-label fs-noto unit"><?php echo $unit[$lang]; ?></label>
                    </div>
                    <p class="text-center my-5">
                        <a href="createsupply.php" class="text-blue"><?php echo $qis[$lang]; ?></a>
                    </p>
                    <button type="submit" class="btn wrapper_btn w-100 bg-red text-white"><?php echo $submit[$lang]; ?></button>
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