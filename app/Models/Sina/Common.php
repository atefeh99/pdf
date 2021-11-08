<?php

namespace App\Models\Sina;

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
}
