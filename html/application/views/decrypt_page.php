<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gallywix | Advertiser Tracker</title>
    <meta name="description" content="Gallywix Boosting Community - Safe & trusted World of Warcraft boosting services for gold">
    <meta name="keywords" content="wow boost, gallywix, gallywix boosting">
    <meta name="author" content="Gallywix">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/glider.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script>
        var whTooltips = {
            colorLinks: false,
            iconizeLinks: false,
            renameLinks: false
        };
    </script>
    <script src="https://wow.zamimg.com/widgets/power.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/public/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/public/favicon-16x16.png">
    <link rel="manifest" href="/public/site.webmanifest">
    <link rel="mask-icon" href="/public/safari-pinned-tab.svg" color="#f5c564">
    <meta name="msapplication-TileColor" content="#f5c564">
    <meta name="theme-color" content="#f5c564">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap-grid.min.css" integrity="sha384-jU4CJoUdEG33AmG7VvO7REtpodKn/YT0vjtWmJfLm3rlc89nEpPMleu4wYttxaA+" crossorigin="anonymous">
    <style>
        header .container {
          width: 75%;
          max-width: 100% !important;
        }
        .table {
            width: 100%;
            color: black;
            text-align: center;
        }

        h1,
        h2,
        h3 {
            color: black;
        }

        header h1.logo,
        header nav ul li a {
            color: orange !important;
        }

        header {
            border-bottom: 2px solid orange;
            background: white !important;
        }

        .smallHeader {
            padding: 20px 7px !important;
            font-size: 18px !important;
        }

        .simpleRow {
            background-color: transparent !important;

        }

        .container {
            color: black;
        }

        .simpleTable {
            border-collapse: collapse;
            border-style: hidden;
        }

        .empty-slots {
            color: green;
        }

        .medium-slots {
            color: yellow;
        }

        .full-slots {
            color: red;
        }

        .simpleTable td {
            border: 1px solid orange;
        }

        textarea {
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="non-static">
        <?= include('header.php') ?>
        <section class="about" id="about">
            <div class="container">
                <div class="container">
                    <div class="row">
                        <div class="col-sm">
                            <form method="post" action="/Decrypt/submit/">
                                <label>Encryption Key:</label><br>
                                <textarea name="keys" rows="20" cols="40"></textarea>
                                <input type="submit">
                            </form>
                        </div>
                        <div class="col">
                            <label>Name-Server: </label><br>
                            <textarea name="name-server" rows="20" cols="35" readonly></textarea>
                        </div>
                        <div class="col">
                            <label>Battletag: </label><br>
                            <textarea name="battletag" rows="20" cols="35" readonly></textarea>
                        </div>
                        <div class="col">
                            <label>Receipt</label><br>
                            <textarea name="receipt" rows="20" cols="35" readonly></textarea>
                        </div>
                    </div>
                </div>
            </div>
    </div>
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
    <script>
        $('form').submit(function(e) {
            e.preventDefault();
            var keysInput = $("textarea[name='keys']").val();

            $.ajax({
                url: 'Decrypt/getBuyersPost',
                type: 'POST',
                data: {
                    keys: keysInput
                },
                error: function() {
                    alert('Error! Could not connect to server.');
                },
                success: function(data) {
                    var responses = JSON.parse(data);
                    var btags = "", nameServers = "", receipts = "";

                    responses.forEach(function(response) {
                        btags = btags + response.bnet_disc_comment + "\n";
                        nameServers = nameServers + response.name_server + "\n";
                        receipts = receipts + response.receipt + "\n";
                    })

                    $("textarea[name='name-server']").text(nameServers);
                    $("textarea[name='battletag']").text(btags);
                    $("textarea[name='receipt']").text(receipts);
                }
            });
        });
    </script>
</body>

</html>
