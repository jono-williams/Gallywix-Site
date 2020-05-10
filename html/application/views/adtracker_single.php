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

      header h1.logo, header nav ul li a, #about h2 {
        color: orange !important;
      }

      .col-md-3 {
        width: 33.33333%;
        float:left;
        display: list-item;
      }

      .about .container {
        max-width: 80% !important;
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

      header {
        border-bottom: 2px solid orange;
        background: white !important;
      }
    </style>
</head>

<body>
    <div class="non-static">
        <?=include('header.php')?>
        <section class="about" id="about">
            <div class="container" style="margin-bottom: 50px; margin-top: 10px;">
              <!-- <h1 style="color:orange; text-align: center; opacity: 0.5;font-size: 50px;">Viewing Cycle: <?=$cycleStart?> - <?=$cycleEnd?></h1> -->
              <form action="<?=current_url()?>" method="post">
                <div style="display: block; margin: 0 auto; width: fit-content; margin-top: 50px;">
                  <select class="btn" name="cycleView">
                    <?php foreach ($cycles as $key => $cycle): ?>
                      <option <?=($this->input->post("cycleView") == $key ? 'selected' : '')?> value="<?=$key?>"><?=$cycle?></option>
                    <?php endforeach; ?>
                  </select>
                  <input type="submit" class="btn" value="Select Cycle"/>
                </div>
              </form>
            </div>
            <div class="container" style="margin-bottom: 50px; margin-top: 50px; border-top:2px solid orange; border-bottom:2px solid orange; padding: 15px 0px;">
              <h1 style="color:orange; text-align: center; opacity: 0.5;font-size: 50px;">Advertiser Tracker</h1>
            </div>
            <div class="container">
              <table class="table responsive" id="dtBasicExample">
                <thead>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Total Raid (TC) Gold</th>
                    <th>Total Raid (BR) Gold</th>
                    <th>Total Mythic Plus (TC) Gold</th>
                    <th>Total Mythic Plus (BR) Gold</th>
                    <th>Total Levelling (TC) Gold</th>
                    <th>Total Levelling (BR) Gold</th>
                    <th>Total PvP (TC) Gold</th>
                    <th>Total PvP (BR) Gold</th>
                    <th>Total Gold</th>
                    <th>Total Gold (BR)</th>
                    <th>Total Gold (TC)</th>
                    <th>Total Raid %</th>
                    <th>Total Mythic Plus %</th>
                    <th>Total Levelling %</th>
                    <th>Total PvP %</th>
                    <th>Goal Reached</th>
                    <th>Life Time Earnings</th>
                    <th>Life Time Earnings BR</th>
                    <th>Promotion?</th>
                </thead>
                <tbody>
                  <?php foreach($advertisers as $ads) { if(@$ads['name']) { ?>
                    <tr>
                      <td><?=$ads['name']?></td>
                      <td><?=$ads['role']?></td>
                      <td><?=(@$ads['tracked_data']['gtrack']['totals']['tc'] ? number_format($ads['tracked_data']['gtrack']['totals']['tc'],0) : '0')?></td>
                      <td><?=(@$ads['tracked_data']['gtrack']['totals']['br'] ? number_format($ads['tracked_data']['gtrack']['totals']['br'],0) : '0')?></td>
                      <td><?=(@$ads['tracked_data']['mythic_plus']['totals']['tc'] ? number_format($ads['tracked_data']['mythic_plus']['totals']['tc'],0) : '0')?></td>
                      <td><?=(@$ads['tracked_data']['mythic_plus']['totals']['br'] ? number_format($ads['tracked_data']['mythic_plus']['totals']['br'],0) : '0')?></td>
                      <td><?=(@$ads['tracked_data']['levelling']['totals']['tc'] ? number_format($ads['tracked_data']['levelling']['totals']['tc'],0) : '0')?></td>
                      <td><?=(@$ads['tracked_data']['levelling']['totals']['br'] ? number_format($ads['tracked_data']['levelling']['totals']['br'],0) : '0')?></td>
                      <td><?=(@$ads['tracked_data']['pvp']['totals']['tc'] ? number_format($ads['tracked_data']['pvp']['totals']['tc'],0) : '0')?></td>
                      <td><?=(@$ads['tracked_data']['pvp']['totals']['br'] ? number_format($ads['tracked_data']['pvp']['totals']['br'],0) : '0')?></td>
                      <td><?=number_format(@$ads['total'],0)?></td>
                      <td><?=number_format(@$ads['total_br'],0)?></td>
                      <td><?=number_format(@$ads['total_tc'],0)?></td>
                      <td><?=(@$ads['total'] ? number_format(((@$ads['tracked_data']['gtrack']['totals']['tc'] + @$ads['tracked_data']['gtrack']['totals']['br'])/@($ads['total']))*100,2) : "0.00")?>%</td>
                      <td><?=(@$ads['total'] ? number_format(((@$ads['tracked_data']['mythic_plus']['totals']['tc'] + @$ads['tracked_data']['mythic_plus']['totals']['br'])/@($ads['total']))*100,2) : "0.00")?>%</td>
                      <td><?=(@$ads['total'] ? number_format(((@$ads['tracked_data']['levelling']['totals']['tc'] + @$ads['tracked_data']['levelling']['totals']['br'])/@($ads['total']))*100,2) : "0.00")?>%</td>
                      <td><?=(@$ads['total'] ? number_format(((@$ads['tracked_data']['pvp']['totals']['tc'] + @$ads['tracked_data']['pvp']['totals']['br'])/@($ads['total']))*100,2) : "0.00")?>%</td>
                      <td><?=$ads['goal']?></td>
                      <td><?=number_format(@$ads['lifetime_total'], 0)?></td>
                      <td><?=number_format(@$ads['lifetime_total_br'], 0)?></td>
                      <td><?=$ads['promo']?></td>
                    </tr>
                <?php }} ?>
                </tbody>
              </table>
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
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/public/js/glide.min.js"></script>
    <script src="/public/js/jquery.inview.min.js"></script>
    <script src="/public/js/app.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script>
      $(document).ready(function () {
        $('#dtBasicExample').DataTable({
          "responsive": true,
          "scrollX": true,
          "order": [[ 10, "desc" ]]
        });
      });
    </script>
</body>

</html>
