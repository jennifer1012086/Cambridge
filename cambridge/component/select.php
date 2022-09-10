<nav class="sidebar">
    <div class="d-flex justify-content-between align-items-center py-4 border-bottom my-0">
        <h3 class="sidebar-header"><?php echo $smulti0[$lang]; ?></h3>
        <label class="check-all text-center"><input type="checkbox" id="all"><?php echo $smulti1[$lang]; ?></label>
    </div>
    <ul class="list-unstyled components">
        <?php
        include('../lib/callfunction.php');
        $i = 1;
        $layer1 = getMain();
        foreach ($layer1 as $l1) {
            $main = simplexml_load_string($l1['name']);
            echo '<li>
                <div href="#mainMenu'.$i.'" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle checkboxs mb-2">'.$main->attributes()->{$lang}.'</div>
                <ul class="collapse list-unstyled" id="mainMenu'.$i.'">';
            $j = 1;
            $layer2 = getSub($l1['catid']);
            foreach ($layer2 as $l2) {
                $sub = simplexml_load_string($l2['name']);
                echo '<li>
                        <div class="checkboxs">
                            <label><input id="sub-'.$l2['catid'].'" class="sub-check ckbox" type="checkbox" name="sub_'.$i.'-'.$j.'"><i class="fas fa-check check"></i></label>
                            <span href="#subMenu'.$i.'-'.$j.'" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">'.$sub->attributes()->{$lang}.'</span>
                        </div>
                        <ul class="collapse" id="subMenu'.$i.'-'.$j.'">';
                $k = 1;
                $layer3 = getItem($l2['catid']);
                foreach ($layer3 as $l3) {
                    $item = simplexml_load_string($l3['item']);
                    echo '<li>
                            <label>
                                <input id="item_'.$i.'-'.$j.'_'.$k.'" type="checkbox" class="item-check ckbox" name="item">
                                <span id="itemname_'.$i.'-'.$j.'_'.$k.'">'.$item->attributes()->{$lang}.'</span>
                            </label>
                        </li>';
                    $k++;
                }
                echo    '</ul></li>';
                $j++;
            }
            echo '</ul></li>';
            $i++;
        }
        ?>
    </ul>
</nav>