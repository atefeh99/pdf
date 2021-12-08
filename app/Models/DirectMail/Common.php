<?php


namespace App\Models\DirectMail;


use Illuminate\Support\Facades\Log;

trait Common
{
     public $state_name = [
         'سیستان و بلوچستان'=>'سیستان',
         'سیستان وبلوچستان'=>'سیستان',
         'چهارمحال و بختیاری'=>'چهارمحال',
         'چهارمحال وبختیاری'=>'چهارمحال',
         'کهگیلویه و بویراحمد'=>'کهگیلویه',
         'کهگیلویه وبویراحمد'=>'کهگیلویه'
     ];
    public function getCountryDivisionAttribute($value)
    {
        $result = '';
        if ($this->attributes['statename']) {

            Log::info($this->attributes['statename']);

            $result .= 'استان ' . $this->attributes['statename'];
            if (($this->attributes['townname'])
                || ($this->attributes['zonename'])
                || ($this->attributes['villagename'])
                || ($this->attributes['locationtype']
                    && $this->attributes['locationname'])
            ) $result .= '،';
//
        }

        if ($this->attributes['locationtype'] == 'شهر' && mb_strlen($result) >= 15) {
            $result = str_replace("استان ", "", $result);

            if (mb_strlen($result) >= 15 && array_key_exists($this->attributes['statename'],$this->state_name)) {
                $result = str_replace($this->attributes['statename'], $this->state_name[$this->attributes['statename']], $result);
            }

        }

        if ($this->attributes['townname']) {
            $result .= 'شهرستان ';

            $result .= $this->attributes['townname'];
            if (($this->attributes['zonename'])
                || ($this->attributes['villagename'])
                || ($this->attributes['locationtype']
                    && $this->attributes['locationname'])
            ) $result .= '،';
        }

        if (mb_strlen($result) >= 20 && $this->attributes['locationtype'] != 'شهر') {
            $result = str_replace('استان ', "", $result);

        }

        if ($this->attributes['zonename'] && $this->attributes['locationtype'] != 'شهر') {

            $result .= 'بخش ' . $this->attributes['zonename'];
            if (($this->attributes['villagename'])
                || ($this->attributes['locationtype']
                    && $this->attributes['locationname'])
            ) $result .= '،';
        }

        if ($this->attributes['villagename']) {
            $result .= 'دهستان ';
            $result .= $this->attributes['villagename'];
            if (($this->attributes['locationtype']
                && $this->attributes['locationname'])
            ) $result .= '،';
        }

        if (mb_strlen($result) >= 40 && $this->attributes['locationtype'] != 'شهر') {
            $result = str_replace('شهرستان ', "", $result);
            if (mb_strlen($result) >= 40 && array_key_exists($this->attributes['statename'],$this->state_name)) {
                $result = str_replace($this->attributes['statename'], $this->state_name[$this->attributes['statename']], $result);
            }
        }

        if ($this->attributes['locationtype']
            && $this->attributes['locationname']
        ) {
            $result .= $this->attributes['locationtype'];
            $result .= ':';
            $result .= $this->attributes['locationname'];
        }
        return $result;

    }

    public function getParishAndWayAttribute($value)
    {
        $result = '';
        if ($this->attributes["parish"]) {
            Log::info($this->attributes["parish"]);

            $result .= $this->attributes["parish"];

            if (($this->attributes["mainavenue"])
                || ($this->attributes["preaventypename"] && $this->attributes["preaven"])
                || ($this->attributes["avenuetypename"] && $this->attributes["avenue"])
            ) $result .= '،';

        }
        if ($this->attributes["mainavenue"]) {

            $result .= $this->attributes["mainavenue"];
            if (($this->attributes["preaventypename"] && $this->attributes["preaven"])
                || ($this->attributes["avenuetypename"] && $this->attributes["avenue"])
            ) $result .= '،';
        }
        if ($this->attributes["preaventypename"] && $this->attributes["preaven"]) {

            $result .= $this->attributes['preaventypename'] . ' ' . $this->attributes["preaven"];
            if (($this->attributes["avenuetypename"] && $this->attributes["avenue"])
            ) $result .= '،';
        }
        if ($this->attributes["avenuetypename"] && $this->attributes["avenue"]) {

            $result .= $this->attributes["avenuetypename"] . ' ' . $this->attributes["avenue"];
        }
        if ($this->attributes['postalcode'] == '8957135616' || $this->attributes['postalcode'] == 8957135616)
            var_dump(mb_strlen($result));
        return $result;

    }

    public function getPelakAndEntranceAttribute($value)
    {
        $result = '';
        if ($this->attributes["plate_no"]) {
            Log::info($this->attributes["plate_no"]);
            $result .= 'پلاک ' . $this->attributes["plate_no"];

            if (($this->attributes["building"])
                || ($this->attributes["blockno"])
                || ($this->attributes["floorno"])
                || ($this->attributes["floorno"] == 0)
                || ($this->attributes["unit"])
            ) $result .= '،';

        }
        if ($this->attributes["building"]) {
            $result .= $this->attributes["building"];
            if (($this->attributes["blockno"])
                || ($this->attributes["floorno"])
                || ($this->attributes["floorno"] == 0)
                || ($this->attributes["unit"])
            ) $result .= '،';
        }
        if ($this->attributes["blockno"]) {

            $result .= 'بلوک ' . $this->attributes['blockno'];
            if (($this->attributes["floorno"])
                || ($this->attributes["floorno"] == 0)
                || ($this->attributes["unit"])
            ) $result .= '،';
        }
        if ($this->attributes["floorno"] || $this->attributes["floorno"] == 0) {
            $result .= 'طبقه ';
            if ($this->attributes["floorno"] == 0) {
                $result .= 'همکف';
            } else {
                $result .= $this->attributes["floorno"];
            }
            if ($this->attributes["unit"]) $result .= '،';
        }
        if ($this->attributes["unit"]) {
            $result .= 'واحد ' . $this->attributes["unit"];

        }

        return $result;

    }
}
