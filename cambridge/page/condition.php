<?php
    session_start();
    include_once('../classes/user.php');
    $user = new user();
    $user::is_login();
    if(!$user::is_admin()) {
        header('location: ../');
    }
    $lang = $_SESSION['lang'];
?>

<?php
    $html_title = array('zh-tw'=>'申請檢視', 'en'=>'Applying Conditions');
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
                    <div class="d-flex col-md-7 col-lg-5">
                        <select id="condition" class="input-box form-control mr-3" required>
                            <option value="" selected disabled hidden><?php echo $vc[$lang]; ?></option>
                            <option value="1"><?php echo $vc1[$lang]; ?></option>
                            <option value="2"><?php echo $vc2[$lang]; ?></option>
                            <option value="3"><?php echo $vc3[$lang]; ?></option>
                        </select>
                        <select id="condition-content" class="input-box form-control" required>
                            <option value="" selected disabled hidden><?php echo $vd[$lang]; ?></option>
                            <?php
                                include_once('../classes/db.php');
                                $DB = new db();
                                $sql = 'SELECT * FROM Userinfo u, userAndRole ur WHERE u.uid = ur.uid AND ur.rid = 1';
                                $result = $DB::get_record($sql);
                                foreach ($result as $r) {
                                    echo '<option class="teacher vd-option" value="'.$r['uid'].'">'.$r['name'].'</option>';
                                }
                                $sql = 'SELECT * FROM Class';
                                $result = $DB::get_record($sql);
                                foreach ($result as $r) {
                                    $n = simplexml_load_string($r['name']);
                                    echo '<option class="class vd-option" value="'.$r['cid'].'">'.$n->attributes()->{$lang}.'</option>';
                                }
                                $sql = "SELECT eid, name FROM Activity";
                                $result = $DB::get_record($sql);
                                foreach ($result as $r) {
                                    $n = simplexml_load_string($r['name']);
                                    echo '<option class="activity vd-option" value="'.$r['eid'].'">'.$n->attributes()->{$lang}.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table teacher">
                        <thead>
                            <tr>
                                <th><?php echo $mc[$lang]; ?></th>
                                <th><?php echo $sc[$lang]; ?></th>
                                <th><?php echo $iname[$lang]; ?></th>
                                <th><?php echo $aq[$lang]; ?></th>
                                <th><?php echo $multistr15[$lang]; ?></th>
                            </tr>
                        </thead>
                        <tbody id="teacher-body" class="teacher"></tbody>
                    </table>
                    <table class="table class activity">
                        <thead>
                            <tr>
                                <th><?php echo $mc[$lang]; ?></th>
                                <th><?php echo $sc[$lang]; ?></th>
                                <th><?php echo $iname[$lang]; ?></th>
                                <th><?php echo $aq[$lang]; ?></th>
                            </tr>
                        </thead>
                        <tbody id="ac-body" class="class"></tbody>
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
        <?php
            echo "var mc = '". $mc[$lang]."';";
            echo "var sc = '". $sc[$lang]."';";
            echo "var iname = '". $iname[$lang]."';";
            echo "var aq = '". $aq[$lang]."';";
            echo "var remain = '". $multistr15[$lang]."';";
        ?>
    </script>
    <script src="../js/changelang.js"></script>
    <script src="../js/condition.js"></script>
</body>
</html>