<?php

namespace App\Enums;

enum PrototypeCategory: string
{
    case SoilSense = 'soilsense';
    case WaterSense = 'watersense';
    case EnviroSense = 'envirosense';
    case Terra = 'terra';
    case IntegratedSystem = 'integrated_system';
    case FieldTesting = 'field_testing';
    case ResearchAndDevelopment = 'research_development';
    case ProductUpdates = 'product_updates';

    public function label(): string
    {
        return match ($this) {
            self::SoilSense => 'SoilSense',
            self::WaterSense => 'WaterSense',
            self::EnviroSense => 'EnviroSense',
            self::Terra => 'Terra',
            self::IntegratedSystem => 'Integrated System',
            self::FieldTesting => 'Field Testing',
            self::ResearchAndDevelopment => 'Research & Development',
            self::ProductUpdates => 'Product Updates',
        };
    }
}
