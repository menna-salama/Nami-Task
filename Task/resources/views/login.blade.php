<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
  <style>
    body {
      background: #f1f3f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    main {
      width: 400px;
    }
    form {
      background: #fff;
      padding: 1.2rem;  
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 1rem;
      color: #333;
      font-size: 1.2rem;
    }
    input {
      font-size: 0.9rem;
      padding: 0.5rem;
    }
    button {
      width: 100%;
      padding: 0.5rem;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <main>
    <h2>Admin Login</h2>
    @if($errors->any())
      <article role="alert">{{ $errors->first() }}</article>
    @endif
    <form method="POST" action="{{ route('login.submit') }}">
      @csrf
      <label>Email
        <input type="email" name="email" value="{{ old('email') }}" required>
      </label>
      <label>Password
        <input type="password" name="password" required>
      </label>
      <button type="submit">Login</button>
    </form>
  </main>
</body>
</html>
