<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermohonanSubmitted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $permohonan,
        public $pdfPath
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bukti Pengajuan Permohonan - ' . $this->permohonan->no_tiket)
            ->line('Permohonan Anda telah berhasil diajukan.')
            ->line('No Tiket: ' . $this->permohonan->no_tiket)
            ->line('Layanan: ' . $this->permohonan->layanan->nama_layanan)
            ->line('Tanggal Pengajuan: ' . $this->permohonan->tanggal_pengajuan->format('d-m-Y'))
            ->attach(storage_path('app/public/' . $this->pdfPath), [
                'as' => 'bukti_pengajuan_' . $this->permohonan->no_tiket . '.pdf',
                'mime' => 'application/pdf',
            ])
            ->line('Bukti pengajuan telah dilampirkan dalam email ini.')
            ->action('Lihat Permohonan', route('pemohon.permohonan.index'))
            ->line('Terima kasih telah menggunakan layanan kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
