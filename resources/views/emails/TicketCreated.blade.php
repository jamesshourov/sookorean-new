@component('mail::message')
    <strong>Hello,</strong> <br>
    <p>
        We have received your request. Our customer service executive will get back to you on this. Your request number is : <strong>{{ $mailData['ticket_number'] }}</strong>
    </p>
@endcomponent
