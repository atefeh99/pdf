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
    public static function setParams($identifier, $data = null)
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
            if (isset($data['block_id'])) {
                $tour_id = Block::getTourId($data['block_id']);
            } else {
                $tour_id = $data['tour_id'];
            }
            $tour_name = Tour::getName($tour_id);
            $d['parts'] = Part::index($tour_id);
            $parts_count = count($d['parts']);
            if ($parts_count != 0) {


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
                "length" => count($gavahi_data)
            ];
        }
        return ['params' => $params, 'barcodes' => $barcodes];
    }

    public static function setNumPersian($result)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $num = range(0, 9);

        if ($result['date']) {
            $result['date'] = str_replace($num, $persian, $result['date']);
        }
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

            // Storage::put($value['identifier'] . '.blade.php', $value['html']);
//            $params[$value['identifier']] =
//                (!$data)
//                    ? self::setParams($value['identifier'], $tour_no)
//                    : $data[$value['identifier']];
            $result = self::setParams($value['identifier'], $data);

//            $params[$value['identifier']] = $result['params'];
            if ($result['params']) {
                $result['params'] = self::setNumPersian($result['params']);
                $view = view($value['identifier'], $result['params']);
                try {
                    $view->render();

                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
//                dd($exception->getMessage());
                }
                $html = $view->toHtml();
//                $en = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
//                $fa = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
//                if ($value['identifier'] == 'gavahi_1') {
//                    $html = preg_replace('/<barcode.*type="QR".*\/\>/', '@', $html);
//                    preg_match_all('/<barcode.*type="C128C".*\/\>/', $html, $matches);
//
//                    $code = '';
//                    $barcodes = [];
//                    foreach ($matches[0] as $val) {
//                        array_push($barcodes, [$code .= '#' => $val]);
//                    }
//                    foreach ($barcodes as $v) {
//                        $html = str_replace(array_values($v)[0], array_keys($v)[0], $html);
//                    }
//                    $html = str_replace($en, $fa, $html);
//                    foreach ($barcodes as $va) {
//                        foreach ($va as $a=>$b){
//                            $pattern = '/'.$a.'/';
//                            $html = preg_replace($pattern, $b, $html);
//
//                        }
//                    }
//                    dd($html);
//                    $html = preg_replace('/@/', '<barcode code=' . $link . ' type="QR" class="barcode" size="1" error="M" height="2" disableborder="1"/>', $html);
//
//                }
                $pages[$key] = $html;

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
            } else {
                return false;
            }

        }
    }
}

