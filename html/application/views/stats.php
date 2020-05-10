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

      .col-md-6 {
        width: 50%;
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
            <div class="container" style="margin-bottom: 50px;">
              <div class="row">
                <div class="col-md-6">
                  <h2>Current Gold Sales Distribution</h2>
                  <canvas id="salesPie"></canvas>
                </div>
                <div class="col-md-6">
                  <h2>Current Count Sales Distribution</h2>
                  <canvas id="countPie"></canvas>
                </div>
              </div>
              <div style="margin-bottom: 50px; margin-top: 50px; border-top:2px solid orange; border-bottom:2px solid orange; padding: 15px 0px; display:inline-table; width:100%;">
                <h1 style="color:orange; text-align: center; opacity: 0.5;font-size: 50px;">Revenue Graphs</h1>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <h2>Mythic Plus Sales</h2>
                  <canvas id="mPlusGraph" height="50"></canvas>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <h2>Raids Sales</h2>
                  <canvas id="raidGraph" height="50"></canvas>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <h2>PvP Sales</h2>
                  <canvas id="pvpGraph" height="50"></canvas>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <h2>Levelling Sales</h2>
                  <canvas id="levellingGraph" height="50"></canvas>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <h2>All Sales</h2>
                  <canvas id="allGraph" height="50"></canvas>
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
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/public/js/glide.min.js"></script>
    <script src="/public/js/jquery.inview.min.js"></script>
    <script src="/public/js/app.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script src="//cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script>
      var ctx = document.getElementById('allGraph').getContext('2d');
      data = {
          datasets: [{
              label: 'Total Everything per cycle (in millions)',
              data: [
                <?php foreach ($data as $key => $value) { ?>
                  <?=floatval($value['total']['all']) / 1000000 ?>,
                <?php } ?>
              ],
              backgroundColor: [
                'rgba(255, 99, 132, 0.75)',
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)',
              ],
              borderWidth: 1
          }, {
              label: 'Count Everything per cycle',
              data: [
                <?php foreach ($data as $key => $value) { ?>
                  <?=$value['count']['all']?>,
                <?php } ?>
              ],
              backgroundColor: [
                'rgba(54, 162, 235, 0.75)',
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)',
              ],
              borderWidth: 1
          }],
          labels: [
            <?php foreach ($data as $key => $value) { ?>
              '<?=str_replace('/', ' -> ', $key)?>',
            <?php } ?>
          ]
      };
      var LineChart5 = new Chart(ctx, {
          type: 'line',
          data: data
      });

      var ctx = document.getElementById('levellingGraph').getContext('2d');
      data = {
          datasets: [{
              label: 'Total Levelling per cycle (in 100 thousands)',
              data: [
                <?php foreach ($data as $key => $value) { ?>
                  <?=floatval($value['total']['levelling']) / 100000 ?>,
                <?php } ?>
              ],
              backgroundColor: [
                'rgba(255, 99, 132, 0.75)',
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)',
              ],
              borderWidth: 1
          }, {
              label: 'Count Levelling per cycle',
              data: [
                <?php foreach ($data as $key => $value) { ?>
                  <?=$value['count']['levelling']?>,
                <?php } ?>
              ],
              backgroundColor: [
                'rgba(54, 162, 235, 0.75)',
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)',
              ],
              borderWidth: 1
          }],
          labels: [
            <?php foreach ($data as $key => $value) { ?>
              '<?=str_replace('/', ' -> ', $key)?>',
            <?php } ?>
          ]
      };
      var LineChart4 = new Chart(ctx, {
          type: 'line',
          data: data
      });

      var ctx = document.getElementById('pvpGraph').getContext('2d');
      data = {
          datasets: [{
              label: 'Total PvP per cycle (in millions)',
              data: [
                <?php foreach ($data as $key => $value) { ?>
                  <?=floatval($value['total']['pvp']) / 1000000 ?>,
                <?php } ?>
              ],
              backgroundColor: [
                'rgba(255, 99, 132, 0.75)',
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)',
              ],
              borderWidth: 1
          }, {
              label: 'Count PvP per cycle',
              data: [
                <?php foreach ($data as $key => $value) { ?>
                  <?=$value['count']['pvp']?>,
                <?php } ?>
              ],
              backgroundColor: [
                'rgba(54, 162, 235, 0.75)',
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)',
              ],
              borderWidth: 1
          }],
          labels: [
            <?php foreach ($data as $key => $value) { ?>
              '<?=str_replace('/', ' -> ', $key)?>',
            <?php } ?>
          ]
      };
      var LineChart3 = new Chart(ctx, {
          type: 'line',
          data: data
      });

      var ctx = document.getElementById('mPlusGraph').getContext('2d');
      data = {
          datasets: [{
              label: 'Total Mythic Plus per cycle (in 100 thousands)',
              data: [
                <?php foreach ($data as $key => $value) { ?>
                  <?=floatval($value['total']['mplus']) / 100000 ?>,
                <?php } ?>
              ],
              backgroundColor: [
                'rgba(255, 99, 132, 0.75)',
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)',
              ],
              borderWidth: 1
          }, {
              label: 'Count Mythic Plus per cycle',
              data: [
                <?php foreach ($data as $key => $value) { ?>
                  <?=$value['count']['mplus']?>,
                <?php } ?>
              ],
              backgroundColor: [
                'rgba(54, 162, 235, 0.75)',
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)',
              ],
              borderWidth: 1
          }],
          labels: [
            <?php foreach ($data as $key => $value) { ?>
              '<?=str_replace('/', ' -> ', $key)?>',
            <?php } ?>
          ]
      };
      var LineChart2 = new Chart(ctx, {
          type: 'line',
          data: data
      });

      var ctx = document.getElementById('raidGraph').getContext('2d');
      data = {
          datasets: [{
              label: 'Total Raids per cycle (in millions)',
              data: [
                <?php foreach ($data as $key => $value) { ?>
                  <?=floatval($value['total']['raids']) / 1000000 ?>,
                <?php } ?>
              ],
              backgroundColor: [
                'rgba(255, 99, 132, 0.75)',
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)',
              ],
              borderWidth: 1
          }, {
              label: 'Count Raids per cycle',
              data: [
                <?php foreach ($data as $key => $value) { ?>
                  <?=$value['count']['raids']?>,
                <?php } ?>
              ],
              backgroundColor: [
                'rgba(54, 162, 235, 0.75)',
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)',
              ],
              borderWidth: 1
          }],
          labels: [
            <?php foreach ($data as $key => $value) { ?>
              '<?=str_replace('/', ' -> ', $key)?>',
            <?php } ?>
          ]
      };
      var LineChart = new Chart(ctx, {
          type: 'line',
          data: data
      });

      var ctx = document.getElementById('salesPie').getContext('2d');
      data = {
          datasets: [{
              label: 'Gold by Category',
              data: [
                <?=array_values($data)[count($data)-1]['total']['raids']?>,
                <?=array_values($data)[count($data)-1]['total']['pvp']?>,
                <?=array_values($data)[count($data)-1]['total']['levelling']?>,
                <?=array_values($data)[count($data)-1]['total']['mplus']?>
              ],
              backgroundColor: [
                'rgba(255, 99, 132, 0.75)',
                'rgba(54, 162, 235, 0.75)',
                'rgba(255, 206, 86, 0.75)',
                'rgba(75, 192, 192, 0.75)'
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
              ],
              borderWidth: 1
          }],
          labels: [
              'Raids Income',
              'PvP',
              'Levelling',
              'Mythic Plus'
          ]
      };
      var myPieChart = new Chart(ctx, {
          type: 'pie',
          data: data
      });

      var ctx = document.getElementById('countPie').getContext('2d');
      data = {
          datasets: [{
              label: 'Count by Category',
              data: [
                <?=array_values($data)[0]['count']['raids']?>,
                <?=array_values($data)[0]['count']['pvp']?>,
                <?=array_values($data)[0]['count']['levelling']?>,
                <?=array_values($data)[0]['count']['mplus']?>
              ],
              backgroundColor: [
                'rgba(255, 99, 132, 0.75)',
                'rgba(54, 162, 235, 0.75)',
                'rgba(255, 206, 86, 0.75)',
                'rgba(75, 192, 192, 0.75)'
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
              ],
              borderWidth: 1
          }],
          labels: [
              'Raids Income',
              'PvP',
              'Levelling',
              'Mythic Plus'
          ]
      };
      var myPieChart2 = new Chart(ctx, {
          type: 'pie',
          data: data
      });
    </script>
</body>

</html>
