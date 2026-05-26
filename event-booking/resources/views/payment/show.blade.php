<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:
                radial-gradient(circle at top right, rgba(37,99,235,0.25), transparent 30%),
                linear-gradient(135deg,#020617,#071132,#020617);
            min-height:100vh;
            color:white;
            font-family:'Segoe UI',sans-serif;
        }

        /* PAGE */
        .payment-page{
            padding:50px 30px;
        }

        .top-bar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:40px;
            flex-wrap:wrap;
            gap:15px;
        }

        .page-title{
            font-size:42px;
            font-weight:800;
            margin:0;
        }

        .page-subtitle{
            color:#94a3b8;
            margin-top:8px;
        }

        .back-btn{
            text-decoration:none;
            color:white;
            background:rgba(255,255,255,0.08);
            border:1px solid rgba(255,255,255,0.08);
            padding:12px 18px;
            border-radius:14px;
            transition:0.3s;
        }

        .back-btn:hover{
            background:#2563eb;
            color:white;
            transform:translateY(-2px);
        }

        /* GLASS CARD */
        .glass-card{
            background:rgba(15,23,42,0.75);
            backdrop-filter:blur(12px);
            border:1px solid rgba(255,255,255,0.06);
            border-radius:28px;
            padding:30px;
            box-shadow:0 20px 50px rgba(0,0,0,0.45);
        }

        .section-title{
            font-size:28px;
            font-weight:700;
            margin-bottom:25px;
        }

        /* BOOKING INFO */
        .info-box{
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.05);
            border-radius:20px;
            padding:20px;
            margin-bottom:18px;
        }

        .info-label{
            color:#94a3b8;
            font-size:13px;
            margin-bottom:5px;
        }

        .info-value{
            font-size:18px;
            font-weight:600;
        }

        .ticket-badge{
            display:inline-block;
            margin-top:10px;
            background:rgba(59,130,246,0.15);
            color:#60a5fa;
            padding:8px 14px;
            border-radius:30px;
            font-size:14px;
            font-weight:600;
        }

        /* PAYMENT METHODS */
        .payment-option{
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.08);
            border-radius:22px;
            padding:20px;
            margin-bottom:18px;
            cursor:pointer;
            transition:0.35s;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
        }

        .payment-option:hover{
            transform:translateY(-4px);
            border-color:#3b82f6;
            background:rgba(37,99,235,0.12);
            box-shadow:0 10px 30px rgba(37,99,235,0.2);
        }

        .payment-option.active{
            border:2px solid #22c55e;
            background:rgba(34,197,94,0.12);
            box-shadow:0 0 25px rgba(34,197,94,0.25);
        }

        .payment-option input{
            display:none;
        }

        .method-left{
            display:flex;
            align-items:center;
            gap:18px;
        }

        .method-logo{
            width:60px;
            height:60px;
            background:white;
            border-radius:16px;
            padding:10px;
            object-fit:contain;
        }

        .method-name{
            font-size:20px;
            font-weight:700;
        }

        .method-desc{
            color:#94a3b8;
            font-size:14px;
            margin-top:4px;
        }

        .check-icon{
            width:28px;
            height:28px;
            border-radius:50%;
            background:rgba(255,255,255,0.08);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:14px;
        }

        .payment-option.active .check-icon{
            background:#22c55e;
            color:#02140a;
            font-weight:bold;
        }

        /* TOTAL CARD */
        .total-box{
            margin-top:25px;
            background:linear-gradient(135deg,#1d4ed8,#2563eb);
            border-radius:24px;
            padding:25px;
        }

        .total-label{
            opacity:0.8;
            margin-bottom:5px;
        }

        .total-price{
            font-size:42px;
            font-weight:800;
        }

        /* BUTTON */
        .btn-pay{
            width:100%;
            margin-top:25px;
            border:none;
            border-radius:18px;
            padding:18px;
            font-size:18px;
            font-weight:700;
            color:#04110a;
            background:linear-gradient(135deg,#22c55e,#4ade80);
            transition:0.35s;
        }

        .btn-pay:hover{
            transform:translateY(-3px);
            box-shadow:0 15px 35px rgba(34,197,94,0.4);
        }

        .btn-pay.loading{
            opacity:0.7;
            pointer-events:none;
        }

        /* SECURE */
        .secure-note{
            margin-top:18px;
            text-align:center;
            color:#94a3b8;
            font-size:14px;
        }

        /* MOBILE */
        @media(max-width:768px){

            .page-title{
                font-size:30px;
            }

            .glass-card{
                padding:22px;
            }

            .method-name{
                font-size:17px;
            }

            .payment-option{
                padding:16px;
            }

            .method-logo{
                width:52px;
                height:52px;
            }

            .total-price{
                font-size:32px;
            }
        }
    </style>
</head>
<body>

<div class="container payment-page">

    <!-- TOP -->
    <div class="top-bar">

        <div>
            <h1 class="page-title">💳 Complete Payment</h1>
            <p class="page-subtitle">
                Secure your booking by choosing your preferred payment method
            </p>
        </div>

        <a href="{{ route('events.index') }}" class="back-btn">
            ← Back to Events
        </a>

    </div>

    <div class="row g-4">

        <!-- LEFT SIDE -->
        <div class="col-lg-4">

            <div class="glass-card">

                <div class="section-title">
                    Booking Summary
                </div>

                <div class="info-box">
                    <div class="info-label">Customer Name</div>
                    <div class="info-value">{{ $booking->name }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $booking->email }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Ticket Type</div>
                    <div class="info-value">
                        {{ ucfirst($booking->ticket_type) }}
                    </div>

                    <div class="ticket-badge">
                        @if($booking->ticket_type == 'vip')
                            VIP EXPERIENCE
                        @else
                            REGULAR ACCESS
                        @endif
                    </div>
                </div>

                <!-- TOTAL -->
                <div class="total-box">

                    <div class="total-label">
                        Total Amount
                    </div>

                    <div class="total-price">
                        @if($booking->ticket_type == 'vip')
                            15,000 RWF
                        @else
                            5,000 RWF
                        @endif
                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="col-lg-8">

            <div class="glass-card">

                <div class="section-title">
                    Choose Payment Method
                </div>

                <form method="POST"
                      action="{{ route('payment.process') }}"
                      id="paymentForm">

                    @csrf

                    <input type="hidden"
                           name="booking_id"
                           value="{{ $booking->id }}">

                    <!-- MTN -->
                    <label class="payment-option">

                        <input type="radio"
                               name="payment_method"
                               value="mtn"
                               required>

                        <div class="method-left">

                            <img src="{{ asset('images/mtn.png') }}"
                                 class="method-logo">

                            <div>
                                <div class="method-name">
                                    MTN Mobile Money
                                </div>

                                <div class="method-desc">
                                    Fast and secure mobile payment
                                </div>
                            </div>

                        </div>

                        <div class="check-icon">
                            ✓
                        </div>

                    </label>

                    <!-- AIRTEL -->
                    <label class="payment-option">

                        <input type="radio"
                               name="payment_method"
                               value="airtel">

                        <div class="method-left">

                            <img src="{{ asset('images/airtel.png') }}"
                                 class="method-logo">

                            <div>
                                <div class="method-name">
                                    Airtel Money
                                </div>

                                <div class="method-desc">
                                    Instant mobile transaction
                                </div>
                            </div>

                        </div>

                        <div class="check-icon">
                            ✓
                        </div>

                    </label>

                    <!-- BK -->
                    <label class="payment-option">

                        <input type="radio"
                               name="payment_method"
                               value="bank">

                        <div class="method-left">

                            <img src="{{ asset('images/bk.png') }}"
                                 class="method-logo">

                            <div>
                                <div class="method-name">
                                    Bank of Kigali
                                </div>

                                <div class="method-desc">
                                    Direct secure bank payment
                                </div>
                            </div>

                        </div>

                        <div class="check-icon">
                            ✓
                        </div>

                    </label>

                    <!-- PAY BUTTON -->
                    <button type="submit"
                            class="btn-pay"
                            id="payBtn">

                        Complete Payment →
                    </button>

                    <div class="secure-note">
                        🔒 Your payment information is encrypted and secure
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<script>

    // ACTIVE PAYMENT METHOD
    document.querySelectorAll('.payment-option').forEach(option => {

        option.addEventListener('click', () => {

            document.querySelectorAll('.payment-option')
                .forEach(o => o.classList.remove('active'));

            option.classList.add('active');

            option.querySelector('input').checked = true;

        });

    });

    // LOADING STATE
    document.getElementById('paymentForm')
        .addEventListener('submit', function () {

            const btn = document.getElementById('payBtn');

            btn.classList.add('loading');

            btn.innerHTML = 'Processing Payment...';

        });

</script>

</body>
</html>