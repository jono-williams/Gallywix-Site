<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require BASEPATH . '../vendor/autoload.php';
/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Management extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
      parent::__construct();
      $this->load->library(array('session', 'grocery_CRUD'));
      $this->load->helper('url');
      $allowedRoles = array("681017712914464778", "443520205826818058", "466418911475400746", "225454025372336129", "557611505869258756", "524032946072715264");
      $isManagement = @!empty(array_intersect($allowedRoles, $this->session->userdata('gallywix_user_data')->roles));
  		if(!$isManagement && (!$this->session->userdata('access_token') || !$this->session->userdata('gallywix_name'))) {
  			redirect('balance/login');
  		}
    }

    private function checkServiceAccountCredentialsFile()
    {
      // service account creds
      $application_creds = __DIR__ . '/../../service-account-credentials.json';
      return file_exists($application_creds) ? $application_creds : false;
    }

    private function getClient() {
      $client = new Google_Client();
      if ($credentials_file = $this->checkServiceAccountCredentialsFile()) {
        // set the location manually
        $client->setAuthConfig($credentials_file);
      } elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
        // use the application default credentials
        $client->useApplicationDefaultCredentials();
      } else {
        return;
      }

      $client->setApplicationName("Gallywix Balance Fetch");
      $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
      $client->setAccessType('offline');
      return $client;
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {

    }

    public function payouts() {

    }

    public function editGambling() {
      if($this->session->userdata('region')) {
  			$region = $this->session->userdata('region');
  		}
      $crud = new grocery_CRUD();

      $crud->set_theme('datatables');
      $crud->set_table('gambling');
      $output = $crud->render();

      $data['pageTitle'] = 'Edit Gambling';
      $data['crud'] = (array)$output;
      $this->load->view("crud", $data);
    }

    public function inAndOut() {
      if($this->session->userdata('region')) {
  			$region = $this->session->userdata('region');
  		}

      $cycleStart = $this->config->item('eu_cycle_start');
      $cycleEnd = $this->config->item('eu_cycle_end');

      $this->db->select('date, pot, ad_cut, advertiser, mythic_plus.realm, id, id_from_sheet, collected, "Mplus" as type, realm_gwix.wow_account, booster_cut');
      $this->db->from('mythic_plus');
      $this->db->where('mythic_plus.region', $region);
      $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(mythic_plus.realm," ", "")),SUBSTRING_INDEX(replace(mythic_plus.realm," ", ""), "(", 1),replace(mythic_plus.realm," ", "")), "%")', 'left');
      $mplus = $this->db->get()->result_array();
      $this->db->select('date, pot, ad_cut, advertiser, levelling.realm, id, id_from_sheet, collected, "Levelling" as type, realm_gwix.wow_account, booster_cut');
      $this->db->from('levelling');
      $this->db->where('levelling.region', $region);#
      $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(levelling.realm," ", "")),SUBSTRING_INDEX(replace(levelling.realm," ", ""), "(", 1),replace(levelling.realm," ", "")), "%")', 'left');
      $levelling = $this->db->get()->result_array();
      $this->db->select('date, pot, ad_cut, advertiser, pvp.realm, id, id_from_sheet, collected, "PvP" as type, realm_gwix.wow_account, booster_cut');
      $this->db->from('pvp');
      $this->db->where('pvp.region', $region);
      $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(pvp.realm," ", "")),SUBSTRING_INDEX(replace(pvp.realm," ", ""), "(", 1),replace(pvp.realm," ", "")), "%")', 'left');
      $pvp = $this->db->get()->result_array();
      $this->db->select('date, pot, ad_cut, gold_collector as advertiser, gtrack.realm, id, id_from_sheet, collected, "GTrack" as type, realm_gwix.wow_account');
      $this->db->from('gtrack');
      $this->db->where('gtrack.region', $region);
      $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(gtrack.realm," ", "")),SUBSTRING_INDEX(replace(gtrack.realm," ", ""), "(", 1),replace(gtrack.realm," ", "")), "%")', 'left');
      $gtrack = $this->db->get()->result_array();

      $this->db->select('boosters, booster_cut');
      $this->db->from('raids');
      $this->db->where('raids.region', $region);
      $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $raids = $this->db->get()->result_array();

      $merged = array_merge($mplus, $levelling, $pvp, $gtrack);
      $ids = array_column($merged, 'id_from_sheet');
      $ids = array_unique($ids);
      $merged = array_filter($merged, function ($key, $value) use ($ids) {
          return in_array($value, array_keys($ids));
      }, ARRAY_FILTER_USE_BOTH);

      foreach($merged as $m) {
        if($m['type'] == "Mplus") {
          @$data['inAndOut']["Mplus"]['in'] += floatval($m['pot']);
          @$data['inAndOut']["Mplus"]['out'] += (floatval($m['booster_cut']) * 4) + floatval($m['ad_cut']);
        }
        if($m['type'] == "PvP") {
          @$data['inAndOut']["PvP"]['in'] += floatval($m['pot']);
          @$data['inAndOut']["PvP"]['out'] += floatval($m['booster_cut']) + floatval($m['ad_cut']);
        }
        if($m['type'] == "Levelling") {
          @$data['inAndOut']["Levelling"]['in'] += floatval($m['pot']);
          @$data['inAndOut']["Levelling"]['out'] += floatval($m['booster_cut']) + floatval($m['ad_cut']);
        }
        if($m['type'] == "GTrack") {
          @$data['inAndOut']["GTrack"]['in'] += floatval($m['pot']) * 1000;
        }
      }

      foreach ($raids as $key => $m) {
        @$data['inAndOut']["GTrack"]['out'] += (floatval($m['booster_cut']) * count(explode(',', $m['boosters']))) * 10000;
      }
      $data['pageTitle'] = "In & Out";
      $this->load->view("inAndOut", $data);
    }

    public function editAlts() {
      if($this->session->userdata('region')) {
  			$region = $this->session->userdata('region');
  		}
      $crud = new grocery_CRUD();

      $crud->set_theme('datatables');
      $crud->set_table('wow_characters');
      $output = $crud->render();

      $data['pageTitle'] = 'Edit Users Alts';
      $data['crud'] = (array)$output;
      $this->load->view("crud", $data);
    }

    public function editPeople() {
      if($this->session->userdata('region')) {
  			$region = $this->session->userdata('region');
  		}
      $crud = new grocery_CRUD();

      $crud->set_theme('datatables');
      $crud->set_table('balance');
      $crud->where('region', $region);
      $crud->unset_columns(array('balance'));
      $output = $crud->render();

      $data['pageTitle'] = 'Edit Users';
      $data['crud'] = (array)$output;
      $this->load->view("crud", $data);
    }

    function format_num($value, $row){
      return number_format(floatval($value), 0);
    }

    public function editRuns()
    {
      if($this->session->userdata('region')) {
  			$region = $this->session->userdata('region');
  		}
      if($this->input->post('table')) {
  			$table = $this->input->post('table');
  		} else {
        $table = 'mythic_plus';
      }

      if($this->input->post("cycleView")) {
        $cycle = explode('/', $this->input->post("cycleView"));
        $cycleStart = $cycle[0];
        $cycleEnd = $cycle[1];
        if($region == "EU") {
          $cycles = $this->config->item('eu_cycles');
        } else {
          $cycles = $this->config->item('na_cycles');
        }
      } else {
        if($region == "EU") {
          $cycleStart = $this->config->item('eu_cycle_start');
          $cycleEnd = $this->config->item('eu_cycle_end');
          $cycles = $this->config->item('eu_cycles');
        } else {
          $cycleStart = $this->config->item('na_cycle_start');
          $cycleEnd = $this->config->item('na_cycle_end');
          $cycles = $this->config->item('na_cycles');
        }
      }

      $data['region'] = $region;
      $data['cycleStart'] = $cycleStart;
      $data['cycleEnd'] = $cycleEnd;
      $data['cycles'] = $cycles;

      $crud = new grocery_CRUD();

      $crud->set_theme('datatables');
      $crud->set_table($table);
      $crud->where('region', $region);
      $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $crud->unset_columns(array('collected', 'region'));
      $crud->callback_column('pot',array($this,'format_num'));
      $crud->callback_column('amount',array($this,'format_num'));
      $output = $crud->render();

      $data['pageTitle'] = 'Edit ' . str_replace('_', ' ', $table);
      $data['crud'] = (array)$output;
      $this->load->view("front_crud", $data);
    }

    public function addRuns()
    {
      if($this->session->userdata('region')) {
        $region = $this->session->userdata('region');
      }
      $data = [];

      $cycleStart = $this->config->item('eu_cycle_start');
      $cycleEnd = $this->config->item('eu_cycle_end');
      $this->db->select('date, pot, ad_cut, gold_collector as advertiser, gtrack.realm, id, id_from_sheet, collected, boost_type');
  		$this->db->from('gtrack');
  		$this->db->where('gtrack.region', $region);
  		$this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $gtracks = $this->db->get()->result_array();

      $data['gtrack'] = [];
      $data['pageTitle'] = "Add Runs";
  		foreach($gtracks as $k => $gtrack) {
  		    @$data['gtrack'][$gtrack['date'] . " : " . $gtrack['boost_type']] += floatval(str_replace(',', '', $gtrack['pot'])) * 1000;
      }

      $crud = new grocery_CRUD();

      $crud->set_theme('datatables');
      $crud->set_table("raids");
      $crud->where('region', $region);
      $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $crud->unset_columns(array('collected', 'region'));
      $crud->callback_column('pot',array($this,'format_num'));
      $crud->callback_column('amount',array($this,'format_num'));
      $data['crud'] = (array)$crud->render();

      $data['gtrack'] = array_chunk($data['gtrack'], 15, true);
      $this->load->view("addRuns", $data);
    }

    public function cuts()
    {
      if($this->session->userdata('region')) {
        $region = $this->session->userdata('region');
      }

      if($this->input->post("cycleView")) {
        $cycle = explode('/', $this->input->post("cycleView"));
        $cycleStart = $cycle[0];
        $cycleEnd = $cycle[1];
        if($region == "EU") {
          $cycles = $this->config->item('eu_cycles');
        } else {
          $cycles = $this->config->item('na_cycles');
        }
      } else {
        if($region == "EU") {
          $cycleStart = $this->config->item('eu_cycle_start');
          $cycleEnd = $this->config->item('eu_cycle_end');
          $cycles = $this->config->item('eu_cycles');
        } else {
          $cycleStart = $this->config->item('na_cycle_start');
          $cycleEnd = $this->config->item('na_cycle_end');
          $cycles = $this->config->item('na_cycles');
        }
      }

      $this->db->select('SUM(pot) as pot, faction, "Mplus" as type');
      $this->db->from('mythic_plus');
      $this->db->where('region', $region);
      $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $this->db->group_by('faction');
      $mplus = $this->db->get()->result_array();
      $this->db->select('SUM(pot) as pot, faction, "Levelling" as type');
      $this->db->from('levelling');
      $this->db->where('region', $region);
      $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $this->db->group_by('faction');
      $levelling = $this->db->get()->result_array();
      $this->db->select('SUM(pot) as pot, faction, "PvP" as type');
      $this->db->from('pvp');
      $this->db->where('region', $region);
      $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $this->db->group_by('faction');
      $pvp = $this->db->get()->result_array();

      $this->db->select('pot, expense, faction, type, date, boosters');
      $this->db->from('raids');
      $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
      $this->db->where('region', $region);
      $raids = $this->db->get()->result_array();

      $raids = array_unique($raids, SORT_REGULAR);

      foreach ($raids as $key => $v) {
        $pot = floatval(str_replace(',', '', str_replace('.', '', $v['pot'])));
        $expense = floatval(str_replace(',', '', str_replace('.', '', $v['expense'])));
        $percentage = ($expense / $pot) * 100;
        $percentage -= 10;
        if($v['type'] == "Legacy") {
          if($v['faction'] !== "Alliance") {
            @$data['legacy_horde'] += $pot * floatval("0." . $percentage);
            @$data['legacy_horde_pot'] += $pot;
          } else {
            @$data['legacy_alliance'] += $pot * floatval("0." . $percentage);
            @$data['legacy_alliance_pot'] += $pot;
          }
        } elseif ($v['type'] == "AoTC") {
          if($v['faction'] !== "Alliance") {
            @$data['aotc_horde'] += $pot * floatval("0." . $percentage);
            @$data['aotc_horde_pot'] += $pot;
          } else {

            @$data['aotc_alliance'] += $pot * floatval("0." . $percentage);
            @$data['aotc_alliance_pot'] += $pot;
          }
        } elseif ($v['type'] == "Mythic") {
          if($v['faction'] !== "Alliance") {
            @$data['mythic_horde'] += $pot * floatval("0." . $percentage);
            @$data['mythic_horde_pot'] += $pot;
          } else {

            @$data['mythic_alliance'] += $pot * floatval("0." . $percentage);
            @$data['mythic_alliance_pot'] += $pot;
          }
        } else {
          if($v['faction'] !== "Alliance") {
            @$data['raid_horde'] += $pot * floatval("0." . $percentage);
            @$data['raid_horde_pot'] += $pot;
          } else {
            @$data['raid_alliance'] += $pot * floatval("0." . $percentage);
            @$data['raid_alliance_pot'] += $pot;
          }
        }
      }

      $merged = array_merge($mplus, $levelling, $pvp);

      $data['total'] = [];
      $data['total']['pvp']['h'] = 0;
      $data['total']['pvp']['hBR'] = 0;
      $data['total']['pvp']['a'] = 0;
      $data['total']['pvp']['aBR'] = 0;
      foreach($merged as $k => $m) {
        if($m['type'] == "Mplus") {
           if(strpos($m['faction'], 'Horde') !== false) {
             @$data['total']['mplus']['h'] += floatval($m['pot']);
             if(strpos($m['faction'], 'BR') !== false) {
               @$data['total']['mplus']['hBR'] += floatval($m['pot']);
             }
           } else {
             @$data['total']['mplus']['a'] += floatval($m['pot']);
             if(strpos($m['faction'], 'BR') !== false) {
               @$data['total']['mplus']['aBR'] += floatval($m['pot']);
             }
           }
        }
        if($m['type'] == "PvP") {
          if($m['faction'] == 'Horde-BR') {
            $data['total']['pvp']['h'] += floatval($m['pot']);
            $data['total']['pvp']['hBR'] += floatval($m['pot']);
          } elseif ($m['faction'] == 'Horde-TC') {
            $data['total']['pvp']['h'] += floatval($m['pot']);
          } elseif ($m['faction'] == 'Horde') {
            $data['total']['pvp']['h'] += floatval($m['pot']);
          } elseif ($m['faction'] == 'Alliance-BR') {
            $data['total']['pvp']['a'] += floatval($m['pot']);
            $data['total']['pvp']['aBR'] += floatval($m['pot']);
          } elseif ($m['faction'] == 'Alliance-TC') {
            $data['total']['pvp']['a'] += floatval($m['pot']);
          } elseif ($m['faction'] == 'Alliance') {
            $data['total']['pvp']['a'] += floatval($m['pot']);
          }
        }
        if($m['type'] == "Levelling") {
          if(strpos($m['faction'], 'Horde') !== false) {
            @$data['total']['levelling']['h'] += floatval($m['pot']);
            if(strpos($m['faction'], 'BR') !== false) {
              @$data['total']['levelling']['hBR'] += floatval($m['pot']);
            }
          } else {
            @$data['total']['levelling']['a'] += floatval($m['pot']);
            if(strpos($m['faction'], 'BR') !== false) {
              @$data['total']['levelling']['aBR'] += floatval($m['pot']);
            }
          }
        }
      }
      $data['merged'] = $merged;
      $data['realm_list'] = array_unique(array_column($merged, 'realm'));
      $data['region'] = $region;
      $data['cycleStart'] = $cycleStart;
      $data['cycleEnd'] = $cycleEnd;
      $data['cycles'] = $cycles;
      $this->load->view('mcuts', $data);
    }

    public function stats()
    {
      if($this->session->userdata('region')) {
        $region = $this->session->userdata('region');
      }

      if($region == "EU") {
        $cycles = $this->config->item('eu_cycles');
      } else {
        $cycles = $this->config->item('na_cycles');
      }

      foreach ($cycles as $cycle => $textValue) {
        $data2 = [];
        $cycleD = explode('/', $cycle);
        $cycleStart = $cycleD[0];
        $cycleEnd = $cycleD[1];

        $this->db->select('SUM(pot) as pot, COUNT(id) as count, faction, "Mplus" as type');
        $this->db->from('mythic_plus');
        $this->db->where('region', $region);
        $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
        $this->db->group_by('faction');
        $mplus = $this->db->get()->result_array();
        $this->db->select('SUM(pot) as pot, COUNT(id) as count, faction, "Levelling" as type');
        $this->db->from('levelling');
        $this->db->where('region', $region);
        $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
        $this->db->group_by('faction');
        $levelling = $this->db->get()->result_array();
        $this->db->select('SUM(pot) as pot, COUNT(id) as count, faction, "PvP" as type');
        $this->db->from('pvp');
        $this->db->where('region', $region);
        $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
        $this->db->group_by('faction');
        $pvp = $this->db->get()->result_array();

        $this->db->select('pot, expense, faction, type, date, boosters');
        $this->db->from('raids');
        $this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
        $this->db->where('region', $region);
        $raids = $this->db->get()->result_array();

        $raids = array_unique($raids, SORT_REGULAR);

        $data2['total'] = [];
        $data2['total']['pvp'] = 0;
        $data2['total']['mplus'] = 0;
        $data2['total']['levelling'] = 0;
        $data2['total']['raids'] = 0;
        $data2['total']['all'] = 0;

        $data2['count'] = [];
        $data2['count']['pvp'] = 0;
        $data2['count']['mplus'] = 0;
        $data2['count']['levelling'] = 0;
        $data2['count']['raids'] = 0;
        $data2['count']['all'] = 0;

        foreach ($raids as $key => $v) {
          $pot = floatval(str_replace(',', '', str_replace('.', '', $v['pot'])));
          $expense = floatval(str_replace(',', '', str_replace('.', '', $v['expense'])));
          $percentage = ($expense / $pot) * 100;
          $percentage -= 10;
          $data2['total']['raids'] += $pot;
          $data2['total']['all'] += $pot;
          $data2['count']['raids']++;
          $data2['count']['all']++;
        }

        $merged = array_merge($mplus, $levelling, $pvp);

        foreach($merged as $k => $m) {
          if($m['type'] == "Mplus") {
            $data2['count']['mplus'] += intval($m['count']);
            $data2['total']['mplus'] += floatval($m['pot']);
            $data2['count']['all'] += intval($m['count']);
            $data2['total']['all'] += floatval($m['pot']);
          }
          if($m['type'] == "PvP") {
            $data2['count']['pvp'] += intval($m['count']);
            $data2['total']['pvp'] += floatval($m['pot']);
            $data2['count']['all'] += intval($m['count']);
            $data2['total']['all'] += floatval($m['pot']);
          }
          if($m['type'] == "Levelling") {
            $data2['count']['levelling'] += intval($m['count']);
            $data2['total']['levelling'] += floatval($m['pot']);
            $data2['count']['all'] += intval($m['count']);
            $data2['total']['all'] += floatval($m['pot']);
          }
        }
        $data['data'][$cycle] = $data2;
      }

      // print_r($data);
      $data['data'] = array_reverse($data['data'], TRUE);
      $this->load->view('stats', $data);
    }

    public function editPricelist() {
      if($this->session->userdata('region')) {
        $region = $this->session->userdata('region');
      }

      $crud = new grocery_CRUD();

      $crud->set_theme('datatables');
      $crud->set_table('price_list');
      $crud->where('region', $region);
      if($this->session->userdata('gallywix_user_data')->user->id == "218872849291542528") {
        $crud->where('category', "Legacy");
      }
      $crud->unset_columns(array('region'));

      $output = $crud->render();

      $data['pageTitle'] = 'Edit Pricelist';
      $data['crud'] = (array)$output;
      $this->load->view("crud", $data);
    }

    public function fetchMains() {
      $data['pageTitle'] = "Fetch Mains";
      $this->load->view("fetchMains", $data);
    }

    public function getMains() {
      $unFoundList = [];
      $list = explode(',', $this->input->post("list"));
      $client = $this->getClient();
			$service = new Google_Service_Sheets($client);
			$result = $service->spreadsheets_values->get("1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk", "Alts!A3:AG");
			$altsList = $result->getValues();

      $foundCharacters = [];
      foreach($list as $key => $characterName) {
        $found = false;
        $key = array_search($characterName, array_column($altsList, 0));
        if($key !== false) {
          $foundCharacters[] = $altsList[$key][0];
          $found = true;
        } else {
          $characterNameNew = explode("-", $characterName)[0];
          foreach($altsList as $alts) {
            $k = array_search($characterNameNew, $alts, true);
            if($k !== false) {
              $foundCharacters[] = $alts[0];
              $found = true;
            }
          }
        }
        if(!$found) {
          $unFoundList[] = $characterName;
        }
      }
      $data = array(
        'unfound' => $unFoundList,
        'found' => $foundCharacters,
      );
      print_r(json_encode($data));
    }

    public function renamePeople() {
      $data['title'] = "Rename Advertisers (Ad tracker only)";
      if($this->input->post('firstName') && $this->input->post('secondName')) {
        $this->rAds($this->input->post('firstName'), $this->input->post('secondName'));
        $this->rBoosters($this->input->post('firstName'), $this->input->post('secondName'));
      }
      $this->load->view('renameAds', $data);
    }

    private function rAds($firstName, $secondName) {
      $firstName = urldecode($firstName);
      $secondName = urldecode($secondName);
      $gtrackFirstName = explode("-", $firstName, 2)[0];
      $gtrackSecondName = explode("-", $secondName, 2)[0];
      $this->db->query("UPDATE levelling SET advertiser = '{$secondName}' WHERE advertiser = '{$firstName}'");
      $this->db->query("UPDATE mythic_plus SET advertiser = '{$secondName}' WHERE advertiser = '{$firstName}'");
      $this->db->query("UPDATE pvp SET advertiser = '{$secondName}' WHERE advertiser = '{$firstName}'");
      $this->db->query("UPDATE gtrack SET advertiser = '{$gtrackSecondName}' WHERE advertiser = '{$gtrackFirstName}'");
    }

    private function rBoosters($firstName, $secondName) {
      $firstName = urldecode($firstName);
      $secondName = urldecode($secondName);
      $this->db->query("UPDATE levelling SET booster_1 = '{$secondName}' WHERE booster_1 = '{$firstName}'");
      $this->db->query("UPDATE levelling SET booster_2 = '{$secondName}' WHERE booster_2 = '{$firstName}'");
      $this->db->query("UPDATE mythic_plus SET booster_1 = '{$secondName}' WHERE booster_1 = '{$firstName}'");
      $this->db->query("UPDATE mythic_plus SET booster_2 = '{$secondName}' WHERE booster_2 = '{$firstName}'");
      $this->db->query("UPDATE mythic_plus SET booster_3 = '{$secondName}' WHERE booster_3 = '{$firstName}'");
      $this->db->query("UPDATE mythic_plus SET booster_4 = '{$secondName}' WHERE booster_4 = '{$firstName}'");
      $this->db->query("UPDATE pvp SET booster_1 = '{$secondName}' WHERE booster_1 = '{$firstName}'");
      $this->db->query("UPDATE pvp SET booster_2 = '{$secondName}' WHERE booster_2 = '{$firstName}'");
      $this->db->query("UPDATE wow_characters SET ownerName = '{$secondName}' WHERE ownerName = '{$firstName}'");
    }
}

?>
