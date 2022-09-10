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
    $html_title = array('zh-tw'=>'借用上限', 'en'=>'Applying Limit');
    require '../component/multilang.php';
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <title><?php echo $html_title[$lang]; ?></title>
</head>

<body>
    <div class="container px-0">
        <?php require '../component/header_admin.php';?>
        <?php require '../component/lang.php';?>
        <div class="content">
            <div class="table-style">
                <div class="table-header flex-column flex-md-row">
                    <h3 class="text-gray"><?php echo $html_title[$lang]; ?></h3>
                    <div class="d-flex col-md-8 col-lg-6">
                        <select id="user" class="input-box form-control mr-3" required>
                            <option value="0" selected disabled hidden><?php echo $ruser[$lang]; ?></option>
                            <?php
                                include_once('../classes/db.php');
                                $DB = new db();
                                $admin = 4;
                                $sql = "SELECT u.uid, u.name FROM Userinfo u, userAndRole ur WHERE u.uid = ur.uid AND ur.rid != $admin";
                                $result = $DB::get_record($sql);
                                foreach ($result as $r) {
                                    echo '<option value="'.$r['uid'].'">'.$r['name'].'</option>';
                                }
                            ?>
                        </select>
                        <select id="main" class="input-box form-control mr-3" required>
                            <option value="0" selected disabled hidden><?php echo $mc[$lang]; ?></option>
                            <?php
                                include('../lib/callfunction.php');
                                $layer1 = getMain();
                                foreach ($layer1 as $l1) {
                                    $main = simplexml_load_string($l1['name']);
                                    echo "<option value='".$l1['catid']."'>".$main->attributes()->{$lang}."</option>";
                                }
                            ?>
                        </select>
                        <select id="sub" class="input-box form-control" required>
                            <option value="0" selected disabled hidden><?php echo $sc[$lang]; ?></option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="limit-table">
                        <thead>
                            <tr>
                                <th><?php echo $iname[$lang]; ?></th>
                                <th><?php echo $html_title[$lang]; ?></th>
                                <th><?php echo $edit[$lang]; ?></th>
                            </tr>
                        </thead>
                        <tbody id="item"></tbody>
                    </table>
                </div>
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
    <script src="../js/limit.js"></script>
</body>

</html>