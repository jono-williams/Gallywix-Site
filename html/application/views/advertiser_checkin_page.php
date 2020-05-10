<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gallywix | Advertiser Tracker</title>
    <meta name="description"
        content="Gallywix Boosting Community - Safe & trusted World of Warcraft boosting services for gold">
    <meta name="keywords" content="wow boost, gallywix, gallywix boosting">
    <meta name="author" content="Gallywix">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/glider.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap-grid.min.css" integrity="sha384-jU4CJoUdEG33AmG7VvO7REtpodKn/YT0vjtWmJfLm3rlc89nEpPMleu4wYttxaA+" crossorigin="anonymous">
    <style>
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
    </style>
</head>

<body>
    <div class="non-static">
        <?=include('header.php')?> 
        <section class="about" id="about">
            <div class="container">
                <section class="pricelist">
                    <div class="container">
                        <div class="container-header">
                            <div class="toggleWrapper">
                                <input class="dn" type="checkbox" id="dn" />
                                <label class="toggle" for="dn"><span class="toggle__handler"></span></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <table style="width: 100%;" id="horde-checkin-table">
                                </table>
                            </div>
                            <div class="col-sm">
                                <table style="width: 100%;" id="alliance-checkin-table">
                                </table>
                            </div>
                        </div>

                    </div>
                </section>
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
        let hordeEUCheckins = `
                <table style="width: 100%;" id="checkin-table">
                    <tr class="heading">
                        <th>Horde</th>
                    </tr>
                    <?php $currentRealm = "";
                    foreach ($advertiser_checkin_eu as $key => $checkIn) {  ?>
                        <?php if ("Horde" == $checkIn->faction) { ?>
                            <?php if ($currentRealm != $checkIn->realm) { ?>
                                <?php if ($currentRealm != "") { ?>                                    
                                            </table>
                                        </div>
                                    </td>
                                </tr>   
                                <?php } ?>
                                <tr>
                                    <td class="clickItem">
                                        <div class="row">
                                            <div class="col-5"><?= $checkIn->realm ?></div>
                                            <div class="col-3 <?php 
											if ($checkIn->slot > 2) 
											{ 
												echo "empty-slots"; 
											} 
											elseif ($checkIn->slot < 1)
											{ 
												echo "full-slots"; 
											} ?>"><?= $checkIn->slot ?> Slots</div>
                                            <div class="col-4"><i class="fas fa-arrow-down"></i></div>
                                        </div>                                        
                                        <div class="more-info">
                                            <table style="width: 90%; margin: 10px 20px; " class="simpleTable">
                                                <tr class="simpleRow">
                                                    <th class="smallHeader">Name</th>
                                                </tr>
                                <?php $currentRealm = $checkIn->realm;
                            } ?>

                                                <tr class="simpleRow">
                                                    <td>
                                                        <?= $checkIn->name ?>
                                                    </td>                                           
                                                </tr>   
                        <?php } ?>
                    <?php } ?>  
                    </table>   
      `;

        let allianceEUCheckins = `
                <table style="width: 100%;" id="checkin-table">
                    <tr class="heading">
                        <th>Alliance</th>
                    </tr>
                    <?php $currentRealm = "";
                    foreach ($advertiser_checkin_eu as $key => $checkIn) {  ?>
                        <?php if ("Alliance" == $checkIn->faction) { ?>
                            <?php if ($currentRealm != $checkIn->realm) { ?>
                                <?php if ($currentRealm != "") { ?>                                    
                                            </table>
                                        </div>
                                    </td>
                                </tr>   
                                <?php } ?>
                                <tr>
                                    <td class="clickItem">
                                        <div class="row">
                                            <div class="col-5"><?= $checkIn->realm ?></div>
                                            <div class="col-3 <?php 
											if ($checkIn->slot > 2) 
											{ 
												echo "empty-slots"; 
											} 
											elseif ($checkIn->slot < 1)
											{ 
												echo "full-slots"; 
											} ?>"><?= $checkIn->slot ?> Slots</div>
                                            <div class="col-4"><i class="fas fa-arrow-down"></i></div>
                                        </div>                                        
                                        <div class="more-info">
                                            <table style="width: 90%; margin: 10px 20px; " class="simpleTable">
                                                <tr class="simpleRow">
                                                    <th class="smallHeader">Name</th>
                                                </tr>
                                <?php $currentRealm = $checkIn->realm;
                            } ?>

                                                <tr class="simpleRow">
                                                    <td>
                                                        <?= $checkIn->name ?>
                                                    </td>                                           
                                                </tr>   
                        <?php } ?>
                    <?php } ?>  
                    </table>   
      `;


        let hordeNAheckins = `
                <table style="width: 100%;" id="checkin-table">
                    <tr class="heading">
                        <th>Horde</th>
                    </tr>
                    <?php $currentRealm = "";
                    foreach ($advertiser_checkin_na as $key => $checkIn) {  ?>
                        <?php if ("Horde" == $checkIn->faction) { ?>
                            <?php if ($currentRealm != $checkIn->realm) { ?>
                                <?php if ($currentRealm != "") { ?>                                    
                                            </table>
                                        </div>
                                    </td>
                                </tr>   
                                <?php } ?>
                                <tr>
                                    <td class="clickItem">
                                        <div class="row">
                                            <div class="col-5"><?= $checkIn->realm ?></div>
                                            <div class="col-3 <?php 
											if ($checkIn->slot > 2) 
											{ 
												echo "empty-slots"; 
											} 
											elseif ($checkIn->slot < 1)
											{ 
												echo "full-slots"; 
											} ?>"><?= $checkIn->slot ?> Slots</div>
                                            <div class="col-4"><i class="fas fa-arrow-down"></i></div>
                                        </div>                                        
                                        <div class="more-info">
                                            <table style="width: 90%; margin: 10px 20px; " class="simpleTable">
                                                <tr class="simpleRow">
                                                    <th class="smallHeader">Name</th>
                                                </tr>
                                <?php $currentRealm = $checkIn->realm;
                            } ?>

                                                <tr class="simpleRow">
                                                    <td>
                                                        <?= $checkIn->name ?>
                                                    </td>                                           
                                                </tr>   
                        <?php } ?>
                    <?php } ?>  
                    </table>   
      `;


        let allianceNACheckins = `
                <table style="width: 100%;" id="checkin-table">
                    <tr class="heading">
                        <th>Alliance</th>
                    </tr>
                    <?php $currentRealm = "";
                    foreach ($advertiser_checkin_na as $key => $checkIn) {  ?>
                        <?php if ("Alliance" == $checkIn->faction) { ?>
                            <?php if ($currentRealm != $checkIn->realm) { ?>
                                <?php if ($currentRealm != "") { ?>                                    
                                            </table>
                                        </div>
                                    </td>
                                </tr>   
                                <?php } ?>
                                <tr>
                                    <td class="clickItem">
                                        <div class="row">
                                            <div class="col-5"><?= $checkIn->realm ?></div>
                                            <div class="col-3 <?php 
											if ($checkIn->slot > 2) 
											{ 
												echo "empty-slots"; 
											} 
											elseif ($checkIn->slot < 1)
											{ 
												echo "full-slots"; 
											} ?>"><?= $checkIn->slot ?> Slots</div>
                                            <div class="col-4"><i class="fas fa-arrow-down"></i></div>
                                        </div>                                        
                                        <div class="more-info">
                                            <table style="width: 90%; margin: 10px 20px; " class="simpleTable">
                                                <tr class="simpleRow">
                                                    <th class="smallHeader">Name</th>
                                                </tr>
                                <?php $currentRealm = $checkIn->realm;
                            } ?>

                                                <tr class="simpleRow">
                                                    <td>
                                                        <?= $checkIn->name ?>
                                                    </td>                                           
                                                </tr>   
                        <?php } ?>
                    <?php } ?>  
                    </table>   
      `;


        $("#dn").change(function() {
            $("#service-search").val("");
            if (this.checked) {
                $("#alliance-checkin-table").fadeOut();
                $("#alliance-checkin-table").html(allianceNACheckins);
                $("#alliance-checkin-table").fadeIn();
                $("#horde-checkin-table").fadeOut();
                $("#horde-checkin-table").html(hordeNAheckins);
                $("#horde-checkin-table").fadeIn();
            } else {
                $("#alliance-checkin-table").fadeOut();
                $("#alliance-checkin-table").html(allianceEUCheckins);
                $("#alliance-checkin-table").fadeIn();
                $("#horde-checkin-table").fadeOut();
                $("#horde-checkin-table").html(hordeEUCheckins);
                $("#horde-checkin-table").fadeIn();
            }			
        });
		
		
		$("#alliance-checkin-table").html(allianceEUCheckins);
		$("#horde-checkin-table").html(hordeEUCheckins);
    </script>
</body>

</html>