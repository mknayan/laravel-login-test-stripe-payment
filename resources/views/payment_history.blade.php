@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">Payment History</div>
                        <div class="col-md-6 text-right">
                            <form action="" method="get">
                            <select class="form-control" name="year" id="year">
                                <option value="2018" @if($year == 2018) selected @else @endif>2018</option>
                                <option value="2019" @if($year == 2019) selected @else @endif>2019</option>
                                <option value="2020" @if($year == 2020) selected @else @endif>2020</option>
                            </select>
                            <select class="form-control" name="month" id="month">
                                <option value="1" @if($month == 1) selected @else @endif>January</option>
                                <option value="2" @if($month == 2) selected @else @endif>February</option>
                                <option value="3" @if($month == 3) selected @else @endif>March</option>
                                <option value="4" @if($month == 4) selected @else @endif>April</option>
                                <option value="5" @if($month == 5) selected @else @endif>May</option>
                                <option value="6" @if($month == 6) selected @else @endif>June</option>
                                <option value="7" @if($month == 7) selected @else @endif>July</option>
                                <option value="8" @if($month == 8) selected @else @endif>August</option>
                                <option value="9" @if($month == 9) selected @else @endif>September</option>
                                <option value="10" @if($month == 10) selected @else @endif>October</option>
                                <option value="11" @if($month == 11) selected @else @endif>November</option>
                                <option value="12" @if($month == 12) selected @else @endif>December</option>
                            </select>
                                <button type="submit" id="filter_submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <td>Payment Method</td>
                            <td>Amount</td>
                            <td>Status</td>
                            <td>Payment Datetime</td>
                        </tr>
                        <?php $i = 1; ?>
                        @if(count($payment_history)>0)
                            @foreach($payment_history as $payment_history_single)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{ucfirst($payment_history_single->payment_gateway)}}</td>
                                    <td>${{$payment_history_single->amount}} USD</td>
                                    <td>{{ucfirst($payment_history_single->status)}}</td>
                                    <td>{{date('d M, Y h:i a', strtotime($payment_history_single->payment_datetime))}}</td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No payment data found</td>
                            </tr>
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">
    $(function() {
        var $form         = $(".require-validation");
        $('form.require-validation').bind('submit', function(e) {
            var $form         = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'].join(', '),
                $inputs       = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid         = true;
            $errorMessage.addClass('hide');

            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });

            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }

        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error')
                    .removeClass('hide')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                // insert the token into the form so it gets submitted to the server
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }

        $('.activate_account').bind('click', function(e) {
            $('.activate_account').addClass('hide');
            $('.payment_form').removeClass('hide');
        })

    });
</script>
@endsection
