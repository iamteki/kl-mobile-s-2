<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #9333EA;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .booking-details {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .button {
            display: inline-block;
            background-color: #9333EA;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: left;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Booking Confirmed!</h1>
        <p>Thank you for your booking with KL Mobile DJ & Events</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $booking->customer_name }},</p>
        
        <p>We're pleased to confirm your booking. Here are your booking details:</p>
        
        <div class="booking-details">
            <h3>Booking Information</h3>
            <div class="detail-row">
                <strong>Booking Number:</strong>
                <span>{{ $booking->booking_number }}</span>
            </div>
            <div class="detail-row">
                <strong>Event Date:</strong>
                <span>{{ $eventDate }}</span>
            </div>
            <div class="detail-row">
                <strong>Event Time:</strong>
                <span>{{ $eventTime }}</span>
            </div>
            <div class="detail-row">
                <strong>Event Type:</strong>
                <span>{{ ucfirst($booking->event_type) }}</span>
            </div>
            <div class="detail-row">
                <strong>Venue:</strong>
                <span>{{ $booking->venue }}</span>
            </div>
            <div class="detail-row">
                <strong>Number of Guests:</strong>
                <span>{{ $booking->number_of_pax }} pax</span>
            </div>
        </div>
        
        <div class="booking-details">
            <h3>Items Booked</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>LKR {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><strong>Total Amount:</strong></td>
                        <td><strong>LKR {{ number_format($booking->total, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="booking-details">
            <h3>What Happens Next?</h3>
            <ul>
                <li>Our team will contact you 24-48 hours before your event to confirm all details</li>
                <li>We'll arrive at {{ $booking->installation_time }} for setup</li>
                <li>All equipment will be ready before your event starts at {{ $booking->event_start_time }}</li>
                <li>We'll return to collect equipment at {{ $booking->dismantle_time }}</li>
            </ul>
        </div>
        
        <center>
            <a href="{{ route('account.booking.details', $booking) }}" class="button">
                View Booking Details
            </a>
        </center>
        
        <p>If you have any questions or need to make changes to your booking, please don't hesitate to contact us:</p>
        <ul>
            <li>Phone: +94 77 123 4567</li>
            <li>Email: support@klmobile.lk</li>
            <li>WhatsApp: +94 77 123 4567</li>
        </ul>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} KL Mobile DJ & Events. All rights reserved.</p>
        <p>This is an automated email. Please do not reply to this email address.</p>
    </div>
</body>
</html>