<?php

namespace App\Observers;

use App\Models\PengajuanMagangModel;

class PengajuanMagangObserver
{
    /**
     * Handle the PengajuanMagangModel "updated" event.
     */
    public function updated(PengajuanMagangModel $pengajuan)
    {
        // Ambil dosen terkait pengajuan
        $dosen = $pengajuan->dosen;
        if (!$dosen) {
            return; // Jika tidak ada dosen, keluar dari fungsi
        }

        // Definisikan status yang dianggap aktif
        $statusAktif = ['diterima', 'ongoing'];
        $statusSebelumnya = $pengajuan->getOriginal('status'); // Status sebelum diperbarui
        $statusSekarang = $pengajuan->status; // Status setelah diperbarui

        // Jika status berubah dari non-aktif ke aktif (diterima/ongoing)
        if (!in_array($statusSebelumnya, $statusAktif) && in_array($statusSekarang, $statusAktif)) {
            $dosen->increment('jumlah_bimbingan'); // Tambah 1 ke jumlah_bimbingan
        }
        // Jika status berubah dari aktif ke non-aktif
        elseif (in_array($statusSebelumnya, $statusAktif) && !in_array($statusSekarang, $statusAktif)) {
            $dosen->decrement('jumlah_bimbingan'); // Kurangi 1 dari jumlah_bimbingan
        }
    }

    /**
     * Handle the PengajuanMagangModel "deleted" event.
     */
    public function deleted(PengajuanMagangModel $pengajuan)
    {
        $dosen = $pengajuan->dosen;
        if ($dosen && in_array($pengajuan->status, ['diterima', 'ongoing'])) {
            $dosen->decrement('jumlah_bimbingan'); // Kurangi 1 jika pengajuan dihapus dan status aktif
        }
    }
}