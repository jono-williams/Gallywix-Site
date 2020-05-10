<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Adtracker extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('access_token') || !$this->session->userdata('gallywix_name')) {
    			redirect('balance/login');
    		}
    }

    public function all()
    {
      set_time_limit(100);
      if($this->session->userdata('region')) {
        $region = $this->session->userdata('region');
      }
      $data = [];
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

      $cycleEndLeg = date('Y-m-d',strtotime($cycleStart . ' - 1 day'));
      $cycleStartLeg = date('Y-m-d',strtotime($cycleStart . ' - 2 week'));


      $data['advertisers'] = $this->db->where('region', $region)->get('advertisers')->result_array();
      $mythic_plus_all = $this->db->where('region', $region)->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false)->get('mythic_plus')->result_array();
      $data['mythic_plus'] = $this->db
      ->where('region', $region)
      ->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'")
      ->get('mythic_plus')->result_array();
      $data['mythic_plus_leg'] = $this->db->where('region', $region)->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStartLeg."' AND '".$cycleEndLeg."'")->get('mythic_plus')->result_array();
      $gtrack_all = $this->db->where('region', $region)->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false)->get('gtrack')->result_array();
      $data['gtrack'] = $this->db
      ->where('region', $region)
      ->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'")
      ->get('gtrack')->result_array();
      $data['gtrack_leg'] = $this->db->where('region', $region)->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStartLeg."' AND '".$cycleEndLeg."'")->get('gtrack')->result_array();
      $levelling_all = $this->db->where('region', $region)->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false)->get('levelling')->result_array();
      $data['levelling'] = $this->db
      ->where('region', $region)
      ->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'")
      ->get('levelling')->result_array();
      $data['levelling_leg'] = $this->db->where('region', $region)->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStartLeg."' AND '".$cycleEndLeg."'")->get('levelling')->result_array();
      $pvp_all = $this->db->where('region', $region)->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false)->get('pvp')->result_array();
      $data['pvp'] = $this->db
      ->where('region', $region)
      ->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'")
      ->get('pvp')->result_array();
      $data['pvp_leg'] = $this->db->where('region', $region)->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStartLeg."' AND '".$cycleEndLeg."'")->get('pvp')->result_array();
      $merged_all = array_merge($pvp_all, $levelling_all, $mythic_plus_all);
      foreach ($data['advertisers'] as $key => $value) {
        foreach ($data['mythic_plus'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            $data['advertisers'][$key]['tracked_data']['mythic_plus']['runs'][] = $v;
            if(strpos($v['faction'], 'BR') !== false) {
              if(@$data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['br']) {
                $data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['br'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['br'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              }
            } else {
              if(@$data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['tc']) {
                $data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['tc'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['tc'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              }
            }
            @$data['advertisers'][$key]['total'] += floatval($v['pot']);
            @$data['totalsales']['mythic_plus'] += floatval($v['pot']);
          }
        }
        foreach ($data['levelling'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            $data['advertisers'][$key]['tracked_data']['levelling']['runs'][] = $v;
            if(strpos($v['faction'], 'BR') !== false) {
              if(@$data['advertisers'][$key]['tracked_data']['levelling']['totals']['br']) {
                $data['advertisers'][$key]['tracked_data']['levelling']['totals']['br'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['levelling']['totals']['br'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              }
            } else {
              if(@$data['advertisers'][$key]['tracked_data']['levelling']['totals']['tc']) {
                $data['advertisers'][$key]['tracked_data']['levelling']['totals']['tc'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['levelling']['totals']['tc'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              }
            }
            @$data['advertisers'][$key]['total'] += floatval($v['pot']);
            @$data['totalsales']['levelling'] += floatval($v['pot']);
          }
        }
        foreach ($data['pvp'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            $data['advertisers'][$key]['tracked_data']['pvp']['runs'][] = $v;
            if(strpos($v['faction'], 'BR') !== false) {
              if(@$data['advertisers'][$key]['tracked_data']['pvp']['totals']['br']) {
                $data['advertisers'][$key]['tracked_data']['pvp']['totals']['br'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['pvp']['totals']['br'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              }
            } else {
              if(@$data['advertisers'][$key]['tracked_data']['pvp']['totals']['tc']) {
                $data['advertisers'][$key]['tracked_data']['pvp']['totals']['tc'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['pvp']['totals']['tc'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              }
            }
            @$data['advertisers'][$key]['total'] += floatval($v['pot']);
            @$data['totalsales']['pvp'] += floatval($v['pot']);
          }
        }
        foreach ($data['gtrack'] as $k => $v) {
          if(strtolower(explode("-", $value['name'], 2)[0]) == strtolower(ucwords($v['advertiser']))) {
            $data['advertisers'][$key]['tracked_data']['gtrack']['runs'][] = $v;
            if($v['source'] == "Booking Request" || $v['source'] == "Returning-BR") {
              if(@$data['advertisers'][$key]['tracked_data']['gtrack']['totals']['br']) {
                $data['advertisers'][$key]['tracked_data']['gtrack']['totals']['br'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
                @$data['advertisers'][$key]['total_br'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
              } else {
                $data['advertisers'][$key]['tracked_data']['gtrack']['totals']['br'] = floatval(str_replace(',', '', $v['pot'])) * 1000;
                @$data['advertisers'][$key]['total_br'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
              }
            } else {
              if(@$data['advertisers'][$key]['tracked_data']['gtrack']['totals']['tc']) {
                $data['advertisers'][$key]['tracked_data']['gtrack']['totals']['tc'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
                @$data['advertisers'][$key]['total_tc'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
              } else {
                $data['advertisers'][$key]['tracked_data']['gtrack']['totals']['tc'] = floatval(str_replace(',', '', $v['pot'])) * 1000;
                @$data['advertisers'][$key]['total_tc'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
              }
            }
            @$data['advertisers'][$key]['total'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
            @$data['totalsales']['gtrack'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
          }
        }
        foreach ($data['gtrack_leg'] as $k => $v) {
          if(strtolower(explode("-", $value['name'], 2)[0]) == strtolower(ucwords($v['advertiser']))) {
            if($v['source'] != "Booking Request" || $v['source'] != "Returning-BR") {
              @$data['advertisers'][$key]['leg_total'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
            }
          }
        }
        foreach ($data['pvp_leg'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            if(strpos($v['faction'], 'BR') === false) {
              @$data['advertisers'][$key]['leg_total'] += floatval($v['pot']);
            }
          }
        }
        foreach ($data['mythic_plus_leg'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            if(strpos($v['faction'], 'BR') === false) {
              @$data['advertisers'][$key]['leg_total'] += floatval($v['pot']);
            }
          }
        }
        foreach ($data['levelling_leg'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            if(strpos($v['faction'], 'BR') === false) {
              @$data['advertisers'][$key]['leg_total'] += floatval($v['pot']);
            }
          }
        }
        foreach ($merged_all as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            if(strpos($v['faction'], 'BR') == false) {
              @$data['advertisers'][$key]['lifetime_total'] += floatval(str_replace(',', '', $v['pot']));
            } else {
              @$data['advertisers'][$key]['lifetime_total_br'] += floatval(str_replace(',', '', $v['pot']));
            }
          }
        }
        foreach ($gtrack_all as $k => $v) {
          if(strtolower(explode("-", $value['name'], 2)[0]) == strtolower($v['advertiser'])) {
            if($v['source'] != "Booking Request" || $v['source'] != "Returning-BR") {
              @$data['advertisers'][$key]['lifetime_total'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
            } else {
              @$data['advertisers'][$key]['lifetime_total_br'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
            }
          }
        }
      }

      $characterRewards = [];
      $characterGoals = [];
      $data['tokensNeeded'] = 0;
      foreach ($data['advertisers'] as $key => $ads) {
        if(!$ads['name']) {
          unset($data['advertisers'][$key]);
        }

        $data['advertisers'][$key]['promo'] = "No Promotion Yet";
        if($ads['role'] == "Legendary Advertiser") {
          if($ads['total'] < 5000000) {
            $data['advertisers'][$key]['promo'] = "Elite Advertiser";
          } else {
            $data['advertisers'][$key]['promo'] = "Highest Role";
          }
        } else {
          if($ads['role'] == "Trainee Advertiser") {
            if(@$data['advertisers'][$key]['lifetime_total'] > 5000000) {
              $data['tokensNeeded'] += 1;
              @$data['advertisers'][$key]['tokens'] += 1;
              $data['advertisers'][$key]['promo'] = "Junior Advertiser";
            }
            if(@$data['advertisers'][$key]['lifetime_total'] > 12000000) {
              $data['tokensNeeded'] += 2;
              @$data['advertisers'][$key]['tokens'] += 2;
              $data['advertisers'][$key]['promo'] = "Senior Advertiser";
            }
            if(@$data['advertisers'][$key]['lifetime_total'] > 40000000) {
              $data['tokensNeeded'] += 4;
              @$data['advertisers'][$key]['tokens'] += 4;
              $data['advertisers'][$key]['promo'] = "Elite Advertiser";
            }
          } elseif ($ads['role'] == "Junior Advertiser") {
            if(@$data['advertisers'][$key]['lifetime_total'] > 12000000) {
              $data['tokensNeeded'] += 2;
              @$data['advertisers'][$key]['tokens'] += 2;
              $data['advertisers'][$key]['promo'] = "Senior Advertiser";
            }
            if(@$data['advertisers'][$key]['lifetime_total'] > 40000000) {
              $data['tokensNeeded'] += 4;
              @$data['advertisers'][$key]['tokens'] += 4;
              $data['advertisers'][$key]['promo'] = "Elite Advertiser";
            }
          } elseif ($ads['role'] == "Senior Advertiser") {
            if(@$data['advertisers'][$key]['lifetime_total'] > 40000000) {
              $data['tokensNeeded'] += 4;
              @$data['advertisers'][$key]['tokens'] += 4;
              $data['advertisers'][$key]['promo'] = "Elite Advertiser";
            }
          } elseif ($ads['role'] == "Elite Advertiser") {
            if(@$ads['leg_total'] > 50000000 && $ads['total'] > 50000000) {
              $data['advertisers'][$key]['promo'] = "Legendary Advertiser";
              @$data['advertisers'][$key]['tokens'] += 0;
            }
          }
        }

        $data['advertisers'][$key]['goal'] = "No Goals Achieved";
        if(@$data['advertisers'][$key]['total_tc'] > 5000000 && @$data['advertisers'][$key]['total_tc'] < 15000000) {
          $data['advertisers'][$key]['goal'] = "Goal 1";
        } elseif (@$data['advertisers'][$key]['total_tc'] > 15000000 && @$data['advertisers'][$key]['total_tc'] < 17500000) {
          $data['advertisers'][$key]['goal'] = "Goal 2";
        } elseif (@$data['advertisers'][$key]['total_tc'] > 17500000 && @$data['advertisers'][$key]['total_tc'] < 50000000) {
          $data['advertisers'][$key]['goal'] = "Goal 3";
        } elseif (@$data['advertisers'][$key]['total_tc'] > 50000000) {
          $data['advertisers'][$key]['goal'] = "Goal 4";
        }

        if($data['advertisers'][$key]['promo'] != "No Promotion Yet" && $data['advertisers'][$key]['promo'] != "Highest Role") {
          array_push($characterRewards, $data['advertisers'][$key]);
        }
        if($data['advertisers'][$key]['goal'] != "No Goals Achieved") {
          array_push($characterGoals, $data['advertisers'][$key]);
        }
      }

      function method1($a,$b)
      {
        return (@$a["total"] >= @$b["total"]) ? -1 : 1;
      }

      usort($data['advertisers'], 'method1');
      $data['tokenPrice'] = json_decode(file_get_contents('https://wowtokenprices.com/current_prices.json'))->eu->current_price;
      $data['cycleStart'] = $cycleStart;
      $data['cycleEnd'] = $cycleEnd;
      $data['cycles'] = $cycles;
      $data['characterRewards'] = $characterRewards;
      $data['characterGoals'] = $characterGoals;
      $this->load->view('adtracker', $data);
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
      if($this->session->userdata('region')) {
        $region = $this->session->userdata('region');
      }
      $data = [];
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

      $balance_name = $this->session->userdata('gallywix_name');
      $balance_name2 = explode("-", $balance_name, 2)[0];

      $cycleEndLeg = date('Y-m-d',strtotime($cycleStart . ' - 1 day'));
      $cycleStartLeg = date('Y-m-d',strtotime($cycleStart . ' - 2 week'));


      $data['advertisers'] = $this->db->where('region', $region)->where('name', $balance_name)->get('advertisers')->result_array();
      $mythic_plus_all = $this->db->where('region', $region)->where('advertiser', $balance_name)->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false)->get('mythic_plus')->result_array();
      $data['mythic_plus'] = $this->db
      ->where('region', $region)
      ->where('advertiser', $balance_name)
      ->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'")
      ->get('mythic_plus')->result_array();
      $data['mythic_plus_leg'] = $this->db->where('region', $region)->where('advertiser', $balance_name)->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStartLeg."' AND '".$cycleEndLeg."'")->get('mythic_plus')->result_array();
      $gtrack_all = $this->db->where('region', $region)->where('advertiser', $balance_name2)->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false)->get('gtrack')->result_array();
      $data['gtrack'] = $this->db
      ->where('region', $region)
      ->where('advertiser', $balance_name2)
      ->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'")
      ->get('gtrack')->result_array();
      $data['gtrack_leg'] = $this->db->where('region', $region)->where('advertiser', $balance_name2)->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStartLeg."' AND '".$cycleEndLeg."'")->get('gtrack')->result_array();
      $levelling_all = $this->db->where('region', $region)->where('advertiser', $balance_name)->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false)->get('levelling')->result_array();
      $data['levelling'] = $this->db
      ->where('region', $region)
      ->where('advertiser', $balance_name)
      ->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'")
      ->get('levelling')->result_array();
      $data['levelling_leg'] = $this->db->where('region', $region)->where('advertiser', $balance_name)->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStartLeg."' AND '".$cycleEndLeg."'")->get('levelling')->result_array();
      $pvp_all = $this->db->where('region', $region)->where('advertiser', $balance_name)->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false)->get('pvp')->result_array();
      $data['pvp'] = $this->db
      ->where('region', $region)
      ->where('advertiser', $balance_name)
      ->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'")
      ->get('pvp')->result_array();
      $data['pvp_leg'] = $this->db->where('region', $region)->where('advertiser', $balance_name)->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStartLeg."' AND '".$cycleEndLeg."'")->get('pvp')->result_array();
      $merged_all = array_merge($pvp_all, $levelling_all, $mythic_plus_all);
      foreach ($data['advertisers'] as $key => $value) {
        foreach ($data['mythic_plus'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            $data['advertisers'][$key]['tracked_data']['mythic_plus']['runs'][] = $v;
            if(strpos($v['faction'], 'BR') !== false) {
              if(@$data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['br']) {
                $data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['br'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['br'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              }
            } else {
              if(@$data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['tc']) {
                $data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['tc'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['mythic_plus']['totals']['tc'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              }
            }
            @$data['advertisers'][$key]['total'] += floatval($v['pot']);
            @$data['totalsales']['mythic_plus'] += floatval($v['pot']);
          }
        }
        foreach ($data['levelling'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            $data['advertisers'][$key]['tracked_data']['levelling']['runs'][] = $v;
            if(strpos($v['faction'], 'BR') !== false) {
              if(@$data['advertisers'][$key]['tracked_data']['levelling']['totals']['br']) {
                $data['advertisers'][$key]['tracked_data']['levelling']['totals']['br'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['levelling']['totals']['br'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              }
            } else {
              if(@$data['advertisers'][$key]['tracked_data']['levelling']['totals']['tc']) {
                $data['advertisers'][$key]['tracked_data']['levelling']['totals']['tc'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['levelling']['totals']['tc'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              }
            }
            @$data['advertisers'][$key]['total'] += floatval($v['pot']);
            @$data['totalsales']['levelling'] += floatval($v['pot']);
          }
        }
        foreach ($data['pvp'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            $data['advertisers'][$key]['tracked_data']['pvp']['runs'][] = $v;
            if(strpos($v['faction'], 'BR') !== false) {
              if(@$data['advertisers'][$key]['tracked_data']['pvp']['totals']['br']) {
                $data['advertisers'][$key]['tracked_data']['pvp']['totals']['br'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['pvp']['totals']['br'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_br'] += floatval($v['pot']);
              }
            } else {
              if(@$data['advertisers'][$key]['tracked_data']['pvp']['totals']['tc']) {
                $data['advertisers'][$key]['tracked_data']['pvp']['totals']['tc'] += floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              } else {
                $data['advertisers'][$key]['tracked_data']['pvp']['totals']['tc'] = floatval($v['pot']);
                @$data['advertisers'][$key]['total_tc'] += floatval($v['pot']);
              }
            }
            @$data['advertisers'][$key]['total'] += floatval($v['pot']);
            @$data['totalsales']['pvp'] += floatval($v['pot']);
          }
        }
        foreach ($data['gtrack'] as $k => $v) {
          if(strtolower(explode("-", $value['name'], 2)[0]) == strtolower(ucwords($v['advertiser']))) {
            $data['advertisers'][$key]['tracked_data']['gtrack']['runs'][] = $v;
            if($v['source'] == "Booking Request" || $v['source'] == "Returning-BR") {
              if(@$data['advertisers'][$key]['tracked_data']['gtrack']['totals']['br']) {
                $data['advertisers'][$key]['tracked_data']['gtrack']['totals']['br'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
                @$data['advertisers'][$key]['total_br'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
              } else {
                $data['advertisers'][$key]['tracked_data']['gtrack']['totals']['br'] = floatval(str_replace(',', '', $v['pot'])) * 1000;
                @$data['advertisers'][$key]['total_br'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
              }
            } else {
              if(@$data['advertisers'][$key]['tracked_data']['gtrack']['totals']['tc']) {
                $data['advertisers'][$key]['tracked_data']['gtrack']['totals']['tc'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
                @$data['advertisers'][$key]['total_tc'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
              } else {
                $data['advertisers'][$key]['tracked_data']['gtrack']['totals']['tc'] = floatval(str_replace(',', '', $v['pot'])) * 1000;
                @$data['advertisers'][$key]['total_tc'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
              }
            }
            @$data['advertisers'][$key]['total'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
            @$data['totalsales']['gtrack'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
          }
        }
        foreach ($data['gtrack_leg'] as $k => $v) {
          if(strtolower(explode("-", $value['name'], 2)[0]) == strtolower(ucwords($v['advertiser']))) {
            if($v['source'] != "Booking Request" || $v['source'] != "Returning-BR") {
              @$data['advertisers'][$key]['leg_total'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
            }
          }
        }
        foreach ($data['pvp_leg'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            if(strpos($v['faction'], 'BR') === false) {
              @$data['advertisers'][$key]['leg_total'] += floatval($v['pot']);
            }
          }
        }
        foreach ($data['mythic_plus_leg'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            if(strpos($v['faction'], 'BR') === false) {
              @$data['advertisers'][$key]['leg_total'] += floatval($v['pot']);
            }
          }
        }
        foreach ($data['levelling_leg'] as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            if(strpos($v['faction'], 'BR') === false) {
              @$data['advertisers'][$key]['leg_total'] += floatval($v['pot']);
            }
          }
        }
        foreach ($merged_all as $k => $v) {
          if(strtolower($value['name']) == strtolower($v['advertiser'])) {
            if(strpos($v['faction'], 'BR') == false) {
              @$data['advertisers'][$key]['lifetime_total'] += floatval(str_replace(',', '', $v['pot']));
            } else {
              @$data['advertisers'][$key]['lifetime_total_br'] += floatval(str_replace(',', '', $v['pot']));
            }
          }
        }
        foreach ($gtrack_all as $k => $v) {
          if(strtolower(explode("-", $value['name'], 2)[0]) == strtolower($v['advertiser'])) {
            if($v['source'] != "Booking Request" || $v['source'] != "Returning-BR") {
              @$data['advertisers'][$key]['lifetime_total'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
            } else {
              @$data['advertisers'][$key]['lifetime_total_br'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
            }
          }
        }
      }

      $characterRewards = [];
      $characterGoals = [];
      $data['tokensNeeded'] = 0;
      foreach ($data['advertisers'] as $key => $ads) {
        if(!$ads['name']) {
          unset($data['advertisers'][$key]);
        }

        $data['advertisers'][$key]['promo'] = "No Promotion Yet";
        if($ads['role'] == "Legendary Advertiser") {
          $data['advertisers'][$key]['promo'] = "Highest Role";
        } else {
          if($ads['role'] == "Trainee Advertiser") {
            if(@$data['advertisers'][$key]['lifetime_total'] > 5000000) {
              $data['tokensNeeded'] += 1;
              @$data['advertisers'][$key]['tokens'] += 1;
              $data['advertisers'][$key]['promo'] = "Junior Advertiser";
            }
            if(@$data['advertisers'][$key]['lifetime_total'] > 12000000) {
              $data['tokensNeeded'] += 2;
              @$data['advertisers'][$key]['tokens'] += 2;
              $data['advertisers'][$key]['promo'] = "Senior Advertiser";
            }
            if(@$data['advertisers'][$key]['lifetime_total'] > 40000000) {
              $data['tokensNeeded'] += 4;
              @$data['advertisers'][$key]['tokens'] += 4;
              $data['advertisers'][$key]['promo'] = "Elite Advertiser";
            }
          } elseif ($ads['role'] == "Junior Advertiser") {
            if(@$data['advertisers'][$key]['lifetime_total'] > 12000000) {
              $data['tokensNeeded'] += 2;
              @$data['advertisers'][$key]['tokens'] += 2;
              $data['advertisers'][$key]['promo'] = "Senior Advertiser";
            }
            if(@$data['advertisers'][$key]['lifetime_total'] > 40000000) {
              $data['tokensNeeded'] += 4;
              @$data['advertisers'][$key]['tokens'] += 4;
              $data['advertisers'][$key]['promo'] = "Elite Advertiser";
            }
          } elseif ($ads['role'] == "Senior Advertiser") {
            if(@$data['advertisers'][$key]['lifetime_total'] > 40000000) {
              $data['tokensNeeded'] += 4;
              @$data['advertisers'][$key]['tokens'] += 4;
              $data['advertisers'][$key]['promo'] = "Elite Advertiser";
            }
          } elseif ($ads['role'] == "Elite Advertiser") {
            if(@$ads['leg_total'] > 50000000 && $ads['total'] > 50000000) {
              $data['advertisers'][$key]['promo'] = "Legendary Advertiser";
            }
          }
        }

        $data['advertisers'][$key]['goal'] = "No Goals Achieved";
        if(@$data['advertisers'][$key]['total_tc'] > 5000000 && @$data['advertisers'][$key]['total_tc'] < 15000000) {
          $data['advertisers'][$key]['goal'] = "Goal 1";
        } elseif (@$data['advertisers'][$key]['total_tc'] > 15000000 && @$data['advertisers'][$key]['total_tc'] < 17500000) {
          $data['advertisers'][$key]['goal'] = "Goal 2";
        } elseif (@$data['advertisers'][$key]['total_tc'] > 17500000 && @$data['advertisers'][$key]['total_tc'] < 50000000) {
          $data['advertisers'][$key]['goal'] = "Goal 3";
        } elseif (@$data['advertisers'][$key]['total_tc'] > 50000000) {
          $data['advertisers'][$key]['goal'] = "Goal 4";
        }

        if($data['advertisers'][$key]['promo'] != "No Promotion Yet") {
          array_push($characterRewards, $data['advertisers'][$key]);
        }
        if($data['advertisers'][$key]['goal'] != "No Goals Achieved") {
          array_push($characterGoals, $data['advertisers'][$key]);
        }
      }

      function method1($a,$b)
      {
        return (@$a["total"] >= @$b["total"]) ? -1 : 1;
      }

      usort($data['advertisers'], 'method1');
      $data['tokenPrice'] = json_decode(file_get_contents('https://wowtokenprices.com/current_prices.json'))->eu->current_price;
      $data['cycleStart'] = $cycleStart;
      $data['cycleEnd'] = $cycleEnd;
      $data['cycles'] = $cycles;
      $data['characterRewards'] = $characterRewards;
      $data['characterGoals'] = $characterGoals;
      $this->load->view('adtracker_single', $data);
    }
}

?>
