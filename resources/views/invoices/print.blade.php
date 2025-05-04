<!-- resources/views/invoices/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .invoice-header { text-align: center; margin-bottom: 30px; }
        .invoice-details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>INVOICE</h1>
        <h2>#{{ $invoice->invoice_number }}</h2>
        <p>Date: {{ $invoice->created_at->format('d M Y') }}</p>
    </div>
    
    <div class="invoice-details">
        <p><strong>Customer:</strong> {{ $invoice->user->full_name }}</p>
        <p><strong>Shipping Address:</strong> {{ $invoice->shipping_address }}</p>
        <p><strong>Postal Code:</strong> {{ $invoice->postal_code }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->invoiceItems as $item)
            <tr>
                <td>{{ $item->good->category->name }}</td>
                <td>{{ $item->good->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp. {{ number_format($item->good->price, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4" style="text-align: right;">Total:</td>
                <td>Rp. {{ number_format($invoice->total_price, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Thank you for your purchase!</p>
    </div>
    
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()">Print Invoice</button>
        <a href="{{ route('invoices.index') }}"><button>Back to Invoices</button></a>
    </div>
</body>
</html>