<input type="hidden" name="order_id" value="{{$id}}">
<table class="table table-striped">
    <thead>
        <tr>
            <td class="cell">Sr. No</td>
            <td class="cell">Amount</td>
            <td class="cell">Date</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $key => $payment)
            <tr>
                <td class="cell">{{$key+1}}</td>
                <td class="cell">{{$payment->amount}}</td>
                <td class="cell">{{$payment->created_at}}</td>
            </tr>
        @endforeach
    </tbody>
</table>