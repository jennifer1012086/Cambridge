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
    $html_title = array('zh-tw'=>'借用明細', 'en'=>'Applying Details');
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="../css/datepicker.css">
    <title><?php echo $html_title[$lang]; ?></title>
</head>
<body>
    <div class="container px-0">
        <?php require '../component/header_admin.php';?>
        <?php require '../component/lang.php';?>
    </div>
    
    <input type="checkbox" class="hide" id="side-menu-switch" checked>
    <div class="supply">
        <?php require '../component/select_day.php';?>
        <div class="content-wrapper">
            <label for="side-menu-switch" class="pl-1 mb-0"><i class="fas fa-times check-btn"></i></label>
            <div class="content">
                <div class="table-style">
                    <div class="table-header">
                        <h3 class="text-gray"><?php echo $html_title[$lang]; ?></h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo $adt[$lang]; ?></th>
                                    <th><?php echo $class[$lang]; ?></th>
                                    <th><?php echo $ruser[$lang]; ?></th>
                                    <th><?php echo $mc[$lang]; ?></th>
                                    <th><?php echo $sc[$lang]; ?></th>
                                    <th><?php echo $iname[$lang]; ?></th>
                                    <th><?php echo $size[$lang]; ?></th>
                                    <th><?php echo $color[$lang]; ?></th>
                                    <th><?php echo $unit[$lang]; ?></th>
                                    <th><?php echo $aq[$lang]; ?></th>
                                    <th><?php echo $ar[$lang]; ?></th>
                                    <th><?php echo $get1[$lang]; ?></th>
                                    <th><?php echo $ret1[$lang]; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once('../classes/db.php');
                                    $DB = new db();
                                    $apply = 1;        
                                    $review = 1;       
                                    $other_event = 19; 
                                    $sem = (int)getSemesterID();
                                    $user = $_SESSION['valid_user'];
                                    $sql = "SELECT io.dt, c.name class, u.name username, c2.catid sub, c1.name parent, c2.name name, o.item, o.size, o.color, o.unit, io.num, io.eid, a.name event, io.reason, io.get, io.ret FROM Category c1, Category c2, Object o, objectAndCat oc, inAndOut io, Userinfo u, userAndClass uc, Class c, Activity a WHERE c1.catid = c2.parent AND o.oid = io.oid AND io.uid = u.uid AND u.uid = uc.uid AND uc.cid = c.cid AND c2.catid = oc.catid AND o.oid = oc.oid AND a.eid = io.eid AND io.aid = $apply AND io.review = $review AND io.sid = $sem";
                                    $result = $DB::get_record($sql);
                                    foreach ($result as $r) {
                                        $cname = simplexml_load_string($r['class']);
                                        $p = simplexml_load_string($r['parent']);
                                        $n = simplexml_load_string($r['name']);
                                        $i = simplexml_load_string($r['item']);
                                        $e = simplexml_load_string($r['event']);
                                        $c = '';
                                        if ($r['color']) {
                                            $c = simplexml_load_string($r['color']);
                                            $c = $c->attributes()->{$lang};
                                        }
                                        if ($r['eid'] != $other_event) {
                                            $reason = $e->attributes()->{$lang};
                                        } else {
                                            $reason = $r['reason'];
                                        }
                                        $yes = array('zh-tw'=>'是', 'en'=>'Y');
                                        $no = array('zh-tw'=>'否', 'en'=>'N');
                                        if ($r['get'] == '1')
                                            $get = $yes[$lang];
                                        else 
                                            $get = $no[$lang];

                                        if ($r['ret'] == '1')
                                            $ret = $yes[$lang];
                                        else 
                                            $ret = $no[$lang];
                                        echo "<tr class='td-".$r['sub']."'>
                                                <td data-title='".$adt[$lang]."' class='datetime'>".$r['dt']."</td>
                                                <td data-title='".$class[$lang]."'>".$cname->attributes()->{$lang}."</td>
                                                <td data-title='".$ruser[$lang]."'>".$r['username']."</td>
                                                <td data-title='".$mc[$lang]."'>".$p->attributes()->{$lang}."</td>
                                                <td data-title='".$sc[$lang]."'>".$n->attributes()->{$lang}."</td>
                                                <td data-title='".$iname[$lang]."' class='itemname'>".$i->attributes()->{$lang}."</td>
                                                <td data-title='".$size[$lang]."'>".$r['size']."</td>
                                                <td data-title='".$color[$lang]."'>".$c."</td>
                                                <td data-title='".$unit[$lang]."'>".$r['unit']."</td>
                                                <td data-title='".$aq[$lang]."'>".$r['num']."</td>
                                                <td data-title='".$ar[$lang]."'>".$reason."</td>
                                                <td data-title='".$get1[$lang]."'>".$get."</td>
                                                <td data-title='".$ret1[$lang]."'>".$ret."</td>
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
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        <?php
            echo "var lang = '$lang';"; 
        ?>
        lang == 'zh-tw' ? $('#change').prop('checked', false) : $('#change').prop('checked', true);
    </script>
    <script src="../js/changelang.js"></script>
    <script type="text/javascript">
            $(function(){
                if(lang == 'zh-tw') {
                    $.datepicker.regional['zh-TW'] = {
                        clearText: '清除', clearStatus: '清除已選日期',
                        closeText: '關閉', closeStatus: '取消選擇',
                        prevText: '<上一月', prevStatus: '顯示上個月',
                        nextText: '下一月>', nextStatus: '顯示下個月',
                        currentText: '今天', currentStatus: '顯示本月',
                        monthNames: ['1月','2月','3月','4月','5月','6月',
                        '7月','8月','9月','10月','11月','12月'],
                        monthNamesShort: ['一','二','三','四','五','六',
                        '七','八','九','十','十一','十二'],
                        monthStatus: '選擇月份', yearStatus: '選擇年份',
                        weekHeader: '周', weekStatus: '',
                        dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
                        dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
                        dayNamesMin: ['日','一','二','三','四','五','六'],
                        dayStatus: '設定每周第一天', dateStatus: '選擇 m月 d日, DD',
                        dateFormat: 'yy/mm/dd', firstDay: 7, 
                        initStatus: '請選擇日期', isRTL: false
                    };
                }
                $("#from").datepicker();
                $("#to").datepicker();
                if(lang == 'zh-tw')
                    $.datepicker.setDefaults($.datepicker.regional['zh-TW']);
            });

            $('#from').on("change", function() {
                var from = $(this).val();
                var to = $('#to').val();
                displayDates(from, to);
            });

            $('#to').on("change", function() {
                var from = $('#from').val();
                var to = $(this).val();
                displayDates(from, to);
            });

            function displayDates(from, to) {
                if(from != '' && to != '') {
                    from = new Date(from);
                    to = new Date(to);
                    if (from <= to) {
                        var tmp;
                        var arr = $('.datetime');
                        for (var i = arr.length - 1; i >= 0; i--) {
                            tmp = new Date(arr[i].innerHTML);
                            if((from<=tmp)&&(tmp<=to)) 
                                arr[i].parentNode.style.display = 'table-row';
                            else
                                arr[i].parentNode.style.display = 'none';
                        }
                    }
                }
            }
    </script>
    <script src="../js/remaining.js"></script>
</body>
</html>
