<!DOCTYPE html>
<html>
<head>
    <title>Bank Payment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
body {
    margin: 0;
    min-height: 100vh;
    background: #0f172a;
    font-family: 'Segoe UI', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

/* CARD */
.payment-card {
    width: 100%;
    max-width: 400px;
    background: #111827;
    border-radius: 16px;
    padding: 30px;
    border: 1px solid rgba(255,255,255,0.05);
}

/* LOGO BOX */
.logo-box {
    width: 70px;
    height: 70px;
    margin: 0 auto 10px;
    background: #1f2937;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo {
    max-width: 45px;
    max-height: 45px;
    object-fit: contain;
}

/* TEXT */
.title {
    font-size: 20px;
    font-weight: 600;
}

.subtitle {
    font-size: 13px;
    color: #9ca3af;
    margin-bottom: 20px;
}

/* BOOKING */
.booking-box {
    background: #1f2937;
    border-radius: 10px;
    padding: 12px;
    font-size: 14px;
    margin-bottom: 20px;
}

/* INPUT */
.form-label {
    font-size: 13px;
    color: #9ca3af;
}

input {
    background: #1f2937 !important;
    border: none !important;
    border-radius: 10px !important;
    color: white !important;
}

input::placeholder {
    color: #6b7280;
}

/* 🔵 Bank accent */
input:focus {
    outline: none;
    box-shadow: 0 0 0 2px #3b82f6;
}

/* BUTTON */
.btn-pay {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    background: #3b82f6;
    color: white;
    font-weight: 600;
    transition: 0.2s;
}

.btn-pay:hover {
    background: #2563eb;
}

/* FOOTER */
.footer-text {
    font-size: 12px;
    color: #6b7280;
    text-align: center;
    margin-top: 15px;
}
    </style>
</head>

<body>

<div class="payment-card text-center">

    <!-- LOGO -->
    <div class="logo-box">
        <img src="/images/bk.png" class="logo" alt="Bank of Kigali">
    </div>

    <div class="title">Bank Payment</div>
    <div class="subtitle">Secure bank transfer</div>

    <!-- BOOKING -->
    <div class="booking-box">
        Booking for <strong>{{ $booking->name }}</strong>
    </div>

    <!-- FORM -->
    <form method="POST" action="{{ route('payment.confirm', $booking->id) }}">
        @csrf

        <div class="mb-3 text-start">
            <label class="form-label">Account Number</label>
            <input 
                type="text" 
                name="account_number"
                class="form-control"
                placeholder="Enter account number"
                pattern="[0-9]{10,16}"
                required
            >
        </div>

        <div class="mb-3 text-start">
            <label class="form-label">Account Name</label>
            <input 
                type="text" 
                name="account_name"
                class="form-control"
                placeholder="Account holder name"
                required
            >
        </div>

        <button class="btn-pay">
            Pay via Bank
        </button>
    </form>

    <div class="footer-text">
        Powered by Bank Transfer
    </div>

</div>

</body>
</html>