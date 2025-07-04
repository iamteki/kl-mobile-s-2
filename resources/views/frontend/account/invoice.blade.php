<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - {{ $booking->booking_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        
        .invoice-container {
            padding: 20px;
        }
        
        /* Header */
        .invoice-header {
            margin-bottom: 30px;
            border-bottom: 2px solid #9333EA;
            padding-bottom: 20px;
        }
        
        .company-details {
            float: left;
            width: 50%;
        }
        
        .invoice-details {
            float: right;
            width: 50%;
            text-align: right;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #9333EA;
            margin-bottom: 5px;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .invoice-number {
            font-size: 14px;
            color: #666;
        }
        
        .invoice-date {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        /* Clear float */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        /* Customer Details */
        .customer-section {
            margin: 30px 0;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #9333EA;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .customer-info {
            line-height: 1.8;
        }
        
        /* Event Details */
        .event-section {
            margin: 30px 0;
        }
        
        .event-details {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        
        .event-row {
            display: table-row;
        }
        
        .event-label {
            display: table-cell;
            width: 30%;
            padding: 5px 0;
            font-weight: bold;
            color: #666;
        }
        
        .event-value {
            display: table-cell;
            width: 70%;
            padding: 5px 0;
        }
        
        /* Items Table */
        .items-section {
            margin: 30px 0;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .items-table th {
            background-color: #9333EA;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Totals */
        .totals-section {
            float: right;
            width: 40%;
            margin-top: 20px;
        }
        
        .total-row {
            display: table;
            width: 100%;
            padding: 5px 0;
        }
        
        .total-label {
            display: table-cell;
            width: 60%;
            text-align: right;
            padding-right: 10px;
        }
        
        .total-value {
            display: table-cell;
            width: 40%;
            text-align: right;
            font-weight: bold;
        }
        
        .total-row.final {
            border-top: 2px solid #9333EA;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .total-row.final .total-label,
        .total-row.final .total-value {
            font-size: 16px;
            color: #9333EA;
        }
        
        /* Footer */
        .invoice-footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .payment-info {
            background-color: #f0f0f0;
            padding: 15px;
            margin: 30px 0;
            border-radius: 5px;
        }
        
        .payment-info h4 {
            color: #9333EA;
            margin-bottom: 10px;
        }
        
        /* Notes Section */
        .notes-section {
            margin: 30px 0;
            padding: 15px;
            background-color: #fff9e6;
            border-left: 4px solid #f59e0b;
            border-radius: 5px;
        }
        
        .notes-section h4 {
            color: #f59e0b;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header clearfix">
            <div class="company-details">
                <div class="company-name">KL Mobile DJ & Events</div>
                <div>Professional Event Equipment & Services</div>
                <div>123 Main Street, Colombo</div>
                <div>Phone: +94 77 123 4567</div>
                <div>Email: info@klmobile.lk</div>
            </div>
            <div class="invoice-details">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">#{{ $booking->booking_number }}</div>
                <div class="invoice-date">Date: {{ $booking->created_at->format('d M Y') }}</div>
                <div class="invoice-date">Event Date: {{ $booking->event_date->format('d M Y') }}</div>
            </div>
        </div>
        
        <!-- Customer Details -->
        <div class="customer-section">
            <div class="section-title">Bill To</div>
            <div class="customer-info">
                <strong>{{ $booking->customer_name }}</strong><br>
                @if($booking->customer_company)
                    {{ $booking->customer_company }}<br>
                @endif
                {{ $booking->customer_email }}<br>
                {{ $booking->customer_phone }}<br>
                {{ $booking->delivery_address }}
            </div>
        </div>
        
        <!-- Event Details -->
        <div class="event-section">
            <div class="section-title">Event Information</div>
            <div class="event-details">
                <div class="event-row">
                    <div class="event-label">Event Type:</div>
                    <div class="event-value">{{ ucfirst($booking->event_type) }}</div>
                </div>
                <div class="event-row">
                    <div class="event-label">Venue:</div>
                    <div class="event-value">{{ $booking->venue }}</div>
                </div>
                <div class="event-row">
                    <div class="event-label">Number of Guests:</div>
                    <div class="event-value">{{ $booking->number_of_pax }} pax</div>
                </div>
                <div class="event-row">
                    <div class="event-label">Event Time:</div>
                    <div class="event-value">{{ $booking->event_start_time }} - {{ $booking->dismantle_time }}</div>
                </div>
                <div class="event-row">
                    <div class="event-label">Setup Time:</div>
                    <div class="event-value">{{ $booking->installation_time }}</div>
                </div>
            </div>
        </div>
        
        <!-- Items -->
        <div class="items-section">
            <div class="section-title">Items</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 40%">Description</th>
                        <th style="width: 15%" class="text-center">Quantity</th>
                        <th style="width: 15%" class="text-center">Days</th>
                        <th style="width: 15%" class="text-right">Unit Price</th>
                        <th style="width: 15%" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->item_name }}</strong>
                                @if($item->variation_name)
                                    <br><small>{{ $item->variation_name }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-center">{{ $item->rental_days ?? 1 }}</td>
                            <td class="text-right">LKR {{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">LKR {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Totals -->
        <div class="clearfix">
            <div class="totals-section">
                <div class="total-row">
                    <div class="total-label">Subtotal:</div>
                    <div class="total-value">LKR {{ number_format($booking->subtotal, 2) }}</div>
                </div>
                @if($booking->discount_amount > 0)
                    <div class="total-row">
                        <div class="total-label">Discount:</div>
                        <div class="total-value">-LKR {{ number_format($booking->discount_amount, 2) }}</div>
                    </div>
                @endif
                <div class="total-row">
                    <div class="total-label">Delivery Charge:</div>
                    <div class="total-value">LKR {{ number_format($booking->delivery_charge, 2) }}</div>
                </div>
                @if($booking->tax_amount > 0)
                    <div class="total-row">
                        <div class="total-label">Tax:</div>
                        <div class="total-value">LKR {{ number_format($booking->tax_amount, 2) }}</div>
                    </div>
                @endif
                <div class="total-row final">
                    <div class="total-label">Total Amount:</div>
                    <div class="total-value">LKR {{ number_format($booking->total, 2) }}</div>
                </div>
            </div>
        </div>
        
        <!-- Payment Information -->
        <div class="payment-info clearfix">
            <h4>Payment Information</h4>
            <div>Payment Status: <strong>{{ ucfirst($booking->payment_status) }}</strong></div>
            <div>Payment Method: <strong>{{ ucfirst($booking->payment_method) }}</strong></div>
            @if($booking->paid_at)
                <div>Paid On: <strong>{{ $booking->paid_at->format('d M Y, h:i A') }}</strong></div>
            @endif
        </div>
        
        @if($booking->special_requests)
            <div class="notes-section">
                <h4>Special Requests</h4>
                <p>{{ $booking->special_requests }}</p>
            </div>
        @endif
        
        <!-- Footer -->
        <div class="invoice-footer">
            <p><strong>Thank you for your business!</strong></p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>For any queries, please contact us at support@klmobile.lk or +94 77 123 4567</p>
            <p style="margin-top: 10px;">© {{ date('Y') }} KL Mobile DJ & Events. All rights reserved.</p>
        </div>
    </div>
</body>
</html>