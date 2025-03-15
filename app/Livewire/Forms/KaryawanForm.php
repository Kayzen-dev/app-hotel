<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Karyawan;
use Livewire\Attributes\Validate;

class KaryawanForm extends Form
{
    public ?Karyawan $karyawan = null;
    public $id;
    public $gaji_pokokRp;

    #[Validate('required', message: 'Nama wajib diisi')]
    public $nama;

    #[Validate('required', message: 'Nomor Telepon wajib diisi')]
    public $no_tlpn;

    #[Validate('required', message: 'Alamat wajib diisi')]
    public $alamat;

    #[Validate('required', message: 'Jenis kelamin wajib diisi')]
    public $jenis_kelamin;

    #[Validate('required', message: 'Posisi wajib diisi')]
    public $posisi;

    #[Validate('required', message: 'Gaji pokok wajib diisi')]
    public $gaji_pokok;

    #[Validate('required', message: 'Status kerja wajib diisi')]
    public $status_kerja;

    public $shift_kerja;
    public $tanggal_bergabung;

    public function setKaryawan(Karyawan $karyawan)
    {
        $this->karyawan = $karyawan;

        $this->id = $karyawan->id;
        $this->nama = $karyawan->nama;
        $this->no_tlpn = $karyawan->no_tlpn;
        $this->alamat = $karyawan->alamat;
        $this->jenis_kelamin = $karyawan->jenis_kelamin;
        $this->posisi = $karyawan->posisi;
        $this->gaji_pokok = $karyawan->gaji_pokok;
        $this->status_kerja = $karyawan->status_kerja;
        $this->tanggal_bergabung = $karyawan->tanggal_bergabung;
        $this->shift_kerja = $karyawan->shift_kerja;
    }

    public function store()
    {
        return Karyawan::create([
            'nama' => $this->nama,
            'no_tlpn' => $this->no_tlpn,
            'alamat' => $this->alamat,
            'jenis_kelamin' => $this->jenis_kelamin,
            'posisi' => $this->posisi,
            'gaji_pokok' => $this->gaji_pokok,
            'status_kerja' => $this->status_kerja,
            'tanggal_bergabung' => $this->tanggal_bergabung,
            'shift_kerja' => $this->shift_kerja,
        ]);
    }

    public function update($id)
    {
        $this->validate([
            'nama' => 'required',
            'no_tlpn' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'posisi' => 'required',
            'gaji_pokok' => 'required',
            'status_kerja' => 'required',
            'tanggal_bergabung' => 'nullable',
            'shift_kerja' => 'nullable',
        ]);

        $karyawan = Karyawan::findOrFail($id);

        $karyawan->update([
            'nama' => $this->nama,
            'no_tlpn' => $this->no_tlpn,
            'alamat' => $this->alamat,
            'jenis_kelamin' => $this->jenis_kelamin,
            'posisi' => $this->posisi,
            'gaji_pokok' => $this->gaji_pokok,
            'status_kerja' => $this->status_kerja,
            'tanggal_bergabung' => $this->tanggal_bergabung,
            'shift_kerja' => $this->shift_kerja,
        ]);

        return $karyawan;
    }

    public function delete()
    {
        return $this->karyawan->delete();
    }
}
