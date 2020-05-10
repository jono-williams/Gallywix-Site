<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require BASEPATH . '../vendor/autoload.php';

class Goldcollecting extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct(){
    parent::__construct();
		$this->load->library('session');
    $this->load->helper('url');
	}

	private function checkServiceAccountCredentialsFile()
	{
		$application_creds = __DIR__ . '/../../service-account-credentials.json';
		return file_exists($application_creds) ? $application_creds : false;
	}

	private function getClient() {
		$client = new Google_Client();
		if ($credentials_file = $this->checkServiceAccountCredentialsFile()) {
			$client->setAuthConfig($credentials_file);
		} elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
			$client->useApplicationDefaultCredentials();
		} else {
			return;
		}

		$client->setApplicationName("Gallywix Balance Fetch");
		$client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
		$client->setAccessType('offline');
		return $client;
	}

	public function goldLeftPerRealm($key='', $region = 'EU') {
		if($key != 'notasmellytools') {
			return;
		}
		$this->db->select('date, pot, ad_cut, advertiser, mythic_plus.realm, id, id_from_sheet, collected, "Mplus" as type, realm_gwix.wow_account');
		$this->db->from('mythic_plus');
		$this->db->where('mythic_plus.region', $region);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(mythic_plus.realm," ", "")),SUBSTRING_INDEX(replace(mythic_plus.realm," ", ""), "(", 1),replace(mythic_plus.realm," ", "")), "%")', 'left');
		$mplus = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, advertiser, levelling.realm, id, id_from_sheet, collected, "Levelling" as type, realm_gwix.wow_account');
		$this->db->from('levelling');
		$this->db->where('levelling.region', $region);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(levelling.realm," ", "")),SUBSTRING_INDEX(replace(levelling.realm," ", ""), "(", 1),replace(levelling.realm," ", "")), "%")', 'left');
		$levelling = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, advertiser, pvp.realm, id, id_from_sheet, collected, "PvP" as type, realm_gwix.wow_account');
		$this->db->from('pvp');
		$this->db->where('pvp.region', $region);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(pvp.realm," ", "")),SUBSTRING_INDEX(replace(pvp.realm," ", ""), "(", 1),replace(pvp.realm," ", "")), "%")', 'left');
		$pvp = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, gold_collector as advertiser, gtrack.realm, id, id_from_sheet, collected, "GTrack" as type, realm_gwix.wow_account, CONCAT("%", IF(LOCATE("(", replace(gtrack.realm," ", "")),SUBSTRING_INDEX(replace(gtrack.realm," ", ""), "(", 1),replace(gtrack.realm," ", "")), "%")');
		$this->db->from('gtrack');
		$this->db->where('gtrack.region', $region);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(gtrack.realm," ", "")),SUBSTRING_INDEX(replace(gtrack.realm," ", ""), "(", 1),replace(gtrack.realm," ", "")), "%")', 'left');
		$gtrack = $this->db->get()->result_array();

		$merged = array_merge($mplus, $levelling, $pvp, $gtrack);
		$ids = array_column($merged, 'id_from_sheet');
		$ids = array_unique($ids);
		$merged = array_filter($merged, function ($key, $value) use ($ids) {
				return in_array($value, array_keys($ids));
		}, ARRAY_FILTER_USE_BOTH);

		$data['total'] = 0;
		foreach($merged as $m) {
			if($m['collected'] == "FALSE") {
			 if($m['type'] == "Mplus") {
				 @$data[$m['realm']]['total'] += floatval($m['pot']);
			 }
			 if($m['type'] == "PvP") {
				 @$data[$m['realm']]['total'] += floatval($m['pot']);
			 }
			 if($m['type'] == "Levelling") {
				 @$data[$m['realm']]['total'] += floatval($m['pot']);
			 }
			 if($m['type'] == "GTrack") {
				 @$data[$m['realm']]['total'] += floatval(str_replace(',', '', $m['pot'])) * 1000;
			 }
		 }
		}
		print_r(json_encode($data));
	}

	public function goldleft($key='', $region = 'EU') {
		if($key != 'notasmellytools') {
			return;
		}
		$this->db->select('date, pot, ad_cut, advertiser, mythic_plus.realm, id, id_from_sheet, collected, "Mplus" as type, realm_gwix.wow_account');
		$this->db->from('mythic_plus');
		$this->db->where('mythic_plus.region', $region);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(mythic_plus.realm," ", "")),SUBSTRING_INDEX(replace(mythic_plus.realm," ", ""), "(", 1),replace(mythic_plus.realm," ", "")), "%")', 'left');
		$mplus = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, advertiser, levelling.realm, id, id_from_sheet, collected, "Levelling" as type, realm_gwix.wow_account');
		$this->db->from('levelling');
		$this->db->where('levelling.region', $region);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(levelling.realm," ", "")),SUBSTRING_INDEX(replace(levelling.realm," ", ""), "(", 1),replace(levelling.realm," ", "")), "%")', 'left');
		$levelling = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, advertiser, pvp.realm, id, id_from_sheet, collected, "PvP" as type, realm_gwix.wow_account');
		$this->db->from('pvp');
		$this->db->where('pvp.region', $region);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(pvp.realm," ", "")),SUBSTRING_INDEX(replace(pvp.realm," ", ""), "(", 1),replace(pvp.realm," ", "")), "%")', 'left');
		$pvp = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, gold_collector as advertiser, gtrack.realm, id, id_from_sheet, collected, "GTrack" as type, realm_gwix.wow_account');
		$this->db->from('gtrack');
		$this->db->where('gtrack.region', $region);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(gtrack.realm," ", "")),SUBSTRING_INDEX(replace(gtrack.realm," ", ""), "(", 1),replace(gtrack.realm," ", "")), "%")', 'left');
		$gtrack = $this->db->get()->result_array();

		$merged = array_merge($mplus, $levelling, $pvp, $gtrack);
		$ids = array_column($merged, 'id_from_sheet');
		$ids = array_unique($ids);
		$merged = array_filter($merged, function ($key, $value) use ($ids) {
				return in_array($value, array_keys($ids));
		}, ARRAY_FILTER_USE_BOTH);

		$data['total'] = 0;
		$data['mPlusCount'] = 0;
		foreach($merged as $m) {
			if($m['collected'] == "FALSE" || $m['collected'] == "false") {
			 if($m['type'] == "Mplus") {
				 $data['total'] += floatval($m['pot']);
				 $data['mPlusCount']++;
			 }
			 if($m['type'] == "PvP") {
				 $data['total'] += floatval($m['pot']);
			 }
			 if($m['type'] == "Levelling") {
				 $data['total'] += floatval($m['pot']);
			 }
			 if($m['type'] == "GTrack") {
				 if($region == 'EU') {
				 	$data['total'] += floatval(str_replace(',', '', $m['pot'])) * 1000;
				 } else {
					$data['total'] += floatval(str_replace(',', '', $m['pot']));
				 }
			 }
		 }
		}
		print_r(json_encode($data));
	}

	public function index($key='', $region = 'EU')
	{
		if($key != 'oJtFqbQ7l6') {
			return;
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

		$this->db->select('date, pot, ad_cut, advertiser, mythic_plus.realm, id, id_from_sheet, collected, "Mplus" as type, realm_gwix.wow_account');
		$this->db->from('mythic_plus');
		$this->db->where('mythic_plus.region', $region);
		$this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(mythic_plus.realm," ", "")),SUBSTRING_INDEX(replace(mythic_plus.realm," ", ""), "(", 1),replace(mythic_plus.realm," ", "")), "%")', 'left');
		$mplus = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, advertiser, levelling.realm, id, id_from_sheet, collected, "Levelling" as type, realm_gwix.wow_account');
		$this->db->from('levelling');
		$this->db->where('levelling.region', $region);
		$this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(levelling.realm," ", "")),SUBSTRING_INDEX(replace(levelling.realm," ", ""), "(", 1),replace(levelling.realm," ", "")), "%")', 'left');
		$levelling = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, advertiser, pvp.realm, id, id_from_sheet, collected, "PvP" as type, realm_gwix.wow_account');
		$this->db->from('pvp');
		$this->db->where('pvp.region', $region);
		$this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(pvp.realm," ", "")),SUBSTRING_INDEX(replace(pvp.realm," ", ""), "(", 1),replace(pvp.realm," ", "")), "%")', 'left');
		$pvp = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, gold_collector as advertiser, gtrack.realm, id, id_from_sheet, collected, "GTrack" as type, realm_gwix.wow_account, CONCAT("%", IF(LOCATE("(", replace(gtrack.realm," ", "")),SUBSTRING_INDEX(replace(gtrack.realm," ", ""), "(", 1),replace(gtrack.realm," ", "")), "%")');
		$this->db->from('gtrack');
		$this->db->where('gtrack.region', $region);
		$this->db->where("DATE(CONCAT('2020','-',TRIM(SUBSTRING_INDEX(date,'/',-1)),'-',TRIM(SUBSTRING_INDEX(date,'/', 1)))) BETWEEN '".$cycleStart."' AND '".$cycleEnd."'");
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(gtrack.realm," ", "")),SUBSTRING_INDEX(replace(gtrack.realm," ", ""), "(", 1),replace(gtrack.realm," ", "")), "%")', 'left');
		$gtrack = $this->db->get()->result_array();

		$merged = array_merge($mplus, $levelling, $pvp, $gtrack);
		$ids = array_column($merged, 'id_from_sheet');
		$ids = array_unique($ids);
		$merged = array_filter($merged, function ($key, $value) use ($ids) {
				return in_array($value, array_keys($ids));
		}, ARRAY_FILTER_USE_BOTH);
		foreach ($merged as $key => $merge) {
			$merged[$key]['realm'] = ucwords(str_replace(' ', '', preg_replace('/\(../', '', $merge['realm'])));
			$merged[$key]['date'] = strtotime(str_replace('/', '-', $merge['date'] . "/2020"));
		}

		array_multisort(array_column($merged, 'wow_account'), SORT_ASC, array_column($merged, 'realm'), SORT_ASC, array_column($merged, 'date'), SORT_ASC, $merged);


		$data['total'] = 0;
		foreach($merged as $k => $m) {
			if($m['collected'] == "FALSE" || $m['collected'] == "false") {
			 if($m['type'] == "Mplus") {
				 $data['total'] += floatval($m['pot']);
			 }
			 if($m['type'] == "PvP") {
				 $data['total'] += floatval($m['pot']);
			 }
			 if($m['type'] == "Levelling") {
				 $data['total'] += floatval($m['pot']);
			 }
			 if($m['type'] == "GTrack") {
				 if($region == 'EU') {
					 $data['total'] += floatval(str_replace(',', '', $m['pot'])) * 1000;
					 $merged[$k]['pot'] = str_replace(',', '', $m['pot']) * 1000;
					 $merged[$k]['ad_cut'] = str_replace(',', '', $m['ad_cut']) * 1000;
				 } else {
					 $data['total'] += floatval(str_replace(',', '', $m['pot']));
					 $merged[$k]['pot'] = str_replace(',', '', $m['pot']);
					 $merged[$k]['ad_cut'] = str_replace(',', '', $m['ad_cut']);
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
		$this->load->view('collecting', $data);
	}

	public function changeRun($type = '', $id = '', $checked = 'TRUE', $region = 'EU') {
		if($type == "Mplus") {
			$this->db->set('collected', $checked)->where('id', $id)->update('mythic_plus');
		} else if($type == "Levelling") {
			$this->db->set('collected', $checked)->where('id', $id)->update('levelling');
		} else if($type == "GTrack") {
			$this->db->set('collected', $checked)->where('id', $id)->update('gtrack');
		} else if($type == "PvP") {
			$this->db->set('collected', $checked)->where('id', $id)->update('pvp');
		}
		exit;
		if($type && $id) {
			$client = $this->getClient();
      $service = new Google_Service_Sheets($client);
      $importSheet = [];
			if($region == "EU") {
				$spreadsheetId = '1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk';
			} else {
				$spreadsheetId = '1199viuHN-DhUNglHaPGYwiCXVsTtdEC7r_kAN3QL_DM';
			}
      if($type == "Mplus") {
				$range = "M+!B2:N";
			} else if($type == "Levelling") {
				$range = "Leveling!B2:L";
			} else if($type == "GTrack") {
				$range = "GOLD TRACK!B3:L";
			} else if($type == "PvP") {
				$range = "PvP!B2:L";
			}
      @$response = $service->spreadsheets_values->get($spreadsheetId, $range);
      $values = $response->getValues();
			print_r($values);
			if($type == "Mplus") {
				$key = array_search($id, array_column($values, 11));
			} else if($type == "Levelling") {
				$key = array_search($id, array_column($values, 9));
			} else if($type == "GTrack") {
				$key = array_search($id, array_column($values, 10));
			} else if($type == "PvP") {
				$key = array_search($id, array_column($values, 9));
			}

			$takeValues = $values[$key];
			if($type == "Mplus") {
				$range2 = "M+!B".($key+2);
			} else if($type == "Levelling") {
				$range2 = "Leveling!B".($key+2);
			} else if($type == "GTrack") {
				$range2 = "GOLD TRACK!B".($key+3);
			} else if($type == "PvP") {
				$range2 = "PvP!B".($key+2);
			}

			$body = new Google_Service_Sheets_ValueRange([
			    'values' => [[strtoupper($checked)]]
			]);
			$params = [
			    'valueInputOption' => "USER_ENTERED"
			];
			$result = $service->spreadsheets_values->update($spreadsheetId, $range2, $body, $params);

			if($type == "Mplus") {
				$this->db->set('collected', $checked)->where('id_from_sheet', $id)->update('mythic_plus');
			} else if($type == "Levelling") {
				$this->db->set('collected', $checked)->where('id_from_sheet', $id)->update('levelling');
			} else if($type == "GTrack") {
				$this->db->set('collected', $checked)->where('id_from_sheet', $id)->update('gtrack');
			} else if($type == "PvP") {
				$this->db->set('collected', $checked)->where('id_from_sheet', $id)->update('pvp');
			}
			printf("%d cells updated.", $result->getUpdatedCells());
			print_r($range2);
		}
	}

	public function goldLeftPerAdvertiser($key='', $region = 'EU')
	{
		if($key != 'oJtFqbQ7l6') {
			return;
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

		$this->db->select('date, pot, ad_cut, advertiser, mythic_plus.realm, id, id_from_sheet, collected, "Mplus" as type, realm_gwix.wow_account');
		$this->db->from('mythic_plus');
		$this->db->where('mythic_plus.region', $region);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '>= DATE("' . $cycleStart . '")', false);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(mythic_plus.realm," ", "")),SUBSTRING_INDEX(replace(mythic_plus.realm," ", ""), "(", 1),replace(mythic_plus.realm," ", "")), "%")', 'left');
		$mplus = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, advertiser, levelling.realm, id, id_from_sheet, collected, "Levelling" as type, realm_gwix.wow_account');
		$this->db->from('levelling');
		$this->db->where('levelling.region', $region);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '>= DATE("' . $cycleStart . '")', false);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(levelling.realm," ", "")),SUBSTRING_INDEX(replace(levelling.realm," ", ""), "(", 1),replace(levelling.realm," ", "")), "%")', 'left');
		$levelling = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, advertiser, pvp.realm, id, id_from_sheet, collected, "PvP" as type, realm_gwix.wow_account');
		$this->db->from('pvp');
		$this->db->where('pvp.region', $region);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '>= DATE("' . $cycleStart . '")', false);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(pvp.realm," ", "")),SUBSTRING_INDEX(replace(pvp.realm," ", ""), "(", 1),replace(pvp.realm," ", "")), "%")', 'left');
		$pvp = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, gold_collector as advertiser, gtrack.realm, id, id_from_sheet, collected, "GTrack" as type, realm_gwix.wow_account, CONCAT("%", IF(LOCATE("(", replace(gtrack.realm," ", "")),SUBSTRING_INDEX(replace(gtrack.realm," ", ""), "(", 1),replace(gtrack.realm," ", "")), "%")');
		$this->db->from('gtrack');
		$this->db->where('gtrack.region', $region);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '>= DATE("' . $cycleStart . '")', false);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(gtrack.realm," ", "")),SUBSTRING_INDEX(replace(gtrack.realm," ", ""), "(", 1),replace(gtrack.realm," ", "")), "%")', 'left');
		$gtrack = $this->db->get()->result_array();

		$merged = array_merge($mplus, $levelling, $pvp, $gtrack);
		$ids = array_column($merged, 'id_from_sheet');
		$ids = array_unique($ids);
		$merged = array_filter($merged, function ($key, $value) use ($ids) {
				return in_array($value, array_keys($ids));
		}, ARRAY_FILTER_USE_BOTH);
		$advertiser = [];
		foreach ($merged as $key => $merge) {
			$merged[$key]['realm'] = ucwords(str_replace(' ', '', preg_replace('/\(../', '', $merge['realm'])));
			$merged[$key]['date'] = strtotime(str_replace('/', '-', $merge['date'] . "/2020"));
		}

		array_multisort(array_column($merged, 'wow_account'), SORT_ASC, array_column($merged, 'realm'), SORT_ASC, array_column($merged, 'date'), SORT_ASC, $merged);


		$data['total'] = 0;
		foreach($merged as $k => $m) {
			if($m['collected'] == "FALSE") {
			 if($m['type'] == "Mplus") {
				 $data['total'] += floatval($m['pot']);
				 @$advertiser[$m['advertiser']][$m['realm']] += floatval($m['pot']);
			 }
			 if($m['type'] == "PvP") {
				 $data['total'] += floatval($m['pot']);
				 @$advertiser[$m['advertiser']][$m['realm']] += floatval($m['pot']);
			 }
			 if($m['type'] == "Levelling") {
				 $data['total'] += floatval($m['pot']);
				 @$advertiser[$m['advertiser']][$m['realm']] += floatval($m['pot']);
			 }
			 if($m['type'] == "GTrack") {
				 $data['total'] += floatval(str_replace(',', '', $m['pot'])) * 1000;
				 $merged[$k]['pot'] = str_replace(',', '', $m['pot']) * 1000;
				 $merged[$k]['ad_cut'] = str_replace(',', '', $m['ad_cut']) * 1000;
				 @$advertiser[$m['advertiser']][$m['realm']] += str_replace(',', '', $m['ad_cut']) * 1000;
			 }
		 }
		}
		$data['merged'] = $merged;
		$data['realm_list'] = array_unique(array_column($merged, 'realm'));
		$data['region'] = $region;
		$data['cycleStart'] = $cycleStart;
		$data['cycleEnd'] = $cycleEnd;
		$data['cycles'] = $cycles;
		print_r($advertiser);
		// $this->load->view('collecting', $data);
	}

	public function goldLeftPerAccount($accountName='', $region = 'EU')
	{
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

		$this->db->select('date, pot, ad_cut, advertiser, mythic_plus.realm, id, id_from_sheet, collected, "Mplus" as type, realm_gwix.wow_account');
		$this->db->from('mythic_plus');
		$this->db->where('mythic_plus.region', $region);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '>= DATE("' . $cycleStart . '")', false);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(mythic_plus.realm," ", "")),SUBSTRING_INDEX(replace(mythic_plus.realm," ", ""), "(", 1),replace(mythic_plus.realm," ", "")), "%")', 'left');
		$mplus = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, advertiser, levelling.realm, id, id_from_sheet, collected, "Levelling" as type, realm_gwix.wow_account');
		$this->db->from('levelling');
		$this->db->where('levelling.region', $region);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '>= DATE("' . $cycleStart . '")', false);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(levelling.realm," ", "")),SUBSTRING_INDEX(replace(levelling.realm," ", ""), "(", 1),replace(levelling.realm," ", "")), "%")', 'left');
		$levelling = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, advertiser, pvp.realm, id, id_from_sheet, collected, "PvP" as type, realm_gwix.wow_account');
		$this->db->from('pvp');
		$this->db->where('pvp.region', $region);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '>= DATE("' . $cycleStart . '")', false);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(pvp.realm," ", "")),SUBSTRING_INDEX(replace(pvp.realm," ", ""), "(", 1),replace(pvp.realm," ", "")), "%")', 'left');
		$pvp = $this->db->get()->result_array();
		$this->db->select('date, pot, ad_cut, gold_collector as advertiser, gtrack.realm, id, id_from_sheet, collected, "GTrack" as type, realm_gwix.wow_account');
		$this->db->from('gtrack');
		$this->db->where('gtrack.region', $region);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '>= DATE("' . $cycleStart . '")', false);
		$this->db->where('DATE(CONCAT("2020","-",SUBSTRING_INDEX(date,"/",-1),"-",SUBSTRING_INDEX(date,"/", 1)))', '<= DATE("' . $cycleEnd . '")', false);
		$this->db->join('realm_gwix', 'replace(realm_gwix.realm_name," ", "") LIKE CONCAT("%", IF(LOCATE("(", replace(gtrack.realm," ", "")),SUBSTRING_INDEX(replace(gtrack.realm," ", ""), "(", 1),replace(gtrack.realm," ", "")), "%")', 'left');
		$gtrack = $this->db->get()->result_array();

		$merged = array_merge($mplus, $levelling, $pvp, $gtrack);
		$ids = array_column($merged, 'id_from_sheet');
		$ids = array_unique($ids);
		$merged = array_filter($merged, function ($key, $value) use ($ids) {
				return in_array($value, array_keys($ids));
		}, ARRAY_FILTER_USE_BOTH);
		$account = [];
		foreach ($merged as $key => $merge) {
			$merged[$key]['realm'] = ucwords(str_replace(' ', '', preg_replace('/\(../', '', $merge['realm'])));
			$merged[$key]['date'] = strtotime(str_replace('/', '-', $merge['date'] . "/2020"));
		}

		array_multisort(array_column($merged, 'wow_account'), SORT_ASC, array_column($merged, 'realm'), SORT_ASC, array_column($merged, 'date'), SORT_ASC, $merged);


		$data['total'] = 0;
		foreach($merged as $k => $m) {
			if($m['collected'] != "FALSE") {
			 if($m['type'] == "Mplus") {
				 $data['total'] += floatval($m['pot']);
				 if($m['wow_account'] == $accountName) {
					 @$account[$m['wow_account']]['total'] += floatval($m['pot']);
					 @$account[$m['wow_account']]['runs'][] = $m;
				 }
			 }
			 if($m['type'] == "PvP") {
				 $data['total'] += floatval($m['pot']);
				 if($m['wow_account'] == $accountName) {
					 @$account[$m['wow_account']]['total'] += floatval($m['pot']);
					 @$account[$m['wow_account']]['runs'][] = $m;
				 }
			 }
			 if($m['type'] == "Levelling") {
				 $data['total'] += floatval($m['pot']);
				 if($m['wow_account'] == $accountName) {
					 @$account[$m['wow_account']]['total'] += floatval($m['pot']);
					 @$account[$m['wow_account']]['runs'][] = $m;
				 }
			 }
			 if($m['type'] == "GTrack") {
				 $data['total'] += floatval(str_replace(',', '', $m['pot'])) * 1000;
				 $merged[$k]['pot'] = str_replace(',', '', $m['pot']) * 1000;
				 $merged[$k]['ad_cut'] = str_replace(',', '', $m['ad_cut']) * 1000;
				 if($m['wow_account'] == $accountName) {
					 @$account[$m['wow_account']]['total'] += str_replace(',', '', $m['ad_cut']) * 1000;
					 @$account[$m['wow_account']]['runs'][] = $m;
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
		print_r(json_encode($account));
		// $this->load->view('collecting', $data);
	}
}
