<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IPinput implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value, $must_cidr = false)
    {   
        //remove white space
        $value = preg_replace('/\s+/','',$value);
        //explode to individual ip
        $cidrlist = explode(",", $value);

        foreach($cidrlist as $cidr){
            if (!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}(\/[0-9]{1,2})?$/", $cidr))
            {
                return false;
            }else{
                $parts = explode("/", $cidr);
                $ip = $parts[0];
                $netmask = 32;//default to be ip address
                if (strpos($cidr,'/') !== false){
                    $netmask = $parts[1];
                }
                $octets = explode(".", $ip);
                foreach ($octets as $octet)
                {
                    if ($octet > 255)
                    {
                        
                        return false;
                    }
                }
                if ((($netmask != "") && ($netmask > 32) && !$must_cidr) || (($netmask == ""||$netmask > 32) && $must_cidr))
                {
                    return false;
                }
            }
        }
        return true;     
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '[Wrong Input] Please put in the IP ranges with the correct format. (e.g.123.123.123.123, 123.123.123.123/24)';
    }
}
