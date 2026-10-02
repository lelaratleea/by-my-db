<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<main id="app-main-content" class="app-login">
    <h1 class="app-cmp-content-header__title">Login</h1>

    <section class="app-form-section">
        <form action="{{ route('authentication') }}" method="post" class="app-form">
            @csrf
            <div class="app-form__field">
                <label for="email" class="app-form__label">E-mail</label>
                <input type="email" id="email" name="email"
                       class="app-form__input" required />
            </div>
            <div class="app-form__field">
                <label for="password" class="app-form__label">Password</label>
                <input type="password" id="password" name="password"
                       class="app-form__input" required />
            </div>
            <div class="app-form__actions">
                <button type="submit" class="app-form__submit">Login</button>
            </div>
            <div class="app-cmp-notifications">
                @error('credentials')
                    <div role="alert" class="app-cmp-notifications__error">{{ $message }}</div>
                @enderror
            </div>
        </form>
    </section>
</main>
<footer id="app-main-footer" class="app-main-footer">
    &#xA9; Copyright Lelarat's Database.
</footer>
</body>
</html>