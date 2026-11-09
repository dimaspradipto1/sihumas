<?php

namespace App\Mail;

use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;

    /**
     * Create a new message instance.
     */
    public function __construct(Pengajuan $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusUpper = strtoupper($this->pengajuan->status ?? 'UPDATE');
        $kegiatan = $this->pengajuan->nama_kegiatan ?? 'Kegiatan';
        $fromEmail = config('mail.mailers.smtp.username') ?: config('mail.from.address', 'humasuis@gmail.com');
        $fromName = 'SIHUMAS Universitas Ibnu Sina';

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            replyTo: [new Address($fromEmail, $fromName)],
            subject: "[SIHUMAS] Status Pengajuan {$statusUpper}: {$kegiatan}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pengajuan_updated',
            with: [
                'pengajuan' => $this->pengajuan,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
