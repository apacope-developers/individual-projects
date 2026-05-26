<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class ReceiptController extends Controller
{
    public function download($id)
    {
        // Get booking with event
        $booking = Booking::with('event')->findOrFail($id);

        // Create QR content (you can customize this)
        $qrData = "Booking ID: {$booking->id}, Name: {$booking->name}, Event: {$booking->event->title}";

        // Generate QR Code
        $qr = new QrCode($qrData);
        $writer = new PngWriter();
        $result = $writer->write($qr);

        // Convert to base64 (for PDF)
        $qrCode = base64_encode($result->getString());

        // Load PDF view
        $pdf = Pdf::loadView('receipt_pdf', [
            'booking' => $booking,
            'qrCode' => $qrCode
        ]);

        return $pdf->download('receipt_'.$booking->id.'.pdf');
    }
}