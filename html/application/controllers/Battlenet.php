<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require BASEPATH . '../vendor/autoload.php';

class Battlenet extends CI_Controller {

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
		if(!$this->session->userdata('access_token') || !$this->session->userdata('gallywix_name')) {
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

	public function blizzard_oauth()
	{
	  $redirect_uri = base_url() . 'battlenet/blizzard_oauth_return';
	  $client_id = '8833ca03b3c2459ab4770ec477a7eb2c';
	  $client_secret = 'O3ahYJhA27037uywf2w8nQK2SisBsF0P';
	  if($_GET['code']) {

	  } else {
	    $url = 'https://eu.battle.net/oauth/authorize';
	    $params = array(
	      'client_id' => $client_id,
	      'scope' => 'wow.profile',
	      'state' => 'yes',
	      'redirect_uri' => $redirect_uri,
	      'response_type' => 'code'
	    );
	    redirect($url . '?' . http_build_query($params));
	  }
	}

	public function blizzard_oauth_return()
	{
		if(!$this->session->userdata('gallywix_name')) {
			exit;
		}
	  $redirect_uri = base_url() . 'battlenet/blizzard_oauth_return';
		$client_id = '8833ca03b3c2459ab4770ec477a7eb2c';
	  $client_secret = 'O3ahYJhA27037uywf2w8nQK2SisBsF0P';
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, 'https://eu.battle.net/oauth/token');
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, "redirect_uri={$redirect_uri}&scope=wow.profile&grant_type=authorization_code&code={$_GET['code']}");
	  curl_setopt($ch, CURLOPT_POST, 1);
	  curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $client_secret);

	  $headers = array();
	  $headers[] = 'Content-Type: application/x-www-form-urlencoded';
	  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	  $result = curl_exec($ch);
	  $token = json_decode($result)->access_token;
	  if (curl_errno($ch)) {
	      echo 'Error:' . curl_error($ch);
	  }
	  curl_close ($ch);

	  if($token) {

	    $curL = curl_init();

	    curl_setopt($curL, CURLOPT_URL, "https://eu.api.blizzard.com/profile/user/wow?namespace=profile-eu&access_token={$token}");
	    curl_setopt($curL, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curL, CURLOPT_CUSTOMREQUEST, 'GET');

	    $result = curl_exec($curL);
	    if (curl_errno($curL)) {
	        echo 'Error:' . curl_error($curL);
	    }
	    curl_close ($curL);
			echo "<pre>";
			$wow_accounts = json_decode($result)->wow_accounts;
			foreach($wow_accounts as $wowa) {
				foreach ($wowa->characters as $key => $character) {
					if($character->level >= 20) {
						$import[] = array(
							'name' => $character->name,
							'realm' => str_replace('-', '', str_replace(' ', '', $character->realm->name->en_GB)),
							'class' => $character->playable_class->name->en_GB,
							'race' => $character->playable_race->name->en_GB,
							'gender' => $character->gender->name->en_GB,
							'level' => $character->level,
							'achievementPoints' => 0,
							'thumbnail' => '',
							'spec' => '',
							'lastModified' => date("Y-m-d H:i:s"),
							'ownerName' => $this->session->userdata('gallywix_name'),
						);
					}
				}
			}



			usort($import, function($b, $a) {
			   return $a['level'] - $b['level'];
			});

			if($import) {
				$this->db->where('ownerName', $this->session->userdata('gallywix_name'));
				$this->db->delete('wow_characters');
				$this->db->insert_batch('wow_characters', $import);
			}
	  }
		$this->session->set_flashdata('loginSuccess', "Characters Synced");
		$this->sendAltsToSheet($import);
		redirect('battlenet/characters');
	}

	public function characters() {
		$data['characters'] = $this->db->where('ownerName', $this->session->userdata('gallywix_name'))->get('wow_characters')->result();
		$this->load->view('characters', $data);
	}

	public function sendAltsToSheet($characters = '') {
		if(strpos($this->session->userdata('gallywix_name'), '-') === false) {
			return;
		}

		if($characters || true) {
			$client = $this->getClient();
			$service = new Google_Service_Sheets($client);
			$result = $service->spreadsheets_values->get("1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk", "Alts!A3:AG");
			$range = $result->getValues();
			$foundCharacter = array_search($this->session->userdata('gallywix_name'), array_column($range, 0));
			$characterList = array_column($characters, 'name');

			if(!@$characterList[12]) {
				$characterList[12] = "BOT";
			} else {
				array_splice($characterList, 12, 0, "BOT");
			}

			if(!@$characterList[32]) {
				$characterList[32] = "BOT";
			} else {
				array_splice($characterList, 32, 0, "BOT");
			}

			for ($i = 0; $i <= 32; $i++)
			{
			    if (!isset($characterList[$i]))
			    {
			        $characterList[$i] = "";
			    }
			}

			ksort($characterList);
			$characterList = array_slice($characterList, 0, 32);

			if($foundCharacter === false) {
				$nameList = array_filter(array_column($range, 0), function ($var) {
				    return (strlen($var) == 0);
				});
				$foundCharacter = key($nameList);
				array_unshift($characterList, $this->session->userdata('gallywix_name'));
				$range = "Alts!A" . ($foundCharacter + 3) . ":AH" . ($foundCharacter + 3);
			} else {
				$range = "Alts!B" . ($foundCharacter + 3) . ":AH" . ($foundCharacter + 3);
			}

			array_slice($characterList, 0, 32);

			$values = [
			    $characterList,
			];

			$body = new Google_Service_Sheets_ValueRange([
			    'values' => $values
			]);
			$params = [
			    'valueInputOption' => "USER_ENTERED"
			];

			$result = $service->spreadsheets_values->update("1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk", $range, $body, $params);

		}
	}
}
