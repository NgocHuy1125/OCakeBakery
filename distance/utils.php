<?php
// utils.php (no Google; OSM/OSRM only)

/**
 * HTTP GET JSON với cURL
 */
function http_get_json(string $url, array $headers = [], int $timeout = 15) {
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => $timeout,
    CURLOPT_TIMEOUT => $timeout,
    CURLOPT_HTTPHEADER => array_merge([
      'User-Agent: BongTra-Delivery-Module/1.2 (+support@bongtra.vn)'
    ], $headers),
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
  ]);
  $res = curl_exec($ch);
  if ($res === false) {
    $err = curl_error($ch);
    curl_close($ch);
    throw new Exception("HTTP error: ".$err);
  }
  $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
  curl_close($ch);
  if ($code >= 400) throw new Exception("Upstream $code: $url");
  $data = json_decode($res, true);
  if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception("JSON parse error from $url");
  }
  return $data;
}

/**
 * Haversine (m) – fallback khi không có tuyến OSRM
 */
function haversine_m(float $lat1, float $lon1, float $lat2, float $lon2): float {
  $R = 6371000.0;
  $phi1 = deg2rad($lat1); $phi2 = deg2rad($lat2);
  $dphi = deg2rad($lat2 - $lat1);
  $dlambda = deg2rad($lon2 - $lon1);
  $a = sin($dphi/2)**2 + cos($phi1)*cos($phi2)*sin($dlambda/2)**2;
  return 2*$R*atan2(sqrt($a), sqrt(1-$a));
}

/**
 * File-cache đơn giản
 */
function cache_get(string $key, int $ttl_sec = 900) {
  $path = sys_get_temp_dir() . "/btcache_" . md5($key) . ".json";
  if (is_file($path) && (time() - filemtime($path) < $ttl_sec)) {
    $txt = file_get_contents($path);
    return json_decode($txt, true);
  }
  return null;
}
function cache_set(string $key, $data) {
  $path = sys_get_temp_dir() . "/btcache_" . md5($key) . ".json";
  file_put_contents($path, json_encode($data, JSON_UNESCAPED_UNICODE));
}

/* =========================================================
 * OSM / OSRM HELPERS (ưu tiên)
 * =======================================================*/

/**
 * OSRM table (matrix) – 1 origin, nhiều destinations
 * Trả về raw JSON từ server OSRM cộng đồng.
 */
function osrm_table(array $coords) {
  // coords: [['lat'=>..., 'lon'=>...], ...] (index 0 = origin)
  $parts = array_map(fn($c)=> $c['lon'].",".$c['lat'], $coords);
  $coordStr = implode(";", $parts);
  $url = "https://routing.openstreetmap.de/routed-car/table/v1/driving/$coordStr?sources=0&annotations=distance,duration";
  return http_get_json($url);
}

/**
 * OSRM route (1-1): trả về ['distance_m'=>int|null,'duration_s'=>int|null]
 */
function osrm_route_pair(float $oLat, float $oLng, float $dLat, float $dLng): ?array {
  $cacheKey = sprintf("osrm_route_%f_%f_%f_%f", $oLat, $oLng, $dLat, $dLng);
  if ($c = cache_get($cacheKey, 300)) return $c;

  $coord = $oLng.','.$oLat.';'.$dLng.','.$dLat;
  $url = "https://routing.openstreetmap.de/routed-car/route/v1/driving/$coord?overview=false&alternatives=false&annotations=distance,duration&steps=false";
  try {
    $data = http_get_json($url, [], 15);
    if (($data['code'] ?? '') !== 'Ok' || empty($data['routes'][0]['legs'][0])) {
      return null;
    }
    $leg = $data['routes'][0]['legs'][0];
    $res = [
      'distance_m' => isset($leg['distance']) ? (int)round($leg['distance']) : null,
      'duration_s' => isset($leg['duration']) ? (int)round($leg['duration']) : null,
    ];
    cache_set($cacheKey, $res);
    return $res;
  } catch (\Throwable $e) {
    return null;
  }
}
