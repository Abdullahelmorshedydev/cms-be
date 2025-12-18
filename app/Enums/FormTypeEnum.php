<?php

namespace App\Enums;

enum FormTypeEnum: int
{
    case CONTACT        = 1;
    case NEWSLETTER     = 2;
    case SUPPORT        = 3;
    case QUOTE          = 4;
    case CAREER         = 5;
    case FEEDBACK       = 6;
    case INQUIRY        = 7;
    case PARTNERSHIP    = 8;
    case COMPLAINT      = 9;
    case SUGGESTION     = 10;
    case REGISTRATION   = 11;
    case BOOKING        = 12;
    case CONSULTATION   = 13;
    case DEMO_REQUEST   = 14;
    case OTHER          = 15;

    /**
     * Get all cases as array for select options
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'lang' => $case->lang()
        ], self::cases());
    }

    /**
     * Get human readable label
     */
    public function lang(): string
    {
        return match ($this) {
            self::CONTACT => __('custom.enums.contact_form'),
            self::NEWSLETTER => __('custom.enums.newsletter'),
            self::SUPPORT => __('custom.enums.support'),
            self::QUOTE => __('custom.enums.quote'),
            self::CAREER => __('custom.enums.career'),
            self::FEEDBACK => __('custom.enums.feedback'),
            self::INQUIRY => __('custom.enums.inquiry'),
            self::PARTNERSHIP => __('custom.enums.partnership'),
            self::COMPLAINT => __('custom.enums.complaint'),
            self::SUGGESTION => __('custom.enums.suggestion'),
            self::REGISTRATION => __('custom.enums.registration'),
            self::BOOKING => __('custom.enums.booking'),
            self::CONSULTATION => __('custom.enums.consultation'),
            self::DEMO_REQUEST => __('custom.enums.demo_request'),
            self::OTHER => __('custom.enums.other'),
        };
    }

    /**
     * Get icon for form type
     */
    public function icon(): string
    {
        return match ($this) {
            self::CONTACT => 'mdi mdi-email-outline',
            self::NEWSLETTER => 'mdi mdi-newspaper-variant-outline',
            self::SUPPORT => 'mdi mdi-help-circle-outline',
            self::QUOTE => 'mdi mdi-file-document-outline',
            self::CAREER => 'mdi mdi-briefcase-outline',
            self::FEEDBACK => 'mdi mdi-comment-quote-outline',
            self::INQUIRY => 'mdi mdi-information-outline',
            self::PARTNERSHIP => 'mdi mdi-handshake-outline',
            self::COMPLAINT => 'mdi mdi-alert-circle-outline',
            self::SUGGESTION => 'mdi mdi-lightbulb-outline',
            self::REGISTRATION => 'mdi mdi-account-plus-outline',
            self::BOOKING => 'mdi mdi-calendar-check-outline',
            self::CONSULTATION => 'mdi mdi-account-tie',
            self::DEMO_REQUEST => 'mdi mdi-presentation-play',
            self::OTHER => 'mdi mdi-dots-horizontal',
        };
    }

    /**
     * Get color for form type badge
     */
    public function color(): string
    {
        return match ($this) {
            self::CONTACT => 'primary',
            self::NEWSLETTER => 'info',
            self::SUPPORT => 'warning',
            self::QUOTE => 'success',
            self::CAREER => 'dark',
            self::FEEDBACK => 'info',
            self::INQUIRY => 'primary',
            self::PARTNERSHIP => 'success',
            self::COMPLAINT => 'danger',
            self::SUGGESTION => 'warning',
            self::REGISTRATION => 'info',
            self::BOOKING => 'success',
            self::CONSULTATION => 'dark',
            self::DEMO_REQUEST => 'primary',
            self::OTHER => 'secondary',
        };
    }

    /**
     * Get route name for form type
     */
    public function route(): string
    {
        return match ($this) {
            self::CONTACT => 'dashboard.forms.contact',
            self::NEWSLETTER => 'dashboard.forms.newsletter',
            self::SUPPORT => 'dashboard.forms.support',
            self::QUOTE => 'dashboard.forms.quote',
            self::CAREER => 'dashboard.forms.career',
            self::FEEDBACK => 'dashboard.forms.feedback',
            self::INQUIRY => 'dashboard.forms.inquiry',
            self::PARTNERSHIP => 'dashboard.forms.partnership',
            self::COMPLAINT => 'dashboard.forms.complaint',
            self::SUGGESTION => 'dashboard.forms.suggestion',
            self::REGISTRATION => 'dashboard.forms.registration',
            self::BOOKING => 'dashboard.forms.booking',
            self::CONSULTATION => 'dashboard.forms.consultation',
            self::DEMO_REQUEST => 'dashboard.forms.demo-request',
            self::OTHER => 'dashboard.forms.other',
        };
    }

    /**
     * Get permission for form type
     */
    public function permission(): string
    {
        return match ($this) {
            self::CONTACT => 'form-contact.show',
            self::NEWSLETTER => 'form-newsletter.show',
            self::SUPPORT => 'form-support.show',
            self::QUOTE => 'form-quote.show',
            self::CAREER => 'form-career.show',
            self::FEEDBACK => 'form-feedback.show',
            self::INQUIRY => 'form-inquiry.show',
            self::PARTNERSHIP => 'form-partnership.show',
            self::COMPLAINT => 'form-complaint.show',
            self::SUGGESTION => 'form-suggestion.show',
            self::REGISTRATION => 'form-registration.show',
            self::BOOKING => 'form-booking.show',
            self::CONSULTATION => 'form-consultation.show',
            self::DEMO_REQUEST => 'form-demo_request.show',
            self::OTHER => 'form-other.show',
        };
    }

    /**
     * Get slug for form type (used in route)
     */
    public function slug(): string
    {
        return match ($this) {
            self::CONTACT       => 'contact',
            self::NEWSLETTER    => 'newsletter',
            self::SUPPORT       => 'support',
            self::QUOTE         => 'quote',
            self::CAREER        => 'career',
            self::FEEDBACK      => 'feedback',
            self::INQUIRY       => 'inquiry',
            self::PARTNERSHIP   => 'partnership',
            self::COMPLAINT     => 'complaint',
            self::SUGGESTION    => 'suggestion',
            self::REGISTRATION  => 'registration',
            self::BOOKING       => 'booking',
            self::CONSULTATION  => 'consultation',
            self::DEMO_REQUEST  => 'demo-request',
            self::OTHER         => 'other',
        };
    }

    /**
     * Get all form types with their metadata for sidebar/navigation
     */
    public static function getNavigationData(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'lang' => $case->lang(),
            'route' => $case->route(),
            'permission' => $case->permission(),
            'icon' => $case->icon(),
            'slug' => $case->slug(),
        ], self::cases());
    }
}
