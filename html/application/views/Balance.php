<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require BASEPATH . '../vendor/autoload.php';

class Balance extends CI_Controller {

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

	public function index()
	{
		// $this->session->set_userdata('access_token', 'test');
		if(!$this->session->userdata('access_token')) {
			redirect('balance/login');
		}
		$data['balance'] = $this->db->where('name', $this->session->userdata('gallywix_name'))->get('balance')->row();
		$data['mplus'] = $this->db
											->or_where('advertiser', $this->session->userdata('gallywix_name'))
											->or_where('booster_1', $this->session->userdata('gallywix_name'))
											->or_where('booster_2', $this->session->userdata('gallywix_name'))
											->or_where('booster_3', $this->session->userdata('gallywix_name'))
											->or_where('booster_4', $this->session->userdata('gallywix_name'))
											->get('mythic_plus')->result_array();
		$data['levelling'] = $this->db
											->or_where('advertiser', $this->session->userdata('gallywix_name'))
											->or_where('booster_1', $this->session->userdata('gallywix_name'))
											->or_where('booster_2', $this->session->userdata('gallywix_name'))
											->get('levelling')->result_array();
		$data['pvp'] = $this->db
											->or_where('advertiser', $this->session->userdata('gallywix_name'))
											->or_where('booster_1', $this->session->userdata('gallywix_name'))
											->or_where('booster_2', $this->session->userdata('gallywix_name'))
											->get('pvp')->result_array();
		$data['raids'] = $this->db
											->or_where('gc', $this->session->userdata('gallywix_name'))
											->or_where("FIND_IN_SET('{$this->session->userdata('gallywix_name')}', boosters)")
											->get('raids')->result_array();
		$data['manual_edits'] = $this->db
											->or_where('name', $this->session->userdata('gallywix_name'))
											->get('manual_edits')->result_array();
		$this->load->view('balance_page', $data);
	}

	public function login()
	{
		$params = array(
			'client_id' => "577243084639698954",
			'redirect_uri' => 'https://www.gallywix.eu/balance/discordreturn',
			'response_type' => 'code',
			'scope' => 'identify guilds'
		);
		header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));
		die();
	}

	public function logout()
	{
		session_destroy();
		redirect(base_url());
	}

	public function discordreturn()
	{
		$token = $this->apiRequest("https://discordapp.com/api/oauth2/token", array(
			"grant_type" => "authorization_code",
			'client_id' => "577243084639698954",
			'client_secret' => "M-PhHa8jRdi5VE5iA-jhgAX5ZxCSl-bZ",
			'redirect_uri' => 'https://www.gallywix.eu/balance/discordreturn',
			'code' => $_GET['code']
		));

		if(@$token->access_token) {
			$this->session->set_userdata('access_token', $token->access_token);

			$user_data = $this->apiRequest("https://discordapp.com/api/users/@me", NULL, array(
				'Authorization' => "Bearer {$token->access_token}",
				"Content-Type" => "application/x-www-form-urlencoded",
			));

			$this->session->set_userdata('user_data', $user_data);

			if(@$user_data->id) {
				$gallywix_discord_match = $this->apiRequest2("https://discordapp.com/api/guilds/443203800728207371/members/{$user_data->id}", FALSE, array(
					"Content-Type" => "application/x-www-form-urlencoded",
				));
				$gallywixName = $gallywix_discord_match->nick;
				if(strpos($gallywixName, '|') !== false) {
					$gallywixName = trim(preg_replace('/\|(.*?)\|/', '', $gallywixName));
				}

				if(!$gallywixName) {
					$gallywixName = $gallywix_discord_match->user->username;
				}

				if($gallywixName == "Azortharion") {
					$gallywixName = "Azortharion-Ragnaros";
				} elseif ($gallywixName == "Tolls") {
					$gallywixName = "Tolls-TarrenMill";
				} elseif ($gallywixName == "Bogi") {
					$gallywixName = "Rollingmonk-Kazzak";
				} elseif ($gallywixName == "Kit") {
					$gallywixName = "Kit-TarrenMill";
				}

				$allowedRoles = array("467495325381296131", "443520205826818058", "461839226581942276", "443759760534142976");
				$allowedIn = !empty(array_intersect($allowedRoles, $gallywix_discord_match->roles))

				print_r($allowedIn);
				exit;
				$this->session->set_userdata('gallywix_user_data', $gallywix_discord_match);
				$this->session->set_userdata('gallywix_name', $gallywixName);
				redirect('balance/index');
			}
		}
	}

	private function apiRequest($url, $post=FALSE, $headers=array())
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		if($post)
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		$headers[] = 'Accept: application/json';
		if($this->session->userdata('access_token'))
		  $headers[] = 'Authorization: Bearer ' . $this->session->userdata('access_token');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		return json_decode($response);
	}

	private function apiRequest2($url, $post=FALSE, $headers=array())
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		if($post)
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		$headers[] = 'Accept: application/json';
		$headers[] = 'Authorization: Bot NTgxNjY2ODk5NDYxNjY4ODY0.XVxTyw.b7Dm48uIDUfgalqHLtBNS4hys20';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		print_r($response);
		return json_decode($response);
	}
}
