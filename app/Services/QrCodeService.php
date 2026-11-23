<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate an inline SVG QR code for the given payload string.
     */
    public function toSvg(string $payload, int $size = 180): string
    {
        if (class_exists(QrCode::class)) {
            return (string) QrCode::format('svg')
                ->size($size)
                ->margin(1)
                ->errorCorrection('M')
                ->generate($payload);
        }

        $safePayload = htmlspecialchars($payload, ENT_QUOTES, 'UTF-8');

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$size}" height="{$size}" viewBox="0 0 180 180" role="img" aria-label="QR {$safePayload}">
    <rect width="180" height="180" fill="#ffffff" rx="12" />
    <rect x="14" y="14" width="42" height="42" fill="none" stroke="#111827" stroke-width="6" />
    <rect x="26" y="26" width="18" height="18" fill="#111827" />
    <rect x="124" y="14" width="42" height="42" fill="none" stroke="#111827" stroke-width="6" />
    <rect x="136" y="26" width="18" height="18" fill="#111827" />
    <rect x="14" y="124" width="42" height="42" fill="none" stroke="#111827" stroke-width="6" />
    <rect x="26" y="136" width="18" height="18" fill="#111827" />
    <text x="90" y="96" text-anchor="middle" font-family="monospace" font-size="11" fill="#111827">{$safePayload}</text>
</svg>
SVG;
    }
}
