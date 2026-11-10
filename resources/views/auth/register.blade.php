<x-layouts.guest title="إنشاء حساب">
    <h1>ابدأ مع مدار</h1>
    <p>مساحة عمل واحدة لعملائك وعروضك وفواتيرك</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <x-field name="name" label="الاسم" required autofocus autocomplete="name" />
        <x-field name="job_title" label="المسمى المهني" placeholder="مثال: مصمم هويات بصرية" />
        <x-field name="email" label="البريد الإلكتروني" type="email" required autocomplete="email" />
        <x-field name="password" label="كلمة المرور" type="password" required autocomplete="new-password" />
        <x-field name="password_confirmation" label="تأكيد كلمة المرور" type="password" required autocomplete="new-password" />
        <button type="submit" class="btn pri">إنشاء الحساب</button>
    </form>

    <div class="alt">لديك حساب؟ <a href="{{ route('login') }}">سجّل الدخول</a></div>
</x-layouts.guest>
