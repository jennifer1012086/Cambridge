<header class="pt-4 mx-2">
    <?php
        if ($lang == 'zh-tw') {
            echo '<a href="index.php" class="d-block mb-3"><img src="../img/logo.png" alt="<?php echo $multistr0[$lang]; ?>" class="header-logo d-block m-auto"></a>';
        } else if ($lang == 'en') {
            echo '<a href="index.php" class="text-black d-block text-center text-hover-none mb-3 fs-en">
                    <h3 class="h6">Kang Chiao International School Preschool (Linkou Campus)</h3>
                    <h1>Supplies Management System</h1>
                </a>';
        }
    ?>
</header>
<nav class="navbar navbar-expand-lg navbar-light bg-white px-5 main-nav sticky-top mb-6">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerNav" aria-controls="navbarTogglerNav" aria-expanded="false" aria-label="Toggle navigation" id="btn-nav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <button class="navbar-toggler user-btn bg-lgray text-white rounded-circle" type="button" data-toggle="collapse" data-target="#navbarTogglerUser" aria-controls="navbarTogglerUser" aria-expanded="false" aria-label="Toggle navigation" id="btn-user">
        <i class="fas fa-user"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerNav">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item mr-5">
                <a class="nav-link" href="apply.php" role="button"><?php echo $multistr16[$lang]; ?></a>
            </li>
            <li class="nav-item mr-5">
                <a class="nav-link" href="record.php" role="button"><?php echo $multistr19[$lang]; ?></a>
            </li>
        </ul>
    </div>
    <div class="collapse navbar-collapse user-list" id="navbarTogglerUser">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="userinfo.php"><?php echo $multistr12[$lang]; ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="modifypassword.php"><?php echo $multistr13[$lang]; ?></a>
            </li>
            <div class="dropdown-divider"></div>
            <li class="nav-item">
                <a class="nav-link" href="../lib/logout.php"><i class="fas fa-sign-out-alt"></i><?php echo $multistr14[$lang]; ?></a>
            </li>
        </ul>
    </div>

    <div class="nav-item dropdown user-list-lg">
        <button class="user-btn bg-lgray text-white rounded-circle user-btn-lg" data-toggle="dropdown"><i class="fas fa-user"></i></button>
        <div class="dropdown-menu">
            <a href="userinfo.php" class="dropdown-item py-2"><?php echo $multistr12[$lang]; ?></a>
            <a href="modifypassword.php" class="dropdown-item py-2"><?php echo $multistr13[$lang]; ?></a>
            <div class="dropdown-divider"></div>
            <a href="../lib/logout.php" class="dropdown-item py-2 border_top"><i class="fas fa-sign-out-alt"></i><?php echo $multistr14[$lang]; ?></a>
        </div>
    </div>
</nav>

<script src="../js/nav.js"></script>