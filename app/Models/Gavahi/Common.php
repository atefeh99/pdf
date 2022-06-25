<?php

namespace App\Models\Gavahi;

use Illuminate\Support\Facades\Log;

trait Common
{
    public function getAddressAttribute($value)
    {
//        TODO if key exist; blockno
        $result = '';
        $address['part1'] = null;
        $address['plate_sign'] = null;
        $address['part2'] = null;
        $address['part3'] = null;
        $address['part4'] = null;
        $address['floor_sign'] = null;
        $address['part5'] = null;
        $address["floor_is_neg"] = false;


//        parish
        if (array_key_exists('parish', $this->attributes)
            && array_key_exists('tour', $this->attributes)) {
            if ($this->attributes['parish']) {
                $result .= $this->attributes['parish'];
            }
            if ($this->attributes['parish'] && $this->attributes['tour']) {
                $result .= '/';
            }
            if ($this->attributes['tour']) {
                $result .= $this->attributes['tour'];
            }
            if ($this->attributes['parish'] || $this->attributes['tour']) {
                $result .= '، ';
            }
        }

        if (array_key_exists('avenue', $this->attributes)
            && array_key_exists('avenuetypename', $this->attributes)
        ) {
            if ($this->attributes['avenuetypename'] ||
                $this->attributes['avenue']) {
                $result .= $this->attributes['avenuetypename'];
                $result .= ' ';
                $result .= $this->attributes['avenue'];
            }
            if ($this->attributes['avenuetypename'] ||
                $this->attributes['avenue']
            ) {
                $result .= '، ';
            }
        }
//        plateno
        if (array_key_exists('plate_no', $this->attributes)
            && $this->attributes['plate_no']) {
            $result .= 'پلاک ';
            $address['part1'] .= $result;

            if($this->attributes['plate_no'] < 0){
                $address['plate_sign'] = '-';
            }

            $address['part2'] .= abs($this->attributes['plate_no']);
        }
//        floor
        if (array_key_exists('floorno', $this->attributes)) {
            $address['part3'] .= '، ';
            $address['part3'] .= 'طبقه ';
            if($this->attributes['floorno'] < 0){
                $address['floor_sign'] = '-';
                $address["floor_is_neg"] = true;
                $address['part4'] = (int)abs($this->attributes['floorno']);
            } else{
            $address['part4'] .= ((int)$this->attributes['floorno'] == 0) ?
                'همکف' : $this->attributes['floorno'];
            }
        }
//        unit
        if (array_key_exists('unit', $this->attributes)
            && $this->attributes['unit']) {
            $address['part5'] .= '، ';
            $address['part5'] .= 'واحد ';
            $address['part5'] .= $this->attributes['unit'];
        }
        return $address;
    }

    public function getCountryDivisionAttribute($value)
    {
        $result = '';

        if (!empty($this->attributes["statename"])) {
//            $result .= 'استان ';
            Log::info($this->attributes["statename"]);

            $result .= $this->attributes["statename"];
            if (!empty(($this->attributes["townname"]))
                || !empty($this->attributes['zonename'])
                || !empty($this->attributes['villagename'])
                || !empty($this->attributes['locationtype'])
                && !empty($this->attributes['locationname'])
            ) $result .= '،';

        }
        if (!empty($this->attributes['townname'])) {
            $result .= 'شهرستان ';
            $result .= $this->attributes['townname'];
            if (!empty($this->attributes['zonename'])
                || !empty($this->attributes['villagename'])
                || !empty($this->attributes['locationtype'])
                && !empty($this->attributes['locationname'])
            ) $result .= '،';
        }
        if (!empty($this->attributes['zonename'])) {
            if ($this->attributes['locationtype'] == 'شهر') {
                $result .= 'بخش ';
            }
            $result .= $this->attributes['zonename'];
            if (!empty(($this->attributes['villagename']))
                || !empty(($this->attributes['locationtype'])
                    && !empty($this->attributes['locationname']))
            ) $result .= '،';
        }
        if (!empty($this->attributes['villagename'])) {
            $result .= 'دهستان ';
            $result .= $this->attributes['villagename'];
            if (!empty($this->attributes['locationtype'])
                && !empty($this->attributes['locationname'])
            ) $result .= '،';
        }
        if (!empty($this->attributes['locationtype'])
            && !empty($this->attributes['locationname'])
        ) {
            $result .= $this->attributes['locationtype'];
            $result .= ':';
            $result .= $this->attributes['locationname'];
        }
        return $result;

    }

    public function getPostAddressAttribute($value)
    {
        $result = '';
        $post_address['part1'] = null;
        $post_address['plate_sign'] = null;
        $post_address['part2'] = null;
        $post_address['part3'] = null;
        $post_address['part4'] = null;
        $post_address['part5'] = null;
        $post_address['part6'] = null;
        $post_address['part7'] = null;
        $post_address['floor_sign'] = null;
        $post_address['part8'] = null;
        $post_address['part9'] = null;
        $post_address["floor_is_neg"] = false;
        $post_address['part10'] = null;


        if (!empty($this->attributes["parish"])) {
            Log::info($this->attributes["parish"]);

            $result .= 'محله: ' . $this->attributes["parish"];

            if (!empty($this->attributes["preaventypename"]) && !empty($this->attributes["preaven"])
                || !empty($this->attributes["avenuetypename"]) && !empty($this->attributes["avenue"])
                || !empty($this->attributes["plate_no"])
                || !empty($this->attributes["building"])
                || !empty($this->attributes["blockno"])
                || !empty($this->attributes["floorno"])
                || $this->attributes["floorno"] == 0
            ) $result .= '،';

        }

        if (!empty($this->attributes["preaventypename"]) && !empty($this->attributes["preaven"])) {

            $result .= 'معبر ماقبل آخر:' . $this->attributes['preaventypename'] . ' ' . $this->attributes["preaven"];
            if (!empty($this->attributes["avenuetypename"]) && !empty($this->attributes["avenue"])
                || !empty($this->attributes["plate_no"])
                || !empty($this->attributes["building"])
                || !empty($this->attributes["blockno"])
                || !empty($this->attributes["floorno"])
                || $this->attributes["floorno"] == 0
            ) $result .= '،';
        }
        if (!empty($this->attributes["avenuetypename"]) && !empty($this->attributes["avenue"])) {

            $result .= 'معبر آخر:' . $this->attributes["avenuetypename"] . ' ' . $this->attributes["avenue"];
            if (!empty($this->attributes["plate_no"])
                || !empty($this->attributes["building"])
                || !empty($this->attributes["blockno"])
                || !empty($this->attributes["floorno"])
                || $this->attributes["floorno"] == 0
            ) $result .= '،';
        }

        if (!empty($this->attributes["plate_no"])) {
            Log::info($this->attributes["plate_no"]);
            $result .= 'پلاک ' . abs($this->attributes["plate_no"]);
            $post_address['part1'] .= $result;

            if($this->attributes["plate_no"] < 0 ){
                $post_address['plate_sign'] = '-';
            }
            if (!empty($this->attributes["building"])
                || !empty($this->attributes["blockno"])
                || !empty($this->attributes["floorno"])
                || ($this->attributes["floorno"] == 0)
                || !empty($this->attributes["unit"])
                || !empty($this->attributes["blockno"])
            ) $post_address['part2'] .= '،';

        }

        if (!empty($this->attributes["entrance"])) {
            $post_address['part3'] .= ' ' . $this->attributes['entrance'];
            if (!empty($this->attributes["building"])
                || !empty($this->attributes["floorno"])
                || ($this->attributes["floorno"] == 0)
                || !empty($this->attributes["unit"])
            ) $post_address['part4'] .= '،';
        }
        if (!empty($this->attributes["building"])) {
            $post_address['part5'] .= $this->attributes["building"];
            if (!empty($this->attributes["floorno"])
                || ($this->attributes["floorno"] == 0)
                || !empty($this->attributes["unit"])
            ) $post_address['part6'] .= '،';
        }
        if (isset($this->attributes["floorno"])) {
            $post_address['part7'] .= 'طبقه ';
            if ($this->attributes["floorno"] == 0) {
                $post_address['part8'] .= 'همکف';
            } else {
                if($this->attributes["floorno"] < 0 ){
                    $post_address['floor_sign'] .= '-';
                    $post_address["floor_is_neg"] = true;
                }
                $post_address['part8'] .= abs($this->attributes["floorno"]);
            }
            if (!empty($this->attributes["unit"])) $post_address['part9'] .= '،';
        }
        if (!empty($this->attributes["unit"])) {
            $post_address['part10'] .= 'واحد ' . $this->attributes["unit"];

        }

        return $post_address;

    }

    public function getPoiTypeNameAttribute($value)
    {
        return preg_replace('/\((-)?[0-9]\)/', '', $value);
    }
}
