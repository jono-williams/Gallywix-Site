<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$pageTitle?> | Gallywix</title>
    <meta name="description"
        content="Gallywix Boosting Community - Safe & trusted World of Warcraft boosting services for gold">
    <meta name="keywords" content="wow boost, gallywix, gallywix boosting">
    <meta name="author" content="Gallywix">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/glider.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script>var whTooltips = { colorLinks: false, iconizeLinks: false, renameLinks: false };</script>
    <script src="https://wow.zamimg.com/widgets/power.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/public/apple-touch-icon.png">
  	<link rel="icon" type="image/png" sizes="32x32" href="/public/favicon-32x32.png">
  	<link rel="icon" type="image/png" sizes="16x16" href="/public/favicon-16x16.png">
  	<link rel="manifest" href="/public/site.webmanifest">
  	<link rel="mask-icon" href="/public/safari-pinned-tab.svg" color="#f5c564">
  	<meta name="msapplication-TileColor" content="#f5c564">
  	<meta name="theme-color" content="#f5c564">
    <?php foreach($crud['css_files'] as $file): ?>
    	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <style>
      .table {
        width: 100%;
        color: black;
        text-align: center;
      }

      th {
        border-bottom: 2px solid #adadad;
      }

      td {
        border-bottom: 1px solid #e8e8e8;
        border-left: 1px solid #e8e8e8;
        border-right: 1px solid #e8e8e8;
      }

      h1, h2, h3 {
        color: black;
      }

      header h1.logo, header nav ul li a {
        color: orange !important;
      }

      header {
        border-bottom: 2px solid orange;
        background: white !important;
      }

      .btn {
        margin-bottom: 25px;
        outline: none;
        border: none;
        color: white;
        font-weight: 800;
        font-size: 22px;
        border-radius: 5px;
        background-color: orange;
        padding: 15px 50px;
        transition: .3s ease-in-out;
        cursor: pointer;
      }

      select.btn {
        background: transparent;
        color: orange;
        border: 2px solid orange;
        font-size: 20px;
      }

      table {
        color: #000 !important;
      }

      .grocerycrud-container {
        width: 65%;
      }
    </style>
</head>

<body>
    <div class="non-static">
        <?=include('header.php')?>
        <section class="about" id="about">
            <?php echo $crud['output']; ?>
        </section>
    </div>
    <footer>
        <div class="footer-container">
            <div class="footer-block">
                <div class="title">
                    <h1>Gallywix</h1>
                    <p>Boosting Community</p>
                </div>
            </div>
            <div class="footer-block">
                <div class="title">
                    <h2>Explore</h2>
                    <ul>
                        <li><a href="#pricelist">Pricelist</a></li>
                        <li><a href="#whyus">Why Us</a></li>
                        <li><a href="https://discord.gg/gallywix">Discord</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-block">
                <div class="title">
                    <h2>Admins</h2>
                    <ul>
                        <li onclick="purchaseBoost();"><i class="fab fa-discord"></i> Tolls#0001</li>
                        <li><i class="fab fa-discord"></i> Kit#2973</li>
                        <li><i class="fab fa-discord"></i> Bogi#2259</li>
                    </ul>
                </div>
            </div>
            <div class="footer-block">
                <div class="title">
                    <h2>Contact</h2>
                    <ul>
                        <li><a href="https://discord.gg/gallywix" target="tab">Discord</a></li>
                        <li><a href="mailto:admin@gallywix.eu">Mail</a></li>
                        <li><a href="https://twitter.com/Gallywixboost" target="tab">Twitter</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-block">
                <div class="title">
                    <h2>Legal</h2>
                    <ul>
                        <li><a href="/public/terms.txt" target="tab">Terms</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="/public/js/glide.min.js"></script>
    <script src="/public/js/jquery.inview.min.js"></script>
    <script src="/public/js/app.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <?php foreach($crud['js_files'] as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>
</body>

</html>
