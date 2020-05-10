<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
require_once '/var/www/html/vendor/autoload.php';
class Cron extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */

    private function generateRandomString($length = 10) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }

    private function discordMessage($message, $channel) {
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/".$channel."/messages");

  		$headers = array();
      $headers[] = "Content-Type: application/json";
      $headers[] = "Authorization: Bot " . $this->config->item('DISCORD_BOT_TOKEN');
      $postdata = array();
      $postdata['content'] = $message;
      $postdata['tts'] = false;

  		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_POST, count($postdata));
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));

  		$result = curl_exec($ch);
      $confirm = json_decode($result);
      if(@$confirm->content) {
        $sent = true;
      } else {
        $sent = false;
      }
  		curl_close ($ch);
      return $sent;
    }

    public function fetchBalanceJSON($key = '', $name) {
      if(urldecode($key) == "sCkGyThy2LGgsjKC!") {
        $balance = $this->db->where('name', $name)->get('balance')->result();
        print_r(json_encode($balance));
      }
    }

    public function fetchAddRewards($key = '', $region)
    {
      if(urldecode($key) != "sCkGyThy2LGgsjKC!") {
        exit;
      }
      if($region == "EU") {
        $cycleStart = $this->config->item('eu_cycle_start');
        $cycleEnd = $this->config->item('eu_cycle_end');
        $cycles = $this->config->item('eu_cycles');
      } else {
        $cycleStart = $this->config->item('na_cycle_start');
        $cycleEnd = $this->config->item('na_cycle_end');
        $cycles = $this->config->item('na_cycles');
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
          if($value['name'] == $v['advertiser']) {
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
          if($value['name'] == $v['advertiser']) {
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
          if($value['name'] == $v['advertiser']) {
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
          if(explode("-", $value['name'], 2)[0] == ucwords($v['advertiser'])) {
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
          if(explode("-", $value['name'], 2)[0] == ucwords($v['advertiser'])) {
            if($v['source'] != "Booking Request" || $v['source'] != "Returning-BR") {
              @$data['advertisers'][$key]['leg_total'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
            }
          }
        }
        foreach ($data['pvp_leg'] as $k => $v) {
          if($value['name'] == $v['advertiser']) {
            if(strpos($v['faction'], 'BR') === false) {
              @$data['advertisers'][$key]['leg_total'] += floatval($v['pot']);
            }
          }
        }
        foreach ($data['mythic_plus_leg'] as $k => $v) {
          if($value['name'] == $v['advertiser']) {
            if(strpos($v['faction'], 'BR') === false) {
              @$data['advertisers'][$key]['leg_total'] += floatval($v['pot']);
            }
          }
        }
        foreach ($data['levelling_leg'] as $k => $v) {
          if($value['name'] == $v['advertiser']) {
            if(strpos($v['faction'], 'BR') === false) {
              @$data['advertisers'][$key]['leg_total'] += floatval($v['pot']);
            }
          }
        }
        foreach ($merged_all as $k => $v) {
          if($value['name'] == $v['advertiser']) {
            if(strpos($v['faction'], 'BR') == false) {
              @$data['advertisers'][$key]['lifetime_total'] += floatval(str_replace(',', '', $v['pot']));
            }
          }
        }
        foreach ($gtrack_all as $k => $v) {
          if(explode("-", $value['name'], 2)[0] == $v['advertiser']) {
            if($v['source'] != "Booking Request" || $v['source'] != "Returning-BR") {
              @$data['advertisers'][$key]['lifetime_total'] += floatval(str_replace(',', '', $v['pot'])) * 1000;
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
      print_r(json_encode($data));
    }

    private function whatRole($search) {
      if (array_search("635189021278076949", $search) !== false) {
        return "Legendary Advertiser";
      }
      if (array_search("532629304853528607", $search) !== false) {
        return "Elite Advertiser";
      }
      if(array_search("444436643139682304", $search) !== false) {
        return "Senior Advertiser";
      }
      if (array_search("459132062671306762", $search) !== false) {
        return "Junior Advertiser";
      }
      if (array_search("629311720317255691", $search) !== false) {
        return "Trainee Advertiser";
      }
      return "N/A";
    }

    public function advertisersEU($key = '') {
      error_reporting(0);
      ini_set('display_errors', 0);
      if($key != "0858341946") {
        die("Wrong Key");
      }
      echo "<pre>";
      $snowflake = 0;
      $players = [];
      $role = '461839226581942276';
      $message = [];
      $going = true;
      while ($going) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/guilds/443203800728207371/members?limit=1000&after=$snowflake");
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bot " . $this->config->item('DISCORD_BOT_TOKEN');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close ($ch);

        if($result) {
          foreach ($result as $key => $value) {
            if (count($result) < 1000) {
              $going = false;
            }
            if(array_search($role, $value->roles) !== false) {
              echo trim($this->remove_emoji(preg_replace('/\|(.*?)\|/', "", $value->nick))) . "," . $this->whatRole($value->roles) . "\n";
            }
          }
          $snowflake = $result[999]->user->id;
        }
      }
    }

    public function advertisersEUList($key = '') {
      error_reporting(0);
      ini_set('display_errors', 0);
      if($key != "0858341946") {
        die("Wrong Key");
      }

      $snowflake = 0;
      $players = [];
      $role = '461839226581942276';
      $message = [];
      $going = true;
      while ($going) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/guilds/443203800728207371/members?limit=1000&after=$snowflake");
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bot " . $this->config->item('DISCORD_BOT_TOKEN');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close ($ch);

        if($result) {
          foreach ($result as $key => $value) {
            if (count($result) < 1000) {
              $going = false;
            }
            if(array_search($role, $value->roles) !== false) {
              $players[] = array(
                'name' => trim($this->remove_emoji(preg_replace('/\|(.*?)\|/', "", $value->nick))),
                'role' => $this->whatRole($value->roles),
                'region' => 'EU',
              );
            }
          }
          $snowflake = $result[999]->user->id;
        }
      }
      $this->db->where('region', 'EU');
      $this->db->delete('advertisers');
      $this->db->insert_batch('advertisers', $players);
      redirect($_SERVER['HTTP_REFERER']);
    }

    public function advertisersNAList($key = '') {
      error_reporting(0);
      ini_set('display_errors', 0);
      if($key != "0858341946") {
        die("Wrong Key");
      }

      $snowflake = 0;
      $players = [];
      $role = '556622546351423498';
      $going = true;
      while ($going) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/guilds/443203800728207371/members?limit=1000&after=$snowflake");
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bot " . $this->config->item('DISCORD_BOT_TOKEN');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close ($ch);

        if($result) {
          foreach ($result as $key => $value) {
            if (count($result) < 1000) {
              $going = false;
            }
            if(array_search($role, $value->roles) !== false) {
              $players[] = array(
                'name' => trim($this->remove_emoji(preg_replace('/\|(.*?)\|/', "", $value->nick))),
                'role' => $this->whatRole($value->roles),
                'region' => 'NA',
              );
            }
          }
          $snowflake = $result[999]->user->id;
        }
      }

      $this->db->where('region', 'NA');
      $this->db->delete('advertisers');
      $this->db->insert_batch('advertisers', $players);
    }

    public function fetchMPlus($startPoint, $key = '') {
      if($key != "0361093863") {
        die("Wrong Key");
      }
      $snowflake = '';
      $allRuns = [];
      for ($i=0; $i < 10; $i++) {
        $ch = curl_init();
        if($i==0) {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/506976442358169611/messages?after=$startPoint");
        } else {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/506976442358169611/messages?after=$snowflake");
        }
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bot " . $this->config->item('DISCORD_BOT_TOKEN');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);
        $numItems = count($result);

        foreach ($result as $key => $value) {
          if($key == 0) {
            @$snowflake = $result[$key]->id;
          }
          $runs = explode("\n", $value->content);
          foreach ($runs as $key => $r) {
            if(count($runs) > 4) {
              if($key < 11) {
                $r = preg_replace("/http:\/\/prntscr.com.*/", '', $r);
                $r = preg_replace("/https:\/\/gyazo.com.*/", '', $r);
                $runs[$key] = trim($r);
              } else {
                unset($runs[$key]);
              }

              if(@empty($runs[10])) {
                $runs[10] = $this->generateRandomString();
              } else {
                if(strpos($runs[10], 'High') !== false) {
                  $runs[10] = $this->generateRandomString();
                }
              }
            } else {
              unset($runs[$key]);
            }
          }
          $allRuns[] = implode(',', $runs);
        }
      }
      $allRuns = array_filter($allRuns);
      echo "Total Runs: " . count($allRuns);
      echo '<pre>';
      foreach ($allRuns as $key => $allRun) {
        echo $allRun . "\n";
      }
    }

    public function fetchMPlusNA($startPoint, $key = '') {
      if($key != "0361093863") {
        die("Wrong Key");
      }
      $snowflake = '';
      $allRuns = [];
      for ($i=0; $i < 10; $i++) {
        $ch = curl_init();
        if($i==0) {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/556623015069089816/messages?after=$startPoint");
        } else {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/556623015069089816/messages?after=$snowflake");
        }
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bot " . $this->config->item('DISCORD_BOT_TOKEN');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);
        $numItems = count($result);

        foreach ($result as $key => $value) {
          if($key == 0) {
            @$snowflake = $result[$key]->id;
          }
          $runs = explode("\n", $value->content);
          foreach ($runs as $key => $r) {
            if(count($runs) > 4) {
              if($key < 11) {
                $r = preg_replace("/http:\/\/prntscr.com.*/", '', $r);
                $r = preg_replace("/https:\/\/gyazo.com.*/", '', $r);
                $runs[$key] = trim($r);
              } else {
                unset($runs[$key]);
              }

              if(@empty($runs[10])) {
                $runs[10] = $this->generateRandomString();
              } else {
                if(strpos($runs[10], 'High') !== false) {
                  $runs[10] = $this->generateRandomString();
                }
              }
            } else {
              unset($runs[$key]);
            }
          }
          $allRuns[] = implode(';', $runs);
        }
      }
      $allRuns = array_filter($allRuns);
      echo "Total Runs: " . count($allRuns);
      echo '<pre>';
      foreach ($allRuns as $key => $allRun) {
        echo $allRun . "\n";
      }
    }

    public function fetchLevelling($startPoint, $key = '') {
      if($key != "0361093863") {
        die("Wrong Key");
      }
      $snowflake = '';
      $allRuns = [];
      for ($i=0; $i < 10; $i++) {
        $ch = curl_init();
        if($i==0) {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/519126073255133194/messages?after=$startPoint");
        } else {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/519126073255133194/messages?after=$snowflake");
        }
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bot " . $this->config->item('DISCORD_BOT_TOKEN');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);
        $numItems = count($result);

        foreach ($result as $key => $value) {
          if($key == 0) {
            @$snowflake = $result[$key]->id;
          }
          $runs = explode("\n", $value->content);
          foreach ($runs as $key => $r) {
            if(count($runs) > 4) {
              if($key < 8) {
                $r = preg_replace("/http:\/\/prntscr.com.*/", '', $r);
                $runs[$key] = trim($r);
              } else {
                unset($runs[$key]);
              }
              if(@empty($runs[8])) {
                $runs[8] = $this->generateRandomString();
              }
            } else {
              unset($runs[$key]);
            }
          }
          $allRuns[] = implode(',', $runs);
        }
      }
      $allRuns = array_filter($allRuns);
      echo "Total Runs: " . count($allRuns);
      echo '<pre>';
      foreach ($allRuns as $key => $allRun) {
        echo $allRun . "\n";
      }
    }

    public function fetchLevellingNA($startPoint, $key = '') {
      if($key != "0361093863") {
        die("Wrong Key");
      }
      $snowflake = '';
      $allRuns = [];
      for ($i=0; $i < 10; $i++) {
        $ch = curl_init();
        if($i==0) {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/556628846741553174/messages?after=$startPoint");
        } else {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/556628846741553174/messages?after=$snowflake");
        }
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bot " . $this->config->item('DISCORD_BOT_TOKEN');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);
        $numItems = count($result);

        foreach ($result as $key => $value) {
          if($key == 0) {
            @$snowflake = $result[$key]->id;
          }
          $runs = explode("\n", $value->content);
          foreach ($runs as $key => $r) {
            if(count($runs) > 4) {
              if($key < 8) {
                $r = preg_replace("/http:\/\/prntscr.com.*/", '', $r);
                $runs[$key] = trim($r);
              } else {
                unset($runs[$key]);
              }
              if(@empty($runs[8])) {
                $runs[8] = $this->generateRandomString();
              }
            } else {
              unset($runs[$key]);
            }
          }
          $allRuns[] = implode(';', $runs);
        }
      }
      $allRuns = array_filter($allRuns);
      echo "Total Runs: " . count($allRuns);
      echo '<pre>';
      foreach ($allRuns as $key => $allRun) {
        echo $allRun . "\n";
      }
    }

    public function fetchPvP($startPoint, $key = '') {
      if($key != "0361093863") {
        die("Wrong Key");
      }
      $snowflake = '';
      $allRuns = [];
      for ($i=0; $i < 10; $i++) {
        $ch = curl_init();
        if($i==0) {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/533002875488698408/messages?after=$startPoint");
        } else {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/533002875488698408/messages?after=$snowflake");
        }
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bot " . $this->config->item('DISCORD_BOT_TOKEN');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);
        $numItems = count($result);

        foreach ($result as $key => $value) {
          if($key == 0) {
            @$snowflake = $result[$key]->id;
          }
          $runs = explode("\n", $value->content);
          foreach ($runs as $key => $r) {
            if(count($runs) > 4) {
              if($key < 8) {
                $r = preg_replace("/http:\/\/prntscr.com.*/", '', $r);
                $runs[$key] = trim($r);
              } else {
                unset($runs[$key]);
              }
              if(@empty($runs[8])) {
                $runs[8] = $this->generateRandomString();
              }
            } else {
              unset($runs[$key]);
            }
          }
          $allRuns[] = implode(',', $runs);
        }
      }
      $allRuns = array_unique(array_filter($allRuns));
      echo "Total Runs: " . count($allRuns);
      echo '<pre>';
      foreach ($allRuns as $key => $allRun) {
        echo $allRun . "\n";
      }
    }

    public function fetchPvPNA($startPoint, $key = '') {
      if($key != "0361093863") {
        die("Wrong Key");
      }
      $snowflake = '';
      $allRuns = [];
      for ($i=0; $i < 10; $i++) {
        $ch = curl_init();
        if($i==0) {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/556615502965899274/messages?after=$startPoint");
        } else {
          curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/channels/556615502965899274/messages?after=$snowflake");
        }
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bot " . $this->config->item('DISCORD_BOT_TOKEN');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);
        $numItems = count($result);

        foreach ($result as $key => $value) {
          if($key == 0) {
            @$snowflake = $result[$key]->id;
          }
          $runs = explode("\n", $value->content);
          foreach ($runs as $key => $r) {
            if(count($runs) > 4) {
              if($key < 8) {
                $r = preg_replace("/http:\/\/prntscr.com.*/", '', $r);
                $runs[$key] = trim($r);
              } else {
                unset($runs[$key]);
              }
              if(@empty($runs[8])) {
                $runs[8] = $this->generateRandomString();
              }
            } else {
              unset($runs[$key]);
            }
          }
          $allRuns[] = implode(';', $runs);
        }
      }
      $allRuns = array_unique(array_filter($allRuns));
      echo "Total Runs: " . count($allRuns);
      echo '<pre>';
      foreach ($allRuns as $key => $allRun) {
        echo $allRun . "\n";
      }
    }

    private function remove_emoji($text) {
      return preg_replace('/[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0077}\x{E006C}\x{E0073}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0073}\x{E0063}\x{E0074}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0065}\x{E006E}\x{E0067}\x{E007F})|[\x{1F3F4}](?:\x{200D}\x{2620}\x{FE0F})|[\x{1F3F3}](?:\x{FE0F}\x{200D}\x{1F308})|[\x{0023}\x{002A}\x{0030}\x{0031}\x{0032}\x{0033}\x{0034}\x{0035}\x{0036}\x{0037}\x{0038}\x{0039}](?:\x{FE0F}\x{20E3})|[\x{1F441}](?:\x{FE0F}\x{200D}\x{1F5E8}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F468})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F468})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B0})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2640}\x{FE0F})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FF}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FE}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FD}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FC}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FB}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F9B8}\x{1F9B9}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FF}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FE}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FD}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FC}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FB}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F9B8}\x{1F9B9}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{200D}\x{2642}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2695}\x{FE0F})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FF})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FE})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FD})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FC})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FB})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FA}](?:\x{1F1FF})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1FA}](?:\x{1F1FE})|[\x{1F1E6}\x{1F1E8}\x{1F1F2}\x{1F1F8}](?:\x{1F1FD})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F9}\x{1F1FF}](?:\x{1F1FC})|[\x{1F1E7}\x{1F1E8}\x{1F1F1}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1FB})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1FB}](?:\x{1F1FA})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FE}](?:\x{1F1F9})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FA}\x{1F1FC}](?:\x{1F1F8})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F7})|[\x{1F1E6}\x{1F1E7}\x{1F1EC}\x{1F1EE}\x{1F1F2}](?:\x{1F1F6})|[\x{1F1E8}\x{1F1EC}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}](?:\x{1F1F5})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EE}\x{1F1EF}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1F8}\x{1F1F9}](?:\x{1F1F4})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1F3})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F4}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FF}](?:\x{1F1F2})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F1})|[\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FD}](?:\x{1F1F0})|[\x{1F1E7}\x{1F1E9}\x{1F1EB}\x{1F1F8}\x{1F1F9}](?:\x{1F1EF})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EB}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F3}\x{1F1F8}\x{1F1FB}](?:\x{1F1EE})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1ED})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1EC})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F9}\x{1F1FC}](?:\x{1F1EB})|[\x{1F1E6}\x{1F1E7}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FB}\x{1F1FE}](?:\x{1F1EA})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1E9})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FB}](?:\x{1F1E8})|[\x{1F1E7}\x{1F1EC}\x{1F1F1}\x{1F1F8}](?:\x{1F1E7})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F6}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}\x{1F1FF}](?:\x{1F1E6})|[\x{00A9}\x{00AE}\x{203C}\x{2049}\x{2122}\x{2139}\x{2194}-\x{2199}\x{21A9}-\x{21AA}\x{231A}-\x{231B}\x{2328}\x{23CF}\x{23E9}-\x{23F3}\x{23F8}-\x{23FA}\x{24C2}\x{25AA}-\x{25AB}\x{25B6}\x{25C0}\x{25FB}-\x{25FE}\x{2600}-\x{2604}\x{260E}\x{2611}\x{2614}-\x{2615}\x{2618}\x{261D}\x{2620}\x{2622}-\x{2623}\x{2626}\x{262A}\x{262E}-\x{262F}\x{2638}-\x{263A}\x{2640}\x{2642}\x{2648}-\x{2653}\x{2660}\x{2663}\x{2665}-\x{2666}\x{2668}\x{267B}\x{267E}-\x{267F}\x{2692}-\x{2697}\x{2699}\x{269B}-\x{269C}\x{26A0}-\x{26A1}\x{26AA}-\x{26AB}\x{26B0}-\x{26B1}\x{26BD}-\x{26BE}\x{26C4}-\x{26C5}\x{26C8}\x{26CE}-\x{26CF}\x{26D1}\x{26D3}-\x{26D4}\x{26E9}-\x{26EA}\x{26F0}-\x{26F5}\x{26F7}-\x{26FA}\x{26FD}\x{2702}\x{2705}\x{2708}-\x{270D}\x{270F}\x{2712}\x{2714}\x{2716}\x{271D}\x{2721}\x{2728}\x{2733}-\x{2734}\x{2744}\x{2747}\x{274C}\x{274E}\x{2753}-\x{2755}\x{2757}\x{2763}-\x{2764}\x{2795}-\x{2797}\x{27A1}\x{27B0}\x{27BF}\x{2934}-\x{2935}\x{2B05}-\x{2B07}\x{2B1B}-\x{2B1C}\x{2B50}\x{2B55}\x{3030}\x{303D}\x{3297}\x{3299}\x{1F004}\x{1F0CF}\x{1F170}-\x{1F171}\x{1F17E}-\x{1F17F}\x{1F18E}\x{1F191}-\x{1F19A}\x{1F201}-\x{1F202}\x{1F21A}\x{1F22F}\x{1F232}-\x{1F23A}\x{1F250}-\x{1F251}\x{1F300}-\x{1F321}\x{1F324}-\x{1F393}\x{1F396}-\x{1F397}\x{1F399}-\x{1F39B}\x{1F39E}-\x{1F3F0}\x{1F3F3}-\x{1F3F5}\x{1F3F7}-\x{1F3FA}\x{1F400}-\x{1F4FD}\x{1F4FF}-\x{1F53D}\x{1F549}-\x{1F54E}\x{1F550}-\x{1F567}\x{1F56F}-\x{1F570}\x{1F573}-\x{1F57A}\x{1F587}\x{1F58A}-\x{1F58D}\x{1F590}\x{1F595}-\x{1F596}\x{1F5A4}-\x{1F5A5}\x{1F5A8}\x{1F5B1}-\x{1F5B2}\x{1F5BC}\x{1F5C2}-\x{1F5C4}\x{1F5D1}-\x{1F5D3}\x{1F5DC}-\x{1F5DE}\x{1F5E1}\x{1F5E3}\x{1F5E8}\x{1F5EF}\x{1F5F3}\x{1F5FA}-\x{1F64F}\x{1F680}-\x{1F6C5}\x{1F6CB}-\x{1F6D2}\x{1F6E0}-\x{1F6E5}\x{1F6E9}\x{1F6EB}-\x{1F6EC}\x{1F6F0}\x{1F6F3}-\x{1F6F9}\x{1F910}-\x{1F93A}\x{1F93C}-\x{1F93E}\x{1F940}-\x{1F945}\x{1F947}-\x{1F970}\x{1F973}-\x{1F976}\x{1F97A}\x{1F97C}-\x{1F9A2}\x{1F9B0}-\x{1F9B9}\x{1F9C0}-\x{1F9C2}\x{1F9D0}-\x{1F9FF}]/u', '', $text);
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

    public function sheetToDB($key = '') {
      set_time_limit(1000);
      if($key == "importthisshit") {
        $this->fetchMplusDB();
        $this->fetchLevelingDB();
        $this->fetchPvPDB();
        $this->fetchGTackDB();
        $this->fetchBalance();
        $this->fetchOldBalance();
        $this->fetchManualEdits();
        $this->fetchRaids();
        $this->fetchGambling();
      }
    }

    private function fetchBalance()
    {
      $client = $this->getClient();
      $service = new Google_Service_Sheets($client);
      $importSheet = [];
      $spreadsheetId = '1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk';
      $range = 'Balance!C3:G';
      @$response = $service->spreadsheets_values->get($spreadsheetId, $range);
      $values = $response->getValues();

      $spreadsheetId2 = '1199viuHN-DhUNglHaPGYwiCXVsTtdEC7r_kAN3QL_DM';
      $range = 'Balance!C3:F';
  		@$response2 = $service->spreadsheets_values->get($spreadsheetId2, $range);
  		$values2 = $response2->getValues();

      if (empty($values)) {
        print "No data found.\n";
      } else {
        foreach ($values as $row) {
          if(@$row[0]) {
            $importSheet[] = array(
              'name' => @$row[0],
              'balance' => @$row[4],
              'faction' => @$row[2],
              'realm' => @$row[1],
              'region' => 'eu',
            );
          }
        }
      }

      if (empty($values2)) {
        print "No data found.\n";
      } else {
        foreach ($values2 as $row) {
          if(@$row[0]) {
            $importSheet[] = array(
              'name' => @$row[0],
              'realm' => @$row[1],
              'faction' => "",
              'balance' => @$row[3],
              'region' => 'na',
            );
          }
        }
      }
      $this->db->truncate('balance');
      $this->db->insert_batch('balance', $importSheet);
    }

    private function fetchOldBalance()
    {
      $client = $this->getClient();
      $service = new Google_Service_Sheets($client);
      $importSheet = [];
      $spreadsheetId = '1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk';
      $range = 'Past';
      @$response = $service->spreadsheets_values->get($spreadsheetId, $range);
      $values = $response->getValues();

      // $spreadsheetId2 = '1199viuHN-DhUNglHaPGYwiCXVsTtdEC7r_kAN3QL_DM';
      // $range = 'PreviousCycle.Balance!C3:F';
  		// @$response2 = $service->spreadsheets_values->get($spreadsheetId2, $range);
  		// $values2 = $response2->getValues();

      if (empty($values)) {
        print "No data found.\n";
      } else {
        foreach ($values as $row) {
          if(@$row[0]) {
            $importSheet[] = array(
              'name' => @$row[0],
              'balance' => @$row[3],
              'region' => 'eu',
            );
          }
        }
      }

      // if (empty($values2)) {
      //   print "No data found.\n";
      // } else {
      //   foreach ($values2 as $row) {
      //     if(@$row[0]) {
      //       $importSheet[] = array(
      //         'name' => @$row[0],
      //         'balance' => @$row[3],
      //         'region' => 'na',
      //       );
      //     }
      //   }
      // }
      $this->db->truncate('old_balance');
      $this->db->insert_batch('old_balance', $importSheet);
    }

    private function fetchManualEdits()
    {
      $client = $this->getClient();
      $service = new Google_Service_Sheets($client);
      $importSheet = [];
      $importSheet_na = [];
      $spreadsheetId = '1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk';
      $range = 'Balance!M3:Q';
      @$response = $service->spreadsheets_values->get($spreadsheetId, $range);
      $values = $response->getValues();

      $spreadsheetId2 = '1199viuHN-DhUNglHaPGYwiCXVsTtdEC7r_kAN3QL_DM';
      $range = 'Balance!L3:P';
  		@$response2 = $service->spreadsheets_values->get($spreadsheetId2, $range);
  		$values2 = $response2->getValues();

      if (empty($values)) {
        print "No data found.\n";
      } else {
        foreach ($values as $row) {
          if(@$row[0]) {
            $importSheet[] = array(
              'name' => @$row[0],
              'amount' => @$row[1],
              'date' => @$row[2],
              'reason' => @$row[3],
              'amended_by' => @$row[4],
              'region' => 'eu',
            );
          }
        }
      }

      if (empty($values2)) {
        print "No data found.\n";
      } else {
        foreach ($values2 as $row) {
          if(@$row[0]) {
            $importSheet_na[] = array(
              'name' => @$row[0],
              'amount' => @$row[1],
              'date' => @$row[2],
              'reason' => @$row[3],
              'amended_by' => @$row[4],
              'region' => 'na',
            );
          }
        }
      }
      $this->db->where("DATE(CONCAT('2020','-',SUBSTRING_INDEX(date,'/',-1),'-',SUBSTRING_INDEX(date,'/', 1))) BETWEEN '".$this->config->item('eu_cycle_start')."' AND '".$this->config->item('eu_cycle_end')."' AND region = 'eu'");
      $this->db->delete('manual_edits');
      if($importSheet) {
        $this->db->insert_batch('manual_edits', $importSheet);
      }
      $this->db->where("DATE(CONCAT('2020','-',SUBSTRING_INDEX(date,'/',-1),'-',SUBSTRING_INDEX(date,'/', 1))) BETWEEN '".$this->config->item('na_cycle_start')."' AND '".$this->config->item('na_cycle_end')."' AND region = 'na'");
      $this->db->delete('manual_edits');
      if($importSheet_na) {
        $this->db->insert_batch('manual_edits', $importSheet_na);
      }
    }

    public function fetchGambling()
    {
      $client = $this->getClient();
      $service = new Google_Service_Sheets($client);
      $importSheet = [];
      $importSheet_na = [];
      $spreadsheetId = '1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk';
      $range = 'Gambling!B2:D';
      @$response = $service->spreadsheets_values->get($spreadsheetId, $range);
      $values = $response->getValues();

      $spreadsheetId2 = '1199viuHN-DhUNglHaPGYwiCXVsTtdEC7r_kAN3QL_DM';
      $range = 'Gambling!A2:D';
  		@$response2 = $service->spreadsheets_values->get($spreadsheetId2, $range);
  		$values2 = $response2->getValues();

      if (empty($values)) {
        print "No data found.\n";
      } else {
        foreach ($values as $key => $row) {
          if(@$row[1]) {
            $importSheet[] = array(
              'name' => $row[0],
              'amount' => str_replace(',', '', $row[1]),
              'runId' => $row[2],
              'region' => 'eu',
            );
          }
        }
      }

      if (empty($values2)) {
        print "No data found.\n";
      } else {
        foreach ($values2 as $key => $row) {
          if(@$row[1]) {
            $importSheet_na[] = array(
              'name' => $row[0],
              'amount' => str_replace(',', '', $row[1]),
              'runId' => $row[2],
              'region' => 'na',
            );
          }
        }
      }

      $this->db->where("date BETWEEN '".$this->config->item('eu_cycle_start')."' AND '".$this->config->item('eu_cycle_end')."' AND region = 'eu'");
      $this->db->delete('gambling');
      if($importSheet) {
        $this->db->insert_batch('gambling', $importSheet);
      }

      $this->db->where("date BETWEEN '".$this->config->item('na_cycle_start')."' AND '".$this->config->item('na_cycle_end')."' AND region = 'na'");
      $this->db->delete('gambling');
      if($importSheet_na) {
        $this->db->insert_batch('gambling', $importSheet_na);
      }
    }

    private function fetchMplusDB()
    {
      $client = $this->getClient();
      $service = new Google_Service_Sheets($client);
      $importSheet = [];
      $spreadsheetId = '1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk';
      $range = 'M+!B2:M';
      @$response = $service->spreadsheets_values->get($spreadsheetId, $range);
      $values = $response->getValues();

      $spreadsheetId2 = '1199viuHN-DhUNglHaPGYwiCXVsTtdEC7r_kAN3QL_DM';
      $range = 'M+!B2:N';
      @$response2 = $service->spreadsheets_values->get($spreadsheetId2, $range);
      $values2 = $response2->getValues();

      if (empty($values)) {
        print "No data found.\n";
      } else {
        foreach ($values as $key => $row) {
          if(@$row[1]) {
            $importSheet[] = array(
              'collected' => "FALSE",
              'date' => $row[0],
              'pot' => str_replace(',', '', $row[1]),
              'ad_cut' => $row[2],
              'faction' => $row[3],
              'booster_1' => $row[4],
              'booster_2' => $row[5],
              'booster_3' => $row[6],
              'booster_4' => $row[7],
              'advertiser' => $row[8],
              'realm' => @$row[9],
              'id_from_sheet' => @$row[10],
              'booster_cut' => @$row[11],
              'region' => 'eu',
            );
          }
        }
      }

      if (empty($values2)) {
        print "No data found.\n";
      } else {
        foreach ($values2 as $key => $row) {
          if(@$row[1]) {
            $importSheet[] = array(
              'collected' => @$row[0],
              'date' => $row[1],
              'pot' => str_replace(',', '', $row[2]),
              'ad_cut' => $row[3],
              'faction' => $row[4],
              'booster_1' => $row[5],
              'booster_2' => $row[6],
              'booster_3' => $row[7],
              'booster_4' => $row[8],
              'advertiser' => $row[9],
              'realm' => @$row[10],
              'id_from_sheet' => @$row[11],
              'booster_cut' => @$row[12],
              'region' => 'na',
            );
          }
        }
      }
      $currentData = $this->db->get('mythic_plus')->result_array();
      foreach($importSheet as $k => $im) {
          $key = array_search($im['id_from_sheet'], array_column($currentData, 'id_from_sheet'));
          if($key !== false) {
            unset($importSheet[$k]);
          }
      }
      $addThis = $importSheet;
      if($addThis) {
        $this->db->insert_batch('mythic_plus', $addThis);
      }
    }

    public function fetchGTackDB()
    {
      set_time_limit(100);
      $client = $this->getClient();
      $service = new Google_Service_Sheets($client);
      $importSheet = [];
      $spreadsheetId = '1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk';
      $range = 'GOLD TRACK!B3:M';
      @$response = $service->spreadsheets_values->get($spreadsheetId, $range);
      $values = $response->getValues();

      $spreadsheetId2 = '1199viuHN-DhUNglHaPGYwiCXVsTtdEC7r_kAN3QL_DM';
  		@$response2 = $service->spreadsheets_values->get($spreadsheetId2, $range);
  		$values2 = $response2->getValues();

      if (empty($values)) {
        print "No data found.\n";
      } else {
        foreach ($values as $key => $row) {
          if(@$row[0]) {
            $importSheet[] = array(
              'collected' => "FALSE",
              'date' => @$row[0],
              'realm' => @$row[1],
              'faction' => @$row[2],
              'pot' => @$row[3],
              'ad_cut' => @$row[4],
              'boost_type' => @$row[6],
              'gold_collector' => $row[7],
              'advertiser' => @$row[8],
              'source' => $row[9],
              'id_from_sheet' => @$row[10],
              'region' => 'eu',
            );
          }
        }
      }

      if (empty($values2)) {
        print "No data found.\n";
      } else {
        foreach ($values2 as $key => $row) {
          if(@$row[1]) {
            $importSheet[] = array(
              'collected' => @$row[0],
              'date' => @$row[1],
              'realm' => @$row[2],
              'faction' => @$row[3],
              'pot' => @$row[4],
              'ad_cut' => @$row[5],
              'boost_type' => @$row[7],
              'gold_collector' => $row[8],
              'advertiser' => @$row[10],
              'source' => $row[9],
              'id_from_sheet' => @$row[11],
              'region' => 'na',
            );
          }
        }
      }
      $currentData = $this->db->get('gtrack')->result_array();
      foreach($importSheet as $k => $im) {
          $key = array_search($im['id_from_sheet'], array_column($currentData, 'id_from_sheet'));
          if($key !== false) {
            unset($importSheet[$k]);
          }
      }
      $addThis = $importSheet;
      if($addThis) {
        $this->db->insert_batch('gtrack', $addThis);
      }
    }

    private function fetchLevelingDB()
  	{
  		$client = $this->getClient();
  		$service = new Google_Service_Sheets($client);
      $importSheet = [];
  		$spreadsheetId = '1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk';
  		$range = 'Leveling!B2:K';
  		@$response = $service->spreadsheets_values->get($spreadsheetId, $range);
  		$values = $response->getValues();

      $spreadsheetId2 = '1199viuHN-DhUNglHaPGYwiCXVsTtdEC7r_kAN3QL_DM';
      $range = 'Leveling!B2:L';
  		@$response2 = $service->spreadsheets_values->get($spreadsheetId2, $range);
  		$values2 = $response2->getValues();

  		if (empty($values)) {
  			print "No data found.\n";
  		} else {
  			foreach ($values as $key => $row) {
          if(@$row[1]) {
    				$importSheet[] = array(
              'collected' => "FALSE",
              'date' => $row[0],
              'pot' => str_replace(',', '', $row[1]),
              'ad_cut' => $row[2],
              'faction' => $row[3],
              'booster_1' => $row[4],
              'booster_2' => $row[5],
              'advertiser' => $row[6],
              'realm' => $row[7],
              'id_from_sheet' => @$row[8],
              'booster_cut' => @$row[9],
              'region' => 'eu',
            );
          }
  			}
  		}

      if (empty($values2)) {
  			print "No data found.\n";
  		} else {
  			foreach ($values2 as $key => $row) {
          if(@$row[1]) {
    				$importSheet[] = array(
              'collected' => $row[0],
              'date' => $row[1],
              'pot' => str_replace(',', '', $row[2]),
              'ad_cut' => $row[3],
              'faction' => $row[4],
              'booster_1' => $row[5],
              'booster_2' => $row[6],
              'advertiser' => $row[7],
              'realm' => $row[8],
              'id_from_sheet' => @$row[9],
              'booster_cut' => @$row[10],
              'region' => 'na',
            );
          }
  			}
  		}
      $currentData = $this->db->get('levelling')->result_array();
      foreach($importSheet as $k => $im) {
          $key = array_search($im['id_from_sheet'], array_column($currentData, 'id_from_sheet'));
          if($key !== false) {
            unset($importSheet[$k]);
          }
      }
      $addThis = $importSheet;
      if($addThis) {
        $this->db->insert_batch('levelling', $addThis);
      }
  	}

    private function fetchPvPDB()
  	{
      $values2 = [];
  		$client = $this->getClient();
  		$service = new Google_Service_Sheets($client);
      $importSheet = [];
  		$spreadsheetId = '1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk';
  		$range = 'PvP!B2:K';
  		@$response = $service->spreadsheets_values->get($spreadsheetId, $range);
  		$values = $response->getValues();

  		$spreadsheetId2 = '1199viuHN-DhUNglHaPGYwiCXVsTtdEC7r_kAN3QL_DM';
      $range = 'PvP!B2:L';
  		@$response2 = $service->spreadsheets_values->get($spreadsheetId2, $range);
  		$values2 = $response2->getValues();

  		if (empty($values)) {
  			print "No data found.\n";
  		} else {
  			foreach ($values as $key => $row) {
          if(@$row[1]) {
    				$importSheet[] = array(
              'collected' => "FALSE",
              'date' => $row[0],
              'pot' => str_replace(',', '', $row[1]),
              'ad_cut' => $row[2],
              'faction' => $row[3],
              'booster_1' => $row[4],
              'booster_2' => $row[5],
              'advertiser' => $row[6],
              'realm' => $row[7],
              'id_from_sheet' => @$row[8],
              'booster_cut' => @$row[9],
              'region' => 'eu',
            );
          }
  			}
  		}

      if (empty($values2)) {
  			print "No data found.\n";
  		} else {
  			foreach ($values2 as $key => $row) {
          if(@$row[1]) {
    				$importSheet[] = array(
              'collected' => $row[0],
              'date' => $row[1],
              'pot' => str_replace(',', '', $row[2]),
              'ad_cut' => $row[3],
              'faction' => $row[4],
              'booster_1' => $row[5],
              'booster_2' => $row[7],
              'advertiser' => $row[7],
              'realm' => $row[8],
              'id_from_sheet' => @$row[9],
              'booster_cut' => @$row[10],
              'region' => 'na',
            );
          }
  			}
  		}
      $currentData = $this->db->get('pvp')->result_array();
      foreach($importSheet as $k => $im) {
          $key = array_search($im['id_from_sheet'], array_column($currentData, 'id_from_sheet'));
          if($key !== false) {
            unset($importSheet[$k]);
          }
      }
      $addThis = $importSheet;
      if($addThis) {
        $this->db->insert_batch('pvp', $addThis);
      }
  	}

    public function fetchRaids()
  	{
  		$client = $this->getClient();
  		$service = new Google_Service_Sheets($client);

  		$spreadsheetId = '1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk';
  		$range = 'Runs/Attendance!D2:37';
  		$params = array(
  			'majorDimension' => "COLUMNS"
  		);
  		@$response = $service->spreadsheets_values->get($spreadsheetId, $range, $params);
  		$values = $response->getValues();

  		$spreadsheetId2 = '1199viuHN-DhUNglHaPGYwiCXVsTtdEC7r_kAN3QL_DM';
  		@$response2 = $service->spreadsheets_values->get($spreadsheetId2, $range, $params);
  		$values2 = $response2->getValues();

      // if(@$values2) {
      //   @$values = array_merge($values, $values2);
      // }
      $return = [];
  		$return_na = [];
  		if (empty($values)) {
  			print "No data found.\n";
  		} else {
  			foreach ($values as $key => $row) {
          if(@$row[4]) {
    				@$boosters = 		@$row[10] . ","
    												. @$row[11] . ","
    												. @$row[12] . ","
    												. @$row[13] . ","
    												. @$row[14] . ","
    												. @$row[15] . ","
    												. @$row[16] . ","
    												. @$row[17] . ","
    												. @$row[18] . ","
    												. @$row[19] . ","
    												. @$row[20] . ","
    												. @$row[21] . ","
    												. @$row[22] . ","
    												. @$row[23] . ","
    												. @$row[24] . ","
    												. @$row[25] . ","
    												. @$row[26] . ","
    												. @$row[27] . ","
    												. @$row[28] . ","
    												. @$row[29] . ","
    												. @$row[30] . ","
    												. @$row[31] . ","
    												. @$row[32] . ","
    												. @$row[33] . ","
    												. @$row[34] . ",";
    				$boosters = implode(',', array_filter(explode(',', $boosters)));
            if($boosters) {
      				$raid = array(
      				  'date' => preg_replace('/^\p{Z}+|\p{Z}+$/u', '', explode("y", $row[0])[1]),
      					'type' => $row[1],
      					'faction' => $row[2],
      					'pot' => $row[4],
      					'expense' => $row[5],
      					'boosters' => $boosters,
      					'booster_cut' => $row[6],
      					'leader_cut' => @$row[7],
      					'gc' => @$row[35],
                'gc_total' => @$values[$key+1][35],
      					'region' => "eu",
      				);
            }
    				$return[] = $raid;
  			  }
        }
  		}

      if (empty($values2)) {
  			print "No data found.\n";
  		} else {
  			foreach ($values2 as $key => $row) {
          if(@$row[4]) {
    				@$boosters = 		@$row[10] . ","
    												. @$row[11] . ","
    												. @$row[12] . ","
    												. @$row[13] . ","
    												. @$row[14] . ","
    												. @$row[15] . ","
    												. @$row[16] . ","
    												. @$row[17] . ","
    												. @$row[18] . ","
    												. @$row[19] . ","
    												. @$row[20] . ","
    												. @$row[21] . ","
    												. @$row[22] . ","
    												. @$row[23] . ","
    												. @$row[24] . ","
    												. @$row[25] . ","
    												. @$row[26] . ","
    												. @$row[27] . ","
    												. @$row[28] . ","
    												. @$row[29] . ","
    												. @$row[30] . ","
    												. @$row[31] . ","
    												. @$row[32] . ","
    												. @$row[33] . ","
    												. @$row[34] . ",";
    				$boosters = implode(',', array_filter(explode(',', $boosters)));
            if($boosters) {
      				$raid = array(
      				  'date' => preg_replace('/^\p{Z}+|\p{Z}+$/u', '', explode("y", $row[0])[1]),
      					'type' => $row[1],
      					'faction' => $row[2],
      					'pot' => $row[4],
      					'expense' => $row[5],
      					'boosters' => $boosters,
      					'booster_cut' => $row[6],
      					'leader_cut' => @$row[7],
      					'gc' => @$row[35],
                'gc_total' => @$values[$key+1][35],
      					'region' => "na",
      				);
            }
    				$return_na[] = $raid;
  			  }
        }
  		}

      $this->db->where("DATE(CONCAT('2020','-',SUBSTRING_INDEX(date,'/',-1),'-',SUBSTRING_INDEX(date,'/', 1))) BETWEEN '".$this->config->item('eu_cycle_start')."' AND '".$this->config->item('eu_cycle_end')."' AND region = 'eu'");
      $this->db->delete('raids');
      $this->db->insert_batch('raids', $return);

      $this->db->where("DATE(CONCAT('2020','-',SUBSTRING_INDEX(date,'/',-1),'-',SUBSTRING_INDEX(date,'/', 1))) BETWEEN '".$this->config->item('na_cycle_start')."' AND '".$this->config->item('na_cycle_end')."' AND region = 'na'");
      $this->db->delete('raids');
      $this->db->insert_batch('raids', $return_na);
  	}

    private function unique_multi_array($array, $key) {
      $temp_array = array();
      $i = 0;
      $key_array = array();

      foreach($array as $val) {
          if (!in_array($val[$key], $key_array)) {
              $key_array[$i] = $val[$key];
              $temp_array[$i] = $val;
          }
          $i++;
      }
      return $temp_array;
    }

    public function fetchSchedule()
    {
      $client = $this->getClient();
      $service = new Google_Service_Sheets($client);
      $importSheet = [];
      $spreadsheetId = '1x5L7jHt_e_Yh_Rdv52OC9uBoJt4h33Qf03iw87RvxQk';
      $range = 'Schedule!C:J';
      $params = array(
  			'majorDimension' => "COLUMNS"
  		);
      echo "<pre>";
      @$response = $service->spreadsheets_values->get($spreadsheetId, $range, $params);
      $values = $response->getValues();
      $raids = [];
      foreach($values as $key => $v) {
        if($key == 0) {
          foreach($v as $keyTime => $time) {
            if(strpos($time, ':') !== false) {
              for ($i=1; $i < count($values); $i++) {
                if(@$values[$i][$keyTime]) {
                  $guildName = '';
                  $faction = '';
                  if(strpos($values[$i][$keyTime], ':') !== false) {
                    $guildName = substr($values[$i][$keyTime], 6);
                    $time = substr($values[$i][$keyTime], 0, 5);
                  } else {
                    $guildName = $values[$i][$keyTime];
                    $time = $time;
                  }
                  if($keyTime < 14) {
                    $faction = "Horde";
                  } else {
                    $faction = "Alliance";
                  }
                  $raids[] = array(
                    'guild_name' => trim($guildName),
                    'time' => $time,
                    'day' => strtolower($values[$i][0]),
                    'faction' => $faction,
                  );
                }
              }
            }
          }
        }
      }
      $hallOfFame = json_decode(file_get_contents("https://raider.io/api/v1/raiding/raid-rankings?raid=Ny'alotha%2C%20the%20Waking%20City&difficulty=mythic&region=world"))->raidRankings;

      function searchForGuild($name, $array) {
         foreach ($array as $key => $val) {
             if ($val->guild->name === $name) {
                 return $key;
             }
         }
         return null;
      }

      $fields = [];
      $newRaids = [];
      foreach ($raids as $key => $raid) {
        $newRaids[$raid['day']][] = $raid;
      }

      foreach ($newRaids as $key => $nRaid) {
          $string = "";
          foreach ($newRaids[$key] as $nRaid) {
            if($nRaid['guild_name']) {
              if($nRaid['faction'] == "Alliance") {
                $emote = "<:Alliance:624194514449334282>";
              } else {
                $emote = "<:Horde:624194543188967424>";
              }
              $guildFound = searchForGuild($nRaid['guild_name'], $hallOfFame);
              if($guildFound) {
                $worldRaking = "(WR#" . $hallOfFame[$guildFound]->rank . ") ";
              } else {
                $worldRaking = "";
              }
              $string .= $emote . " <" . $nRaid['guild_name'] . "> " . $worldRaking . $nRaid['time'] . "\n";
            }
          }
          $fields[] = array(
            "name" => ucwords($key),
            "value" => $string,
            "inline" => true,
          );
      }


      $timestamp = date("c", strtotime("now"));
      $json_data = json_encode([
        "embeds" => [
            [
                "title" => "The Gallywix Boosting Community - Raid Schedule",
                "type" => "rich",
                "description" => "If you are interested in any of these runs, head to <#444773078052896778>!",
                "timestamp" => $timestamp,
                "color" => hexdec("e7b657"),
                "fields" => $fields
            ]
        ]

    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    $ch = curl_init("https://discordapp.com/api/webhooks/695579405442875483/_HajQqFjkHVujmVC-AREa9dpRSq887A7nDu3d2gdmbFXyOQkPX5WybQ75XvT5oTX0tEQ");
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    $this->db->truncate('schedule');
    $this->db->insert_batch('schedule', $raids);
    }
}

?>
