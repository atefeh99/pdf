<?php

namespace App\Http\Services;

use App\Helpers\Random;
use App\Models\File;
use App\Models\Interpreter;
use App\Modules\GetMap\GetMap;
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
    public static function setParams($identifier, $link, $data = null)
    {

        $params = [];
        $datetime = explode(' ', Date::convertCarbonToJalali(Carbon::now()));
        $date = $datetime[0];
        $barcodes = [];
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
            $tour_name = '';
            $code_joze = '';
            $province_name = '';
            $region = '';
            $county = '';
            $districe = '';
            $zone = '';
            $time = round(microtime(true) * 1000);
            if (isset($data['block_id'])) {
                $block = Block::getData($data['block_id']);
                $tour_no = $block->tour->name;
                $code_joze = $block->part->name;
                $province = $block->province->name;
                $county = $block->county->name;
                $zone = $block->zone->name;
                $neighbourhoods = [];
                $ways=[];
                $blocks_c = 1;
                $parts_count = 1;
                $d['parts'] = $block->part;
                $d['parts'][0]['blocks'][0] = $block;
                $d['parts'][0]['blocks'][0]['buildings'] = $block->buildings;

                $all_buildings = count($block->buildings);
                for ($i = 0; $i < $all_buildings; $i++) {

                    $d['parts'][0]['blocks'][0]['buildings'][$i]['addresses'] = $block->buildings[$i]->addresses;
                    $d['parts'][0]['blocks'][0]['buildings'][$i]['neighbourhood'] = $block->buildings[$i]->neighbourhood->name;
                    array_push($neighbourhoods, $block->buildings[$i]->neighbourhood->name);


                    $addresses_count = count($block->buildings[$i]->addresses);

                    for ($j = 0; $j < $addresses_count; $j++) {

                        $d['parts'][0]['blocks'][0]['buildings'][$i]['addresses'][$j] = $block->buildings[$i]->addresses[$j]->entrances;
                        array_push($ways, $block->buildings[$i]->addresses[$j]->street,$block->buildings[$i]->addresses[$j]->secondary_street);

                        $entrances_count = count($block->buildings[$i]->addresses[$j]->entrances);

                        for ($k = 0; $k < $entrances_count; $k++) {

                            $d['parts'][0]['blocks'][0]['buildings'][$i]['addresses'][$j]['entrances'][$k]['units'] = $block->buildings[$i]->addresses[$j]->entrances[$k]->units;

                            $units_count = count($tour->parts[$i]->blocks[$j]->buildings[$k]->addresses[$l]->entrances[$m]->units);
                            $unique_recog_code_count += count(array_unique(array_column($block->buildings[$i]->addresses[$j]->entrances[$k]->units->toArray(), 'unit_identifier')));
                            $records += $units_count;

                        }

                    }
                }

            } else {


                $tour = Tour::getData($data['tour_id']);
                $tour_name = $tour->name;
                $province_name = $tour->province->name;
                $d['parts'] = $tour->parts;
                $neighbourhoods = [];
                $ways=[];
                $parts_count = count($tour->parts);
                if ($parts_count != 0) {

                    for ($i = 0; $i < $parts_count; $i++) {

                        $d['parts'][$i]['blocks'] = $tour->parts[$i]->blocks;
                        $blocks_count = count($tour->parts[$i]->blocks);
                        $blocks_c += $blocks_count;

                        for ($j = 0; $j < $blocks_count; $j++) {

                            $d['parts'][$i]['blocks'][$j]['buildings'] = $tour->parts[$i]->blocks[$j]->buildings;
                            $buildings_count = count($tour->parts[$i]->blocks[$j]->buildings);
                            $all_buildings += $buildings_count;

                            for ($k = 0; $k < $buildings_count; $k++) {

                                $d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'] = $tour->parts[$i]->blocks[$j]->buildings[$k]->addreses;
                                $d['parts'][$i]['blocks'][$j]['buildings'][$k]['neighbourhood'] = $tour->parts[$i]->blocks[$j]->buildings[$k]->neighbourhood->name;
                                array_push($neighbourhoods, $tour->parts[$i]->blocks[$j]->buildings[$k]->neighbourhood->name);
                                $addresses_count = count($tour->parts[$i]->blocks[$j]->buildings[$k]->addresses);


                                for ($l = 0; $l < $addresses_count; $l++) {

                                    $d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['entrances'] = $tour->parts[$i]->blocks[$j]->buildings[$k]->addreses[$l]->entrances;
                                    array_push($ways, $tour->parts[$i]->blocks[$j]->buildings[$k]->addreses[$l]->street,$tour->parts[$i]->blocks[$j]->buildings[$k]->addreses[$l]->secondary_street);
                                    $entrances_count = count($tour->parts[$i]->blocks[$j]->buildings[$k]->addresses[$l]->entrances);

                                    for ($m = 0; $m < $entrances_count; $m++) {

                                        $d['parts'][$i]['blocks'][$j]['buildings'][$k]['addresses'][$l]['entrances'][$m]['units'] = $tour->parts[$i]->blocks[$j]->buildings[$k]->addreses[$l]->entrances[$m]->units;
                                        $units_count = count($tour->parts[$i]->blocks[$j]->buildings[$k]->addresses[$l]->entrances[$m]->units);
                                        $unique_recog_code_count += count(array_unique(array_column($tour->parts[$i]->blocks[$j]->buildings[$k]->addresses[$l]->entrances[$m]->units->toArray(), 'unit_identifier')));
                                        $records += $units_count;

                                    }
                                }
                            }
                        }

                    }
                }

                if ($identifier == 'notebook_1') {

                    //req body tourno,
                    $params = [
                        "tour_no" => $tour_name,
                        "code_joze" => $code_joze,
                        "province" => $province_name,
                        "region" => $region,
                        "county" => $county,
                        "district" => $district,
                        "postal_region" => $zone,
                        "blocks_count" => $blocks_c,
                        "buildings" => $all_buildings,
                        "recog_count" => $unique_recog_code_count,
                        "records_counts" => $records,
                        "date" => $date,

                    ];
                } elseif ($identifier == 'notebook_2') {
                    $params = [
                        "tour_no" => $tour_name,
                        "code_joze" => $code_joze,
                        "date" => $date,
                        "data" => $d,
                    ];


                } elseif ($identifier == 'notebook_3') {
                    
                    $params = [
                        "tour_no" => $tour_name,
                        "date" => $date,
                        "code_joze" => $code_joze,
                        "roads" => $ways,
                        "neighbourhoods" => $neighbourhoods,
                        "parts_count" => $parts_count,

                    ];

                }
            }
        } elseif (strpos($identifier, 'gavahi') !== false) {
            $gavahi_data = [];
            foreach ($data['postalcode'] as $key => $postalcode) {

                $gavahi_data[$key] = PostData::getInfo($postalcode);

                $barcode = '';
                $barcode_unique = false;
                while (!$barcode_unique) {
                    $barcode = Random::randomNumber(20);
                    if (File::isUniqueBarcode($barcode)) {
                        $barcode_unique = true;
                    }
                }
                $gavahi_data[$key]['barcode'] = $barcode;
                array_push($barcodes, $barcode);

                if ($data['geo'] == 1) {
                    $image = GetMap::vectorMap($postalcode);
                    if (!$image) {
                        $gavahi_data[$key]['image_exists'] = false;
                    } else {
                        $gavahi_data[$key]['image_exists'] = true;
                        $name = $postalcode . '.png';
                        Storage::disk('images')->put($name, $image);
                    }
                } else {
                    $gavahi_data[$key]['image_exists'] = false;
                }
            }

            $params = [
                "date" => $date,
                "data" => $gavahi_data,
                "x" => 1,
                "length" => count($gavahi_data),
                "QRCode" => $link
            ];
        }
        return ['params' => $params, 'barcodes' => $barcodes];
    }

    public static function setNumPersian($result, $id)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $num = range(0, 9);

        if ($result['date']) {
            $result['date'] = str_replace($num, $persian, $result['date']);
        }
        if ($id == 'gavahi_1') {
            foreach ($result['data'] as $index => $value) {
                foreach ($value as $field => $v) {
                    if ($field != 'barcode') {
                        $result['data'][$index][$field] = str_replace($num, $persian, $v);
                        if ($field == 'postalcode') {
                            $result['data'][$index][$field] = mb_str_split($result['data'][$index][$field], $length = 1);

                        }
                    }
                }
            }
        } elseif ($id == 'notebook_1') {
            foreach ($result as $index => $value) {
                $result[$index] = str_replace($num, $persian, $value);
            }

        } elseif ($id == 'notebook_2') {
            if ($result['tour_no']) {
                $result['tour_no'] = str_replace($num, $persian, $result['tour_no']);
            }
            if ($result['code_joze']) {
                $result['code_joze'] = str_replace($num, $persian, $result['code_joze']);
            }
            foreach ($result['data']['parts'] as $key => $part) {
                foreach ($part['blocks'] as $k => $block) {
                    $result['data']['parts'][$key]['blocks'][$k]['id'] = str_replace($num, $persian, $block['id']);
                    foreach ($block['buildings'] as $b => $building) {
                        $result['data']['parts'][$key]['blocks'][$k]['buildings'][$b]['building_no'] =
                            str_replace($num, $persian, $building['building_no']);
                        $result['data']['parts'][$key]['blocks'][$k]['buildings'][$b]['floor_count'] =
                            str_replace($num, $persian, $building['floor_count']);
                        foreach ($building['addresses'] as $a => $add) {
                            foreach ($add['entrances'] as $e => $ent) {
                                foreach ($ent['units'] as $u => $unit) {

                                    $result['data']["parts"][$key]["blocks"][$k]["buildings"][$b]["addresses"][$a]["entrances"][$e]["units"][$u]["row_no"] =
                                        str_replace($num, $persian, $unit['row_no']);
                                    $result['data']["parts"][$key]["blocks"][$k]["buildings"][$b]["addresses"][$a]["entrances"][$e]["plate_no"] =
                                        str_replace($num, $persian, $ent["plate_no"]);
                                    $result['data']["parts"][$key]["blocks"][$k]["buildings"][$b]["addresses"][$a]["entrances"][$e]["units"][$u]["floor_no"] =
                                        str_replace($num, $persian, $unit["floor_no"]);
                                    $result['data']["parts"][$key]["blocks"][$k]["buildings"][$b]["addresses"][$a]["entrances"][$e]["units"][$u]["unit_no"] =
                                        str_replace($num, $persian, $unit["unit_no"]);
                                    $result['data']["parts"][$key]["blocks"][$k]["buildings"][$b]["addresses"][$a]["entrances"][$e]["units"][$u]["location_type_id"] =
                                        str_replace($num, $persian, $unit["location_type_id"]);
                                    $result['data']["parts"][$key]["blocks"][$k]["buildings"][$b]["addresses"][$a]["entrances"][$e]["units"][$u]["location_type_id"] =
                                        str_replace($num, $persian, $unit["location_type_id"]);
                                    $result['data']["parts"][$key]["blocks"][$k]["buildings"][$b]["addresses"][$a]["entrances"][$e]["units"][$u]["recog_code"] =
                                        str_replace($num, $persian, $unit["recog_code"]);
                                }
                            }
                        }
                    }
                }
            }
        } elseif ($id == 'notebook_3') {
            if ($result['tour_no']) {
                $result['tour_no'] = str_replace($num, $persian, $result['tour_no']);
            }
            if ($result['code_joze']) {
                $result['code_joze'] = str_replace($num, $persian, $result['code_joze']);
            }
        }


//        dd($result);
        return $result;
    }

    public static function getPdf($identifier, $link, $uuid, $user_id, $data = null)
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
            $result = self::setParams($value['identifier'], $link, $data);
//            $params[$value['identifier']] = $result['params'];
            if ($result['params']) {
                //$result['params'] = self::setNumPersian($result['params'], $value['identifier']);
                $view = view($value['identifier'], $result['params']);
                try {
                    $view->render();

                } catch (\Exception $exception) {
//                    Log::error($exception->getMessage());
                    dd($exception->getMessage());
                }
                $html = $view->toHtml();

                $pages[$key] = $html;
            } else {
                return false;
            }
        }

//        return $params;
        if ($pages) {
            MakePdf::createPdf($identifier, $pages, $result['params'], $uuid);
            $data = [
                'user_id' => $user_id,
                'filename' => $uuid,
                'barcodes' => $result['barcodes']
            ];
            File::store($data);
            return true;
        } else {
            return false;
        }


    }
}

