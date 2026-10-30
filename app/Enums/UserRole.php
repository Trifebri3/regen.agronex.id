<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case ContentAdmin = 'content_admin';
    case ProductAdmin = 'product_admin';
    case TechnicalAdmin = 'technical_admin';
    case OrganizationAdmin = 'organization_admin';
    case FarmManager = 'farm_manager';
    case Farmer = 'farmer';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::ContentAdmin => 'Content Administrator',
            self::ProductAdmin => 'Product Administrator',
            self::TechnicalAdmin => 'Technical Administrator',
            self::OrganizationAdmin => 'Organization Administrator',
            self::FarmManager => 'Farm Manager',
            self::Farmer => 'Farmer / User',
        };
    }

    public function canAccessAdminPanel(): bool
    {
        return in_array($this, [
            self::SuperAdmin,
            self::ContentAdmin,
            self::ProductAdmin,
            self::TechnicalAdmin,
        ], true);
    }

    public function canAccessAdminSection(string $section): bool
    {
        if ($this === self::SuperAdmin) {
            return true;
        }

        return match ($section) {
            'overview' => $this->canAccessAdminPanel(),
            'website_content' => $this === self::ContentAdmin,
            'products' => $this === self::ProductAdmin,
            'prototypes' => in_array($this, [self::ContentAdmin, self::ProductAdmin, self::TechnicalAdmin], true),
            'documents' => in_array($this, [self::ContentAdmin, self::TechnicalAdmin], true),
            'media' => in_array($this, [self::ContentAdmin, self::ProductAdmin], true),
            'contacts' => in_array($this, [self::ContentAdmin, self::ProductAdmin], true),
            'farms', 'devices', 'settings' => $this === self::TechnicalAdmin,
            'reports' => in_array($this, [self::ProductAdmin, self::TechnicalAdmin], true),
            'users', 'enterprise' => false,
            default => false,
        };
    }

    public function canAccessEnterpriseDashboard(): bool
    {
        return in_array($this, [
            self::SuperAdmin,
            self::OrganizationAdmin,
        ], true);
    }

    public function canAccessFarmApp(): bool
    {
        return in_array($this, [
            self::SuperAdmin,
            self::TechnicalAdmin,
            self::OrganizationAdmin,
            self::FarmManager,
            self::Farmer,
        ], true);
    }

    public function canManageFarmStructure(): bool
    {
        return in_array($this, [
            self::SuperAdmin,
            self::TechnicalAdmin,
            self::OrganizationAdmin,
            self::FarmManager,
        ], true);
    }
}
