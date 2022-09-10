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
        if(isset($_POST['uid']) && isset($_POST['create-condition']) && isset($_POST['item-ch']) && isset($_POST['item-en']) && isset($_POST['unit']) && isset($_POST['quantity']) && isset($_POST['limit']) && isset($_POST['place'])){
            $sub = 0;
            include_once('../classes/db.php');
            $DB = new db();
            if ($_POST['create-condition'] == 1 && isset($_POST['select-main']) && isset($_POST['select-sub'])) {
                $sub = (int)$_POST['select-sub'];
            } else if($_POST['create-condition'] == 2 && isset($_POST['select-main']) && isset($_POST['input-sub-ch']) && isset($_POST['input-sub-en'])) {
                $subch = htmlspecialchars($_POST['input-sub-ch']);
                $suben = htmlspecialchars($_POST['input-sub-en']);
                $sub_combine = "<div zh-tw=\'$subch\' en=\'$suben\'></div>";
                $sql = "INSERT INTO Category(name, parent) VALUES('$sub_combine', ".$_POST['select-main'].")";
                $sub = (int)$DB::insert($sql);
            } else if($_POST['create-condition'] == 3 && isset($_POST['input-main-ch']) && isset($_POST['input-main-en']) && isset($_POST['input-sub-ch']) && isset($_POST['input-sub-en'])) {
                $mainch = htmlspecialchars($_POST['input-main-ch']);
                $mainen = htmlspecialchars($_POST['input-main-en']);
                $main_combine = "<div zh-tw=\'$mainch\' en=\'$mainen\'></div>";
                $sql = "INSERT INTO Category(name, parent) VALUES('$main_combine', 0)";
                $id = $DB::insert($sql);
                $subch = htmlspecialchars($_POST['input-sub-ch']);
                $suben = htmlspecialchars($_POST['input-sub-en']);
                $sub_combine = "<div zh-tw=\'$subch\' en=\'$suben\'></div>";
                $sql = "INSERT INTO Category(name, parent) VALUES('$sub_combine', $id)";
                $sub = (int)$DB::insert($sql);
            } else {
                echo '<script type="text/javascript"> alert("Incomplete Data!") </script>';
                header('refresh: 0.5; url=./createsupply.php');
            }

            if($sub != 0) {
                $itemch = htmlspecialchars($_POST['item-ch']);
                $itemen = htmlspecialchars($_POST['item-en']);
                $item_combine = "<div zh-tw=\'$itemch\' en=\'$itemen\'></div>";
                $color_combine = '';
                if (isset($_POST['color-ch']) || isset($_POST['color-en'])) {
                    isset($_POST['color-ch']) ? $colorch = htmlspecialchars($_POST['color-ch']) : $colorch = '';
                    isset($_POST['color-en']) ? $coloren = htmlspecialchars($_POST['color-en']) : $coloren = '';
                    $color_combine = "<div zh-tw=\'$colorch\' en=\'$coloren\'></div>";
                }
                $unit = htmlspecialchars($_POST['unit']);
                isset($_POST['size']) ? $size = htmlspecialchars($_POST['size']) : $size = '';
                isset($_POST['brand']) ? $com = htmlspecialchars($_POST['brand']) : $com = '';
                $ret = (int)$_POST['return'];
                $sql = "INSERT INTO Object (item,size,color,unit,ret,com) VALUES ('$item_combine','$size','$color_combine','$unit',$ret,'$com')";
                $oid = (int)$DB::insert($sql);
                $sql = "INSERT INTO objectAndCat (oid,catid) VALUES ($oid,$sub)";
                $none = $DB::insert($sql);
                $place = (int)$_POST['place'];
                $sql = "INSERT INTO objectAndPlace (oid,pid) VALUES ($oid,$place)";
                $none = $DB::insert($sql);
                $quantity = (int)$_POST['quantity'];
                $sql = "INSERT INTO Inventory (oid,num) VALUES ($oid,$quantity)";
                $none = $DB::insert($sql);
                $limit = (int)$_POST['limit'];
                $sql = "INSERT INTO OLimit (oid,num) VALUES ($oid,$limit)";
                $none = $DB::insert($sql);
                echo '<script type="text/javascript"> alert("Success!") </script>';
            }
            header('refresh: 0.5; url=./createsupply.php');
        } else {
            echo '<script type="text/javascript"> alert("Failed!") </script>';
            header('refresh: 0.5; url=./createsupply.php');
        }
    }
} catch (Exception $e) {
        echo 'Caught exception: '.$e->getMessage()."\n";
}
?>

<?php
    $html_title = array('zh-tw'=>'新增品項', 'en'=>'Create Item');
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
                    <input type="hidden" name="uid" value="<?php echo $_SESSION['valid_user']; ?>">
                    <div class="form-row mb-4">
                        <h5 class="mb-3 require"><?php echo $cc[$lang]; ?>：</h5>
                        <div class="col-12">
                            <select id="append-condition" class="input-box form-control" name="create-condition" required>
                                <option selected disabled hidden><?php echo $cc[$lang]; ?></option>
                                <option value="1"><?php echo $cc1[$lang]; ?></option>
                                <option value="2"><?php echo $cc2[$lang]; ?></option>
                                <option value="3"><?php echo $cc3[$lang]; ?></option>
                            </select>
                            <small class="text-muted"><?php echo $ce1[$lang]; ?></small>
                        </div>
                    </div>

                    <div class="mb-4 option-1 option-2 option">
                        <h5 class="mb-3 require"><?php echo $mc[$lang].' & '.$sc[$lang]; ?>：</h5>
                        <div class="form-row">
                            <div class="col-md-6 mb-4">
                                <select id="main" class="input-box form-control option-1 option-2 option" name="select-main">
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
                                <select id="sub" class="input-box form-control option-1 option" name="select-sub">
                                    <option value="" selected disabled hidden><?php echo $sc[$lang]; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 option-3 option">
                        <h5 class="mb-3 require"><?php echo $mc[$lang]; ?>：</h5>
                        <div class="form-row">
                            <div class="col-md-6 mb-4">
                                <input type="text" id="main-category-ch" placeholder="<?php echo $chinese[$lang]; ?>" class="input-box form-control" name="input-main-ch">
                            </div>
                            <div class="col-md-6 mb-4">
                                <input type="text" id="main-category-en" placeholder="<?php echo $english[$lang]; ?>" class="input-box form-control" name="input-main-en">
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 option-2 option-3 option">
                        <h5 class="mb-3 require"><?php echo $sc[$lang]; ?>：</h5>
                        <div class="form-row">
                            <div class="col-md-6 mb-4">
                                <input type="text" id="sub-category-ch" placeholder="<?php echo $chinese[$lang]; ?>" class="input-box form-control" name="input-sub-ch">
                            </div>
                            <div class="col-md-6 mb-4">
                                <input type="text" id="sub-category-en" placeholder="<?php echo $english[$lang]; ?>" class="input-box form-control" name="input-sub-en">
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h5 class="mb-3 require"><?php echo $iname[$lang]; ?>：</h5>
                        <div class="form-row">
                            <div class="col-md-6 mb-4">
                                <input type="text" id="item-ch" placeholder="<?php echo $chinese[$lang]; ?>" class="input-box form-control" name="item-ch" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <input type="text" id="item-en" placeholder="<?php echo $english[$lang]; ?>" class="input-box form-control" name="item-en" required>
                            </div>
                        </div>

                        <h5 class="mb-3"><?php echo $color[$lang]; ?>：</h5>
                        <div class="form-row">
                            <div class="col-md-6 mb-4">
                                <input type="text" id="color-ch" placeholder="<?php echo $chinese[$lang]; ?>" class="input-box form-control" name="color-ch">
                            </div>
                            <div class="col-md-6 mb-4">
                                <input type="text" id="color-en" placeholder="<?php echo $english[$lang]; ?>" class="input-box form-control" name="color-en">
                            </div>
                        </div>

                        <h5 class="mb-3 require"><?php echo $ii[$lang]; ?>：</h5>
                        <div class="form-row">
                            <div class="col-md-6 mb-4">
                                <input type="text" id="brand" placeholder="<?php echo $com[$lang]; ?>" class="input-box form-control" name="brand">
                            </div>
                            <div class="col-md-6 mb-4">
                                <input type="text" id="specification" placeholder="<?php echo $size[$lang]; ?>" class="input-box form-control" name="size">
                            </div>
                            <div class="col-md-6 mb-4">
                                <input type="text" id="unit" placeholder="<?php echo $unit[$lang]; ?>" class="input-box form-control" name="unit" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <select id="place" class="input-box form-control" name="place" required>
                                    <option value="" selected disabled hidden><?php echo $pos[$lang]; ?></option>
                                    <?php
                                        include_once('../classes/db.php');
                                        $DB = new db();
                                        $sql = "SELECT pid, name FROM Place";
                                        $result = $DB::get_record($sql);
                                        foreach ($result as $r) {
                                            echo "<option value='".$r['pid']."'>".$r['name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <input type="number" min="1" id="quantity" placeholder="<?php echo $num[$lang]; ?>" class="input-box form-control" name="quantity" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <input type="number" min="1" id="applying_limit" placeholder="<?php echo $il[$lang]; ?>" class="input-box form-control" name="limit" required>
                            </div>
                            <div class="col-12">
                                <select class="input-box form-control" name="return" required>
                                    <?php
                                        $yes = array('zh-tw'=>'是', 'en'=>'Yes');
                                        $no = array('zh-tw'=>'否', 'en'=>'No');
                                    ?>
                                    <option selected disabled hidden><?php echo $intr[$lang]; ?></option>
                                    <option value="1"><?php echo $yes[$lang]; ?></option>
                                    <option value="0"><?php echo $no[$lang]; ?></option>
                                </select>
                            </div>
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
    <script src="../js/createsupply.js"></script>
</body>

</html>