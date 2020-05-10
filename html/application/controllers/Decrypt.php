<?php
defined('BASEPATH') or exit('No direct script access allowed');
require BASEPATH . '../vendor/autoload.php';

class Decrypt extends CI_Controller
{

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

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
	}

	public function index()
	{      
		$allowedRoles = array("681017712914464778", "443520205826818058", "466418911475400746", "225454025372336129", "557611505869258756", "524032946072715264", "554885838882996244");
		$isManagementOrGC = @!empty(array_intersect($allowedRoles, $this->session->userdata('gallywix_user_data')->roles));

		if(!$isManagementOrGC && (!$this->session->userdata('access_token') || !$this->session->userdata('gallywix_name'))) {
			redirect('balance/login');
		}
		else{
			$this->load->view('decrypt_page');
		}
	}

	public function getBuyersPost()
	{
		$keys = explode("\n", $this->input->post('keys'));
		$results = array();

		foreach ($keys as $key) {
			// Remove erroneous spaces
			$cleanKey = str_replace(' ', "", $key);
			$result = null;

			// Google sheets sometimes adds random quotes on single lines, ignore these.
			if ($cleanKey == '"') {
				continue;
			}

			// Remove erroneous spaces or quotes
			$cleanKey = str_replace('"', "", $key);

			// Maintain empty values
			if (strlen($cleanKey) == 0) {
				$result["bnet_disc_comment"] = "";
				$result["name_server"] = "";
				$result["receipt"] = "";
				array_push($results, $result);
				continue;
			}

			// All valid keys must be 22 characters
			if (strlen($cleanKey) == 22) {
				$this->db->select('bnet_disc_comment, name_server, receipt');
				$this->db->from('buyer_names');
				$this->db->where('uuid', $cleanKey);
				$this->db->limit(1);
				$result = $this->db->get()->result();

				if (empty($result)) {
					$result["bnet_disc_comment"] = "KEY NOT FOUND";
					$result["name_server"] = "KEY NOT FOUND";
					$result["receipt"] = "KEY NOT FOUND";
					array_push($results, $result);
				} 
				else {
					array_push($results, $result[0]);
				}
			} else {
				$result["bnet_disc_comment"] = "INVALID KEY - NOT 22 CHARACTERS";
				$result["name_server"] = "INVALID KEY - NOT 22 CHARACTERS";
				$result["receipt"] = "INVALID KEY - NOT 22 CHARACTERS";
				array_push($results, $result);
			}
		}
		echo json_encode($results);
	}
}
