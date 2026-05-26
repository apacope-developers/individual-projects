<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .receipt {
            width: 420px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            overflow: hidden;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        /* HEADER */
        .receipt-header {
            background: linear-gradient(135deg, #22c55e, #4ade80);
            color: white;
            text-align: center;
            padding: 25px;
        }

        .success-icon {
            font-size: 40px;
        }

        /* BODY */
        .receipt-body {
            padding: 25px;
        }

        .row-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .row-item span {
            color: #555;
        }

        .divider {
            border-top: 1px dashed #ddd;
            margin: 20px 0;
        }

        .amount {
            font-size: 18px;
            font-weight: bold;
            color: #22c55e;
        }

        /* BADGES */
        .badge-ticket {
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 12px;
        }

        .vip {
            background: gold;
            color: black;
        }

        .normal {
            background: #6c757d;
            color: white;
        }

        /* QR */
        .qr-box {
            text-align: center;
            margin-top: 20px;
        }

        .qr-box div {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 12px;
            display: inline-block;
        }

        .qr-box small {
            display: block;
            margin-top: 8px;
            color: #666;
        }

        /* FOOTER */
        .receipt-footer {
            text-align: center;
            padding: 20px;
        }

        /* DOWNLOAD BUTTON */
        .btn-download {
            display: block;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
            margin-bottom: 10px;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(34,197,94,0.4);
        }

        /* BACK BUTTON */
        .btn-home {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border-radius: 10px;
            padding: 10px;
            display: block;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-home:hover {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
        }

    </style>
</head>

<body>

<div class="receipt">

    <!-- HEADER -->
    <div class="receipt-header">
        <div class="success-icon">✔</div>
        <h4>Payment Successful</h4>
        <small>Your booking is confirmed</small>
    </div>

    <!-- BODY -->
    <div class="receipt-body">

        <div class="row-item">
            <strong>Name</strong>
            <span>{{ $booking->name }}</span>
        </div>

        <div class="row-item">
            <strong>Email</strong>
            <span>{{ $booking->email }}</span>
        </div>

        <div class="row-item">
            <strong>Event</strong>
            <span>{{ $booking->event->title }}</span>
        </div>

        <div class="row-item">
            <strong>Ticket</strong>
            <span>
                <span class="badge-ticket {{ $booking->ticket_type == 'vip' ? 'vip' : 'normal' }}">
                    {{ strtoupper($booking->ticket_type) }}
                </span>
            </span>
        </div>

        <div class="row-item">
            <strong>Status</strong>
            <span class="text-success">Confirmed</span>
        </div>

        <div class="divider"></div>

        <div class="row-item">
            <strong>Total Paid</strong>
            <span class="amount">
                {{ $booking->ticket_type == 'vip' ? '15,000 RWF' : '5,000 RWF' }}
            </span>
        </div>

        <div class="divider"></div>

        <div class="row-item">
            <strong>Reference</strong>
            <span>EVT-{{ $booking->id }}{{ rand(100,999) }}</span>
        </div>

        <div class="row-item">
            <strong>Date</strong>
            <span>{{ now()->format('d M Y, H:i') }}</span>
        </div>

        <!-- QR -->
        <div class="qr-box">
            <div>
                {!! QrCode::size(140)->generate(
                    'Name: '.$booking->name.
                    ' | Event: '.$booking->event->title.
                    ' | Ticket: '.strtoupper($booking->ticket_type)
                ) !!}
            </div>
            <small>Scan at event entrance</small>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="receipt-footer">

        <!-- DOWNLOAD BUTTON -->
        <a href="{{ route('receipt.download', $booking->id) }}" class="btn-download">
            ⬇ Download Receipt (PDF)
        </a>

        <!-- BACK -->
        <a href="{{ route('events.index') }}" class="btn-home">
            ← Back to Events
        </a>

    </div>

</div>

</body>
</html>