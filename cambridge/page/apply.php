<?php
    session_start();
    include_once('../classes/user.php');
    $user = new user();
    $user::is_login();
    if($user::is_admin()) {
        header('location: ./');
    }
    $lang = $_SESSION['lang'];
?>

<?php
try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['oid']) && isset($_POST['uid']) && isset($_POST['quantity']) && isset($_POST['remain']) && isset($_POST['alimit']) && isset($_POST['event'])) {
            $id = (int)$_POST['oid'];
            $user = (int)$_POST['uid'];
            $quantity = (float)$_POST['quantity'];
            $remain = (float)$_POST['remain'];
            $alimit = (int)$_POST['alimit'];
            $event = $_POST['event'];

            include('../lib/callfunction.php');
            $semester = (int)getSemesterID();
            $action = 1;               
            $get = 0;                  
            $return = 0;               
            $review = 0;               
            if (is_float($quantity) && ($quantity > 0) && ($quantity <= $remain) && ($quantity <= $alimit)) {
                include_once('../classes/db.php');
                $DB = new db();
                $reason = '';
                if (isset($_POST['reason'])) {
                    $reason = $_POST['reason'];
                }
                $sql = "INSERT INTO inAndOut(sid,uid,aid,oid,eid,num,get,ret,review,dt,reason) VALUES($semester,$user,$action,$id,$event,$quantity,$get,$return,$review,curdate(),'$reason')";
                $none = $DB::insert($sql);
                $sql = "UPDATE Inventory SET num = (num-$quantity) WHERE oid = $id";
                $none = $DB::update($sql);
                header('Location: ../page/record.php');
            } else {
                echo '<script type="text/javascript"> alert("Failed!") </script>';
                header('refresh: 0.5; url=./apply.php');
            }
        }
    }
} catch (Exception $e) {
        echo 'Caught exception: '.$e->getMessage()."\n";
}
?>

<?php
    $html_title = array('zh-tw'=>'申請耗材', 'en'=>'Apply Supplies');
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <title><?php echo $html_title[$lang]; ?></title>
</head>

<body>
    <div class="container px-0">
        <?php require '../component/header_user.php';?>
        <?php require '../component/lang.php';?>    

        <div class="row justify-content-center pb-5">
            <div class="col px-5 py-4 py-md-5 col-12 col-md-10 col-lg-8 wrapper__border bg-white">
                <h3 class="text-gray mb-5 text-md-center"><?php echo $html_title[$lang]; ?></h3>
                <form method="post">
                    <input id="oid" name="oid" type="hidden" value="">
                    <input id="uid" type="hidden" name="uid" value="<?php echo $_SESSION['valid_user']; ?>">
                    <div class="form-row mb-4">
                        <div class="col-md-6 mb-4">
                            <select id="main" class="input-box form-control" required>
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
                        </div>
                        <div class="col-md-6 mb-4">
                            <select id="sub" class="input-box form-control" required>
                                <option value="" selected disabled hidden><?php echo $sc[$lang]; ?></option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <select id="item" class="input-box form-control" required>
                                <option value="" selected disabled hidden><?php echo $iname[$lang]; ?></option>
                            </select>
                        </div>
                        <input id="brand" type="hidden" placeholder="<?php echo $com[$lang]; ?>" readonly>
                        <input type="hidden" id="size" class="input-box form-control" placeholder="<?php echo $size[$lang]; ?>" readonly>
                        <input type="hidden" id="color" class="input-box form-control" placeholder="<?php echo $color[$lang]; ?>" readonly>
                        <div class="col-md-6 mb-4">
                            <input id="unit" class="input-box form-control"  placeholder="<?php echo $unit[$lang]; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-row mb-md-4">
                        <div class="col-md-6 mb-4">
                            <h5 class="mb-3"><?php echo $re[$lang]; ?></h5>
                            <input id="remaining" name="remain" class="input-box form-control" readonly>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h5 class="mb-3"><?php echo $acc[$lang]; ?></h5>
                            <input id="accumulation" name="alimit" class="input-box form-control" readonly>
                        </div>
                    </div>
                    <div class="form-row mb-md-4">
                        <div class="col-md-6 mb-4">
                            <h5 class="mb-3"><?php echo $orq[$lang]; ?></h5>
                            <input type="number" min="1" id="quantity" name="quantity" class="input-box form-control" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h5 class="mb-3"><?php echo $ar[$lang]; ?></h5>
                            <select id="reason" name="event" class="input-box form-control" required>
                                <option value="" selected disabled hidden> </option>
                                <?php
                                    include_once('../classes/db.php');
                                    $DB = new db();
                                    $sql = "SELECT eid, name FROM Activity";
                                    $result = $DB::get_record($sql);
                                    foreach ($result as $r) {
                                        $name = simplexml_load_string($r['name']);
                                        echo "<option value='".$r['eid']."'>".$name->attributes()->{$lang}."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4 hide" id="other-reason">
                            <h5 class="mb-3"><?php echo $oar[$lang]; ?></h5>
                            <input type="text" name="reason" maxlength="10" class="input-box form-control" value="">
                        </div>
                    </div>
                    <div class="pt-5">
                        <button id="submit" type="submit" class="btn wrapper_btn w-100 bg-red text-white"><?php echo $submit[$lang]; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script src="../js/apply.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript">
            <?php
                echo "var lang = '$lang';"; 
            ?>
            lang == 'zh-tw' ? $('#change').prop('checked', false) : $('#change').prop('checked', true);
        </script>
        <script src="../js/changelang.js"></script>
        <script src="../js/moreajax.js"></script>
</body>

</html>