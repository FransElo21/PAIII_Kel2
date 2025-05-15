<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-XNo7nj1ne98oOSb5"></script>
</head>
<body>
    <a id="pay-button"  class="btn btn-dark btn-lg card-footer-btn justify-content-center text-uppercase-bold-sm hover-lift-light">
                <span class="svg-icon text-white me-2">
                  {{-- <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512"><title>ionicons-v5-g</title><path d="M336,208V113a80,80,0,0,0-160,0v95" style="fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></path><rect x="96" y="208" width="320" height="272" rx="48" ry="48" style="fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></rect></svg> --}}
                </span>
                BAYAR
              </a>

              <script type="text/javascript">
              var orderId = '{{$order->id}}'; // Ambil ID pesanan dari data yang ada di view
              
              // Kirim request Ajax ke endpoint backend untuk validasi stok

                          // Stok valid, lanjutkan ke pembayaran
                          var snapToken = $('#snapToken').val(); // Get Snap token from hidden input                       

                          // Trigger Snap popup
                          $('#pay-button').click(function(){
                            snap.pay(snapToken, {
                            onSuccess: function (result) {
                                // Payment success handling
                                window.location.href = 'tiket/{{$order->id}}';
                                console.log(result);
                            },
                            onPending: function (result) {
                                // Payment pending handling
                                alert("Waiting for payment!");
                                console.log(result);
                            },
                            onError: function (result) {
                                // Payment error handling
                                alert("Payment failed!");
                                console.log(result);
                            },
                            onClose: function () {
                                // Popup closed handling
                                alert('You closed the popup without finishing the payment');
                            }
                        });

                        });  
  </script>
</body>
</html>