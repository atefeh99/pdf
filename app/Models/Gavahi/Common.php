<?php

namespace App\Models\Gavahi;

use Illuminate\Support\Facades\Log;

trait Common
{
    public function getAddressAttribute($value)
    {
//        TODO if key exist; blockno
        $result = '';

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
            $result .= 'پلاک ' . $this->attributes["plate_no"];

            if (!empty($this->attributes["building"])
                || !empty($this->attributes["blockno"])
                || !empty($this->attributes["floorno"])
                || ($this->attributes["floorno"] == 0)
                || !empty($this->attributes["unit"])
                || !empty($this->attributes["blockno"])
            ) $result .= '،';

        }

        if (!empty($this->attributes["entrance"])) {
            $result .= ' ' . $this->attributes['entrance'];
            if (!empty($this->attributes["building"])
                || !empty($this->attributes["floorno"])
                || ($this->attributes["floorno"] == 0)
                || !empty($this->attributes["unit"])
            ) $result .= '،';
        }
        if (!empty($this->attributes["building"])) {
            $result .= $this->attributes["building"];
            if (!empty($this->attributes["floorno"])
                || ($this->attributes["floorno"] == 0)
                || !empty($this->attributes["unit"])
            ) $result .= '،';
        }
        if (isset($this->attributes["floorno"])) {
            $result .= 'طبقه ';
            if ($this->attributes["floorno"] == 0) {
                $result .= 'همکف';
            } else {
                $result .= $this->attributes["floorno"];
            }
            if (!empty($this->attributes["unit"])) $result .= '،';
        }
        if (!empty($this->attributes["unit"])) {
            $result .= 'واحد ' . $this->attributes["unit"];

        }

        return $result;

    }

    public function getPoiTypeNameAttribute($value)
    {
        return preg_replace('/\((-)?[0-9]\)/', '', $value);
    }
}
