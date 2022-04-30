<?php

namespace App\Http\Services;

use App\Exceptions\PaymentException;
use App\Helpers\Random;
use App\Jobs\MakePdfJob;
use App\Models\DirectMail\SinaUnits;
use App\Models\File;
use App\Models\Interpreter;
use App\Modules\GetMap\GetMap;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Date;
use Carbon\Carbon;
use App\Models\Notebook\{Entrance, Tour, Part, Province, Block, Building, Address, Unit, Neighbourhood, Way};
use App\Models\Gavahi\PostData;
use App\Models\PdfStatus;
use Ramsey\Uuid\Uuid;
use App\Modules\MakePdf;
use App\Modules\Payment\PaymentModule;


class PdfMakerService
{
    use CommonTrait;

    public static $composite_response = [

        'barcode' => 'CertificateNo',
        'statename' => 'Province',
        'townname' => 'TownShip',
        'zonename' => 'Zone',
        'villagename' => 'Village',
        'locationtype' => 'LocalityType',
        'locationname' => 'LocalityName',
//            'localitycode'=>'LocalityCode',
        'parish' => 'SubLocality',
        'preaven' => 'Street2',
        'avenue' => 'Street',
        'plate_no' => 'HouseNumber',
        'floorno' => 'Floor',
        'unit' => 'SideFloor',
        'building_name' => 'BuildingName',
        //            'description'=>'Description',
        'postcode' => 'PostCode'
    ];


    public static function getPdf($identifier, $user_id, $data = null)
    {
        $pages = [];
        $indexes = [];
        $ttl = '';
        $extra_info = null;
        $indexes = Interpreter::getBy('identifier', $identifier . '%');
        if ($identifier == 'gavahi') $ttl = $indexes[0]['ttl'];

        usort($indexes, function ($a, $b) {
            return strcmp($a['identifier'], $b['identifier']);//*
        });

        $uuid = Uuid::uuid4();
        $link = $indexes[0]['api_prefix'] . '/' . $uuid . '.pdf';
        Log::info('setting params');
        $result = self::setParams($identifier, $link, $ttl, $data);
        if ($identifier == 'notebook') $extra_info = $result['params'];

        foreach ($indexes as $key => $value) {

            Storage::put($value['identifier'] . '.blade.php', $value['html']);//**
            if ($result['params'][$value['identifier']]) {
                $result['params'][$value['identifier']]
                    = self::setNumPersian($result['params'][$value['identifier']], $value['identifier']);
                $view = view($value['identifier'], $result['params'][$value['identifier']]);
                try {
                    $view->render();
                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
                }
                $html = $view->toHtml();
                $pages[$key] = $html;
            } else return false;


        }
        $time = round(microtime(true) * 1000);
        Log::info("#pages complete "
            . (round(microtime(true) * 1000) - $time)
            . " milisec long");

        if ($pages) {
            MakePdf::createPdf($identifier, $pages, $result['params'], $uuid, $data);
            Log::info("#pdf created "
                . (round(microtime(true) * 1000) - $time)
                . " milisec long");

            $d = [
                'user_id' => $user_id,
                'filename' => $uuid,
                'barcodes' => $result['barcodes'],
            ];
            if ($identifier == 'gavahi') {
                $d['expired_at'] = CommonTrait::getExpirationTime($ttl);
                $extra_info = $result['params']['gavahi_1']['data'];
            }

            File::store($d);

            return ['link' => $link, 'extra_info' => $extra_info];
        } else {
            return false;
        }

    }

    public static function asyncPdf($identifier, $user_id, $data)
    {
        $time = round(microtime(true) * 1000);
        $job_id = Queue::push(new MakePdfJob($identifier, $user_id, $data));
        Log::info("#push " . (round(microtime(true) * 1000) - $time) . " milisec long");
        $data = [
            'job_id' => $job_id,
            'user_id' => $user_id,
            'identifier' => $identifier,
        ];
        PdfStatus::store($data);
        if ($job_id) return ['job_id' => $job_id];
        else return false;
    }

    public static function pdfStatus($job_id, $user_id)
    {
        $item = PdfStatus::getStatus($job_id, $user_id);
        return $item ?? Null;
    }

    public static function pdfLink($job_id, $user_id)
    {
        $data = PdfStatus::show($job_id, $user_id);
        $api_prefix = '';
        if (!empty($data)) {
            Log::info('data not empty');

            if ($data['status'] == 'success') {
                Log::info('data status success');

                $indexes = Interpreter::getBy('identifier', $data['identifier'] . "%");
                $api_prefix = $indexes[0]['api_prefix'];
                Log::info($api_prefix);
                $filename = str_replace(array($api_prefix . '/', '.pdf'), '', $data['link']);
                Log::info($filename);

                $expired = File::checkExpiration($filename, $user_id);
                Log::info($expired);

                if ($expired) {
                    Log::info('exp');
                    return 'expired';
                } else {
                    unset($data['identifier']);
                    Log::info('not exp');
                    return $data;
                }
            } elseif ($data['status'] == 'failed') {
                Log::info('failed');
                return 'failed';
            } elseif ($data['status'] == 'pending') {
                Log::info('pending');
                return 'pending';
            }

        } else throw new ModelNotFoundException();
    }

    public static function gavahiPdfWithInfo($user_id, $data)
    {

        $pages = [];
        $identifier = 'gavahi_with_info';
        $indexes = Interpreter::getBy('identifier', 'gavahi%');
        $ttl = $indexes[0]['ttl'];
        $uuid = Uuid::uuid4();
        $link = $indexes[0]['api_prefix'] . '/' . $uuid . '.pdf';
        $result = self::setParams($identifier, $link, $ttl, $data);
        $result_copy = $result;
        foreach ($indexes as $key => $value) {
            Storage::put($value['identifier'] . '.blade.php', $value['html']);//**

            if ($result['params'][$value['identifier']]['data']) {
                $result['params'][$value['identifier']]
                    = self::setNumPersian($result['params'][$value['identifier']], $value['identifier']);
                $view = view($value['identifier'], $result['params'][$value['identifier']]);
                try {
                    $view->render();
                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
                }

                $html = $view->toHtml();
                $pages[$key] = $html;
            } else {
                //data does not exist for all postcodes
                $info = array();
                foreach ($data['Postcodes'] as $datum) {
                    $client_row_id = $datum['ClientRowID'];
                    $postcode = $datum['PostCode'];
                    $info[$postcode] = [
                        "ClientRowID" => $client_row_id,
                        "Postcode" => $postcode,
                        "Succ" => 'false',
                        "Result" => null,
                        "Errors" => [
                            'ErrorCode' => "",
                            'ErrorMessage' => ""
                        ]
                    ];
                }
                return [
                    'ResCode' => "",
                    'ResMsg' => trans('messages.custom.error.ResMsg'),
                    'Data' => array_values($info)
                ];
            }
        }

        if ($pages) {
            MakePdf::createPdf($identifier, $pages, $result['params'], $uuid, $data);
            $d = [
                'user_id' => $user_id,
                'filename' => $uuid,
                'barcodes' => $result['barcodes'],
                'expired_at' => CommonTrait::getExpirationTime($ttl)

            ];
            File::store($d);
            return self::makeGavahiInfo($data, $result_copy['params']['gavahi_1'], env('API_HOST') . $link);

        } else return false;
    }

    public static function makeGavahiInfo($reqData, $resData, $link)
    {
// toDo response namovafagh
        $resData = $resData['data'];
        $data = array();
        foreach ($reqData['Postcodes'] as $datum) {
            $postcode = $datum['PostCode'];
            $client_row_id = $datum['ClientRowID'];

            $data[$postcode] = [
                'ClientRowID' => $client_row_id,
                "Postcode" => $postcode,
            ];
            if (array_key_exists($postcode, $resData)) {
                $data[$postcode] += [
                    'Succ' => 'true',
                    'Result' => [
                        'CertificateUrl' => $link
                    ]
                ];
                foreach ($resData[$postcode] as $field_name => $field_value) {
                    $new_key = array_key_exists($field_name, self::$composite_response) ?
                        self::$composite_response[$field_name] : '';
                    $flag = isset($field_value);
                    if ($new_key && $flag) {
                        if ($field_name == 'floorno' && $field_value == 0) $field_value = 'همکف';
                        $data[$postcode]['Result'][$new_key] = $field_value;
                    } elseif ($new_key && !$flag) $data[$postcode]['Result'][$new_key] = null;
                    $data[$postcode]['Result'] += [
                        'ErrorCode' => "",
                        'ErrorMessage' => "",
                        'TraceID' => ""
                    ];
                }
                $data[$postcode]['Errors'] = null;
                //data does not exist for one specific postcode
            } else $data[$postcode] += [
                'Succ' => 'false',
                'Result' => null,
                'Errors' => [
                    'ErrorCode' => "",
                    'ErrorMessage' => ""
                ]
            ];
        }

        return [
            'ResCode' => 0,
            'ResMsg' => trans('messages.custom.success.ResMsg'),
            'Data' => array_values($data)

        ];
    }

    public static function setParams($identifier, $link, $ttl, $data = null)
    {

        $params = [];
        $datetime = explode(' ', Date::convertCarbonToJalali(Carbon::now()));
        $date = str_replace('-', '/', $datetime[0]);
        $barcodes = [];
        $time = round(microtime(true) * 1000);


        if (strpos($identifier, 'notebook') !== false) {
            $blocks_c = 0;
            $all_buildings = 0;
            $unique_recog_code_count = 0;
            $records = 0;
            $tour_name = '';
            $code_joze = '';
            $province = '';
            $region = '';
            $county = '';
            $district = '';
            $zone = '';
            $block_name = '';
            if (isset($data['block_id'])) {
                $block = Block::getData($data['block_id']) ?? [];
                $block_name = $block->name ?? '';
                $tour_name = $block->tour->name ?? '';
                $code_joze = $block->part->name ?? '';
                $province = $block->province->name ?? '';
                $county = $block->county->name ?? '';
                $zone = $block->zone->name ?? '';
                Log::info("#get zone " . (round(microtime(true) * 1000) - $time) . " milisec long");

                $neighbourhoods = [];
                $ways = [];
                $blocks_c = count($block->toArray() ?? []);
                $d['parts'][0] = Part::get($block->part_id ?? '');
                $parts_count = count($d['parts']);
                $d['parts'][0]['blocks'][0] = $block->toArray();

                $all_buildings = count($block->buildings ?? []);
                Log::info("#count buildings " . (round(microtime(true) * 1000) - $time) . " milisec long");
                $records = $block->buildings->sum(function ($building) {
                    return $building->addresses->sum(function ($address) {
                        return $address->entrances->sum(function ($entrance) {
                            return count($entrance->units ?? []);
                        });
                    });
                });

            } else {

                $tour = Tour::getData($data['tour_id']);
                Log::info("#tour get " . (round(microtime(true) * 1000) - $time) . " milisec long");

                $tour_name = $tour->name ?? '';
                $province = $tour->province->name ?? '';
                $neighbourhoods = [];
                $ways = [];
                $d = $tour->toArray();
                $parts_count = count($tour['parts'] ?? []);
                $blocks_c = $tour->parts->sum(function ($part) {
                    return count($part->blocks ?? []);
                });
                $all_buildings = $tour->parts->sum(function ($part) {
                    return $part->blocks->sum(function ($block) {
                        return count($block->buildings ?? []);
                    });
                });
                $records = $tour->parts->sum(function ($part) {
                    return $part->blocks->sum(function ($block) {
                        return $block->buildings->sum(function ($building) {
                            return $building->addresses->sum(function ($address) {
                                return $address->entrances->sum(function ($entrance) {
                                    return count($entrance->units ?? []);
                                });
                            });
                        });
                    });
                });

                Log::info("#count buildings " .
                    (round(microtime(true) * 1000) - $time) .
                    " milisec long");

            }
            foreach ($d['parts'] as $part) {
                foreach ($part['blocks'] as $block) {
                    foreach ($block['buildings'] as $building) {
                        $neighbourhoods[] = $building['neighbourhood']['name'] ?? '';
                        foreach ($building['addresses'] as $address) {
                            $ways[] = [
                                'name' => $address['street']['name'] ?? '',
                                'type' => $address['street']['road_type']['name'] ?? '',
                            ];
                            $ways[] = [
                                'name' => $address['secondary_street']['name'] ?? '',
                                'type' => $address['secondary_street']['road_type']['name'] ?? '',
                            ];
                            foreach ($address['entrances'] as $entrance) {
                                foreach ($entrance['units'] as $unit) {
                                    if ($unit['unit_identifier'] !== null) $unique_recog_code_count++;

                                }
                            }
                        }
                    }
                }
            }
            Log::info("#part data added " . (round(microtime(true) * 1000) - $time) . " milisec long");

//            $ways = array_unique($ways);
            $neighbourhoods = array_unique($neighbourhoods);
            $ways = array_map("unserialize", array_unique(array_map("serialize", $ways)));
            $params = [
                'notebook_1' => [
                    "block_no" => $block_name,
                    "tour_no" => $tour_name,
                    "code_joze" => $code_joze,
                    "province" => $province,
                    "region" => $region,
                    "county" => $county,
                    "district" => $district,
                    "postal_region" => $zone,
                    "blocks_count" => $blocks_c,
                    "buildings" => $all_buildings,
                    "recog_count" => $unique_recog_code_count,
                    "records_counts" => $records,
                    "date" => $date,

                ],
                'notebook_2' => [
                    "tour_no" => $tour_name,
                    "code_joze" => $code_joze,
                    "date" => $date,
                    "data" => $d,
                ],
                'notebook_3' => [
                    "tour_no" => $tour_name,
                    "date" => $date,
                    "code_joze" => $code_joze,
                    "ways" => $ways,
                    "neighbourhoods" => $neighbourhoods,
                    "parts_count" => $parts_count,

                ]
            ];

        } elseif (strpos($identifier, 'gavahi') !== false
            || strpos($identifier, 'gavahi_with_info') !== false) {
            $postalcodes = [];

            if ($identifier == 'gavahi_with_info')
                $postalcodes = collect($data['Postcodes'])->pluck('PostCode')->all();
            elseif ($identifier == 'gavahi') $postalcodes = $data['postalcode'];
            $gavahi_data = PostData::getGavahiInfo($postalcodes);

            foreach ($postalcodes as $key => $postalcode) {

                $barcode = '';
                if (isset($gavahi_data[$postalcode])) {
                    $barcode_unique = false;
                    while (!$barcode_unique) {
                        $barcode = Random::randomNumber(20);
                        if (File::isUniqueBarcode($barcode)) $barcode_unique = true;
                    }
                    $gavahi_data[$postalcode]['barcode'] = $barcode;
                    array_push($barcodes, $barcode);
                    if ($data['geo'] == 1) {
                        $image = GetMap::vectorMap($postalcode);
                        if (!$image) $gavahi_data[$postalcode]['image_exists'] = false;
                        else {
                            $gavahi_data[$postalcode]['image_exists'] = true;
                            $name = $postalcode . '.png';
                            Storage::disk('images')->put($name, $image);
                        }
                    } else $gavahi_data[$postalcode]['image_exists'] = false;

                } else $gavahi_data[$postalcode] = null;
            }

            $gavahi_data = array_filter($gavahi_data, function ($a) {
                return $a !== null;
            });
            if (empty($gavahi_data) && $identifier == 'gavahi') throw new ModelNotFoundException();
//            try {
//                $costs = self::getCost();
//            } catch (PaymentException $e) {
//                throw new PaymentException($e->getMessage());
//            }
            $price = 9;
            $tax = 6;
            $params = [
                "gavahi_1" => [
                    "date" => $date,
                    "data" => $gavahi_data,
                    "x" => 1,
                    "length" => count($gavahi_data),
                    "QRCode" => $link,
                    "ttl" => $ttl,
                    "price" => $price,
                    "tax" => $tax
                ]
            ];
        } elseif (strpos($identifier, 'direct_mail') !== false) {
            Log::info("#start " . (round(microtime(true) * 1000) - $time) . " milisec long");

//            $class_name = IsicClass::getName($data['class_id']);
            Log::info("#name get " . (round(microtime(true) * 1000) - $time) . " milisec long");

            $direct_mail_data = SinaUnits::index($data);
            Log::info("#get data " . (round(microtime(true) * 1000) - $time) . " milisec long");

            foreach ($direct_mail_data as $key => $datum) {
                $direct_mail_data[$key]['font_size1'] = 10;
                $direct_mail_data[$key]['font_size2'] = 12;

                $division = mb_strlen($datum['country_division']);
                if ($division <= 60) $direct_mail_data[$key]['font_size1'] = 10;

                if ($division > 60 && $division <= 79) $direct_mail_data[$key]['font_size1'] = 9;

                if ($division > 79 && $division <= 89) $direct_mail_data[$key]['font_size1'] = 8;

                if ($division > 89 && $division <= 99) $direct_mail_data[$key]['font_size1'] = 7;

                if ($division > 99 || $division >= 109) $direct_mail_data[$key]['font_size1'] = 6;

                if (mb_strlen($datum['parish_and_way']) >= 60) $direct_mail_data[$key]['font_size2'] = 8;
            }

            if (empty($direct_mail_data)) throw new ModelNotFoundException();

            $params = [
                "direct_mail_1" => [
                    "data" => $direct_mail_data,
                    "x" => 1,
                    "length" => count($direct_mail_data),

                ]
            ];
            Log::info("#params set in direct mail " .
                (round(microtime(true) * 1000) - $time) .
                " milisec long");

        }
        return ['params' => $params, 'barcodes' => $barcodes];
    }

    public static function setNumPersian($result, $id)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $num = range(0, 9);
        if ($id == 'postcode') $result = str_replace($num, $persian, $result);
        elseif ($id == 'price') {
            $result['price'] = str_replace($num, $persian, $result['price']);
            $result['tax'] = str_replace($num, $persian, $result['tax']);
        } elseif ($id == 'direct_mail_1') {
            foreach ($result['data'] as $id => $val) {
                foreach ($val as $field => $value) {
                    if ($field != 'font_size1'
                        && $field != 'font_size2'
                    ) $result['data'][$id][$field] = str_replace($num, $persian, $value);
                }
            }
        } else {

            if ($result['date']) $result['date'] = str_replace($num, $persian, $result['date']);

            if ($id == 'gavahi_1') {
                foreach ($result['data'] as $index => $value) {
                    foreach ($value as $field => $v) {
                        if ($field != 'barcode') {
                            $result['data'][$index][$field] = str_replace($num, $persian, $v);
                            if ($field == 'postalcode') {
                                $result['data'][$index]['postcode'] = $result['data'][$index][$field];
                                $result['data'][$index][$field] =
                                    mb_str_split($result['data'][$index][$field], $length = 1);
                            }
                        }
                    }
                }
                $result['ttl'] = str_replace($num, $persian, $result['ttl']);;
            } elseif ($id == 'notebook_1') {
                foreach ($result as $index => $value) {
                    $result[$index] = str_replace($num, $persian, $value);
                }

            } elseif ($id == 'notebook_2') {
                if ($result['tour_no']) $result['tour_no'] = str_replace($num, $persian, $result['tour_no']);

                if ($result['code_joze']) $result['code_joze'] = str_replace($num, $persian, $result['code_joze']);
                foreach ($result['data']['parts'] as $p => $part) {
                    foreach ($part['blocks'] as $bl => $block) {
                        $result['data']['parts'][$p]['blocks'][$bl]['id'] = str_replace($num, $persian, $block['id']);
                        foreach ($block['buildings'] as $bu => $building) {
                            $result['data']['parts'][$p]['blocks'][$bl]['buildings'][$bu]['building_no'] =
                                str_replace($num, $persian, $building['building_no']);
                            $result['data']['parts'][$p]['blocks'][$bl]['buildings'][$bu]['floor_count'] =
                                str_replace($num, $persian, $building['floor_count']);
                            foreach ($building['addresses'] as $a => $add) {
                                $result['data']["parts"][$p]["blocks"][$bl]["buildings"][$bu]["addresses"][$a]['street']['name'] =
                                    str_replace($num, $persian, $add['street']['name']);
                                $result['data']["parts"][$p]["blocks"][$bl]["buildings"][$bu]["addresses"][$a]['secondary_street']['name'] =
                                    str_replace($num, $persian, $add['secondary_street']['name']);
                                foreach ($add['entrances'] as $e => $ent) {
                                    foreach ($ent['units'] as $u => $unit) {

                                        $result['data']["parts"][$p]["blocks"][$bl]["buildings"][$bu]["addresses"][$a]["entrances"][$e]["units"][$u]["row_no"] =
                                            str_replace($num, $persian, $unit['row_no']);
                                        $result['data']["parts"][$p]["blocks"][$bl]["buildings"][$bu]["addresses"][$a]["entrances"][$e]["plate_no"] =
                                            str_replace($num, $persian, $ent["plate_no"]);
                                        $result['data']["parts"][$p]["blocks"][$bl]["buildings"][$bu]["addresses"][$a]["entrances"][$e]["units"][$u]["floor_no"] =
                                            str_replace($num, $persian, $unit["floor_no"]);
                                        $result['data']["parts"][$p]["blocks"][$bl]["buildings"][$bu]["addresses"][$a]["entrances"][$e]["units"][$u]["unit_no"] =
                                            str_replace($num, $persian, $unit["unit_no"]);
                                        $result['data']["parts"][$p]["blocks"][$bl]["buildings"][$bu]["addresses"][$a]["entrances"][$e]["units"][$u]["location_type_id"] =
                                            str_replace($num, $persian, $unit["location_type_id"]);
                                        $result['data']["parts"][$p]["blocks"][$bl]["buildings"][$bu]["addresses"][$a]["entrances"][$e]["units"][$u]["location_type_id"] =
                                            str_replace($num, $persian, $unit["location_type_id"]);
                                        $result['data']["parts"][$p]["blocks"][$bl]["buildings"][$bu]["addresses"][$a]["entrances"][$e]["units"][$u]["recog_code"] =
                                            str_replace($num, $persian, $unit["recog_code"]);
                                    }
                                }
                            }
                        }
                    }
                }
            } elseif ($id == 'notebook_3') {
                if ($result['tour_no']) $result['tour_no'] = str_replace($num, $persian, $result['tour_no']);
                if ($result['code_joze']) $result['code_joze'] = str_replace($num, $persian, $result['code_joze']);
                if ($result['ways']) {
                    foreach ($result['ways'] as $w => $way) {
                        $result['ways'][$w]['name'] = str_replace($num, $persian, $way['name']);
                    }
                }
            }
        }
        return $result;
    }

    public static function getCost()
    {
        $services = PaymentModule::getServices();
        $price = null;
        $tax = null;

        if (isset($services->value)) {
            foreach ($services->value as $res) {
                if ($res->name == env('PAYMENT_SERVICES_NAME')) {
                    $calculate_tax = $res->price + $res->tax;
                    $persian_num = self::setNumPersian(['price' => $res->price, 'tax' => $calculate_tax], 'price');
                    $price = $persian_num['price'];
                    $tax = $persian_num['tax'];
                }
            }
            return ['price' => $price, 'tax' => $tax];


        } else throw new PaymentException(trans('messages.custom.error.payment'));
    }

    public static function getItem($odata)
    {
        $item = File::getItems($odata)->toArray();
        if (count($item) == 0) throw new ModelNotFoundException();
        $date1 = Date::convertCarbonToJalali(Carbon::now());
        $date2 = $item[0]['expired_at'];
        if ($date1 > $date2) return ['link' => 'expired'];

        $filename = $item[0]['filename'];
        return ['link' => env('GAVAHI_HOST') . "/pdf/files/gavahi/$filename.pdf"];
    }
}
