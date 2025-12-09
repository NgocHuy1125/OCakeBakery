<?php
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/v2.php';

/* Chỉ chạy khi gọi trực tiếp */
if (realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_GET['action'] ?? '';
    try {
        switch ($action) {
            case 'provinces':
                echo json_encode(get_provinces(), JSON_UNESCAPED_UNICODE);
                break;
            case 'districts':
                $p = intval($_GET['province_code'] ?? 0);
                if (!$p) throw new Exception('province_code required', 400);
                echo json_encode(get_districts($p), JSON_UNESCAPED_UNICODE);
                break;
            case 'wards':
                $d = intval($_GET['district_code'] ?? 0);
                if (!$d) throw new Exception('district_code required', 400);
                echo json_encode(get_wards($d), JSON_UNESCAPED_UNICODE);
                break;
            case 'geocode':
                $q = trim($_GET['q'] ?? '');
                if (mb_strlen($q) < 5) throw new Exception('query too short', 400);
                echo json_encode(geocode_vn($q), JSON_UNESCAPED_UNICODE);
                break;
            case 'nearest':
                $lat = floatval($_GET['lat'] ?? 0);
                $lon = floatval($_GET['lon'] ?? 0);
                if (!$lat || !$lon) throw new Exception('lat/lon required', 400);
                echo json_encode(find_nearest_branch($lat, $lon), JSON_UNESCAPED_UNICODE);
                break;
            case 'hcm-districts-v2':
                echo json_encode(distance_v2_hcm_districts(), JSON_UNESCAPED_UNICODE);
                break;
            case 'hcm-wards-v2':
                $districtCode = (string)($_GET['district_code'] ?? '');
                if ($districtCode === '') throw new Exception('district_code required', 400);
                echo json_encode([
                    'district' => $districtCode,
                    'wards' => distance_v2_hcm_wards($districtCode),
                ], JSON_UNESCAPED_UNICODE);
                break;
            default:
                echo json_encode(['error' => 'unknown action'], JSON_UNESCAPED_UNICODE);
        }
    } catch (Exception $e) {
        $code = $e->getCode(); if ($code < 100) $code = 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

/** --- LIB --- **/
function get_provinces() {
    $key = 'provinces_v2';
    if ($c = cache_get($key)) return $c;
    $url = 'https://provinces.open-api.vn/api/v2/p';
    $data = http_get_json($url);
    cache_set($key, $data);
    return $data;
}

function get_districts(int $province_code) {
    $key = "districts_v2_$province_code";
    if ($c = cache_get($key)) return $c;
    $url = "https://provinces.open-api.vn/api/v2/p/$province_code?depth=2";
    $data = http_get_json($url);
    cache_set($key, $data);
    return $data;
}

function get_wards(int $district_code) {
    $key = "wards_v2_$district_code";
    if ($c = cache_get($key)) return $c;
    $url = "https://provinces.open-api.vn/api/v2/d/$district_code?depth=2";
    $data = http_get_json($url);
    cache_set($key, $data);
    return $data;
}

/**
 * Geocode VN – chuẩn dựa theo OSM (Nominatim)
 */
function geocode_vn(string $q) {
    $key = 'geocode_osm_'.md5($q);
    if ($c = cache_get($key, 86400)) return $c;

    $queries = [
        $q,
        str_replace(', Việt Nam', '', $q),
        str_replace(['Phường ', 'Quận ', 'Thành phố ', 'Huyện ', 'Tỉnh '], '', $q),
        preg_replace('/^[^,]+, /', '', $q),
        preg_replace('/^([^,]+, )?[^,]+, /', '', $q),
    ];

    foreach ($queries as $query) {
        $url = 'https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&countrycodes=vn&addressdetails=1&q='.rawurlencode($query);
        try {
            $data = http_get_json($url, ['Accept: application/json']);
        } catch (Exception $e) {
            error_log('Geocode failed: '.$e->getMessage());
            continue;
        }
        usleep(120000);
        if ($data && count($data) > 0) {
            $first = $data[0];
            $res = ['lat' => (float)$first['lat'], 'lon' => (float)$first['lon'], 'raw' => $first];
            cache_set($key, $res);
            return $res;
        }
    }
    $fallback = ['lat' => 10.8231, 'lon' => 106.6297, 'raw' => ['display_name' => 'Fallback: Ho Chi Minh City Center', 'fallback' => true]];
    cache_set($key, $fallback);
    return $fallback;
}

/**
 * Tìm chi nhánh gần nhất:
 * - Ưu tiên OSRM table (đường lái xe)
 * - Fallback cuối: Haversine
 *
 * Trả về:
 * [
 *   'method' => 'osrm'|'haversine',
 *   'candidates' => [...],
 *   'best' => candidate gần nhất,
 *   'nearest_*' => dữ liệu tương thích với frontend
 * ]
 */
function find_nearest_branch(float $lat, float $lon) {
    $branches = require __DIR__.'/branches.php';

    // 1) OSRM table (origin + nhiều địa chỉ)
    try {
        $coords = array_merge(
            [["lat" => $lat, "lon" => $lon]],
            array_map(fn($b) => ["lat" => $b["lat"], "lon" => $b["lon"]], $branches)
        );
        $table = osrm_table($coords);
        $dist = $table['distances'][0] ?? [];
        $dur  = $table['durations'][0] ?? [];

        $out = [];
        for ($i = 1; $i < count($dist); $i++) {
            $out[] = [
                'branch'     => $branches[$i - 1],
                'distance_m' => is_numeric($dist[$i]) ? (int) round($dist[$i]) : null,
                'duration_s' => is_numeric($dur[$i])  ? (int) round($dur[$i])  : null,
            ];
        }

        // Sắp xếp theo thời gian hoặc khoảng cách
        usort($out, function ($a, $b) {
            $da = $a['duration_s'];
            $db = $b['duration_s'];
            if ($da !== null && $db !== null && $da !== $db) return $da <=> $db;
            $ra = $a['distance_m'];
            $rb = $b['distance_m'];
            if ($ra !== null && $rb !== null) return $ra <=> $rb;
            return 0;
        });

        $top = array_slice($out, 0, 4);
        $best = $top[0] ?? null;

        if ($best) {
            $nearest = [
                'nearest_id'    => $best['branch']['id'] ?? null,
                'nearest_name'  => $best['branch']['name'] ?? null,
                'nearest_lat'   => $best['branch']['lat'] ?? null,
                'nearest_lng'   => $best['branch']['lon'] ?? null,
                'nearest_km'    => isset($best['distance_m']) ? round($best['distance_m'] / 1000, 1) : null,
            ];
        } else {
            $nearest = [];
        }

        return array_merge([
            'method'     => 'osrm',
            'candidates' => $top,
            'best'       => $best,
        ], $nearest);

    } catch (\Throwable $e) {
        // 2) Fallback Haversine
        $out = [];
        foreach ($branches as $b) {
            $d = haversine_m($lat, $lon, $b['lat'], $b['lon']);
            $out[] = [
                'branch'     => $b,
                'distance_m' => (int) round($d),
                'duration_s' => null,
            ];
        }

        usort($out, fn($a, $b) => $a['distance_m'] <=> $b['distance_m']);
        $top = array_slice($out, 0, 4);
        $best = $top[0] ?? null;

        if ($best) {
            $nearest = [
                'nearest_id'    => $best['branch']['id'] ?? null,
                'nearest_name'  => $best['branch']['name'] ?? null,
                'nearest_lat'   => $best['branch']['lat'] ?? null,
                'nearest_lng'   => $best['branch']['lon'] ?? null,
                'nearest_km'    => isset($best['distance_m']) ? round($best['distance_m'] / 1000, 1) : null,
            ];
        } else {
            $nearest = [];
        }

        return array_merge([
            'method'     => 'haversine',
            'candidates' => $top,
            'best'       => $best,
        ], $nearest);
    }
}
