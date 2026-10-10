<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Tanzania locale setup:
 *  - adds the Kiswahili language (full phrase translation)
 *  - switches system currency to TZS
 *  - sets the timezone to Africa/Dar_es_Salaam
 *  - prepares "Offline" payment for M-Pesa / Tigo Pesa / Airtel Money
 *
 * Run:  php artisan db:seed --class=TanzaniaLocaleSeeder
 * Safe to run more than once.
 */
class TanzaniaLocaleSeeder extends Seeder
{
    /** Set to false if you want to keep the other payment gateways enabled. */
    protected bool $disableOtherGateways = true;

    public function run(): void
    {
        $this->seedLanguage();
        $this->seedSettings();
        $this->seedOfflinePayment();
    }

    protected function seedLanguage(): void
    {
        $now = now();

        $language = DB::table('languages')->where('name', 'Kiswahili')->first();
        $languageId = $language
            ? $language->id
            : DB::table('languages')->insertGetId([
                'name' => 'Kiswahili',
                'direction' => 'ltr',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

        // 1) Start from every English phrase already known to the system
        $englishId = DB::table('languages')->where('name', 'like', 'english')->value('id');
        $translations = [];
        if ($englishId) {
            foreach (DB::table('language_phrases')->where('language_id', $englishId)->get(['phrase', 'translated']) as $row) {
                $translations[$row->phrase] = $row->translated;
            }
        }

        // 2) Overlay the Swahili translations
        $file = database_path('seeders/data/kiswahili.json');
        $swahili = json_decode(file_get_contents($file), true) ?: [];
        foreach ($swahili as $phrase => $translated) {
            $translations[$phrase] = $translated;
        }

        // 3) Save (update existing rows, insert missing ones)
        $existing = DB::table('language_phrases')
            ->where('language_id', $languageId)
            ->pluck('id', 'phrase')
            ->all();

        foreach ($translations as $phrase => $translated) {
            if (isset($existing[$phrase])) {
                // only overwrite when we actually have a Swahili translation
                if (array_key_exists($phrase, $swahili)) {
                    DB::table('language_phrases')->where('id', $existing[$phrase])
                        ->update(['translated' => $translated, 'updated_at' => $now]);
                }
            } else {
                DB::table('language_phrases')->insert([
                    'language_id' => $languageId,
                    'phrase' => $phrase,
                    'translated' => $translated,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    protected function seedSettings(): void
    {
        $values = [
            'language' => 'english', // English stays the default; Kiswahili is selectable in the language switcher
            'system_currency' => 'TZS',
            'currency_position' => 'left-space', // TSh 50000
            'timezone' => 'Africa/Dar_es_Salaam',
            'phone' => '0786936886',
            'system_email' => 'athumanikasimu38@gmail.com',
        ];

        foreach ($values as $type => $description) {
            $exists = DB::table('settings')->where('type', $type)->exists();
            if ($exists) {
                DB::table('settings')->where('type', $type)
                    ->update(['description' => $description, 'updated_at' => now()]);
            } else {
                DB::table('settings')->insert([
                    'type' => $type,
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    protected function seedOfflinePayment(): void
    {
        $instructions = <<<'HTML'
<h5>Lipa kwa Simu (M-Pesa, Tigo Pesa, Airtel Money)</h5>
<ol>
    <li>Piga <b>*150*00#</b> (M-Pesa), <b>*150*01#</b> (Tigo Pesa) au <b>*150*60#</b> (Airtel Money).</li>
    <li>Chagua <b>Lipa kwa Namba ya Kampuni / Tuma Pesa</b> kisha weka namba iliyo hapa chini.</li>
    <li>Kwenye <b>kumbukumbu (reference)</b> andika <b>Namba ya Malipo (Control Number)</b> iliyoonyeshwa hapo juu.</li>
    <li>Weka kiasi kamili cha oda yako kwa <b>TSh</b>.</li>
    <li>Bofya <b>Lipa kwa mkono</b> (unaweza kupakia picha ya SMS ya muamala kama unataka). Msimamizi atakagua kupitia Namba ya Malipo na kukusajili kwenye kozi.</li>
</ol>
<table class="table table-bordered">
    <tr><th>M-Pesa</th><td>Lipa namba: <b>000000</b> &mdash; Jina: <b>JINA LA BIASHARA</b></td></tr>
    <tr><th>Tigo Pesa</th><td>Namba: <b>0000000000</b> &mdash; Jina: <b>JINA LA BIASHARA</b></td></tr>
    <tr><th>Airtel Money</th><td>Namba: <b>0000000000</b> &mdash; Jina: <b>JINA LA BIASHARA</b></td></tr>
</table>
<p><small>Malipo yanakamilika baada ya msimamizi kuyakagua na kuyaidhinisha.</small></p>
HTML;

        DB::table('payment_gateways')->where('identifier', 'offline')->update([
            'currency' => 'TZS',
            'title' => 'M-Pesa / Tigo Pesa / Airtel Money',
            'status' => 1,
            'keys' => json_encode(['bank_information' => $instructions]),
            'updated_at' => now(),
        ]);

        if ($this->disableOtherGateways) {
            DB::table('payment_gateways')->where('identifier', '!=', 'offline')
                ->update(['status' => 0, 'updated_at' => now()]);
        }
    }
}
