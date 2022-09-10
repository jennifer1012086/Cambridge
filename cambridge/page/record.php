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
    $html_title = array('zh-tw'=>'歷史紀錄', 'en'=>'Applying Record');
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
        <?php require '../component/header_user.php';?>
        <?php require '../component/lang.php';?>

        <div class="content">
            <div class="table-style">
                <div class="table-header">
                    <h3 class="text-gray"><span class="h2"><?php echo $html_title[$lang]; ?></h3>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?php echo $adt[$lang]; ?></th>
                                <th><?php echo $rstate[$lang]; ?></th>
                                <th><?php echo $mc[$lang]; ?></th>
                                <th><?php echo $sc[$lang]; ?></th>
                                <th><?php echo $iname[$lang]; ?></th>
                                <th><?php echo $size[$lang]; ?></th>
                                <th><?php echo $color[$lang]; ?></th>
                                <th><?php echo $unit[$lang]; ?></th>
                                <th><?php echo $aq[$lang]; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include_once('../classes/db.php');
                                $DB = new db();
                                $apply = 1; 
                                $user = $_SESSION['valid_user'];
                                $sql = "SELECT io.dt, io.review, c1.catid main, c2.catid sub, c1.name parent, c2.name, o.item, o.size, o.color, o.unit, o.com, io.num FROM Category c1, Category c2, Object o, objectAndCat oc, inAndOut io WHERE c2.catid = oc.catid AND o.oid = oc.oid AND c1.catid = c2.parent AND o.oid = io.oid AND io.aid = $apply AND io.uid = $user ORDER BY io.dt DESC";
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
                                    if($r['review'] == 0) {
                                        $state = $wrev[$lang];
                                    } else if($r['review'] == 1) {
                                        $state = $rev[$lang];
                                    } else if($r['review'] == -1) {
                                        $state = $cancel[$lang];
                                    }
                                    
                                    echo "<tr class='td-".$r['sub']." page-".floor($count/20+1)." tr-content'>
                                            <td data-title='".$adt[$lang]."' class='datetime'>".$r['dt']."</td>
                                            <td data-title='".$rstate[$lang]."'>$state</td>
                                            <td data-title='".$mc[$lang]."'>".$p->attributes()->{$lang}."</td>
                                            <td data-title='".$sc[$lang]."'>".$n->attributes()->{$lang}."</td>
                                            <td data-title='".$iname[$lang]."' class='itemname'>".$i->attributes()->{$lang}."</td>
                                            <td data-title='".$size[$lang]."'>".$r['size']."</td>
                                            <td data-title='".$color[$lang]."'>".$c."</td>
                                            <td data-title='".$unit[$lang]."'>".$r['unit']."</td>
                                            <td data-title='".$aq[$lang]."'>".$r['num']."</td>
                                        </tr>";
                                    $count++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <ul class="pagination pt-3">
                <?php 
                    for ($i=1; $i <= floor($count/20+1); $i++) { 
                        echo "<li class='page-item'><a class='page-link' href='#page-$i'>$i</a></li>";
                    }
                ?>
            </ul>
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
    <script type="text/javascript">
        $('.page-link').on('click', function() {
            var display = $(this).attr('href');
            $('.tr-content').css('display', 'none');
            $('.'+display.split('#')[1]).css('display', 'table-row');
        });
    </script>
</body>
</html>