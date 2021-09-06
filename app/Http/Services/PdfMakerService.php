<?php

namespace App\Http\Services;

use App\Helpers\Random;
use App\Jobs\MakePdfJob;
use App\Jobs\MyCustomJob;
use App\Models\File;
use App\Models\Interpreter;
use App\Modules\GetMap\GetMap;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Date;
use Carbon\Carbon;
use App\Models\Notebook\{Entrance, PdfStatus, Tour, Part, Province, Block, Building, Address, Unit, Neighbourhood, Way};
use App\Models\Gavahi\PostData;
use function PHPUnit\Framework\returnArgument;
use App\Modules\MakePdf;


class PdfMakerService
{
    use CommonTrait;

    public static function setParams($identifier, $link, $ttl, $data = null)
    {

        $params = [];
        $datetime = explode(' ', Date::convertCarbonToJalali(Carbon::now()));
        $date = str_replace('-', '/', $datetime[0]);
        $barcodes = [];

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

            $time = round(microtime(true) * 1000);
            if (isset($data['block_id'])) {
                $block = Block::getData($data['block_id']) ?? [];
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

                Log::info("#count buildings " . (round(microtime(true) * 1000) - $time) . " milisec long");

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
                                    if ($unit['unit_identifier'] !== null) {
                                        $unique_recog_code_count++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
//            $ways = array_unique($ways);
            $neighbourhoods = array_unique($neighbourhoods);
            $ways = array_map("unserialize", array_unique(array_map("serialize", $ways)));
            $params = [
                'notebook_1' => [
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

        } elseif (strpos($identifier, 'gavahi') !== false) {
            $gavahi_data = [];
            foreach ($data['postalcode'] as $key => $postalcode) {
//                dd($postalcode);
                $gavahi_data[$key] = PostData::getInfo($postalcode);

                $barcode = '';
                if (isset($gavahi_data[$key])) {
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
            }
            $gavahi_data = array_filter($gavahi_data, function ($a) {
                return $a !== null;
            });
            if (empty($gavahi_data)) {
                throw new ModelNotFoundException();
            }

            $params = [
                "gavahi_1" => [
                    "date" => $date,
                    "data" => $gavahi_data,
                    "x" => 1,
                    "length" => count($gavahi_data),
                    "QRCode" => $link,
                    "ttl" => $ttl,
                ]
            ];
        }
//        Log::info("#params sent " . (round(microtime(true) * 1000) - $time) . " milisec long");
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
            $result['ttl'] = str_replace($num, $persian, $result['ttl']);;
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
            foreach ($result['data']['parts'] as $p => $part) {
                foreach ($part['blocks'] as $bl => $block) {
                    $result['data']['parts'][$p]['blocks'][$bl]['id'] = str_replace($num, $persian, $block['id']);
                    foreach ($block['buildings'] as $bu => $building) {
                        $result['data']['parts'][$p]['blocks'][$bl]['buildings'][$bu]['building_no'] =
                            str_replace($num, $persian, $building['building_no']);
                        $result['data']['parts'][$p]['blocks'][$bl]['buildings'][$bu]['floor_count'] =
                            str_replace($num, $persian, $building['floor_count']);
                        foreach ($building['addresses'] as $a => $add) {
                            $result['data']["parts"][$p]["blocks"][$bl]["buildings"][$bu]["addresses"][$a]['street']['name'] = str_replace($num, $persian, $add['street']['name']);
                            $result['data']["parts"][$p]["blocks"][$bl]["buildings"][$bu]["addresses"][$a]['secondary_street']['name'] = str_replace($num, $persian, $add['secondary_street']['name']);
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
            if ($result['tour_no']) {
                $result['tour_no'] = str_replace($num, $persian, $result['tour_no']);
            }
            if ($result['code_joze']) {
                $result['code_joze'] = str_replace($num, $persian, $result['code_joze']);
            }
            if ($result['ways']) {
                foreach ($result['ways'] as $w => $way) {
                    $result['ways'][$w]['name'] = str_replace($num, $persian, $way['name']);
                }
            }
        }


//        dd($result);
        return $result;
    }

    public static function asyncPdf($identifier, $link, $uuid, $user_id, $data)
    {
        $time = round(microtime(true) * 1000);
        Log::info("#push " . (round(microtime(true) * 1000) - $time) . " milisec long");

        $job_id = Queue::push(new MakePdfJob($identifier, $link, $uuid, $user_id, $data));
        if ($job_id) {
            $data = [
                'job_id' => $job_id,
                'link' => $link,
                'user_id' => $user_id,

            ];
            PdfStatus::store($data);
            return ['job_id' => $job_id];
        } else {
            return false;
        }


    }

    public static function getPdf($identifier, $link, $uuid, $user_id, $data = null)
    {
        $pages = [];
        $indexes = [];
        $ttl = '۰';
        if ($identifier == 'notebook') {
            $indexes = Interpreter::getBy('identifier', 'notebook%');
        } elseif ($identifier == 'gavahi') {
            $indexes = Interpreter::getBy('identifier', 'gavahi%');
            $ttl = $indexes[0]['ttl'];
        }
//        dd($indexes[0]['ttl']);
        usort($indexes, function ($a, $b) {
            return strcmp($a['identifier'], $b['identifier']);//*
        });
        $result = self::setParams($identifier, $link, $ttl, $data);
        foreach ($indexes as $key => $value) {
            Storage::put($value['identifier'] . '.blade.php', $value['html']);//**


//            $params[$value['identifier']] = $result['params'];
            if ($result['params'][$value['identifier']]) {
                $result['params'][$value['identifier']]
                    = self::setNumPersian($result['params'][$value['identifier']], $value['identifier']);
                $view = view($value['identifier'], $result['params'][$value['identifier']]);
                try {
                    $view->render();

                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
//                    dd($exception->getMessage());
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
                'barcodes' => $result['barcodes'],

            ];
            if ($identifier == 'gavahi') {
                $data['expired_at'] = CommonTrait::getExpirationTime($ttl);
            }

            File::store($data);

            return true;
        } else {
            return false;
        }


    }

    public static function pdfStatus($job_id, $user_id)
    {
        $item = PdfStatus::getStatus($job_id, $user_id);
        return $item ?? Null;
    }

    public static function pdfLink($job_id, $user_id)
    {
        return PdfStatus::show($job_id, $user_id);

    }
}

