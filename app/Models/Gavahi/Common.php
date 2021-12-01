<?php

namespace App\Models\Gavahi;

use Illuminate\Support\Facades\Log;

trait Common
{
    public function getAddressAttribute($value)
    {
//        TODO if key exist; blockno
        $result = '';
//        if (array_key_exists('statename', $this->attributes)) {
//            $result = 'استان ';
//            $result .= $this->attributes['statename'];
//            $result .= '، ';
//        }
////        city
//        if (array_key_exists('locationtype', $this->attributes)
//            && array_key_exists('locationname', $this->attributes)) {
//            if ($this->attributes['locationtype'] == 'شهر' &&
//                $this->attributes['locationname']) {
//
//                $result .= 'شهر ';
//                $result .= $this->attributes['locationname'];
//                $result .= '، ';
//            }
//        }

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

        if (
            array_key_exists('preaventypename', $this->attributes)
            && array_key_exists('preaven', $this->attributes)
            && array_key_exists('avenue', $this->attributes)
            && array_key_exists('avenuetypename', $this->attributes)
        ) {
            if ($this->attributes['preaventypename'] ||
                $this->attributes['preaven']) {
                $result .= $this->attributes['preaventypename'];
                $result .= ' ';
                $result .= $this->attributes['preaven'];
            }
            if (($this->attributes['preaventypename'] ||
                    $this->attributes['preaven']) && (
                    $this->attributes['avenuetypename'] ||
                    $this->attributes['avenue']
                )) {
                $result .= '/';
            }
            if ($this->attributes['avenuetypename'] ||
                $this->attributes['avenue']) {
                $result .= $this->attributes['avenuetypename'];
                $result .= ' ';
                $result .= $this->attributes['avenue'];
            }
            if (($this->attributes['preaventypename'] ||
                    $this->attributes['preaven']) || (
                    $this->attributes['avenuetypename'] ||
                    $this->attributes['avenue']
                )) {
                $result .= '، ';
            }
        }
//        plateno
        if (array_key_exists('plate_no', $this->attributes)
            && $this->attributes['plate_no']) {
            $result .= 'پلاک ';
            $result .= $this->attributes['plate_no'];
        }
//        floor
        if (array_key_exists('floorno', $this->attributes)) {
            $result .= '، ';
            $result .= 'طبقه ';
            $result .= ((int)$this->attributes['floorno'] == 0) ?
                'همکف' : $this->attributes['floorno'];
        }


//        unit
        if (array_key_exists('unit', $this->attributes)
            && $this->attributes['unit']) {
            $result .= '، ';
            $result .= 'واحد ';
            $result .= $this->attributes['unit'];
        }


//        postalcode
//        if (array_key_exists('postalcode', $this->attributes)
//            && $this->attributes['postalcode']) {
//            $result .= 'کد پستی:';
//            $result .= $this->attributes['postalcode'];
//        }


        return $result;
    }

    public function getPostAddressAttribute($value)
    {
        $result = '';
        if ($this->attributes['statename']) {
//            $result .= 'استان ';
            Log::info($this->attributes['statename']);

            $result .= $this->attributes['statename'];
            if (($this->attributes['townname'])
                || ($this->attributes['zonename'])
                || ($this->attributes['villagename'])
                || ($this->attributes['locationtype']
                    && $this->attributes['locationname'])
            ) $result .= '،';

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
        if ($this->attributes['zonename']) {
            if ($this->attributes['locationtype'] == 'شهر') {
                $result .= 'بخش ';
            }
            $result .= $this->attributes['zonename'];
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

            $result .= 'محله: '.$this->attributes["parish"];

            if (($this->attributes["preaventypename"] && $this->attributes["preaven"])
                || ($this->attributes["avenuetypename"] && $this->attributes["avenue"]
                    || $this->attributes["plate_no"]
                    || $this->attributes["building"]
                    || $this->attributes["blockno"]
                    || $this->attributes["floorno"]
                    || $this->attributes["floorno"] == 0)
            ) $result .= '،';

        }

        if ($this->attributes["preaventypename"] && $this->attributes["preaven"]) {

            $result .= 'معبر ماقبل آخر:'.$this->attributes['preaventypename'] . ' ' . $this->attributes["preaven"];
            if (($this->attributes["avenuetypename"] && $this->attributes["avenue"])
                || $this->attributes["plate_no"]
                || $this->attributes["building"]
                || $this->attributes["blockno"]
                || $this->attributes["floorno"]
                || $this->attributes["floorno"] == 0
            ) $result .= '،';
        }
        if ($this->attributes["avenuetypename"] && $this->attributes["avenue"]) {

            $result .= 'معبر آخر:'.$this->attributes["avenuetypename"] . ' ' . $this->attributes["avenue"];
            if ($this->attributes["plate_no"]
                || $this->attributes["building"]
                || $this->attributes["blockno"]
                || $this->attributes["floorno"]
                || $this->attributes["floorno"] == 0
            ) $result .= '،';
        }

        if ($this->attributes["plate_no"]) {
            Log::info($this->attributes["plate_no"]);
            $result .= 'پلاک ' . $this->attributes["plate_no"];

            if (($this->attributes["building"])
                || ($this->attributes["blockno"])
                || ($this->attributes["floorno"])
                || ($this->attributes["floorno"] == 0)
                || ($this->attributes["unit"]
                    || $this->attributes["blockno"])
            ) $result .= '،';

        }

        if ($this->attributes["blockno"]) {
            $result .= 'ورودی/ بلوک: ' . $this->attributes['blockno'];
            if ($this->attributes["building"]
                ||($this->attributes["floorno"])
                || ($this->attributes["floorno"] == 0)
                || ($this->attributes["unit"])
            ) $result .= '،';
        }
        if ($this->attributes["building"]) {
            $result .= $this->attributes["building"];
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
