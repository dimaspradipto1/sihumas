<?php

namespace App\Mail;

use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanSubmitted extends Mailable
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
        $fromEmail = config('mail.mailers.smtp.username') ?: config('mail.from.address', 'humasuis@gmail.com');
        $fromName = 'SIHUMAS Universitas Ibnu Sina';
        $userEmail = $this->pengajuan->user?->email;
        $userName = $this->pengajuan->user?->name;

        $replyTo = [];
        if ($userEmail && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $replyTo[] = new Address($userEmail, $userName ?: $userEmail);
        }

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            replyTo: $replyTo,
            subject: '[SIHUMAS] Pengajuan Kegiatan Baru: ' . $this->pengajuan->nama_kegiatan,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pengajuan.submitted',
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
