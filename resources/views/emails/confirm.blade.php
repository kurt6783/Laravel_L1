<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>註冊確認鏈接</title>
</head>
<body>
  <h1>感谢您在 Weibo App 網站進行註冊！</h1>

  <p>
    請點擊下面的鏈接完成註冊：
    <a href="{{ route('confirm_email', $user->activation_token) }}">
      {{ route('confirm_email', $user->activation_token) }}
    </a>
  </p>

  <p>
    如果這不是您本人的操作，請忽略此郵件。
  </p>
</body>
</html>