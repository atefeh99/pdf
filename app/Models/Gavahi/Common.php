<?php

namespace App\Models\Gavahi;

use Illuminate\Support\Facades\Log;

trait Common
{

    public function getAddressAttribute($value)
    {
//        TODO if key exist; blockno
        $result = null;
        $address['part1'] = null;
        $address['part2'] = null;
        $address['part3'] = null;
        $address['part4'] = null;
        $address['part5'] = null;
        $address['part6'] = null;
        $address['part7'] = null;
        $address["floor_is_neg"] = false;
        $address['plate_sign'] = null;
        $address['floor_sign'] = null;

        $parish_not_null = !empty($this->attributes['parish']);
        $tour_not_null = isset($this->attributes['tour']);
        $avenue_not_null = !empty($this->attributes['avenue']);
        $avenue_type_not_null = !empty($this->attributes['avenuetypename']);
        $plate_not_null = isset($this->attributes['plate_no']);
        $floorno_not_null = isset($this->attributes['floorno']);
        $unit_not_null = !empty($this->attributes['unit']);
        // $this->attributes['parish'] = "";
        // $this->attributes['tour'] = null;
        // $this->attributes['avenue'] = '';
        // $this->attributes['plate_no'] = -14;
        // $this->attributes['floorno'] = -89;
        // $this->attributes['unit'] = '';
//        parish
        if ($parish_not_null
            && $tour_not_null) {
            if ($this->attributes['parish']) {
                $result .= $this->attributes['parish'];
            }
//            if ($this->attributes['parish'] && $this->attributes['tour']) {
            $result .= '/';
//            }
//            if ($this->attributes['tour']) {
            $result .= $this->attributes['tour'];
//            }
            if (
                !empty($result)
                && (
                    (($avenue_not_null && $this->attributes['avenue']) || ($avenue_type_not_null && $this->attributes['avenuetypename']))
                    || $plate_not_null
                    || $floorno_not_null
                    || ($unit_not_null && $this->attributes['unit'])
                )
            ) {
                $result .= '، ';
            }
        }


        if ($avenue_not_null
            &&
            $avenue_type_not_null
        ) {
            if ($this->attributes['avenuetypename'] ||
                $this->attributes['avenue']) {
                $result .= $this->attributes['avenuetypename'];
                $result .= ' ';
                $result .= $this->attributes['avenue'];
            }
            if (
                (($parish_not_null && $this->attributes['parish'])
                    || ($tour_not_null)
                    || ($this->attributes['avenue'] || $this->attributes['avenuetypename'])
                )
                && (
                    $plate_not_null
                    || $floorno_not_null
                    || ($unit_not_null && $this->attributes['unit'])
                )
            ) {
                $result .= '، ';
            }
        }

//        plateno
        if ($plate_not_null) {
            $result .= 'پلاک ';
            if ($this->attributes['plate_no'] < 0) {
                $address['plate_sign'] = '-';
            }
            $address['part2'] .= abs($this->attributes['plate_no']);

            if (
                (
                    ($parish_not_null && $this->attributes['parish'])
                    || $tour_not_null
                    || (($avenue_not_null && $this->attributes['avenue'])
                        || ($avenue_type_not_null && $this->attributes['avenuetypename']))
                )
                && (

                    $floorno_not_null
                    || ($unit_not_null && $this->attributes['unit'])
                )
            ) {
                $address['part3'] = '،';
            }
        }
        $address['part1'] .= $result;

//        floor
        if ($floorno_not_null) {
            $address['part4'] .= 'طبقه ';
            if ($this->attributes['floorno'] < 0) {
                $address['floor_sign'] = '-';
                $address["floor_is_neg"] = true;
                $address['part5'] = (int)abs($this->attributes['floorno']);
            } else {
                $address['part5'] .= ((int)$this->attributes['floorno'] == 0) ?
                    'همکف' : $this->attributes['floorno'];
            }
            if (
                (
                    ($parish_not_null && $this->attributes['parish'])
                    || $tour_not_null
                    || (($avenue_not_null && $this->attributes['avenue'])
                        || ($avenue_type_not_null && $this->attributes['avenuetypename']))
                    || $plate_not_null
                )
                &&
                (
                    $unit_not_null && $this->attributes['unit']
                )
            ) {
                $address['part6'] = '،';
            }
        }

//        unit
        if ($unit_not_null) {
            if ($this->attributes['unit']) {
                $address['part7'] .= 'واحد ';
                $address['part7'] .= $this->attributes['unit'];
            }
        }
        return $address;
    }

    public function getCountryDivisionAttribute($value)
    {
        $result = '';

        $statename_not_null = !empty($this->attributes["statename"]);
        $townname_not_null = !empty($this->attributes["townname"]);
        $zonename_not_null = !empty($this->attributes['zonename']);
        $villagename_not_null = !empty($this->attributes['villagename']);
        $locationtype_not_null = !empty($this->attributes['locationtype']);
        $locationname_not_null = !empty($this->attributes['locationname']);

        if ($statename_not_null) {
//            $result .= 'استان ';
            if ($this->attributes["statename"]) {
                $result .= $this->attributes["statename"];
            }
            if (
                $this->attributes["statename"]
                && (
                    ($townname_not_null && $this->attributes["townname"])
                    || ($zonename_not_null && $this->attributes['zonename'])
                    || ($villagename_not_null && $this->attributes['villagename'])
                    || (
                        ($locationtype_not_null && $this->attributes['locationtype'])
                        || ($locationname_not_null && $this->attributes['locationname'])
                    )
                )
            ) $result .= '،';

        }
        if ($townname_not_null) {
            if ($this->attributes['townname']) {
                $result .= 'شهرستان ';
                $result .= $this->attributes['townname'];
            }
            if (
                ($statename_not_null && $this->attributes["statename"])
                || ($this->attributes['townname'])
                && (
                    ($zonename_not_null && $this->attributes['zonename'])
                    || ($villagename_not_null && $this->attributes['villagename'])
                    || (
                        ($locationtype_not_null && $this->attributes['locationtype'])
                        || ($locationname_not_null && $this->attributes['locationname'])
                    )
                )
            ) $result .= '،';
        }
        if ($zonename_not_null) {
            if ($this->attributes['zonename']) {
                if ($this->attributes['locationtype'] == 'شهر') {
                    $result .= 'بخش ';
                }

                $result .= $this->attributes['zonename'];
            }
            if (
                ($statename_not_null && $this->attributes["statename"])
                || ($townname_not_null && $this->attributes['townname'])
                || $this->attributes['zonename']
                && (

                    ($villagename_not_null && $this->attributes['villagename'])
                    || (
                        ($locationtype_not_null && $this->attributes['locationtype'])
                        || ($locationname_not_null && $this->attributes['locationname'])
                    )
                )
            ) $result .= '،';
        }
        if ($villagename_not_null) {
            if ($this->attributes['villagename']) {
                $result .= 'دهستان ';
                $result .= $this->attributes['villagename'];
            }

            if (
                ($statename_not_null && $this->attributes["statename"])
                || ($townname_not_null && $this->attributes['townname'])
                || ($zonename_not_null && $this->attributes['zonename'])
                || $this->attributes['villagename']
                && (
                    ($locationtype_not_null && $this->attributes['locationtype'])
                    || ($locationname_not_null && $this->attributes['locationname'])
                )
            ) $result .= '،';
        }
        if ($locationtype_not_null
            && $locationname_not_null
        ) {
            if ($this->attributes['locationtype'] && $this->attributes['locationname']) {
                $result .= $this->attributes['locationtype'];
                $result .= ':';
                $result .= $this->attributes['locationname'];
            }

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

        $parish_not_null = !empty($this->attributes['parish']);
        $preaven_not_null = isset($this->attributes['preaven']);
        $preaven_type_not_null = isset($this->attributes['preaventypename']);
        $avenue_not_null = !empty($this->attributes['avenue']);
        $avenue_type_not_null = !empty($this->attributes['avenuetypename']);
        $plate_not_null = isset($this->attributes['plate_no']);
        $floorno_not_null = isset($this->attributes['floorno']);
        $unit_not_null = !empty($this->attributes['unit']);
        $entrance_not_null = !empty($this->attributes['entrance']);
        $building_not_null = !empty($this->attributes['building']);

        // $this->attributes['parish'] = "";
        // $this->attributes["preaventypename"] = null;
        // $this->attributes["preaven"] = null;
        // $this->attributes["avenuetypename"] = null;
        // $this->attributes["building"] = null;
        // $this->attributes['avenue'] = '';
        // $this->attributes['plate_no'] = -14;
        // $this->attributes['floorno'] = -89;
        // $this->attributes['unit'] = '142';

        if ($parish_not_null) {
            if ($this->attributes["parish"]) {
                $result .= 'محله: ' . $this->attributes["parish"];

            }


            if ($this->attributes["parish"]
                && (
                    (
                        ($preaven_type_not_null && $this->attributes["preaventypename"])
                        ||
                        ($preaven_not_null && $this->attributes["preaven"])
                    )

                    || (
                        ($avenue_type_not_null && $this->attributes["avenuetypename"])
                        ||
                        ($avenue_not_null && $this->attributes["avenue"])
                    )

                    || $plate_not_null
                    || ($building_not_null && $this->attributes["building"])
                    || ($entrance_not_null && $this->attributes["entrance"])
                    || ($unit_not_null && $this->attributes["unit"])
                    || $floorno_not_null
                )
            ) $result .= '،';

        }

        if ($preaven_type_not_null && $preaven_not_null) {
            if ($this->attributes["preaventypename"] && $this->attributes["preaven"]) {
                $result .= 'معبر ماقبل آخر:' . $this->attributes['preaventypename'] . ' ' . $this->attributes["preaven"];
            }
            if ((
                    ($parish_not_null && $this->attributes["parish"])
                    || ($this->attributes["preaventypename"] || $this->attributes["preaven"])
                )
                && (

                    (
                        ($avenue_type_not_null && $this->attributes["avenuetypename"])
                        ||
                        ($avenue_not_null && $this->attributes["avenue"])
                    )

                    || $plate_not_null
                    || ($building_not_null && $this->attributes["building"])
                    || ($entrance_not_null && $this->attributes["entrance"])
                    || ($unit_not_null && $this->attributes["unit"])
                    || $floorno_not_null
                )
            ) $result .= '،';

        }
        if ($avenue_type_not_null && $avenue_not_null) {
            if ($this->attributes["avenuetypename"] && $this->attributes["avenue"]) {
                $result .= 'معبر آخر:' . $this->attributes["avenuetypename"] . ' ' . $this->attributes["avenue"];
            }
            if ((
                    ($parish_not_null && $this->attributes["parish"])
                    || (
                        ($preaven_type_not_null && $this->attributes["preaventypename"])
                        ||
                        ($preaven_not_null && $this->attributes["preaven"])
                    )
                    || ($this->attributes["avenuetypename"] || $this->attributes["avenue"])
                )
                && (
                    $plate_not_null
                    || ($building_not_null && $this->attributes["building"])
                    || ($entrance_not_null && $this->attributes["entrance"])
                    || ($unit_not_null && $this->attributes["unit"])
                    || $floorno_not_null
                )
            ) $result .= '،';
        }

        if ($plate_not_null) {
            $result .= 'پلاک ' . abs($this->attributes["plate_no"]);

            if ($this->attributes["plate_no"] < 0) {
                $post_address['plate_sign'] = '-';
            }
            if ((
                    ($parish_not_null && $this->attributes["parish"])
                    || (
                        ($preaven_type_not_null && $this->attributes["preaventypename"])
                        ||
                        ($preaven_not_null && $this->attributes["preaven"])
                    )
                    || (
                        ($avenue_type_not_null && $this->attributes["avenuetypename"])
                        ||
                        ($avenue_not_null && $this->attributes["avenue"])
                    )
                )
                && (
                    ($building_not_null && $this->attributes["building"])
                    || ($entrance_not_null && $this->attributes["entrance"])
                    || ($unit_not_null && $this->attributes["unit"])
                    || $floorno_not_null
                )
            ) $post_address['part2'] .= '،';


        }
        $post_address['part1'] .= $result;


        if ($entrance_not_null) {
            if ($this->attributes["entrance"]) {
                $post_address['part3'] .= ' ' . $this->attributes['entrance'];

            }
            if ((
                    ($parish_not_null && $this->attributes["parish"])
                    || (
                        ($preaven_type_not_null && $this->attributes["preaventypename"])
                        ||
                        ($preaven_not_null && $this->attributes["preaven"])
                    )
                    || (
                        ($avenue_type_not_null && $this->attributes["avenuetypename"])
                        ||
                        ($avenue_not_null && $this->attributes["avenue"])
                    )
                    || $plate_not_null
                    || $this->attributes["entrance"]
                )
                && (
                    ($building_not_null&& $this->attributes["building"])
                    || ($unit_not_null && $this->attributes["unit"])
                    || $floorno_not_null
                )
            ) $post_address['part4'] .= '،';
        }
        if ($building_not_null) {
            if ($this->attributes["building"]) {
                $post_address['part5'] .= $this->attributes["building"];
            }
            if (
                (
                ($parish_not_null && $this->attributes["parish"])
                    || (
                        ($preaven_type_not_null && $this->attributes["preaventypename"])
                        || ($preaven_not_null && $this->attributes["preaven"])
                    )
                    || (
                        ($avenue_type_not_null && $this->attributes["avenuetypename"])
                        ||
                        ($avenue_not_null && $this->attributes["avenue"])
                    )
                    || $plate_not_null
                    || ($entrance_not_null && $this->attributes["entrance"])
                    || $this->attributes["building"]
                )
                && (
                    ($unit_not_null && $this->attributes["unit"])
                    ||$floorno_not_null
                )
            ) $post_address['part6'] .= '،';
        }
        if ($floorno_not_null) {
            $post_address['part7'] .= 'طبقه ';
            if ($this->attributes["floorno"] == 0) {
                $post_address['part8'] .= 'همکف';
            } else {
                if ($this->attributes["floorno"] < 0) {
                    $post_address['floor_sign'] .= '-';
                    $post_address["floor_is_neg"] = true;
                }
                $post_address['part8'] .= abs($this->attributes["floorno"]);
            }
            if ((
                    ($parish_not_null && $this->attributes["parish"])
                    || (
                        ($preaven_type_not_null && $this->attributes["preaventypename"])
                        ||
                        ($preaven_not_null && $this->attributes["preaven"])
                    )
                    || (
                        ($avenue_type_not_null && $this->attributes["avenuetypename"])
                        ||
                        ($avenue_not_null && $this->attributes["avenue"])
                    )
                    || $plate_not_null
                    || ($entrance_not_null && $this->attributes["entrance"])
                    || ($building_not_null && $this->attributes["building"])
                )
                && (
                    $unit_not_null && $this->attributes["unit"]
                )
            ) $post_address['part9'] .= '،';
        }
        if ($unit_not_null) {
            if ($this->attributes["unit"]) {
                $post_address['part10'] .= 'واحد ' . $this->attributes["unit"];
            }

        }
        return $post_address;

    }

    public function getPoiTypeNameAttribute($value)
    {
        return preg_replace('/\((-)?[0-9]\)/', '', $value);
    }
}
