<h1>Cashout Confirmation</h1>

<p>Hi {{ $cashout->vendor->business_name }},</p>

<p>Your Request #{{ $cashout->id }} has been confirmed!</p>

<p><strong>Total: ${{ $cashout->amount }}</strong></p>


<p>Thank you!</p>
