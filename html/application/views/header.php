<?php

$allowedRoles = array("461839226581942276", "556622546351423498");
$isAdvertiser = @!empty(array_intersect($allowedRoles, $this->session->userdata('gallywix_user_data')->roles));

$allowedRoles = array("443759760534142976", "556607029939011604");
$isBooster = @!empty(array_intersect($allowedRoles, $this->session->userdata('gallywix_user_data')->roles));

$allowedRoles = array("681017712914464778", "443520205826818058");
$isHigherManagement = @!empty(array_intersect($allowedRoles, $this->session->userdata('gallywix_user_data')->roles));

$allowedRoles = array("681017712914464778", "443520205826818058", "466418911475400746", "225454025372336129", "557611505869258756", "524032946072715264");
$isManagement = @!empty(array_intersect($allowedRoles, $this->session->userdata('gallywix_user_data')->roles));

$allowedRoles = array("554885838882996244");
$isGoldCollector = @!empty(array_intersect($allowedRoles, $this->session->userdata('gallywix_user_data')->roles));

$allowedRoles = array("524197839358918663");
$isDev = @!empty(array_intersect($allowedRoles, $this->session->userdata('gallywix_user_data')->roles));

$allowedIds = array("108286660722073600", "166992756005142528");
$isTollsOrJzen = @in_array(@$this->session->userdata('gallywix_user_data')->user->id, $allowedIds);

$allowedIds = array("207662094277935105", "134333156877664256");
$isEorT = @in_array(@$this->session->userdata('gallywix_user_data')->user->id, $allowedIds);

$allowedIds = array("191983230205820929", "218872849291542528");
$allowedToEditPrices = @in_array(@$this->session->userdata('gallywix_user_data')->user->id, $allowedIds);

?>
<style>
  header h1.logo, header nav ul li a {
    color: orange !important;
  }

  header {
    border-bottom: 2px solid orange;
    background: white !important;
  }
</style>
<div class="mobile-nav hide-desktop show-mobile" style="display: none;">
    <ul>
      <?php if($isTollsOrJzen) { ?>
        <li><a href="/goldcollecting/index/oJtFqbQ7l6">Gold Collecting</a></li>
      <?php } ?>
      <?php if($isAdvertiser) { ?>
        <li><a href="/adtracker/index">Advertiser Tracker</a></li>
      <?php } ?>
      <?php if($isHigherManagement) { ?>
        <li><a href="/management/cuts">Management Cuts</a></li>
      <?php } ?>
      <li><a href="/balance/index">Balance</a></li>
      <li><a href="/battlenet/characters">Your Characters</a></li>
      <li><a href="/balance/logout">Logout</a></li>
    </ul>
</div>
<header>
    <div class="container">
        <h1 class="logo">Gallywix</h1>
        <nav>
            <a href="#" class="hide-desktop" id="menu-btn">
                <i class="fas fa-bars"></i>
            </a>
            <ul class="show-desktop hide-mobile" id="desktop-nav">
                <?php if($isAdvertiser || @$this->session->userdata('gallywix_user_data')->user->id == "108286660722073600" || $isManagement) { ?>
                  <li><a href="/adtracker">Advertiser Tracker</a></li>
                <?php } ?>
                <?php if($isBooster || @$this->session->userdata('gallywix_user_data')->user->id == "108286660722073600" || $isManagement) { ?>
                  <li><a href="/boostertracker">Booster Tracker</a></li>
                <?php } ?>
                <?php if($isGoldCollector || $isManagement) { ?>
                <li class="dropdown-button">
                  <a>Management</a>
                  <ul class="dropdown-content">
                    <?php if($isGoldCollector || $isManagement) { ?>
                      <li><a href="/decrypt">Decrypt Buyers</a></li>
                    <?php } ?>
                    <?php if($isManagement) { ?>
                      <li><a href="/management/editRuns">Edit Runs</a></li>
                      <li><a href="/management/cuts">Management Cuts</a></li>
                      <li><a href="/management/editPeople">Edit Pay out Character Names</a></li>
                      <li><a href="/management/editAlts">Edit Alts</a></li>
                      <li><a href="/cron/advertisersEUList/0858341946">Sync Advertisers EU</a></li>
                      <li><a href="/management/fetchMains">Fetch Main Character</a></li>
                      <li><a href="/adtracker/all">Advertiser Tracker</a></li>
                      <li><a href="/management/renamePeople">Rename People</a></li>
                      <li><a href="/referral/list">Discord/Website Referrals</a></li>
                    <?php } ?>
                    <?php if($isHigherManagement || $allowedToEditPrices) { ?>
                      <li><a href="/management/editPricelist">Edit Site Prices</a></li>
                    <?php } ?>
                    <?php if($isHigherManagement) { ?>
                      <li><a href="/management/addRuns">Add Runs</a></li>
                      <li><a href="/management/stats">All Stats</a></li>
                      <!-- <li><a href="/management/editGambling">Edit Gambling</a></li> -->
                      <!-- <li><a href="/management/payouts">Payouts</a></li> -->
                    <?php } ?>
                    <?php if($isTollsOrJzen) { ?>
                      <li><a href="/goldcollecting/index/oJtFqbQ7l6">Gold Collecting</a></li>
                    <?php } ?>
                    <?php if($isEorT) { ?>
                      <li><a href="/goldcollecting/index/oJtFqbQ7l6/na">Gold Collecting</a></li>
                    <?php } ?>
                  </ul>
                </li>
                <?php } ?>
                <li><a href="/balance/index">Balance</a></li>
                <?php if($this->session->userdata('region') != "NA") { ?>
                  <li><a href="/battlenet/characters">Your Characters</a></li>
                <?php } ?>
                <li><a href="/balance/logout">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>
