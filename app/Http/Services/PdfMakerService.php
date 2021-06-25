<?php

namespace App\Http\Services;

use App\Models\Interpreter;
use Illuminate\Support\Facades\Storage;
use App\Modules\MakePdf;
use App\Helpers\Date;
use Carbon\Carbon;
use App\Models\Daftarche\ {Entrance, Tour, Part, Province, Block, Building, Address, Unit, Neighbourhood, Way};


class PdfMakerService
{
    public static function setParams($identifier, $tour_no)
    {
//        $params = [
//            "tour_no"=> $tour_no,
//            "code_joze" => 5 ,
//            "province" => $province_name ,
//            "region" => 'منطقه۹' ,
//            "county" => '' ,
//            "district" => '' ,
//            "postal_region" => '' ,
//            "blocks_count" => $blocks_c ,
//            "buildings" => $all_buildings ,
//            "recog_count" => $unique_recog_code_count ,
//            "records_counts" => $records ,
//            "page" => 1,
//            "date" => $date ,
//            "data" => $d,
//            "way_type" => 'خیابان' ,
//            "way_name" => 'بهشتی',
//            "neighbourhood_name" =>'عباس آباد' ,
//            "parts_count" => $parts_count,
//            "pages" => 5,
//
//            ];
        $params = [];
        $blocks_c = 0;
        $all_buildings = 0;
        $unique_recog_code_count = 0;
        $records = 0;
        $datetime = explode(' ', Date::convertCarbonToJalali(Carbon::now()));
        $date = $datetime[0];
        $d['parts'] = Part::index(Tour::getId($tour_no));
        $parts_count = count($d['parts']);
        for ($i = 0; $i < $parts_count; $i++) {

            $d['parts'][$i]['blocks'] = Block::index($d['parts'][$i]['id']);
            $blocks_count = count($d['parts'][$i]['blocks']);
            $blocks_c += $blocks_count;
            for ($j = 0; $j < $blocks_count; $j++) {

                $d['parts'][$i]['blocks'][$j]['buildings'] =
                    Building::index($d['parts'][$i]['blocks'][$j]['id']);
                $buildings_count = count($d['parts'][$i]['blocks'][$j]['buildings']);
                $all_buildings += $buildings_count;
                for ($k = 0; $k < $buildings_count; $k++) {

                    $d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'] =
                        Address::index($d['parts'][$i]['blocks'][$j]['buildings'][$k]['id']);

                    $neighbourhood_id = $d['parts'][$i]['blocks'][$j]['buildings'][$k]['neighbourhood_id'];
                    $d['parts'][$i]['blocks'][$j]['buildings'][$k]['neighbourhood'] = Neighbourhood::getName($neighbourhood_id);

                    $addresses_count = count($d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses']);

                    for ($l = 0; $l < $addresses_count; $l++) {
                        $d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['entrances'] =
                            Entrance::index($d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['id']);

                        $s_way_id = $d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['secondary_way_id'];
                        $way_id = $d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['way_id'];

                        $d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['way_name'] = Way::getName($way_id);
                        $d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['s_way_name'] = Way::getName($s_way_id);

                        $entrances_count = count($d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['entrances']);

                        for ($m = 0; $m < $entrances_count; $m++) {
                            $d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['entrances'][$m]['units'] =
                                Unit::index($d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['entrances'][$m]['id']);
                            $units_count = count($d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['entrances'][$m]['units']);
                            $unique_recog_code_count += count(array_unique(array_column($d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['entrances'][$m]['units'], 'recog_code')));
                            $records += $units_count;

                        }
                    }
                }
            }

        }
        if ($identifier === 'notebook_1') {
            $province_name = Province::getName(Tour::getProvinceId($tour_no));
            //req body tourno,
            $params = [
                "tour_no" => $tour_no,
                "code_joze" => 5,
                "province" => $province_name,
                "region" => 'منطقه۹',
                "county" => '',
                "district" => '',
                "postal_region" => '',
                "blocks_count" => $blocks_c,
                "buildings" => $all_buildings,
                "recog_count" => $unique_recog_code_count,
                "records_counts" => $records,
                "pages" => 5,
                "date" => $date,

            ];
        } elseif ($identifier === 'notebook_2') {
            $params = [
                "tour_no" => $tour_no,
                "code_joze" => 5,
                "page" => 1,
                "date" => $date,
                "data" => $d,
            ];
        } elseif ($identifier === 'notebook_3') {
            $params = [
                "tour_no" => $tour_no,
                "date" => $date,
                "code_joze" => 5,
                "way_type" => 'خیابان',
                "way_name" => 'بهشتی',
                "neighbourhood_name" => 'عباس آباد',
                "parts_count" => $parts_count,
            ];
        }
        return $params;
    }

    public static function daftarche($identifier, $data = null)
    {

        $indexes = Interpreter::getBy('identifier', 'notebook%');
        $pages = [];
        foreach ($indexes as $key => $value) {
            Storage::put($value['identifier'] . '.blade.php', $value['html']);
//            $params[$value['identifier']] =
//                (!$data)
//                    ? self::setParams($value['identifier'], $tour_no)
//                    : $data[$value['identifier']];
            $params = self::setParams($value['identifier'], $identifier);
            $pages[$key] = view($value['identifier'], $params)->toHtml();
        }

        MakePdf::createPdf(__FUNCTION__, $pages);
        return true;
    }

//    public static function getPdf($request, $id)
//    {
//        $data = Interpreter::getHtml($id);
//        Storage::put('example.blade.php', $data);
//
//        if ($request->all()['data'])
//            $params = $request->all()['data'];
//        return view('example', $params)->toHtml();
//    else {
//
//    }
//        MakePdf::createPdf(view('example', $params)->toHtml(), $data->identifier);
//    }
}
