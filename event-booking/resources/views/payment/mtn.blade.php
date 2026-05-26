<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTN Payment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    min-height:100vh;
    background:
        radial-gradient(circle at top left, rgba(37,99,235,0.15), transparent 30%),
        linear-gradient(135deg,#020617,#0f172a);
    font-family:'Segoe UI',sans-serif;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    overflow:hidden;
}

/* BACKGROUND GLOW */
.bg-glow{
    position:absolute;
    width:500px;
    height:500px;
    background:#2563eb;
    filter:blur(180px);
    opacity:0.12;
    border-radius:50%;
    top:-150px;
    left:-100px;
}

/* CARD */
.payment-wrapper{
    position:relative;
    z-index:2;
    width:100%;
    max-width:430px;
    padding:20px;
}

.payment-card{
    background:rgba(15,23,42,0.92);
    backdrop-filter:blur(12px);
    border:1px solid rgba(255,255,255,0.06);
    border-radius:28px;
    padding:35px;
    box-shadow:
        0 20px 60px rgba(0,0,0,0.7),
        inset 0 1px 0 rgba(255,255,255,0.04);
    animation:fadeIn .6s ease;
}

/* ANIMATION */
@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* LOGO */
.logo-box{
    width:90px;
    height:90px;
    margin:auto;
    border-radius:22px;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:20px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.4);
}

.logo-box img{
    width:70px;
    object-fit:contain;
}

/* TEXT */
.payment-title{
    font-size:28px;
    font-weight:700;
    text-align:center;
    margin-bottom:8px;
}

.payment-subtitle{
    text-align:center;
    color:#94a3b8;
    font-size:14px;
    margin-bottom:30px;
}

/* BOOKING INFO */
.booking-box{
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(255,255,255,0.05);
    border-radius:18px;
    padding:18px;
    margin-bottom:25px;
}

.booking-label{
    font-size:13px;
    color:#94a3b8;
    margin-bottom:6px;
}

.booking-name{
    font-size:18px;
    font-weight:600;
}

.ticket-badge{
    margin-top:10px;
    display:inline-block;
    background:rgba(34,197,94,0.15);
    color:#4ade80;
    padding:6px 12px;
    border-radius:30px;
    font-size:12px;
    font-weight:600;
}

/* FORM */
.form-label{
    color:#cbd5e1;
    font-size:14px;
    margin-bottom:10px;
}

.phone-group{
    display:flex;
    align-items:center;
    background:#1e293b;
    border-radius:16px;
    border:1px solid transparent;
    overflow:hidden;
    transition:0.3s;
}

.phone-group:focus-within{
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,0.15);
}

.country-code{
    padding:14px 18px;
    color:#94a3b8;
    font-weight:500;
    border-right:1px solid rgba(255,255,255,0.06);
}

.phone-input{
    flex:1;
    background:transparent !important;
    border:none !important;
    color:white !important;
    padding:14px;
    outline:none !important;
    box-shadow:none !important;
}

.phone-input::placeholder{
    color:#64748b;
}

/* PAY BUTTON */
.btn-pay{
    width:100%;
    margin-top:25px;
    border:none;
    border-radius:16px;
    padding:15px;
    font-size:16px;
    font-weight:700;
    background:linear-gradient(135deg,#22c55e,#4ade80);
    color:#052e16;
    transition:0.3s;
    position:relative;
    overflow:hidden;
}

.btn-pay:hover{
    transform:translateY(-3px);
    box-shadow:0 15px 35px rgba(34,197,94,0.35);
}

.btn-pay.loading{
    pointer-events:none;
    opacity:0.8;
}

/* SECURITY */
.security-box{
    margin-top:22px;
    text-align:center;
    color:#64748b;
    font-size:13px;
}

/* BACK */
.back-link{
    display:inline-flex;
    align-items:center;
    gap:8px;
    margin-top:25px;
    color:#94a3b8;
    text-decoration:none;
    transition:0.3s;
}

.back-link:hover{
    color:white;
}

/* RESPONSIVE */
@media(max-width:500px){

    .payment-card{
        padding:25px;
        border-radius:22px;
    }

    .payment-title{
        font-size:24px;
    }

}

    </style>
</head>

<body>

<div class="bg-glow"></div>

<div class="payment-wrapper">

    <div class="payment-card">

        <!-- LOGO -->
        <div class="logo-box">
            <img src="{{ asset('images/mtn.png') }}" alt="MTN">
        </div>

        <!-- TITLE -->
        <div class="payment-title">
            MTN MoMo Payment
        </div>

        <div class="payment-subtitle">
            Fast • Secure • Trusted Mobile Payment
        </div>

        <!-- BOOKING INFO -->
        <div class="booking-box">

            <div class="booking-label">
                Booking for
            </div>

            <div class="booking-name">
                {{ $booking->name }}
            </div>

            <div class="ticket-badge">
                {{ strtoupper($booking->ticket_type) }} TICKET
            </div>

        </div>

        <!-- FORM -->
        <form method="POST" 
              action="{{ route('payment.confirm', $booking->id) }}"
              id="paymentForm">

            @csrf

            <label class="form-label">
                MTN Mobile Number
            </label>

            <div class="phone-group">

                <div class="country-code">
                    +250
                </div>

                <input
                    type="tel"
                    name="phone"
                    class="phone-input"
                    placeholder="78XXXXXXX"
                    pattern="7[0-9]{8}"
                    required
                >

            </div>

            <button type="submit" class="btn-pay" id="payBtn">
                Pay Now →
            </button>

        </form>

        <!-- SECURITY -->
        <div class="security-box">
            🔒 Secured by MTN Mobile Money
        </div>

        <!-- BACK -->
        <a href="{{ route('events.index') }}" class="back-link">
            ← Back to Events
        </a>

    </div>

</div>

<script>

document.getElementById('paymentForm').addEventListener('submit', function(){

    const btn = document.getElementById('payBtn');

    btn.classList.add('loading');

    btn.innerHTML = 'Processing Payment...';

});

</script>

</body>
</html>