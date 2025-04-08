<?php

namespace App\Enum;

enum SlotHtmlClassEnum
{
    public const FINISHED_CLASS = 'finished';
    public const AVAILABLE_CLASS = 'available-green';
    public const FULL_CLASS = 'full-red';
    public const DEFAULT_CLASS = 'available-green';

    public const FINISHED_TIME_CLASS = 'time-stamp-finished';
    public const AVAILABLE_TIME_CLASS = 'time-stamp-available';
    public const FULL_TIME_CLASS = 'time-stamp-full';
    public const DEFAULT_TIME_CLASS = 'time-stamp-available';

    public const ADMIN_SLOT_VALID = "badge-custom-valid";
    public const ADMIN_SLOT_EXPIRED = "badge-custom-expired";
}
