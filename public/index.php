<?php
    // This file acts as proxy to load the requested form type.
    // I've created it to solve the problem with tunneled nginx redirections which were failing.
    // Probably not the best way, but quickest.
    // Script expects a 'f' URL parameter with the name of the form,
    // then, after ensuring the parameter is valid, calls the form php
    session_start();

    require_once __DIR__ . '/../includes/machinonSerial.php';

    $valid_forms = [
        'main' => 'ADC|machinon ADC Setup',
        'ct' => 'CT|machinon CT Setup',
        'din' => 'DIN|machinon DIN Setup',
        'dout' => 'DOUT|machinon DOUT Setup',
        'internal' => 'GENERAL|machinon Internal Setup',
    ];

    if (empty($_REQUEST['f']) || !key_exists(strtolower($_REQUEST['f']), $valid_forms)) {
        $form = null;
        $link = null;
        $title = null;
    } else {
        $form = strtolower($_REQUEST['f']);
        list($link, $title) = explode('|', $valid_forms[$form]);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-toggle.min.css" />
    <link rel="stylesheet" href="css/main.css" />
</head>
<body>
    <div class="container text-center">
        <div class="row">
            <div class="col-sm-12 text-center">
                <p><img src="images/machinon_logo.png" alt="Machinon"  style="width:175px"/></p>
            </div>
        </div>
        <hr/>
        <?php if (false && !isset($_SESSION['credentials'])) : ?>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <form class="form-group form-signin" method="POST">
                        <div class="alert alert-danger" role="alert" style="display:none">Login error</div>
                        <div class="alert alert-success" role="alert" style="display:none">Login complete, please wait...</div>
                        <h2 class="form-signin-heading">Please Login</h2>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">@</span>
                            </div>
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <label for="inputPassword" class="sr-only">Password</label>
                        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
                    </form>
                </div>
                <div class="col-sm-4"></div>
            </div>
        <?php elseif (!$form) : ?>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <a class="btn btn-lg btn-primary btn-block" href="machinon/">Domoticz</a>
                <a class="btn btn-lg btn-primary btn-block" href="?f=main">Machinon setup</a>
                <a class="btn btn-lg btn-primary btn-block" href="logout.php">Logout</a>
            </div>
            <div class="col-sm-4"></div>
        </div>
        <?php else : ?>
        <div class="row m-auto justify-content-center">
            <div class="col-md-12 top-menu">
            <?php
                echo '<a class="" href="index.php">&lt;&nbsp;BACK</a>';
                foreach ($valid_forms as $f => $menu_item) {
                    list($link) = explode('|', $menu_item);
                    $active = '';
                    if ($form == $f) {
                        $active = 'active-blue';
                    }
                    echo '<a class="'.$active.'" href="?f=' . $f . '">' . $link . '</a>';
                }
            ?>
                <hr/>
            </div>
        </div>
        <div class="row m-auto justify-content-center">
            <div class="form-config col-md-12">
                <?php require_once __DIR__ . '/../forms/' . $form . '.php'; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-toggle.min.js"></script>
    <script>
        $(document).ready(function() {

        });
    </script>
</body>
</html>
