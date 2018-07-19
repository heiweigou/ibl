<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Ip;

class IpController extends Controller
{

    function cidr_match($ip, $cidr)
    {
        list($subnet, $mask) = explode('/', $cidr);
    
        if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1) ) == ip2long($subnet))
        { 
            return true;
        }
    
        return false;
    }

//get the start and ending IP address of a CIDR
    function cidrConv($net) { 
        $start = strtok($net,"/"); 
        $n = 3 - substr_count($net, "."); 
        if ($n > 0)
        {
            for ($i = $n;$i > 0; $i--)
                $start .= ".0";
        } 
        $bits1 = str_pad(decbin(ip2long($start)), 32, "0", STR_PAD_LEFT);
        $net = (1 << (32 - substr(strstr($net, "/"), 1))) - 1; 
        $bits2 = str_pad(decbin($net), 32, "0", STR_PAD_LEFT); 
        $final = "";
        for ($i = 0; $i < 32; $i++)
        { 
            if ($bits1[$i] == $bits2[$i]) $final .= $bits1[$i]; 
            if ($bits1[$i] == 1 and $bits2[$i] == 0) $final .= $bits1[$i]; 
            if ($bits1[$i] == 0 and $bits2[$i] == 1) $final .= $bits2[$i]; 
        } 
        return array(ip2long($start), bindec($final)); //long-number format
    }


/*//THIS IS FOR KNOW ASSET LIST    
//compare ip range x to y to identify the parts of x that in & out of range y
//test on $x in or out
    function compareKeep($xlow,$xhigh, $y){
        //convert CIDR to low and high ip
        //list($xLow,$xHigh)=cidrConv($x);//change to accept $xlow,$xHigh to enable loop 
        list($ylow,$yhigh)=cidrConv($y);

        //arrays to store ranges that in & out
        $in = array();
        $out = array();

        //TOFIX: IF statement type conversion
        //TOFIX: Margin IP for IP ranges (include\exclude border) 
        //cover 6 situation when comparing 2 ranges
  
        if ($xhigh<=$yhigh){
            if($xlow>=$ylow){
                //#fully in range
                $inip = new Ip;//range inside
                $inip->low = $xlow;
                $inip->high = $xhigh;
                array_push($in,$inip);

            }else{//$xLow < $yLow
                if($xhigh>=$ylow){
                    //#partly in range (rightside in)
                    $inip = new Ip;
                    $inip->low = $ylow;
                    $inip->high = $xhigh;
                    array_push($in,$inip);

                    $outip = new Ip;
                    $outip->low = $xlow;
                    $outip->high = $ylow;
                    array_push($out,$outip);

                }else{//$xHigh < $yLow
                    //#full out of range (leftside)
                    $outip = new Ip;
                    $outip->low = $xlow;
                    $outip->high = $xhigh;
                    array_push($out,$outip);
                }
            }
        }else{//$xHigh > $yHigh
            if($xlow<$ylow){
                //#partly in range (middle in range)
                $inip = new Ip;//middle part that's in range
                $inip->low = $ylow;
                $inip->high = $yhigh;
                array_push($in,$inip);

                $outip1 = new Ip;//1st part of the outside ip range (leftside)
                $outip1->low = $xlow;
                $outip1->high = $ylow;
                array_push($out,$outip1);

                $outip2 = new Ip;//2st part of the outside ip range (leftside)
                $outip2->low = $yhigh;
                $outip2->high = $xhigh;
                array_push($out,$outip2);

            }else{//$xLow > $yLow
                if($xlow<=$yhigh){
                    //partly in range (leftside in)
                    $inip = new Ip;
                    $inip->low = $ylow;
                    $inip->high = $xhigh;
                    array_push($in,$inip);

                    $outip = new Ip;
                    $outip->low = $xlow;
                    $outip->high = $ylow;
                    array_push($out,$outip);

                }else{//$xLow > $yHigh
                    //#full out of range (rightside)
                    $outip = new Ip;
                    $outip->low = $xlow;
                    $outip->high = $xhigh;
                    array_push($out,$outip);
                }
            }
        }

        return array($in,$out);
    }
*/
    //test on $x keep or remove against DO-NOT-SCAN-LIST
    function compareRemove($input){//$x is an array of IP variables

        //grab Do Not Scan List(DNSL) and known asset list into an array
        $dnsl = array_map('str_getcsv', file('dnsl.csv'));//Do Not Scan List(DNSL)

        //arrays to store ranges that in & out
        $borders = array();
        $out = array();
        $in = array();


        foreach($input as $x){

            //initial $xlow and $xhigh value
            $xlow = $x->low;
            $xhigh = $x->high;

            //defult $xmin , $xmax value
            $xmin = $xlow;
            $xmax = $xhigh;

            foreach($dnsl as $dns){

                //covert to accecptable format for the comparerange
                List($ylow, $yhigh) = cidrConv($dns);

                //check if no valid test range exist
                if (is_null($xmin) || is_null($xmax)){
                    //break;
    
                    return array($in,$out);
    
                }else{
                    list($ylow,$yhigh)=cidrConv($dns);
    
                    if($xlow>$yhigh || $xhigh<$ylow){//#completely keep
        
                        $xmin = $xlow;
                        $xmax = $xhigh;
                
                    }elseif($xlow>=$ylow && $xhigh<=$yhigh){//#completely out 
            
                        $xmin = NULL;
                        $xmax = NULL;
                        
                        $outip = new Ip;
                        $outip->low = $xlow;
                        $outip->high = $xhigh;
                        array_push($out,$outip);   
            
                    }elseif($xlow>=$ylow && $xhigh>$yhigh && $xlow<=$yhigh){//#parcially keep right
            
                        $xmin = long2ip($yhigh+1);
                        $xmax = $xhigh;
                        
                        $outip = new Ip;
                        $outip->low = $xlow;
                        $outip->high = $yhigh;
                        array_push($out,$outip);
            
                    }elseif($xhigh<=$yhigh && $xlow<$ylow && $xhigh>=$ylow){//#parcially keep left
            
                        $xmin = $xlow;
                        $xmax = long2ip($ylow-1);
                
                        $outip = new Ip;
                        $outip->low = $xlow;
                        $outip->high = $yhigh;
                        array_push($out,$outip);
            
                    }elseif($xlow<$ylow && $xhigh>$yhigh){//#completely in
            
                        $xmin = $xlow;
                        $xmax = $xhigh;
            
                        $in1 = $ylow-1;
                        $in2 = $yhigh+1;
            
                        array_push($borders,$in1);
                        array_push($borders,$in2);

                        $outip = new Ip;
                        $outip->low = $ylow;
                        $outip->high = $yhigh;
                        array_push($out,$outip);
                    }
                }
            } 


            if (sizeof($borders) !== 0){
            
                array_push($borders,$xmin);
                array_push($borders,$xmax);
    
                sort($borders); //sort the array in ascending order
    
                for($i = 0; $i<length($borders); $i+=2){// iterate 2 items at a time to get low and high IP
    
                    $inip = new IP;
                    $inip->low = $borders[i];
                    $inip->high = $borders[i+1];
    
                    array_push($in,$inip);
                }
            }else{//no borders found (no 'completely in' situation)
    
                $inip = new IP;
                $inip->low = $xmin;
                $inip->high = $xmax;
    
                array_push($in,$inip);
            }
    
        }
        
        return array($in,$out);
    }

    //TO DO -- accept the array of tes
    //test on $x keep or remove against SUBNET-LIST, and assign scan time
    function compareKeep($xlow,$xhigh){//$x is an array of IP variables

        //grab Subnet list into an array
        //$snl = array_map('str_getcsv', file('snl.csv'));//Subnet List

        $snl = array_map('str_getcsv', file('snl.csv'));
        array_walk($snl, function(&$a) use ($snl) {
          $a = array_combine($snl[0], $a);
        });
        array_shift($snl); # remove column header

        //arrays to store ranges that in & out
        $borders = array();
        $out = array();
        $in = array();

        foreach($input as $x){

            //initial $xlow and $xhigh value
            $xlow = $x->low;
            $xhigh = $x->high;

            //defult $xmin , $xmax value
            $xmin = $xlow;
            $xmax = $xhigh;

            foreach($snl as $sn){

                if (is_null($xmin) || is_null($xmax)){//completely out
                    //break;
    
                    return array($in,$out);
    
                }else{

                    //decode each $sn item
                    //$object = explode('&', $sn);

                    
                    //covert to accecptable format for the comparerange
                    //List($ylow, $yhigh) = cidrConv($object[0]);
                    List($ylow, $yhigh) = cidrConv($sn['Subnet With Mask']);

                    //determine the type of the scan
                    //$type = $object[1];
                    $type = $sn['Network Profile'];//TODO::assign individual type for scan

                    //#process the result based on parameters 
                    if($xlow>$yhigh || $xhigh<$ylow){//#completely out
        
                        $xmin = $xlow;
                        $xmax = $xhigh;
                
                    }elseif($xlow>=$ylow && $xhigh<=$yhigh){//#completely in
            
                        $xmin = NULL;
                        $xmax = NULL;
                        
                        $inip = new Ip;
                        $inip->low = $xlow;
                        $inip->high = $xhigh;
                        $inip->type = $type;
                        array_push($in,$inip);   
            
                    }elseif($xlow>=$ylow && $xhigh>$yhigh && $xlow<=$yhigh){//#parcially keep right
            
                        $xmin = long2ip($yhigh+1);
                        $xmax = $xhigh;
                        
                        $inip = new Ip;
                        $inip->low = $xlow;
                        $inip->high = $yhigh;
                        $inip->type = $type;
                        array_push($in,$inip);
            
                    }elseif($xhigh<=$yhigh && $xlow<$ylow && $xhigh>=$ylow){//#parcially keep left
            
                        $xmin = $xlow;
                        $xmax = long2ip($ylow-1);
                
                        $inip = new Ip;
                        $inip->low = $xlow;
                        $inip->high = $yhigh;
                        $inip->type = $type;
                        array_push($in,$inip);
            
                    }elseif($xlow<$ylow && $xhigh>$yhigh){//#completely in
            
                        $xmin = $xlow;
                        $xmax = $xhigh;
                        
                        $out1 = $ylow-1;
                        $out2 = $yhigh+1;
            
                        array_push($borders,$out1);
                        array_push($borders,$out2);

                        $inip = new Ip;
                        $inip->low = $ylow;
                        $inip->high = $yhigh;
                        $inip->type = $type;
                        array_push($in,$inip);
                    }
                }
            } 


            if (sizeof($borders) !== 0){
            
                array_push($borders,$xmin);
                array_push($borders,$xmax);
    
                sort($borders); //sort the array in ascending order
    
                for($i = 0; $i<length($borders); $i+=2){// iterate 2 items at a time to get low and high IP
    
                    $outip = new IP;
                    $outip->low = $borders[i];
                    $outip->high = $borders[i+1];
    
                    array_push($out,$outip);
                }
            }else{//no borders found (no 'completely in' situation)
    
                $outip = new IP;
                $outip->low = $xmin;
                $outip->high = $xmax;
    
                array_push($out,$outip);
            }

        }

        return array($in,$out);
    }

    function assignTime($string){
        
        //map timezone based on location
        if (strpos($string, '-AU-ACT-') !== false) {   
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Australia/ACT'));
        }elseif(strpos($string, '-AU-NSW-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Australia/NSW'));
        }elseif(strpos($string, '-AU-NT-') !== false){//to be confirmed
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Australia/North'));
        }elseif(strpos($string, '-AU-QLD-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Australia/Queensland'));
        }elseif(strpos($string, '-AU-SA-') !== false){//to be confirmed
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Australia/South'));
        }elseif(strpos($string, '-AU-TAS-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Australia/Tasmania'));
        }elseif(strpos($string, '-AU-VIC-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Australia/Victoria'));
        }elseif(strpos($string, '-AU-WA-') !== false){//to be confirmed
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Australia/West'));
        }elseif(strpos($string, '-AmericanSamoa-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Pacific/Pago_Pago'));
        }elseif(strpos($string, '-Cambodia-') !== false){//capital city
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Phnom_Penh'));
        }elseif(strpos($string, '-CookIslands-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Pacific/Rarotonga'));
        }elseif(strpos($string, '-Fiji-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Pacific/Fiji'));
        }elseif(strpos($string, '-France-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Europe/Paris'));
        }elseif(strpos($string, '-Germany-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Europe/Berlin'));
        }elseif(strpos($string, '-Guam-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Pacific/Guam'));
        }elseif(strpos($string, '-Hongkong-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Hong_Kong'));
        }elseif(strpos($string, '-Japan-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Tokyo'));
        }elseif(strpos($string, '-Laos-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Vientiane'));
        }elseif(strpos($string, '-NZ-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Pacific/Auckland'));
        }elseif(strpos($string, '-Philippines') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Manila'));
        }elseif(strpos($string, '-PNG-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Pacific/Port_Moresby'));
        }elseif(strpos($string, '-Samoa-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Pacific/Samoa'));
        }elseif(strpos($string, '-Singapore-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Singapore'));
        }elseif(strpos($string, '-SouthKorea-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Seoul'));
        }elseif(strpos($string, '-Taiwan-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Taipei'));
        }elseif(strpos($string, '-Thailand-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Bangkok'));
        }elseif(strpos($string, '-UAE-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Dubai'));
        }elseif(strpos($string, '-UK-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Europe/London'));
        }elseif(strpos($string, '-China') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Chongqing'));
        }elseif(strpos($string, '-EastTimor-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Dili'));
        }elseif(strpos($string, '-Kiribati-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Pacific/Tarawa'));
        }elseif(strpos($string, '-Myanmar-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Vientiane'));
        }elseif(strpos($string, '-Tonga-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Pacific/Tongatapu'));
        }elseif(strpos($string, '-USA-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('America/New_York'));
        }elseif(strpos($string, '-Vanuatu-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Pacific/Guadalcanal'));
        }elseif(strpos($string, '-Vietnam-') !== false){
            $localtime = new DateTime(strtotime('today 10pm'), new DateTimeZone('Asia/Ho_Chi_Minh'));
        }

        //set runtime according to the server local time
        $runtime = $datetime->setTimezone('Australia/Melbourne');
        //echo $runtime->format('Y-m-d H:i:s');
        
        return $runtime;//UTC time equivelant for 7pm local time
    }

    function checkString($string){

        //remove all whitespace in the string
        $string = preg_replace('/\s+/','',$string);

        //break up the string into a list of IP ranges
        $list = explode(',',$string);

        //initiate array to store result
        $stringresult= array();
        $dnsin = array();//the kept Ip ranges after checking do not scan list
        $snin = array();//the kept Ip ranges after checking subnet list
        $dnsout = array();//the out Ip ranges after checking do not scan list
        $snout = array();//the out Ip ranges after checking do not scan list
        $error = array();

        //iterate over the input list


        foreach ($list as $range){
            //grab the IP section of the $range
            $ip = explode('/', $range); 

            //check if the item in the input list is valid CIDR or IP
            if(filter_var($ip[0], FILTER_FLAG_IPV4)){
                
                if(strpos($range,'/') !== true){//process for ip address in 1.1.1.1 format
                    //convert into the format of CIDR
                    $range = $range.'/32';
                }

                //covert to accecptable format for the comparerange
                $range0 = new IP;
                List($range0->low, $range0->high) = cidrConv($range);

                array_push($stringresult,$range0);
                
            }else{// not a valid IP address
                
                array_push($error, $range);
            }

        }
        //$out = array_merge($out,$tempout);
        //check against the do-not-scan-list
        List($dnsin,$dnsout) = compareRemove($stringresult);

        //check against the subnet-list
        List($snin,$snout) = compareKeep($dnsin);

    }

}
