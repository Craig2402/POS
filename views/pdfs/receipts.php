<!-- // Print receipt -->
<!DOCTYPE html>
<html>
<head>
    <title>Naivas Supermarket Receipt</title>
    <style>
        @page {
            size: A7;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 5mm;
        }
        .receipt-container {
            max-width: 100%;
        }
        .receipt-header {
            text-align: center;
        }
        .receipt-items {
            width: 100%;
            margin-bottom: 10px;
        }
        .receipt-items th, .receipt-items td {
            padding: 5px;
            text-align: left;
        }
        .receipt-total {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class='receipt-container'>
        <div class='receipt-header'>
            <h2>NAIVAS SUPERMARKET</h2>
            <p>Nairobi, Kenya - Tel: 123456789</p>
        </div>
        <hr>
        <div>
            <p><strong>Receipt No:</strong> $receiptNumber</p>
            <p><strong>Date:</strong> $date</p>
            <p><strong>Cashier:</strong> $cashierName</p>
        </div>
        <hr>
        <table class='receipt-items'>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>{$product['name']}</td>
                <td>{$product['quantity']}</td>
                <td>{$product['price']}</td>
                <td>{$product['quantity']}</td>
            </tr>
        </table>
        <hr>
        <div class='receipt-total'>
            <p><strong>Subtotal:</strong> $subTotal</p>
            <p><strong>VAT (16%):</strong> $vat</p>
            <p><strong>Total:</strong> $total</p>
        </div>
        <hr>
        <div>
            <p><strong>Payment Method:</strong> Cash</p>
            <p><strong>Change:</strong> 100</p>
        </div>
        <hr>
        <div>
            <p>Thank you for shopping at Naivas!</p>
            <p>Visit us again soon.</p>
        </div>
    </div>
</body>
</html>
