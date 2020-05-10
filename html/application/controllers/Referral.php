<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Referral extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'grocery_CRUD'));
        $this->load->helper('url');
    }

    /**
     * Index Page for this controller.
     */
    public function discord($name = '')
    {
      if($name) {
        if($this->checkRefer($name)) {
          $this->db->query("UPDATE referral SET discord_referrals = discord_referrals+1 WHERE name = '{$name}'");
        }
      }
      redirect('https://discord.gg/gallywix');
    }

    public function website($name = '')
    {
      if($name) {
        if($this->checkRefer($name)) {
          $this->db->query("UPDATE referral SET website_referrals = website_referrals+1 WHERE name = '{$name}'");
        }
      }
      redirect('http://gallywix.eu');
    }

    private function checkRefer($name) {
      $found = $this->db->where('name', $name)->get('referral')->result();
      if(count($found) == 0) {
        $addData = array(
          'name' => $name,
          'discord_referrals' => 0,
          'website_referrals' => 0,
        );
        $this->db->insert('referral', $addData);
      }
      return true;
    }

    public function list() {
      $allowedRoles = array("681017712914464778", "443520205826818058");
      $isHigherManagement = @!empty(array_intersect($allowedRoles, $this->session->userdata('gallywix_user_data')->roles));
      $allowedRoles = array("681017712914464778", "443520205826818058", "466418911475400746", "225454025372336129", "557611505869258756", "524032946072715264");
      $isManagement = @!empty(array_intersect($allowedRoles, $this->session->userdata('gallywix_user_data')->roles));

      if(!$isManagement && (!$this->session->userdata('access_token') || !$this->session->userdata('gallywix_name'))) {
  			redirect('balance/login');
  		}
      $crud = new grocery_CRUD();

      if(!$isHigherManagement) {
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
      }

      $crud->set_theme('datatables');
      $crud->set_table('referral');
      $output = $crud->render();

      $data['pageTitle'] = 'Edit Referrals';
      $data['crud'] = (array)$output;
      $this->load->view("crud", $data);
    }
}

?>
