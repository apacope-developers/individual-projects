<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Receipt</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        .header {
            background: #0f172a;
            color: white;
            padding: 20px;
        }

        .content {
            padding: 25px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #ddd;
        }

        .label {
            font-weight: bold;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
        }

        .vip {
            background: gold;
        }

        .regular {
            background: gray;
            color: white;
        }

        .amount {
            font-weight: bold;
            color: green;
        }

        .qr {
            text-align: center;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2>🎟 Event Receipt</h2>
        <small>Booking Confirmed</small>
    </div>

    <div class="content">

        <div class="row">
            <span class="label">Name</span>
            <span>{{ $booking->name }}</span>
        </div>

        <div class="row">
            <span class="label">Email</span>
            <span>{{ $booking->email }}</span>
        </div>

        <div class="row">
            <span class="label">Event</span>
            <span>{{ $booking->event->title }}</span>
        </div>

        <div class="row">
            <span class="label">Ticket</span>
            <span class="badge {{ $booking->ticket_type == 'vip' ? 'vip' : 'regular' }}">
                {{ strtoupper($booking->ticket_type) }}
            </span>
        </div>

        <div class="row">
            <span class="label">Status</span>
            <span style="color:green;">Confirmed</span>
        </div>

        <div class="row">
            <span class="label">Amount</span>
            <span class="amount">
                {{ $booking->ticket_type == 'vip' ? '15,000 RWF' : '5,000 RWF' }}
            </span>
        </div>

        <div class="row">
            <span class="label">Reference</span>
            <span>EVT-{{ $booking->id }}</span>
        </div>

        <div class="row">
            <span class="label">Date</span>
            <span>{{ now()->format('d M Y H:i') }}</span>
        </div>

        <!-- ✅ QR CODE -->
        <div class="qr">
            <img src="data:image/png;base64,{{ $qrCode }}" width="140">
            <p>Scan for verification</p>
        </div>

    </div>

    <div class="footer">
        Thank you for booking with us
    </div>

</div>

</body>
</html>