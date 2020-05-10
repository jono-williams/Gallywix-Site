<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gallywix | Balance Check</title>
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
    </style>
</head>

<body>
    <div class="non-static">
        <?=include('header.php')?>
        <section class="about" id="about">
            <h1 data-aos="fade-down" data-aos-delay="300" style="color:orange; text-align: center; opacity: 0.5;font-size: 50px;margin-top:100px;"><?=$balance_name?></h1>
            <h2 data-aos="fade-down" data-aos-delay="300"><?php if(@$balance->balance) { ?>Current Balance: <?=$balance->balance?><span style="color:gold;">g</span><?php } else { ?>Current Balance: 0<span style="color:gold;">g</span><?php } ?></h2>
            <h3 data-aos="fade-down" data-aos-delay="300" style="margin-bottom: 50px;">Scroll down for more infomation!</h3>

            <form action="<?=current_url()?>" method="post">
              <div style="display: block; margin: 0 auto; width: fit-content; margin-top: 50px;">
                <select class="btn" name="cycleView">
                  <?php foreach ($cycles as $key => $cycle) : ?>
                    <option <?=($this->input->post("cycleView") == $key ? 'selected' : '')?> value="<?=$key?>"><?=$cycle?></option>
                  <?php endforeach; ?>
                </select>
                <input type="submit" class="btn" value="Select Cycle"/>
              </div>
            </form>

            <div class="container">
                <div class="content-area" id="contentStuff">
                  <h1>Mythic+</h1>
                  <table class='table'>
              			<thead>
              				<th>Date</th>
              				<th>Pot</th>
              				<th>Ad cut</th>
              				<th>Faction</th>
              				<th>Booster #1</th>
              				<th>Booster #2</th>
              				<th>Booster #3</th>
              				<th>Booster #4</th>
              				<th>Advertiser</th>
              				<th>Realm</th>
              			</thead>
              			<tbody>
              				<?php foreach($mplus as $mp) { ?>
              					<tr>
              						<td><?=$mp['date']?></td>
              						<td><?=$mp['pot']?></td>
              						<td><?=$mp['ad_cut']?></td>
              						<td><?=$mp['faction']?></td>
              						<td><?=$mp['booster_1']?><br/><?=($mp['booster_cut'] ? $mp['booster_cut'] : intval($mp['pot']) * 0.1875)?>g</td>
              						<td><?=$mp['booster_2']?><br/><?=($mp['booster_cut'] ? $mp['booster_cut'] : intval($mp['pot']) * 0.1875)?>g</td>
              						<td><?=$mp['booster_3']?><br/><?=($mp['booster_cut'] ? $mp['booster_cut'] : intval($mp['pot']) * 0.1875)?>g</td>
              						<td><?=$mp['booster_4']?><br/><?=($mp['booster_cut'] ? $mp['booster_cut'] : intval($mp['pot']) * 0.1875)?>g</td>
              						<td><?=$mp['advertiser']?><br/><?=$mp['ad_cut']?>g</td>
              						<td><?=$mp['realm']?></td>
              					</tr>
                    <?php } ?>
              			</tbody>
              		</table>
                  <h1 style="margin-top: 40px;">Raids</h1>
                  <table class='table'>
              			<thead>
              				<th>Date</th>
                      <th>Type</th>
                      <th>Faction</th>
              				<th>Pot</th>
              				<th>Boosters</th>
              				<th>Gold Collector</th>
              			</thead>
              			<tbody>
              				<?php foreach($raids as $r) { ?>
              					<tr>
              						<td><?=$r['date']?></td>
              						<td><?=$r['type']?></td>
              						<td><?=$r['faction']?></td>
                          <td><?=$r['pot']?></td>
              						<td><?=$r['boosters']?><br/><?=$r['booster_cut']?>g</td>
              						<td><?php if($r['gc']) { ?><?=$r['gc']?><br/><?=$r['gc_total']?>g<?php } ?></td>
              					</tr>
                    <?php } ?>
              			</tbody>
              		</table>
                  <h1 style="margin-top: 40px;">PvP</h1>
                  <table class='table'>
              			<thead>
              				<th>Date</th>
              				<th>Pot</th>
              				<th>Ad cut</th>
              				<th>Faction</th>
              				<th>Booster #1</th>
              				<th>Booster #2</th>
              				<th>Advertiser</th>
              				<th>Realm</th>
              			</thead>
              			<tbody>
              				<?php foreach($pvp as $p) { ?>
              					<tr>
              						<td><?=$p['date']?></td>
              						<td><?=$p['pot']?></td>
              						<td><?=$p['ad_cut']?></td>
              						<td><?=$p['faction']?></td>
              						<td><?=$p['booster_1']?><br/><?=$p['booster_cut']?>g</td>
              						<td><?=$p['booster_2']?><br/><?=(trim($p['booster_2']) ? $p['booster_cut'] . 'g' : '')?></td>
              						<td><?=$p['advertiser']?><br/><?=$p['ad_cut']?>g</td>
              						<td><?=$p['realm']?></td>
              					</tr>
                    <?php } ?>
              			</tbody>
              		</table>
                  <h1 style="margin-top: 40px;">Levelling</h1>
                  <table class='table'>
              			<thead>
              				<th>Date</th>
              				<th>Pot</th>
              				<th>Ad cut</th>
              				<th>Faction</th>
              				<th>Booster #1</th>
              				<th>Booster #2</th>
              				<th>Advertiser</th>
              				<th>Realm</th>
              			</thead>
              			<tbody>
              				<?php foreach($levelling as $l) { ?>
              					<tr>
              						<td><?=$l['date']?></td>
              						<td><?=$l['pot']?></td>
              						<td><?=$l['ad_cut']?></td>
              						<td><?=$l['faction']?></td>
              						<td><?=$l['booster_1']?><br/><?=$l['booster_cut']?>g</td>
              						<td><?=$l['booster_2']?><br/><?=(trim($l['booster_2']) ? $l['booster_cut'] . 'g' : '')?></td>
              						<td><?=$l['advertiser']?><br/><?=$l['ad_cut']?>g</td>
              						<td><?=$l['realm']?></td>
              					</tr>
                    <?php } ?>
              			</tbody>
              		</table>
                  <h1 style="margin-top: 40px;">Manual Edits</h1>
                  <table class='table'>
              			<thead>
              				<th>Name</th>
              				<th>Amount</th>
              				<th>Date</th>
                      <th>Reason</th>
              				<th>Amended By</th>
              			</thead>
              			<tbody>
              				<?php foreach($manual_edits as $me) { ?>
              					<tr>
              						<td><?=$me['name']?></td>
              						<td><?=$me['amount']?></td>
              						<td><?=$me['date']?></td>
                          <td><?=$me['reason']?></td>
              						<td><?=$me['amended_by']?></td>
              					</tr>
                    <?php } ?>
              			</tbody>
              		</table>
                  <h1 style="margin-top: 40px;">Gambling</h1>
                  <table class='table'>
              			<thead>
              				<th>Name</th>
                      <th>Amount</th>
              				<th>Run Id</th>
              			</thead>
              			<tbody>
              				<?php foreach($gambling as $me) { ?>
              					<tr>
              						<td><?=$me['name']?></td>
              						<td><?=$me['amount']?></td>
              						<td><?=$me['runId']?></td>
              					</tr>
                    <?php } ?>
              			</tbody>
              		</table>
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
</body>

</html>
