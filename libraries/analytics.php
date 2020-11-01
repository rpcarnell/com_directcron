<?php
class DRCLytics
{
    function &getInstance( $here )
    {
        static $instances;
        if (!isset( $instances )) {$instances = array();}
        $signature = base64_encode( $here );
        if (empty($instances[$signature]))
        {
            $instance = new DRCLytics();
            $instances[$signature] = & $instance;
        }
        return $instances[$signature];
    }
    function visitedBefore($catid, $ip, & $cronDB)
    {
        $query = "SELECT date_updated FROM #__directcron_statistics WHERE type='category' AND typeid=$catid AND ip = '$ip' LIMIT 1";
        $updated = $cronDB->getOneValue($query);
        return (is_numeric($updated)) ? $updated : false;
    }
    public function updateAnalys($user, $catid, $type)
    { 
        //if ((int)$user->id < 1)  return;
        $ip = $this->get_user_ip();
        $cronDB = CronDb::getInstance( 'crondb');
        $values = array();
        $user->id = ((int)$user->id > 0) ? $user->id : '';
        $updated = $this->visitedBefore($catid, $ip,  $cronDB);
        if ($updated)
        {
            $day = 24 * 60 * 60;
            if ((time() - $updated) < $day) { return; }
        }
        if ($updated)
        { 
            $useridqr = ($user->id != '') ? "AND userid = '".$user->id."'" : '';
            $query = "UPDATE #__directcron_statistics SET visits = visits + 1 WHERE ip='$ip' $useridqr AND type='category' AND typeid=$catid LIMIT 1";
            $cronDB->update($query);
        } 
        else
        {
            $values['userid'] = $user->id;
            $values['typeid'] = $catid;
            $values['type'] = $type;
            $values['date_updated'] = time();
            $values['visits'] = 1;
            $values['ip'] = $ip;
            $values_2 = array('typeid', 'date_updated', 'visits');
            $query = $cronDB->buildQuery( 'INSERT', '#__directcron_statistics', $values, '', $values_2);
            $cronDB->insert($query);
        }
    }        
    public function get_user_ip()
    {
        //$_SERVER['HTTP_X_FORWARDED_FOR']  <---google uses this for the connection that it proxies.
        $user_ip = $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        /*sometimes $_SERVER['REMOTE_ADDR'] can return two address: 254.2.4.2, 232.23.22.3, for example*/
        if (strstr($user_ip, ', ')) 
        {
           $ips = explode(', ', $user_ip);
           $user_ip = $ips[0];
        }
        $hostip = @gethostbyname($user_ip);
        //5.0.0 <--- Prior to this version, ip2long() returned -1 on failure.
        $ip = ip2long($hostip);//gives 2130706433  for 127.0.0.1
        // this would give us hostname in case of 127.0.0.1 ---> @gethostbyaddr($ip);
        if($ip == -1 || $ip === false ||($ip == @gethostbyaddr($ip) && preg_match("/.*\.[a-zA-Z]{2,3}$/",$user_ip) == 0) )
        {return false;}
        $regisarr = ( $hostip == $user_ip ) ? $user_ip : long2ip($ip);
        return $regisarr;
    }
}
?>
