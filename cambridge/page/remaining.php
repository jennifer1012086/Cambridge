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
    $html_title = array('zh-tw'=>'耗材剩餘', 'en'=>'Remaining Supplies');
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
    </div>
    
    <input type="checkbox" class="hide" id="side-menu-switch" checked>
    <div class="supply">
        <?php require '../component/select.php';?>
        <div class="content-wrapper">
            <label for="side-menu-switch" class="pl-1 mb-0"><i class="fas fa-times check-btn"></i></label>
            <div class="content">
                <div class="table-style">
                    <div class="table-header">
                        <h3 class="text-gray"><?php echo $html_title[$lang]; ?></h3>
                        <a href="../lib/export.php" class="text-blue"><?php echo $export[$lang]; ?></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo $mc[$lang]; ?></th>
                                    <th><?php echo $sc[$lang]; ?></th>
                                    <th><?php echo $iname[$lang]; ?></th>
                                    <th><?php echo $size[$lang]; ?></th>
                                    <th><?php echo $color[$lang]; ?></th>
                                    <th><?php echo $unit[$lang]; ?></th>
                                    <th><?php echo $com[$lang]; ?></th>
                                    <th><?php echo $con[$lang]; ?></th>
                                    <th><?php echo $re[$lang]; ?></th>
                                    <th><?php echo $ret2[$lang]; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once('../classes/db.php');
                                    $DB = new db();
                                    $sql = "SELECT o.oid, c1.catid main, c2.catid sub, c1.name parent, c2.name, o.item, o.size, o.color, o.unit, o.com, i.num, o.ret FROM Category c1, Category c2, Object o, Inventory i, objectAndCat oc WHERE c2.catid = oc.catid AND o.oid = i.oid AND o.oid = oc.oid AND c1.catid = c2.parent";
                                    $result = $DB::get_record($sql);
                                    $semester = (int)getSemesterID();
                                    $sql = "SELECT oid, SUM(num) FROM inAndOut WHERE aid = 1 AND review = 1 AND sid = $semester GROUP BY oid";
                                    $result2 = $DB::get_record($sql);
                                    $used = array();
                                    foreach ($result2 as $r2) {
                                        $used[$r2['oid']] = $r2['SUM(num)'];
                                    }
                                    foreach ($result as $r) {
                                        $p = simplexml_load_string($r['parent']);
                                        $n = simplexml_load_string($r['name']);
                                        $i = simplexml_load_string($r['item']);
                                        $c = '';
                                        if ($r['color']) {
                                            $c = simplexml_load_string($r['color']);
                                            $c = $c->attributes()->{$lang};
                                        }
                                        $tmp = 0.0;
                                        if (isset($used[$r['oid']])) {
                                            $tmp = (float)$used[$r['oid']];
                                        }

                                        $yes = array('zh-tw'=>'是', 'en'=>'Y');
                                        $no = array('zh-tw'=>'否', 'en'=>'N');
                                        if ($r['ret'] == '1')
                                            $ret = $yes[$lang];
                                        else 
                                            $ret = $no[$lang];
                                        
                                        echo "<tr class='td-".$r['sub']."'>
                                                <td data-title='".$mc[$lang]."'>".$p->attributes()->{$lang}."</td>
                                                <td data-title='".$sc[$lang]."'>".$n->attributes()->{$lang}."</td>
                                                <td data-title='".$iname[$lang]."' class='itemname'>".$i->attributes()->{$lang}."</td>
                                                <td data-title='".$size[$lang]."'>".$r['size']."</td>
                                                <td data-title='".$color[$lang]."'>".$c."</td>
                                                <td data-title='".$unit[$lang]."'>".$r['unit']."</td>
                                                <td data-title='".$com[$lang]."'></td>
                                                <td data-title='".$con[$lang]."'>$tmp</td>
                                                <td data-title='".$re[$lang]."'>".$r['num']."</td>
                                                <td data-title='".$ret2[$lang]."'>".$ret."</td>
                                            </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="../js/remaining.js"></script>
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