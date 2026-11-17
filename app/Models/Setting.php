<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Get setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key.
     *
     * @param string $key
     * @param string|null $value
     * @return \App\Models\Setting
     */
    public static function setValue(string $key, ?string $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function normalizeWhatsappNumber(?string $raw): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $raw);
        if ($digits === '') {
            return '';
        }
        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }
        if (str_starts_with($digits, '8')) {
            return '62' . $digits;
        }
        return $digits;
    }

    /**
     * Ambil seluruh daftar nomor WA Admin beserta status is_active.
     *
     * @return array<int, array{nama: string, no_wa: string, is_active: bool}>
     */
    public static function getAdminWhatsappList(): array
    {
        $rawJson = self::getValue('admin_whatsapp_list');
        if (is_string($rawJson) && trim($rawJson) !== '') {
            $decoded = json_decode($rawJson, true);
            if (is_array($decoded) && !empty($decoded)) {
                $list = [];
                foreach ($decoded as $item) {
                    $noWa = self::normalizeWhatsappNumber($item['no_wa'] ?? '');
                    if ($noWa === '') {
                        continue;
                    }
                    $list[] = [
                        'nama' => trim((string) ($item['nama'] ?? '')) ?: 'Admin Humas',
                        'no_wa' => $noWa,
                        'is_active' => !empty($item['is_active']),
                    ];
                }
                if (!empty($list)) {
                    return $list;
                }
            }
        }

        $legacy = self::normalizeWhatsappNumber(self::getValue('admin_whatsapp', '6282283736481'));
        if ($legacy !== '') {
            return [[
                'nama' => 'Admin Humas',
                'no_wa' => $legacy,
                'is_active' => true,
            ]];
        }

        return [];
    }

    /**
     * Ambil hanya daftar nomor WA Admin yang berstatus aktif (bisa lebih dari 1).
     *
     * @return array<int, array{nama: string, no_wa: string, is_active: bool}>
     */
    public static function getActiveAdminWhatsapps(): array
    {
        return array_values(array_filter(
            self::getAdminWhatsappList(),
            fn($item) => !empty($item['is_active']) && !empty($item['no_wa'])
        ));
    }
}
