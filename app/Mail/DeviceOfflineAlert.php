<?php

namespace App\Mail;

use App\Models\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeviceOfflineAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Device $device,
        public ?string $lastSeenAt = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Device Offline Alert: ' . $this->device->name . ' - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.device-offline',
            with: [
                'deviceName'   => $this->device->name,
                'lastSeenAt'   => $this->lastSeenAt ?? 'Unknown',
                'devicesUrl'   => url('/organization/devices'),
                'appName'      => config('app.name', 'Dayaa'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
