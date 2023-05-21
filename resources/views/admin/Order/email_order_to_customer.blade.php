<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đơn Hàng Của Bạn !</title>
</head>
<body>
   Thế Giới Hải Sản Xin Chào {{  $order->shipping->shipping_name }}! <br>
   {{ $type }} <br>
   Xin Cảm Ơn !
</body>
</html>