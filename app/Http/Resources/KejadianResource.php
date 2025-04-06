<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KejadianResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nama_kejadian' => $this->data['nama_kejadian'] ?? null,
            'tanggal_kejadian' => $this->data['tanggal_kejadian'] ?? null,
            'lokasi_kejadian' => $this->data['lokasi_kejadian'] ?? null,
            'tipe_kejadian' => $this->data['tipe_kejadian'] ?? null,
            'keterangan' => $this->data['keterangan'] ?? null,
            'is_kecelakaan' => $this->data['is_kecelakaan'] ?? false,
            'is_pencurian' => $this->data['is_pencurian'] ?? false,
            'is_notifikasi' => $this->data['is_notifikasi'] ?? false,
            'nama_korban' => $this->data['nama_korban'] ?? null,
            'alamat_korban' => $this->data['alamat_korban'] ?? null,
            'keterangan_korban' => $this->data['keterangan_korban'] ?? null,
            'satpam_id' => $this->data['satpam_id'] ?? null,
            'satpam_nama' => $this->data['satpam_nama'] ?? null,
            'waktu_laporan' => $this->data['waktu_laporan'] ?? null,
            'waktu_selesai' => $this->data['waktu_selesai'] ?? null,
            'status' => $this->data['status'] ?? null,
            'foto_bukti' => $this->foto_bukti_kejadian ?? [],
            'tindakan' => $this->tindakan ?? [],
            'links' => [
                'self' => route('admin.kejadian.show', $this->id),
                // 'edit' => route('kejadian.edit', $this->id),
                // 'delete' => route('kejadian.destroy', $this->id),
            ],
        ];
    }
}