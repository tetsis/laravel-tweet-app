<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="x-UA-Compatible" content="ie=edge">
        <title>メール送信テスト</title>
</head>
<body>
    <h1>メール送信テスト</h1>
    @if (session('feedback.success'))
        <p style="color: green">{{ session('feedback.success') }}</p>
    @endif
    <div>
        <form action="{{ route('email.send') }}" method="post">
            @csrf
            <label for="email">宛先</label>
            <input id="email" type="text" name="email" placeholder="メールアドレスを入力"></input>
            @error('email')
            <p style="color: red;">{{ $message }}</p>
            @enderror
            <button type="submit">送信</button>
        </form>
    </div>
</body>
</html>
