<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gallywix | Collecting</title>
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

      .table input[type="checkbox"] {
        width: 30px;
        height: 30px;
      }

      .table tr.checked {
        background: orange;
      }

      .table tr.checked td {
        border-color: orange !important;
        background-color: orange !important;
      }

      th {
        border-bottom: 2px solid #adadad;
      }

      td {
        border-bottom: 1px solid #e8e8e8;
        border-left: 1px solid #e8e8e8;
        border-right: 1px solid #e8e8e8;
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

      #goldTotalMoving {
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
        position: fixed;
        bottom: 0;
        right: 20px;
      }

      #exportArea {
        color: white;
        background: black;
        padding: 1% 2.5%;
        font-size: 24px;
      }

      .tablesorter-blue tbody tr.even > td {
        background-color: #ebf2fa !important;
      }

      .tablesorter-blue tbody tr.odd > td {
        background-color: #fff !important;
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

      .swal2-title {
        font-size: 6em !important;
      }

      .about .container {
        max-width: 100%;
      }

      .about {
        padding:0;
        padding-top: 50px;
      }
    </style>
</head>

<body>
    <div class="non-static">
        <?=include('header.php')?>
        <section class="about" id="about">
            <form action="<?=current_url()?>" method="post">
              <div style="display: block; margin: 0 auto; width: fit-content; margin-top: 50px;">
                <?=$this->input->post('cycleView');?>
                <select class="btn" name="cycleView">
                  <?php foreach ($cycles as $key => $cycle): ?>
                    <option <?=($this->input->post("cycleView") == $key ? 'selected' : '')?> value="<?=$key?>"><?=$cycle?></option>
                  <?php endforeach; ?>
                </select>
                <input type="submit" class="btn" value="Select Cycle"/>
              </div>
            </form>
            <div class="container">
                <div class="content-area" id="contentStuff">
                  <button onclick="filterCollected();" class="btn">Filter Collected Out</button>
                  <button onclick="refreshData();" class="btn">Refresh</button>
                  <button onclick="exportList();" class="btn">Export</button>
                  <button onclick="importList();" class="btn">Import</button>
                  <div id="exportArea"></div>
                  <table id="myTable" class="table">
              			<thead>
              				<th>Collected</th>
              				<th>Date</th>
              				<th>Pot</th>
                      <th>Ad Cut</th>
              				<th>Gold Contains</th>
              				<th>Advertiser</th>
              				<th>Realm</th>
                      <th>Id</th>
              				<th>WoW Account</th>
              				<th>Type</th>
              			</thead>
              			<tbody>
              				<?php foreach($merged as $m) { ?>
              					<tr id="<?=@preg_replace("/[^A-Za-z0-9 ]/", '', $m['id_from_sheet'])?>" class="<?=(strtoupper(@$m['collected']) == "TRUE" ? 'checked' : '')?>">
              						<td><input type="checkbox" <?=(strtoupper(@$m['collected']) == "TRUE" ? 'checked' : '')?> onchange="updateRun('<?=@$m['id']?>', '<?=@$m['type']?>')"/></td>
                          <td><?=@date( "d/m", $m['date'])?></td>
                          <td><?=@number_format($m['pot'],0)?></td>
                          <td><?=@number_format($m['ad_cut'],0)?></td>
                          <td><?=(@$m['ad_cut'] == 0 ? floatval(@number_format(@$m['pot'])) * 0.9 : @number_format(@$m['pot']))?></td>
                          <td><?=@$m['advertiser']?></td>
                          <td><?=@$m['realm']?></td>
                          <td><?=@$m['id_from_sheet']?></td>
                          <td><?=@$m['wow_account']?></td>
              						<td><?=@$m['type']?></td>
              					</tr>
                    <?php } ?>
              			</tbody>
              		</table>
                </div>
            </div>
            <div id="goldTotalMoving"><?=$total?>g</div>
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
    <script src="/public/js/jquery.inview.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.2/css/theme.blue.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.2/js/jquery.tablesorter.widgets.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
      $(function() {
        $("#myTable").tablesorter({
          theme: 'blue',
          widgets: ["filter", "reflow"],
          widgetOptions : {
            filter_reset : '.reset',
            filter_searchFiltered : false,
            reflow_className    : 'ui-table-reflow',
            reflow_headerAttrib : 'data-name',
            reflow_dataAttrib   : 'data-title'
          }
        });
      });

      $(document).bind("paste", function(e){
        e.stopPropagation();
        e.preventDefault();

        var cd = e.originalEvent.clipboardData;

        console.log(cd.getData("text/plain").replace(/[^A-Za-z0-9 ]/gi,""));
        found = $('#myTable tbody tr#' + cd.getData("text/plain").replace(/[^A-Za-z0-9 ]/gi,"")).find("td").eq(0).find('input');
        goldLeft = $('#myTable tbody tr#' + cd.getData("text/plain").replace(/[^A-Za-z0-9 ]/gi,"")).find("td").eq(4).html();
        if(found.prop("checked")) {
          swalImage = 'https://cdn.discordapp.com/emojis/687998557029924898.gif?v=1';
          textH = 'Already Collected: ';
        } else {
          swalImage = 'https://cdn.discordapp.com/emojis/513420102201245696.png?v=1';
          textH = 'Gold Left: ';
        }
        found.click();
        Swal.fire({
          text: 'Run Id: ' + cd.getData("text/plain").replace('+', ''),
          title: textH + goldLeft + 'g',
          imageUrl: swalImage,
          imageWidth: 200,
          timer: 1000,
          timerProgressBar: true,
          imageHeight: 200,
          imageAlt: 'Pog',
        });
        $('html,body').animate({
          scrollTop: found.offset().top - 250
        }, 1000);
      });

      function updateRun(id, type) {
        $(event.target).parents('tr').toggleClass('checked');
        $.ajax({
          url: '<?=base_url()?>goldcollecting/changerun/' + type + '/' + id + '/' + $(event.target).prop('checked') + '/<?=$region?>',
        });
        $.ajax({
          url: '<?=base_url()?>goldcollecting/goldleft/notasmellytools',
          dataType: 'json',
          success: function(data) {
            $('#goldTotalMoving').text(data.total + 'g');
          },
        });
      }

      function refreshData() {
        $.ajax({
          url: '<?=base_url()?>cron/sheetToDB/importthisshit',
          success: function(data) {
            location.reload();
          }
        })
      }

      function filterCollected() {
        $('.checked').toggle();
        $('#myTable tbody tr:visible:odd td').css('background', '#EBEFF4');
        $('#myTable tbody tr:visible:even td').css('background', '#FFFFFF');
      }

      function exportList() {
        list = <?=json_encode($realm_list)?>;

        Swal.fire({
          title: 'Pick a realm',
          input: 'select',
          inputOptions: list,
          showCancelButton: true,
          confirmButtonText: 'Export',
          showLoaderOnConfirm: true,
          preConfirm: (login) => {
            $('#exportArea').empty();
            pickedRealm = list[login];
            foundList = $('#myTable tbody tr:not(.checked):contains("'+pickedRealm+'")');
            finalList = [];
            $.each(foundList, function() {
              finalList.push($(this).find("td").eq(7).text() + ":" + $(this).find("td").eq(2).text());
            });
            Swal.close();
            $('#exportArea').html('<code>' +
              $.each(finalList, function() {
                this + '<br>';
              })
            + '</code>');
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      }

      function importList() {
        Swal.fire({
          title: 'Post input below',
          input: 'textarea',
          showCancelButton: true,
          confirmButtonText: 'Import',
          showLoaderOnConfirm: true,
          preConfirm: (login) => {
            finalList = login.split("\n");
            $.each(finalList, function() {
              $('#myTable tbody tr:not(.checked):contains("'+this+'")').find("td").eq(0).find('input').click();
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      }
    </script>
</body>
</html>
