<?php

namespace App\Notifications;

use App\Models\Keluhan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KeluhanSubmitted extends Notification
{
    use Queueable;

    public function __construct(public Keluhan $keluhan)
    {
        //
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $pelangganNama = optional($this->keluhan->pelanggan)->tipe === 'personal'
            ? (optional($this->keluhan->pelanggan)->nama_lengkap ?? '-')
            : (optional($this->keluhan->pelanggan)->nama_perusahaan ?? '-');

        return [
            'title' => 'Keluhan baru masuk',
            'keluhan_id' => $this->keluhan->id_keluhan,
            'prioritas' => $this->keluhan->prioritas,
            'pelanggan' => $pelangganNama,
            'keluhan1' => $this->keluhan->keluhan1,
            'tujuan' => $this->keluhan->tujuan,
            'via' => $this->keluhan->via,
            'tanggal_input' => optional($this->keluhan->tanggal_input)?->toDateTimeString(),
            'url' => route('admin.keluhan.index'),
        ];
    }
}
