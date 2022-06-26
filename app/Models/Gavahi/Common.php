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



        // $this->attributes['parish'] = "";
        // $this->attributes['tour'] = null;
        // $this->attributes['avenue'] = '';
        // $this->attributes['plate_no'] = -14;
        // $this->attributes['floorno'] = -89;
        // $this->attributes['unit'] = '';
//        parish
        if (!empty($this->attributes['parish'])
            && isset($this->attributes['tour'])) {
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
                    ((!empty($this->attributes['avenue']) && $this->attributes['avenue'])
                        || (!empty($this->attributes['avenuetypename']) && $this->attributes['avenuetypename']))
                    || isset($this->attributes['plate_no'])
                    || isset($this->attributes['floorno'])
                    || (!empty($this->attributes['unit']) && $this->attributes['unit'])
                )
            ) {
                $result .= '، ';
            }
        }


        if (!empty($this->attributes['avenue'])
            && !empty($this->attributes['avenuetypename'])
        ) {
            if ($this->attributes['avenuetypename'] ||
                $this->attributes['avenue']) {
                $result .= $this->attributes['avenuetypename'];
                $result .= ' ';
                $result .= $this->attributes['avenue'];
            }
            if (
                ((!empty($this->attributes['parish']) && $this->attributes['parish'])
                    || (isset($this->attributes['tour']))
                    || ((!empty($this->attributes['avenue']) && $this->attributes['avenue'])
                        || (!empty($this->attributes['avenuetypename']) && $this->attributes['avenuetypename']))
                )
                && (
                    isset($this->attributes['plate_no'])
                    || isset($this->attributes['floorno'])
                    || (!empty($this->attributes['unit']) && $this->attributes['unit'])
                )
            ) {
                $result .= '، ';
            }
        }

//        plateno
        if (isset($this->attributes['plate_no'])) {
            $result .= 'پلاک ';
            if ($this->attributes['plate_no'] < 0) {
                $address['plate_sign'] = '-';
            }
            $address['part2'] .= abs($this->attributes['plate_no']);

            if (
                (
                    (!empty($this->attributes['parish']) && $this->attributes['parish'])
                    || (isset($this->attributes['tour']))
                    || ((!empty($this->attributes['avenue']) && $this->attributes['avenue'])
                        || (!empty($this->attributes['avenuetypename']) && $this->attributes['avenuetypename']))
                    || (isset($this->attributes['plate_no']))
                )
                && (
                    (isset($this->attributes['floorno']))
                    || (!empty($this->attributes['unit']) && $this->attributes['unit'])
                )
            ) {
               $address['part3'] = '،';
            }
        }
        $address['part1'] .= $result;

//        floor
        if (isset($this->attributes['floorno'])) {
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
                    (!empty($this->attributes['parish']) && $this->attributes['parish'])
                    || (isset($this->attributes['tour']))
                    || ((!empty($this->attributes['avenue']) && $this->attributes['avenue'])
                        || (!empty($this->attributes['avenuetypename']) && $this->attributes['avenuetypename']))
                    || (isset($this->attributes['plate_no'])
                        || isset($this->attributes['floorno']))
                )
                &&
                (
                    !empty($this->attributes['unit']) && $this->attributes['unit']
                )
            ) {
               $address['part6'] = '،';
            }
        }

//        unit
        if (!empty($this->attributes['unit'])) {
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

        if (!empty($this->attributes["statename"])) {
//            $result .= 'استان ';
            if($this->attributes["statename"]){
            $result .= $this->attributes["statename"];
            }
            if (
                (!empty($this->attributes["statename"])  && $this->attributes["statename"])
                &&(
                (!empty($this->attributes["townname"]) && $this->attributes["townname"])
                ||( !empty($this->attributes['zonename']) && $this->attributes['zonename'])
                ||( !empty($this->attributes['villagename']) && $this->attributes['villagename'])
                ||( (!empty($this->attributes['locationtype']) && $this->attributes['locationtype'])||(
                
                 !empty($this->attributes['locationname']) && $this->attributes['locationname']))
            )
             ) $result .= '،';

        }
        if (!empty($this->attributes['townname'])) {
            if($this->attributes['townname']){
                $result .= 'شهرستان ';
                $result .= $this->attributes['townname'];
            }
            if (
                (!empty($this->attributes["statename"])  && $this->attributes["statename"])
                ||(!empty($this->attributes['townname']) && $this->attributes['townname'])
                &&(
                ( !empty($this->attributes['zonename']) && $this->attributes['zonename'])
                ||( !empty($this->attributes['villagename']) && $this->attributes['villagename'])
                ||( (!empty($this->attributes['locationtype']) && $this->attributes['locationtype'])||(
                
                 !empty($this->attributes['locationname']) && $this->attributes['locationname']))
            )
             ) $result .= '،';
        }
        if (!empty($this->attributes['zonename'])) {
            if($this->attributes['zonename']){
            if ($this->attributes['locationtype'] == 'شهر') {
                $result .= 'بخش ';
            }
        
            $result .= $this->attributes['zonename'];
        }
        if (
            (!empty($this->attributes["statename"])  && $this->attributes["statename"])
            ||(!empty($this->attributes['townname']) && $this->attributes['townname'])
            ||( !empty($this->attributes['zonename']) && $this->attributes['zonename'])
            &&(
            
            ( !empty($this->attributes['villagename']) && $this->attributes['villagename'])
            ||( (!empty($this->attributes['locationtype']) && $this->attributes['locationtype'])||(
            
             !empty($this->attributes['locationname']) && $this->attributes['locationname']))
        )
         ) $result .= '،';
        }
        if (!empty($this->attributes['villagename'])) {
            if($this->attributes['villagename']){
                $result .= 'دهستان ';
                $result .= $this->attributes['villagename'];
            }
           
            if (
                (!empty($this->attributes["statename"])  && $this->attributes["statename"])
                ||(!empty($this->attributes['townname']) && $this->attributes['townname'])
                ||( !empty($this->attributes['zonename']) && $this->attributes['zonename'])
                ||( !empty($this->attributes['villagename']) && $this->attributes['villagename'])
                &&(
                
                
                 (!empty($this->attributes['locationtype']) && $this->attributes['locationtype'])||(
                
                 !empty($this->attributes['locationname']) && $this->attributes['locationname'])
            )
             ) $result .= '،';
        }
        if (!empty($this->attributes['locationtype'])
            && !empty($this->attributes['locationname'])
        ) {
            if($this->attributes['locationtype'] && $this->attributes['locationname']){
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

        // $this->attributes['parish'] = "";
        // $this->attributes["preaventypename"] = null;
        // $this->attributes["preaven"] = null;
        // $this->attributes["avenuetypename"] = null;
        // $this->attributes["building"] = null;
        // $this->attributes['avenue'] = '';
        // $this->attributes['plate_no'] = -14;
        // $this->attributes['floorno'] = -89;
        // $this->attributes['unit'] = '142';

        if (!empty($this->attributes["parish"])) {
            if($this->attributes["parish"]){
                $result .= 'محله: ' . $this->attributes["parish"];

            }


            if ((!empty($this->attributes["parish"]) && $this->attributes["parish"])
            &&(
                (
                    (!empty($this->attributes["preaventypename"]) && $this->attributes["preaventypename"])
                ||
                    (!empty($this->attributes["preaven"]) && $this->attributes["preaven"])
                    )

                ||(
                    (!empty($this->attributes["avenuetypename"]) && $this->attributes["avenuetypename"])
                ||
                    (!empty($this->attributes["avenue"]) && $this->attributes["avenue"])
                    )

                || isset($this->attributes["plate_no"])
                || (!empty($this->attributes["building"]) && $this->attributes["building"])
                ||(!empty($this->attributes["entrance"]) && $this->attributes["entrance"])
                || (!empty($this->attributes["unit"]) && $this->attributes["unit"])
                || isset($this->attributes["floorno"])
            )
            ) $result .= '،';

        }

        if (!empty($this->attributes["preaventypename"]) && !empty($this->attributes["preaven"])) {
            if($this->attributes["preaventypename"] && $this->attributes["preaven"]){
            $result .= 'معبر ماقبل آخر:' . $this->attributes['preaventypename'] . ' ' . $this->attributes["preaven"];
            }
            if ((
                (!empty($this->attributes["parish"]) && $this->attributes["parish"])
            ||(
                (!empty($this->attributes["preaventypename"]) && $this->attributes["preaventypename"])
            ||
                (!empty($this->attributes["preaven"]) && $this->attributes["preaven"])
                )
            )
            &&(
                
                (
                    (!empty($this->attributes["avenuetypename"]) && $this->attributes["avenuetypename"])
                ||
                    (!empty($this->attributes["avenue"]) && $this->attributes["avenue"])
                    )

                || isset($this->attributes["plate_no"])
                || (!empty($this->attributes["building"]) && $this->attributes["building"])
                ||(!empty($this->attributes["entrance"]) && $this->attributes["entrance"])
                || (!empty($this->attributes["unit"]) && $this->attributes["unit"])
                || isset($this->attributes["floorno"])
            )
            ) $result .= '،';

        }
        if (!empty($this->attributes["avenuetypename"]) && !empty($this->attributes["avenue"])) {
            if($this->attributes["avenuetypename"] && $this->attributes["avenue"]){
                $result .= 'معبر آخر:' . $this->attributes["avenuetypename"] . ' ' . $this->attributes["avenue"];
            }
            if ((
                (!empty($this->attributes["parish"]) && $this->attributes["parish"])
            ||(
                (!empty($this->attributes["preaventypename"]) && $this->attributes["preaventypename"])
            ||
                (!empty($this->attributes["preaven"]) && $this->attributes["preaven"])
                )
            || (
                    (!empty($this->attributes["avenuetypename"]) && $this->attributes["avenuetypename"])
                ||
                    (!empty($this->attributes["avenue"]) && $this->attributes["avenue"])
                )
            )
            &&(
                isset($this->attributes["plate_no"])
                || (!empty($this->attributes["building"]) && $this->attributes["building"])
                ||(!empty($this->attributes["entrance"]) && $this->attributes["entrance"])
                || (!empty($this->attributes["unit"]) && $this->attributes["unit"])
                || isset($this->attributes["floorno"])
            )
            ) $result .= '،';
        }

        if (isset($this->attributes["plate_no"])) {
            $result .= 'پلاک ' . abs($this->attributes["plate_no"]);
            
            if($this->attributes["plate_no"] < 0 ){
                $post_address['plate_sign'] = '-';
            }
            if ((
                (!empty($this->attributes["parish"]) && $this->attributes["parish"])
            ||(
                (!empty($this->attributes["preaventypename"]) && $this->attributes["preaventypename"])
            ||
                (!empty($this->attributes["preaven"]) && $this->attributes["preaven"])
                )
            || (
                    (!empty($this->attributes["avenuetypename"]) && $this->attributes["avenuetypename"])
                ||
                    (!empty($this->attributes["avenue"]) && $this->attributes["avenue"])
                )
            || isset($this->attributes["plate_no"])
            )
            &&(
               (!empty($this->attributes["building"]) && $this->attributes["building"])
               ||(!empty($this->attributes["entrance"]) && $this->attributes["entrance"])
               || (!empty($this->attributes["unit"]) && $this->attributes["unit"])
               || isset($this->attributes["floorno"])
            )
            )$post_address['part2'] .= '،';
               

        }
        $post_address['part1'] .= $result;


        if (!empty($this->attributes["entrance"])) {
            if($this->attributes["entrance"]){
                $post_address['part3'] .= ' ' . $this->attributes['entrance'];

            }
            if ((
                (!empty($this->attributes["parish"]) && $this->attributes["parish"])
            ||(
                (!empty($this->attributes["preaventypename"]) && $this->attributes["preaventypename"])
            ||
                (!empty($this->attributes["preaven"]) && $this->attributes["preaven"])
                )
            || (
                    (!empty($this->attributes["avenuetypename"]) && $this->attributes["avenuetypename"])
                ||
                    (!empty($this->attributes["avenue"]) && $this->attributes["avenue"])
                )
            || isset($this->attributes["plate_no"])
            ||(!empty($this->attributes["entrance"]) && $this->attributes["entrance"])
            )
            &&(
               (!empty($this->attributes["building"]) && $this->attributes["building"])
               || (!empty($this->attributes["unit"]) && $this->attributes["unit"])
               || isset($this->attributes["floorno"])
            )
            )$post_address['part4'] .= '،';
        }
        if (!empty($this->attributes["building"])) {
            if($this->attributes["building"]){
                $post_address['part5'] .= $this->attributes["building"];
            }
            if (((!empty($this->attributes["parish"]) && $this->attributes["parish"])
            ||(
                (!empty($this->attributes["preaventypename"]) && $this->attributes["preaventypename"])
            ||
                (!empty($this->attributes["preaven"]) && $this->attributes["preaven"])
                )
            || (
                    (!empty($this->attributes["avenuetypename"]) && $this->attributes["avenuetypename"])
                ||
                    (!empty($this->attributes["avenue"]) && $this->attributes["avenue"])
                )
            || isset($this->attributes["plate_no"])
            ||(!empty($this->attributes["entrance"]) && $this->attributes["entrance"])
            ||(!empty($this->attributes["building"]) && $this->attributes["building"])
            )
            &&(
                (!empty($this->attributes["unit"]) && $this->attributes["unit"])
                || isset($this->attributes["floorno"])
            )
            )$post_address['part6'] .= '،';
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
            if ((
                (!empty($this->attributes["parish"]) && $this->attributes["parish"])
            ||(
                (!empty($this->attributes["preaventypename"]) && $this->attributes["preaventypename"])
            ||
                (!empty($this->attributes["preaven"]) && $this->attributes["preaven"])
                )
            || (
                    (!empty($this->attributes["avenuetypename"]) && $this->attributes["avenuetypename"])
                ||
                    (!empty($this->attributes["avenue"]) && $this->attributes["avenue"])
                )
            || isset($this->attributes["plate_no"])
            ||(!empty($this->attributes["entrance"]) && $this->attributes["entrance"])
            ||(!empty($this->attributes["building"]) && $this->attributes["building"])
            ||isset($this->attributes["floorno"])
            )
            &&(
                 !empty($this->attributes["unit"]) && $this->attributes["unit"]
                )
            )$post_address['part9'] .= '،';
                }
        if (!empty($this->attributes["unit"])) {
            if($this->attributes["unit"]){
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
