<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gallywix | Management Cuts</title>
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
        width: 35%;
        color: black;
        text-align: center;
        font-size: 30px;
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

      .table tr > td:nth-child(1), .table thead tr > th:nth-child(1) {
        background: #f27474;
        border-color: #f27474;
        color: white;
      }

      .table tr > td:nth-child(2), .table thead tr > th:nth-child(2) {
        background: #3085d6;
        border-color: #3085d6;
        color: white;
      }

      .table .section_headers {
        background: #adadad;
        border-color: #adadad;
        color: white;
      }
    </style>
</head>

<body>
    <div class="non-static">
        <?=include('header.php')?>
        <section class="about" id="about">
            <h1 data-aos="fade-down" data-aos-delay="300" style="color:orange; text-align: center; opacity: 0.5;font-size: 50px;margin-top:100px;">Management Cuts</h1>
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
            <table class="table">
              <thead>
                <tr>
                  <th>Horde</th>
                  <th>Alliance</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th colspan="2" class="section_headers">Mythic Plus</th>
                </tr>
                <tr>
                  <td>Total: <?=@number_format($total['mplus']['h'])?></td>
                  <td>Total: <?=@number_format($total['mplus']['a'])?></td>
                </tr>
                <tr>
                  <td>BR %: <?=@number_format(($total['mplus']['hBR'] / $total['mplus']['h'])*100)?>%</td>
                  <td>BR %: <?=@number_format(($total['mplus']['aBR'] / $total['mplus']['a'])*100)?>%</td>
                </tr>
                <tr>
                  <td>Management: <?=@number_format($total['mplus']['h']*0.15)?></td>
                  <td>Management: <?=@number_format($total['mplus']['a']*0.15)?></td>
                </tr>
                <tr>
                  <th colspan="2" class="section_headers">Raids</th>
                </tr>
                <tr>
                  <td>Total: <?=@number_format($raid_horde_pot)?></td>
                  <td>Total: <?=@number_format($raid_alliance_pot)?></td>
                </tr>
                <tr>
                  <td>Management: <?=@number_format($raid_horde)?></td>
                  <td>Management: <?=@number_format($raid_alliance)?></td>
                </tr>
                <tr>
                  <th colspan="2" class="section_headers">Levelling</th>
                </tr>
                <tr>
                  <td>Total: <?=@number_format($total['levelling']['h'])?></td>
                  <td>Total: <?=@number_format($total['levelling']['a'])?></td>
                </tr>
                <tr>
                  <td>BR %: <?=@number_format(($total['levelling']['hBR'] / $total['levelling']['h'])*100)?>%</td>
                  <td>BR %: <?=@number_format(($total['levelling']['aBR'] / $total['levelling']['a'])*100)?>%</td>
                </tr>
                <tr>
                  <td>Management: <?=@number_format($total['levelling']['h']*0.10)?></td>
                  <td>Management: <?=@number_format($total['levelling']['a']*0.10)?></td>
                </tr>
                <tr>
                  <th colspan="2" class="section_headers">PvP</th>
                </tr>
                <tr>
                  <td>Total: <?=@number_format($total['pvp']['h'])?></td>
                  <td>Total: <?=@number_format($total['pvp']['a'])?></td>
                </tr>
                <tr>
                  <td>BR %: <?=@number_format(($total['pvp']['hBR'] / $total['pvp']['h'])*100)?>%</td>
                  <td>BR %: <?=@number_format(($total['pvp']['aBR'] / $total['pvp']['a'])*100)?>%</td>
                </tr>
                <tr>
                  <td>Management: <?=@number_format($total['pvp']['h']*0.15)?></td>
                  <td>Management: <?=@number_format($total['pvp']['a']*0.15)?></td>
                </tr>
                <tr>
                  <th colspan="2" class="section_headers">Legacy</th>
                </tr>
                <tr>
                  <td>Total: <?=@number_format($legacy_horde_pot)?></td>
                  <td>Total: <?=@number_format($legacy_alliance_pot)?></td>
                </tr>
                <tr>
                  <td>Management: <?=@number_format($legacy_horde)?></td>
                  <td>Management: <?=@number_format($legacy_alliance)?></td>
                </tr>
                <tr>
                  <th colspan="2" class="section_headers">AoTC</th>
                </tr>
                <tr>
                  <td>Total: <?=@number_format($aotc_horde_pot)?></td>
                  <td>Total: <?=@number_format($aotc_alliance_pot)?></td>
                </tr>
                <tr>
                  <td>Management: <?=@number_format($aotc_horde)?></td>
                  <td>Management: <?=@number_format($aotc_alliance)?></td>
                </tr>
                <tr>
                  <th colspan="2" class="section_headers">Mythic</th>
                </tr>
                <tr>
                  <td>Total: <?=@number_format($mythic_horde_pot)?></td>
                  <td>Total: <?=@number_format($mythic_alliance_pot)?></td>
                </tr>
                <tr>
                  <td>Management: <?=@number_format($mythic_horde)?></td>
                  <td>Management: <?=@number_format($mythic_alliance)?></td>
                </tr>
                <tr>
                  <th colspan="2" class="section_headers">Totals</th>
                </tr>
                <tr>
                  <td>Total: <?=@number_format($total['pvp']['h'] + $total['levelling']['h'] + $raid_horde_pot + $legacy_horde_pot + $aotc_horde_pot + $mythic_horde_pot + $total['mplus']['h'])?></td>
                  <td>Total: <?=@number_format($total['pvp']['a'] + $total['levelling']['a'] + $raid_alliance_pot + $legacy_alliance_pot + $aotc_alliance_pot + $mythic_alliance_pot + $total['mplus']['a'])?></td>
                </tr>
                <tr>
                  <td>Management: <?=@number_format($total['pvp']['h']*0.15 + $total['levelling']['h']*0.10 + $raid_horde + $legacy_horde + $aotc_horde + $mythic_horde + $total['mplus']['h']*0.15)?></td>
                  <td>Management: <?=@number_format($total['pvp']['a']*0.15 + $total['levelling']['a']*0.10 + $raid_alliance + $legacy_alliance + $aotc_alliance + $mythic_alliance + $total['mplus']['a']*0.15)?></td>
                </tr>
              </tbody>
            </table>
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
