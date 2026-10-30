<?php

namespace App\Enums;

enum DocumentCategory: string
{
    case Haki = 'haki';
    case Patent = 'patent';
    case IndustrialDesign = 'industrial_design';
    case ResearchPublications = 'research_publications';
    case TechnicalSpecifications = 'technical_specifications';
    case TestingReports = 'testing_reports';
    case Certifications = 'certifications';
    case LegalDocuments = 'legal_documents';
    case PartnershipDocuments = 'partnership_documents';

    public function label(): string
    {
        return match ($this) {
            self::Haki => 'HAKI',
            self::Patent => 'Patent',
            self::IndustrialDesign => 'Industrial Design',
            self::ResearchPublications => 'Research Publications',
            self::TechnicalSpecifications => 'Technical Specifications',
            self::TestingReports => 'Testing Reports',
            self::Certifications => 'Certifications',
            self::LegalDocuments => 'Legal Documents',
            self::PartnershipDocuments => 'Partnership Documents',
        };
    }
}
