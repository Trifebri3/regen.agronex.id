<?php

namespace App\Services;

use App\Models\Advisory;
use App\Models\Device;
use App\Models\SensorReading;

class RuleBasedAdvisoryService
{
    /**
     * Evaluate sensor reading thresholds and generate deterministic agronomic advisories.
     *
     * @return array<int, Advisory>
     */
    public function evaluateReading(Device $device, SensorReading $reading): array
    {
        $created = [];

        if ($reading->soil_ph !== null) {
            $ph = (float) $reading->soil_ph;
            if ($ph < 5.5) {
                $created[] = Advisory::create([
                    'organization_id' => $device->organization_id,
                    'farm_id' => $device->farm_id,
                    'device_id' => $device->id,
                    'source_type' => 'rule_based',
                    'is_demo' => $reading->is_demo,
                    'severity' => 'attention',
                    'category' => 'soil_health',
                    'title' => 'pH Tanah Terlalu Asam ('.number_format($ph, 2).')',
                    'recommendation' => 'Jadwalkan aplikasi kapur pertanian (dolomit) bertahap dan evaluasi dosis pupuk amonium untuk menstabilkan ketersediaan hara.',
                    'rationale' => 'Rule SoilSense: pH < 5.5 menghambat serapan Fosfor (P) dan meningkatkan kelarutan Aluminium.',
                ]);
            } elseif ($ph > 7.5) {
                $created[] = Advisory::create([
                    'organization_id' => $device->organization_id,
                    'farm_id' => $device->farm_id,
                    'device_id' => $device->id,
                    'source_type' => 'rule_based',
                    'is_demo' => $reading->is_demo,
                    'severity' => 'attention',
                    'category' => 'soil_health',
                    'title' => 'pH Tanah Basa / Alkalin ('.number_format($ph, 2).')',
                    'recommendation' => 'Tambahkan bahan organik matang / kompos asam humat dan pantau ketersediaan unsur mikro (Fe, Zn, Mn).',
                    'rationale' => 'Rule SoilSense: pH > 7.5 menurunkan kelarutan unsur mikro esensial.',
                ]);
            }
        }

        if ($reading->soil_moisture_pct !== null) {
            $moisture = (float) $reading->soil_moisture_pct;
            if ($moisture < 35.0) {
                $created[] = Advisory::create([
                    'organization_id' => $device->organization_id,
                    'farm_id' => $device->farm_id,
                    'device_id' => $device->id,
                    'source_type' => 'rule_based',
                    'is_demo' => $reading->is_demo,
                    'severity' => 'critical',
                    'category' => 'irrigation',
                    'title' => 'Defisit Kelembapan Zona Akar ('.number_format($moisture, 1).'%)',
                    'recommendation' => 'Aktifkan siklus irigasi tetes terukur untuk mengembalikan kelembapan tanah ke rentang optimal 55%–70%.',
                    'rationale' => 'Rule WaterSense/SoilSense: Kelembapan tanah < 35% berisiko memicu stres air pada fase vegetatif/generatif.',
                ]);
            }
        }

        if ($reading->ambient_temp_c !== null && (float) $reading->ambient_temp_c > 33.0) {
            $temp = (float) $reading->ambient_temp_c;
            $created[] = Advisory::create([
                'organization_id' => $device->organization_id,
                'farm_id' => $device->farm_id,
                'device_id' => $device->id,
                'source_type' => 'rule_based',
                'is_demo' => $reading->is_demo,
                'severity' => 'attention',
                'category' => 'microclimate',
                'title' => 'Suhu Iklim Mikro Tinggi ('.number_format($temp, 1).' °C)',
                'recommendation' => 'Pastikan kecukupan mulsa permukaan dan hindari penyemprotan nutrisi daun pada jam puncak radiasi matahari.',
                'rationale' => 'Rule EnviroSense: Suhu > 33 °C meningkatkan laju evapotranspirasi dan risiko gugur bunga.',
            ]);
        }

        return $created;
    }
}
