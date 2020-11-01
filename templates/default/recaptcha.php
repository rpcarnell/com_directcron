<?php 
          			
                // prevent recaptchalib already loaded
                if ( !function_exists('_recaptcha_qsencode') ) {
                        require_once (JPATH_SITE.DS.'components'.DS.'com_directcron'.DS.'libraries'.DS.'recaptchalib.php');	
                }			
                // Get a key from http://recaptcha.net/api/getkey
                if (!isset($params))
                { echo "<br /><div style='color: #b00; padding: 5px;'>ERROR - parameters are not available</div>"; }
                $publickey = $params->get('pubkey');
                if (! $publickey) { echo "<br /><div style='color: #b00; padding: 5px;'>ERROR - there's no reCaptcha public key</div>"; }
                				
                // the response from reCAPTCHA
                $resp = null;
                // the error code from reCAPTCHA, if any
                $error = null;	
                echo recaptcha_get_html($publickey, $error);
                echo "<br />";
         
?>
