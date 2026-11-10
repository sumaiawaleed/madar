<x-layouts.guest title="تسجيل الدخول">
    <h1>أهلًا بعودتك</h1>
    <p>سجّل الدخول لمساحة عملك</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <x-field name="email" label="البريد الإلكتروني" type="email" required autofocus autocomplete="email" />
        <x-field name="password" label="كلمة المرور" type="password" required autocomplete="current-password" />
        <label class="check" style="font-size:14px"><input type="checkbox" name="remember" value="1"> تذكرني</label>
        <button type="submit" class="btn pri">تسجيل الدخول</button>
    </form>

    <div class="alt">ليس لديك حساب؟ <a href="{{ route('register') }}">أنشئ حسابًا مجانيًا</a></div>
</x-layouts.guest>
