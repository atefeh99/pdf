<?php

namespace App\Http\Services;

use App\Models\Interpreter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Modules\MakePdf;
use App\Helpers\Date;
use Carbon\Carbon;
use App\Models\Notebook\ {Entrance, Tour, Part, Province, Block, Building, Address, Unit, Neighbourhood, Way};
use App\Models\Gavahi\PostData;
use function PHPUnit\Framework\returnArgument;


class PdfMakerService
{
    public static function setParams($identifier, $data = null)
    {


        $params = [];
        $datetime = explode(' ', Date::convertCarbonToJalali(Carbon::now()));
        $date = $datetime[0];
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
        if (strpos($identifier, 'notebook') !== false) {
            $blocks_c = 0;
            $all_buildings = 0;
            $unique_recog_code_count = 0;
            $records = 0;
            if (isset($data['block_id'])) {
                $tour_id = Block::getTourId($data['block_id']);
            } else {
                $tour_id = $data['tour_id'];
            }
            $tour_name = Tour::getName($tour_id);
            $d['parts'] = Part::index($tour_id);
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
            if (isset($data['block_id'])) {
                $blocks = [];
                $parts = [];
                for ($i = 0; $i < $parts_count; $i++) {
                    for ($j = 0; $j < count($d['parts'][$i]['blocks']); $j++) {
                        if ($d['parts'][$i]['blocks'][$j]['id'] == $data['block_id']) {
//                            dd(var_dump($d['parts'][$i]['blocks']));
                            $blocks = $d['parts'][$i]['blocks'][$j];
                            $parts = $d['parts'][$i];
//                            unset($d['parts'][$i]['blocks']);
//                            unset($d['parts']);
//                            $d['parts'][$i]['blocks'][0] = $blocks;
//                            $d['parts'] = $parts;
                            $all_buildings = count($blocks['buildings']);
                            for ($k = 0; $k < $all_buildings; $k++) {

                                $addresses_count = count($blocks['buildings'][$k]['addresses']);
                                if ($addresses_count != 0) {
                                    for ($l = 0; $l < $addresses_count; $l++) {
                                        $entrances_count = count($blocks['buildings'][$k]['addresses'][$l]['entrances']);
                                        if ($entrances_count != 0) {
                                            for ($m = 0; $m < $entrances_count; $m++) {
                                                $units_count = count($blocks['buildings'][$k]['addresses'][$l]['entrances'][$m]['units']);
                                                $unique_recog_code_count += count(array_unique(array_column($blocks['buildings'][$k]['addresses'][$l]['entrances'][$m]['units'], 'recog_code')));
                                                $records += $units_count;
                                            }
                                        }
                                    }
                                }

                            }

                        }
                    }
                }
                $d['parts'][0] = $parts;
                $d['parts'][0]['blocks'][0] = $blocks;

            }

            if ($identifier == 'notebook_1') {
                $province_name = Province::getName(Tour::getProvinceId($tour_id));
                if (isset($data['block_id'])) {
                    $blocks_c = 1;
                }

                //req body tourno,
                $params = [
                    "tour_no" => $tour_name,
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
                    "date" => $date,

                ];
            } elseif ($identifier == 'notebook_2') {
                $params = [
                    "tour_no" => $tour_name,
                    "code_joze" => 5,
                    "date" => $date,
                    "data" => $d,
                ];


            } elseif ($identifier == 'notebook_3') {
                if (isset($data['block_id'])) {
                    $parts_count = 1;
                }
                $params = [
                    "tour_no" => $tour_name,
                    "date" => $date,
                    "code_joze" => 5,
                    "way_type" => 'خیابان',
                    "way_name" => 'بهشتی',
                    "neighbourhood_name" => 'عباس آباد',
                    "parts_count" => $parts_count,
                ];

            }
        } elseif (strpos($identifier, 'gavahi') !== false) {
            $gavahi_data = [];

            foreach ($data['postalcode'] as $key => $postalcode) {
              //  dd(var_dump($postalcode));
                $gavahi_data[$key] = PostData::getInfo($postalcode);
            }

            $params = [
                "date" => $date,
                "data" => $gavahi_data,
            ];
        }
        return $params;
    }

    public static function getPdf($identifier, $data = null)
    {

        $pages = [];
        $indexes = [];

        if ($identifier == 'notebook') {
            $indexes = Interpreter::getBy('identifier', 'notebook%');
        } elseif ($identifier == 'gavahi') {
            $indexes = Interpreter::getBy('identifier', 'gavahi%');
        }
        usort($indexes, function ($a, $b) {
            return strcmp($a['identifier'], $b['identifier']);
        });

        $params = [];

        foreach ($indexes as $key => $value) {

            Storage::put($value['identifier'] . '.blade.php', $value['html']);
//            $params[$value['identifier']] =
//                (!$data)
//                    ? self::setParams($value['identifier'], $tour_no)
//                    : $data[$value['identifier']];
            $params[$value['identifier']] = self::setParams($value['identifier'], $data);
            $view = view($value['identifier'], $params[$value['identifier']]);
            try {
                $view->render();
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
            }
            $pages[$key] = view($value['identifier'], $params[$value['identifier']])->toHtml();
            //print_r($value['identifier']);
        }

//        return $params;
        MakePdf::createPdf($identifier, $pages);

        return true;


    }

}
