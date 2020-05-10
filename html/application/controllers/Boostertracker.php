<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Boostertracker extends CI_Controller
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

    public function index()
    {
      if($this->session->userdata('region')) {
        $region = $this->session->userdata('region');
      }
      $data = [];
      $data['boosters'] = [];
      $mythic_plus_all = $this->db->where('region', $region)->get('mythic_plus')->result_array();
      $levelling_all = $this->db->where('region', $region)->get('levelling')->result_array();
      $pvp_all = $this->db->where('region', $region)->get('pvp')->result_array();
      $merged_all = array_merge($pvp_all, $levelling_all, $mythic_plus_all);
      foreach ($merged_all as $k => $v) {
        if(@$v['booster_1']) {
          if(array_key_exists($v['booster_1'], $data['boosters'])) {
            $data['boosters'][$v['booster_1']]['total'] += floatval($v['booster_cut']);
            $data['boosters'][$v['booster_1']]['count']++;
          } else {
            $data['boosters'][$v['booster_1']] = array('name' => $v['booster_1'], 'total' => 0, 'count' => 0);
          }
        }
        if(@$v['booster_2']) {
          if(array_key_exists($v['booster_2'], $data['boosters'])) {
            $data['boosters'][$v['booster_2']]['total'] += floatval($v['booster_cut']);
            $data['boosters'][$v['booster_2']]['count']++;
          } else {
            $data['boosters'][$v['booster_2']] = array('name' => $v['booster_2'], 'total' => 0, 'count' => 0);
          }
        }
        if(@$v['booster_3']) {
          if(array_key_exists($v['booster_3'], $data['boosters'])) {
            $data['boosters'][$v['booster_3']]['total'] += floatval($v['booster_cut']);
            $data['boosters'][$v['booster_3']]['count']++;
          } else {
            $data['boosters'][$v['booster_3']] = array('name' => $v['booster_3'], 'total' => 0, 'count' => 0);
          }
        }
        if(@$v['booster_4']) {
          if(array_key_exists($v['booster_4'], $data['boosters'])) {
            $data['boosters'][$v['booster_4']]['total'] += floatval($v['booster_cut']);
            $data['boosters'][$v['booster_4']]['count']++;
          } else {
            $data['boosters'][$v['booster_4']] = array('name' => $v['booster_4'], 'total' => 0, 'count' => 0);
          }
        }
      }

      foreach ($data['boosters'] as $key => $value) {
        if($value['total'] > 25000000) {
          $data['boosters'][$key]['role'] = "Bronze Booster";
        } elseif ($value['total'] > 50000000) {
          $data['boosters'][$key]['role'] = "Silver Booster";
        } elseif ($value['total'] > 75000000) {
          $data['boosters'][$key]['role'] = "Gold Booster";
        } elseif ($value['total'] > 100000000) {
          $data['boosters'][$key]['role'] = "Elite Booster";
        } elseif ($value['total'] > 125000000) {
          $data['boosters'][$key]['role'] = "Veteran Booster";
        } elseif ($value['total'] > 150000000) {
          $data['boosters'][$key]['role'] = "Legendary Booster";
        } else {
          $data['boosters'][$key]['role'] = "No Promotion Yet";
        }
      }

      unset($data['boosters']['x-x']);
      unset($data['boosters']['x-xr']);
      unset($data['boosters']['FAKE-REALM']);

      function method1($a,$b)
      {
        return (@$a['total'] >= @$b['total']) ? -1 : 1;
      }

      usort($data['boosters'], 'method1');
      $this->load->view('boostertracker', $data);
    }
}

?>
