<?php

require_once __DIR__ . '/utils.php';

const DISTANCE_V2_HCM_CACHE_KEY = 'distance_hcm_v2_payload';
const DISTANCE_V2_HCM_CACHE_TTL = 21600; // 6 hours
const DISTANCE_V2_HCM_PROVINCE_ID = 79;

/**
 * Fetch and normalize Ho Chi Minh City districts & wards.
 *
 * @return array{districts: array<int,array{code:string,name:string}>, wards: array<string,array<int,array{code:string,name:string}>>, fetched_at:int, fallback?:bool}
 */
function distance_v2_hcm_payload(): array
{
    if ($cached = cache_get(DISTANCE_V2_HCM_CACHE_KEY, DISTANCE_V2_HCM_CACHE_TTL)) {
        return $cached;
    }

    $url = sprintf('https://provinces.open-api.vn/api/v2/p/%d?depth=2', DISTANCE_V2_HCM_PROVINCE_ID);

    try {
        $province = http_get_json($url, [], 12);
        [$districts, $wardMap] = distance_v2_normalize_province($province);

        $payload = [
            'districts' => $districts,
            'wards' => $wardMap,
            'fetched_at' => time(),
        ];

        cache_set(DISTANCE_V2_HCM_CACHE_KEY, $payload);

        return $payload;
    } catch (\Throwable $e) {
        $fallback = distance_v2_hcm_fallback();
        cache_set(DISTANCE_V2_HCM_CACHE_KEY, $fallback);
        return $fallback;
    }
}

/**
 * @param array<string,mixed> $province
 * @return array{0: array<int,array{code:string,name:string}>, 1: array<string,array<int,array{code:string,name:string}>>}
 */
function distance_v2_normalize_province(array $province): array
{
    $districts = [];
    $wardMap = [];

    foreach ($province['districts'] ?? [] as $district) {
        $code = isset($district['code']) ? (string) $district['code'] : '';
        $name = trim((string)($district['name'] ?? ''));

        if ($code === '' || $name === '') {
            continue;
        }

        $districts[] = [
            'code' => $code,
            'name' => $name,
        ];

        $wards = [];
        foreach ($district['wards'] ?? [] as $ward) {
            $wardCode = isset($ward['code']) ? (string) $ward['code'] : '';
            $wardName = trim((string)($ward['name'] ?? ''));
            if ($wardCode === '' || $wardName === '') {
                continue;
            }

            $wards[] = [
                'code' => $wardCode,
                'name' => $wardName,
            ];
        }

        $wardMap[$code] = $wards;
    }

    usort($districts, fn ($a, $b) => strcmp($a['name'], $b['name']));
    foreach ($wardMap as &$wards) {
        usort($wards, fn ($a, $b) => strcmp($a['name'], $b['name']));
    }

    return [$districts, $wardMap];
}

/**
 * @return array{districts: array<int,array{code:string,name:string}>, wards: array<string,array<int,array{code:string,name:string}>>, fetched_at:int, fallback:bool}
 */
function distance_v2_hcm_fallback(): array
{
    $districts = [
        ['code' => '760', 'name' => 'Quận 1'],
        ['code' => '769', 'name' => 'Quận 3'],
        ['code' => '778', 'name' => 'Quận 7'],
        ['code' => '774', 'name' => 'Quận Bình Thạnh'],
    ];

    $wards = [
        '760' => [
            ['code' => '26734', 'name' => 'Phường Tân Định'],
            ['code' => '26737', 'name' => 'Phường Đa Kao'],
            ['code' => '26740', 'name' => 'Phường Bến Nghé'],
            ['code' => '26743', 'name' => 'Phường Bến Thành'],
        ],
        '769' => [
            ['code' => '26854', 'name' => 'Phường Võ Thị Sáu'],
            ['code' => '26863', 'name' => 'Phường 7'],
            ['code' => '26869', 'name' => 'Phường 8'],
        ],
        '778' => [
            ['code' => '27034', 'name' => 'Phường Tân Phong'],
            ['code' => '27040', 'name' => 'Phường Tân Phú'],
            ['code' => '27043', 'name' => 'Phường Phú Mỹ'],
        ],
        '774' => [
            ['code' => '26959', 'name' => 'Phường 1'],
            ['code' => '26968', 'name' => 'Phường 5'],
            ['code' => '26974', 'name' => 'Phường 7'],
            ['code' => '26986', 'name' => 'Phường 12'],
        ],
    ];

    return [
        'districts' => $districts,
        'wards' => $wards,
        'fetched_at' => time(),
        'fallback' => true,
    ];
}

/**
 * @return array<int,array{code:string,name:string}>
 */
function distance_v2_hcm_districts(): array
{
    $payload = distance_v2_hcm_payload();
    return $payload['districts'] ?? [];
}

/**
 * @param string $districtCode
 * @return array<int,array{code:string,name:string}>
 */
function distance_v2_hcm_wards(string $districtCode): array
{
    $payload = distance_v2_hcm_payload();
    $key = (string) $districtCode;
    return $payload['wards'][$key] ?? [];
}

/**
 * @param string $districtCode
 * @return array{code:string,name:string}|null
 */
function distance_v2_hcm_find_district(string $districtCode): ?array
{
    $districtCode = (string) $districtCode;
    foreach (distance_v2_hcm_districts() as $district) {
        if ($district['code'] === $districtCode) {
            return $district;
        }
    }

    return null;
}

/**
 * @param string $districtCode
 * @param string $wardCode
 * @return array{code:string,name:string}|null
 */
function distance_v2_hcm_find_ward(string $districtCode, string $wardCode): ?array
{
    $wardCode = (string) $wardCode;
    foreach (distance_v2_hcm_wards($districtCode) as $ward) {
        if ($ward['code'] === $wardCode) {
            return $ward;
        }
    }

    return null;
}
