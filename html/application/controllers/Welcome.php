<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Welcome extends CI_Controller
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
    public function index()
    {
        $this->db->select('*');
        $this->db->from('price_list');
        $this->db->where('region', 'EU');
        $this->db->order_by('FIELD(category, "Raids", "Mythic+", "Dungeons", "PvE Coaching", "Leveling", "Island Expeditions", "Legacy", "Mounts", "Herald of the Titans", "Black Market Auction House"), sorter');
        $data['pricelist_eu'] = $this->db->get()->result();
        $this->db->select('*');
        $this->db->from('price_list');
        $this->db->where('region', 'NA');
        $this->db->order_by('FIELD(category, "Raids", "Mythic+", "Dungeons", "PvE Coaching", "Leveling", "Island Expeditions", "Legacy", "Mounts", "Herald of the Titans", "Black Market Auction House"), sorter');
        $data['pricelist_na'] = $this->db->get()->result();
        $this->load->view('homepage', $data);
    }
}

?>
