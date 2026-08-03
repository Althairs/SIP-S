<?php

namespace App\Livewire\Kajur;

use App\Services\PermissionService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImportMahasiswa extends Component
{
    use WithFileUploads;

    public $file;

    protected function rules()
    {
        return [
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ];
    }

    protected $messages = [
        'file.required' => 'Silakan pilih berkas Excel terlebih dahulu.',
        'file.mimes' => 'Berkas harus berupa file Excel (.xlsx atau .xls).',
        'file.max' => 'Ukuran berkas tidak boleh melebihi 10MB.',
    ];

    public function importExcel()
    {
        $this->validate();

        try {
            $filePath = $this->file->getRealPath();

            $headings = (new \Maatwebsite\Excel\HeadingRowImport)->toArray($filePath);
            $excelHeaders = isset($headings[0][0]) ? array_map('trim', array_map('strtolower', $headings[0][0])) : [];

            $requiredColumns = ['no', 'nama', 'nim', 'fakultas', 'prodi', 'status awal', 'semester awal terdaftar', 'status aktif'];

            $missingColumns = [];
            foreach ($requiredColumns as $col) {
                if ($col === 'prodi') {
                    if (!in_array('prodi', $excelHeaders) && !in_array('program studi', $excelHeaders) && !in_array('program_studi', $excelHeaders)) {
                        $missingColumns[] = 'Prodi / Program Studi';
                    }
                } else {
                    $slugCol = str_replace(' ', '_', $col);
                    if (!in_array($col, $excelHeaders) && !in_array($slugCol, $excelHeaders)) {
                        $missingColumns[] = ucwords($col);
                    }
                }
            }

            if (!empty($missingColumns)) {
                $this->addError('file', 'Format kolom berkas Excel tidak sesuai. Pastikan file mengandung kolom: No, Nama, NIM, Fakultas, Prodi, Status Awal, Semester Awal Terdaftar, dan Status Aktif. Kolom yang tidak ditemukan: ' . implode(', ', $missingColumns));
                return;
            }

            $jurusanId = PermissionService::getJurusanId();
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\MahasiswaImport($jurusanId), $filePath);

            session()->flash('success', 'Data mahasiswa berhasil di-import.');
            $this->reset('file');
        } catch (\Exception $e) {
            $this->addError('file', 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.kajur.import-mahasiswa')->layout('components.layouts.app-auth');
    }
}
