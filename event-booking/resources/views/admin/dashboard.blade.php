@extends('admin.layout')

@section('content')

<h2 class="mb-4">📊 Bookings Overview</h2>

<div class="card p-4 text-dark rounded-4">

<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>👤 Name</th>
            <th>📧 Email</th>
            <th>🎟 Ticket</th>
            <th>💰 Amount</th>
            <th>📍 Event</th>
            <th>📊 Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach($bookings as $booking)
        <tr>
            <td>{{ $booking->name }}</td>
            <td>{{ $booking->email }}</td>

            <td>
                <span class="badge bg-{{ $booking->ticket_type == 'vip' ? 'warning' : 'secondary' }}">
                    {{ strtoupper($booking->ticket_type) }}
                </span>
            </td>

            <td>
                {{ $booking->ticket_type == 'vip' ? '15,000 RWF' : '5,000 RWF' }}
            </td>

            <td>{{ $booking->event->title ?? 'N/A' }}</td>

            <td>
                <span class="badge bg-success">Confirmed</span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>

@endsection