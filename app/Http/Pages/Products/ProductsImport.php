<?php

namespace App\Http\Pages\Products;

use App\Imports\NewProductImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ProductsImport extends Component
{
    use WithFileUploads;

    public $file;
    public $transfer;

    public function render()
    {
        return view('pages.products.products-import');
    }

    public function upload()
    {
//        dd($this->transfer);

        $this->validate([
            'file'  => ['required', 'mimes:xls,xlsx']
        ]);

        Excel::import(new NewProductImport($this->transfer), $this->file);
    }
}
