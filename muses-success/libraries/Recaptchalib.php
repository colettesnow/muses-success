<?php
/**
 * Simple class to make interacting with Google's Recaptcha V3 Library
 * easier in CodeIgniter.
 * 
 * This was made to retrofit Recaptcha v3 on a CodeIgniter v1.7.1 site. It may
 * or may not work with later versions.
 * 
 * Requires PHP Version 7
 * 
 * @category Library
 * @package  CI_Recaptcha_Helper_Library
 * @author   Colette Snow <colette@colettesnow.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://colettesnow.com/
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

define('STRICT_THRESHOLD', 0.8);
define('NORMAL_THRESHOLD', 0.5);
define('PERMISSIVE_THRESHOLD', 0.3);

/**
 * Simple class to make interacting with Google's Recaptcha V3 Library
 * easier in CodeIgniter
 * 
 * @category Library
 * @package  CI_Recaptcha_Helper_Library
 * @author   Colette Snow <colette@colettesnow.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://colettesnow.com/
 */
class Recaptchalib
{

    private $_action = "";
    private $_recaptcha;
    private $_secret_key;
    private $_site_key;
    private $_host_name;
    private $_CI;

    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        $this->_CI = &get_instance();

        include_once 'recaptcha/src/autoload.php';

        $this->_secret_key = $this->_CI->config->item('recaptcha_secret_key');
        $this->_site_key = $this->_CI->config->item('recaptcha_site_key');
        $this->_host_name = $this->_CI->config->item('recaptcha_host');

        if (!empty($this->_secret_key)) {
            $this->_recaptcha = new \ReCaptcha\ReCaptcha(
                $this->_secret_key
            );

        } else {
            show_error(
                "Please fill out your Recaptcha secret in 
                <tt>config/recaptcha.php</tt>."
            );
        }
    }

    /**
     * Set a name to identify the action being performed in Recaptcha dashboard
     *
     * @param mixed $action Name of the action to pass to Recaptcha.
     *
     * @return void
     */
    public function setAction(string $action)
    {
        $this->_action = $action;
        return $this;
    }

    /**
     * Retrieve the action being performed
     *
     * @return string Name of the action being performed.
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * Return the host name of this website
     *
     * @return string Host name.
     */
    public function getHost()
    {
        return $this->_host_name;
    }

    /**
     * Recaptcha instance
     *
     * @return \ReCaptcha\
     */
    private function _getRecaptcha()
    {
        return $this->_recaptcha;
    }

    /**
     * Verify recaptcha request with normal threshold
     *
     * @param string $token Recaptcha token from the JS to verify.
     * @param int    $threshold  Optional, threshold as decimal out of 1.0 for
     *                           Recaptcha's human confidence.
     *
     * @return \ReCaptcha\verify\isSuccess()
     */
    public function verify(string $token, $threshold = NORMAL_THRESHOLD)
    {
        if (strlen($this->getAction()) > 2) {
            return $this->_getRecaptcha()->setExpectedHostname(
                $this->getHost()
            )->setScoreThreshold(
                $threshold
            )->setExpectedAction(
                $this->getAction()
            )->verify(
                $token, $_SERVER["CF-Connecting-IP"]
            );
        } else {
            return $this->_getRecaptcha()->setExpectedHostname(
                $this->getHost()
            )->setScoreThreshold(
                $threshold
            )->verify(
                $token, $_SERVER["CF-Connecting-IP"]
            );
        }
    }

    /**
     * Verify recaptcha request with strict threshold
     *
     * @param string $token Recaptcha token from the JS to verify.
     *
     * @return \ReCaptcha\verify\isSuccess()
     */
    public function verifyStrict(string $token)
    {
        return $this->verify($field_name, STRICT_THRESHOLD);
    }

    /**
     * Verify recaptcha request with permissive threshold
     *
     * @param string $token Recaptcha token from the JS to verify.
     *
     * @return \ReCaptcha\verify\isSuccess()
     */
    public function verifyPermissive(string $token)
    {
        return $this->verify($field_name, PERMISSIVE_THRESHOLD);
    }

}