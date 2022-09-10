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
    $html_title = array('zh-tw'=>'審核申請', 'en'=>'Review Apply');
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
        <!-- [update]: switch get.php to admin page -->
        <?php require '../component/header_admin.php';?>
        <?php require '../component/lang.php';?>

        <div class="content">
            <div class="table-style">
                <div class="table-header">
                    <h3 class="text-gray"><?php echo $html_title[$lang]; ?></h3>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?php echo $rev[$lang]; ?></th>
                                <th><?php echo $get2[$lang]; ?></th>
                                <th><?php echo $adt[$lang]; ?></th>
                                <th><?php echo $ruser[$lang]; ?></th>
                                <th><?php echo $mc[$lang]; ?></th>
                                <th><?php echo $sc[$lang]; ?></th>
                                <th><?php echo $iname[$lang]; ?></th>
                                <th><?php echo $size[$lang]; ?></th>
                                <th><?php echo $color[$lang]; ?></th>
                                <th><?php echo $unit[$lang]; ?></th>
                                <th><?php echo $aq[$lang]; ?></th>
                                <th><?php echo $cancel[$lang]; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include_once('../classes/db.php');
                                $DB = new db();
                                $apply = 1; 
                                $sql = "SELECT io.ioid, io.review, io.dt, u.name username, c1.catid main, c2.catid sub, c1.name parent, c2.name, o.item, o.size, o.color, o.unit, io.num FROM Category c1, Category c2, Object o, objectAndCat oc, inAndOut io, Userinfo u WHERE c2.catid = oc.catid AND o.oid = oc.oid AND c1.catid = c2.parent AND o.oid = io.oid AND io.aid = $apply AND review != -1 AND io.get = 0 AND io.uid = u.uid";
                                $result = $DB::get_record($sql);
                                $count = 1;
                                foreach ($result as $r) {
                                    $p = simplexml_load_string($r['parent']);
                                    $n = simplexml_load_string($r['name']);
                                    $i = simplexml_load_string($r['item']);
                                    $c = '';
                                    if ($r['color']) {
                                        $c = simplexml_load_string($r['color']);
                                        $c = $c->attributes()->{$lang};
                                    }
                                    $cked = '';
                                    if($r['review']) {
                                        $cked = 'checked';
                                    }
                                    echo "<tr class='td-".$r['sub']."'>
                                            <td data-title='".$rev[$lang]."'><input type='checkbox' class='ck-box' id='rev-".$r['ioid']."' $cked></td>
                                    		<td data-title='".$get2[$lang]."'><input type='checkbox' class='ck-box' id='get-".$r['ioid']."'></td>
                                            <td data-title='".$adt[$lang]."' class='datetime'>".$r['dt']."</td>
                                            <td data-title='".$ruser[$lang]."'>".$r['username']."</td>
                                            <td data-title='".$mc[$lang]."'>".$p->attributes()->{$lang}."</td>
                                            <td data-title='".$sc[$lang]."'>".$n->attributes()->{$lang}."</td>
                                            <td data-title='".$iname[$lang]."' class='itemname'>".$i->attributes()->{$lang}."</td>
                                            <td data-title='".$size[$lang]."'>".$r['size']."</td>
                                            <td data-title='".$color[$lang]."'>".$c."</td>
                                            <td data-title='".$unit[$lang]."'>".$r['unit']."</td>
                                            <td data-title='".$aq[$lang]."'>".$r['num']."</td>
                                            <td data-title='".$cancel[$lang]."'><input type='checkbox' class='ck-box' id='can-".$r['ioid']."'></td>
                                        </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center py-4">
                    <button id="get" class="btn submit-btn btn-primary" type="submit"><?php echo $submit[$lang]; ?></button>
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
    <script src="../js/getandreturn.js"></script>
</body>
</html>