<?php

namespace App\Services\Location;

class HcmLocationService
{
    /** @var array<int,array{code:string,name:string}>|null */
    protected ?array $districts = null;

    /** @var array<string,array<int,array{code:string,name:string}>>|null */
    protected ?array $wardsByDistrict = null;

    public function __construct()
    {
        require_once base_path('distance/v2.php');
    }

    /**
     * @return array<int,array{code:string,name:string}>
     */
    public function getDistricts(): array
    {
        $this->boot();

        return $this->districts ?? [];
    }

    /**
     * @param string $districtCode
     * @return array<int,array{code:string,name:string}>
     */
    public function getWards(string $districtCode): array
    {
        $this->boot();

        $districtCode = (string) $districtCode;

        return $this->wardsByDistrict[$districtCode] ?? [];
    }

    /**
     * @param string $districtCode
     * @return array{code:string,name:string}|null
     */
    public function findDistrict(string $districtCode): ?array
    {
        $this->boot();

        $districtCode = (string) $districtCode;

        foreach ($this->districts ?? [] as $district) {
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
    public function findWard(string $districtCode, string $wardCode): ?array
    {
        $wardCode = (string) $wardCode;

        foreach ($this->getWards($districtCode) as $ward) {
            if ($ward['code'] === $wardCode) {
                return $ward;
            }
        }

        return null;
    }

    protected function boot(): void
    {
        if ($this->districts !== null && $this->wardsByDistrict !== null) {
            return;
        }

        $payload = distance_v2_hcm_payload();

        $this->districts = $payload['districts'] ?? [];
        $this->wardsByDistrict = $payload['wards'] ?? [];
    }
}
