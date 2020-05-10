<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require BASEPATH . '../vendor/autoload.php';

class Adcheckin extends CI_Controller {

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
		$this->db->select('*');
        $this->db->from('advertiser_checkin');
        $this->db->where('region', 'EU');
        $this->db->order_by('priority ASC, realm ASC, slot DESC');
        $data['advertiser_checkin_eu'] = $this->db->get()->result();
        $this->db->select('*');
        $this->db->from('advertiser_checkin');
        $this->db->where('region', 'NA');
        $this->db->order_by('priority ASC, realm ASC, slot DESC');
		$data['advertiser_checkin_na'] = $this->db->get()->result();
		
		$this->load->view('advertiser_checkin_page', $data);
	}
}
