<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductReceiptExport;

class SendOrderMail extends Mailable
{
    public $data;
    private $excelFile;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $excelFile)
    {
        $this->data = $data;
        $this->excelFile = $excelFile;  // Lưu file Excel vào một biến riêng
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thông tin phiếu đặt hàng từ ' . $this->data['system']['homepage_company'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.sendOrder',
            with: [
                'formattedDetails' => $this->data['formattedDetails'],
                'productReceipt' => $this->data['productReceipt'],
                'system' => $this->data['system']
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $this->excelFile, 'phieu_dat_hang.xlsx')
                ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ];
    }
}
