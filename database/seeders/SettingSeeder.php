<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'APP_PAGE_TITLE' => 'TB SBR',
            'APP_PAGE_DESCRIPTION' => 'Toko Bangunan SBR | Building Material',
            'APP_PAGE_LOGO' => null,
            'INVOICE_PREFIX'    => 'SBR',
            'INVOICE_LOGO'    => null,
            'INVOICE_HEADER_ONE'    => 'Toko Bangunan SBR',
            'INVOICE_HEADER_TWO'    => 'Building Material',
            'INVOICE_HEADER_THREE'    => 'Pasaman Barat',
            'INVOICE_FOOTER_ICON_ONE'    => null,
            'INVOICE_FOOTER_ICON_TWO'    => null,
            'INVOICE_FOOTER_ICON_THREE'    => null,
            'INVOICE_FOOTER_ONE'    => 'SBRPASAMAN BARAT',
            'INVOICE_FOOTER_TWO'    => '0822-1193-5100',
            'INVOICE_FOOTER_THREE'    => 'SBRPASAMAN BARAT',
        ];
    }
}
