<x-layouts.app title="الإعدادات">
    <x-page-header title="الإعدادات" subtitle="بياناتك تظهر في العروض والفواتير وصفحة الروابط" />

    <div class="settings">
        <x-form-errors />
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:16px">
            @csrf
            @method('PUT')
            <div class="panel">
                <h2>شعارك</h2>
                <p style="margin:0 0 12px;color:var(--muted);font-size:14px">يظهر أعلى الفواتير وعروض الأسعار. الأفضل صورة PNG بخلفية شفافة.</p>
                <x-logo-picker label="الشعار" :path="$user->logo_path" />
            </div>
            <div class="panel">
                <h2>ملفك</h2>
                <div class="form">
                    <x-field name="name" label="الاسم" :value="$user->name" required />
                    <x-field name="job_title" label="المسمى المهني" :value="$user->job_title" />
                    <x-field name="business_name" label="اسم النشاط" :value="$user->business_name" full />
                    <x-field name="email" label="البريد" type="email" :value="$user->email" required />
                    <x-field name="phone" label="الجوال" type="tel" :value="$user->phone" />
                    <x-field name="vat_number" label="الرقم الضريبي" :value="$user->vat_number" />
                    <x-field name="iban" label="رقم الآيبان" :value="$user->iban" dir="ltr" />
                    <x-field name="handle" label="اسم المستخدم لصفحة الروابط" :value="$user->handle" dir="ltr" placeholder="ahmad" />
                    <x-field name="bio" label="نبذة قصيرة" type="textarea" :value="$user->bio" full />
                </div>
                <div style="margin-top:14px"><button type="submit" class="btn pri">حفظ التغييرات</button></div>
            </div>
        </form>

        <div class="panel">
            <h2>البيانات</h2>
            <p style="margin:0 0 12px;color:var(--muted)">بياناتك محفوظة في حسابك وخاصة بك وحدك، لا يراها غيرك.</p>
            <form method="POST" action="{{ route('workspace.destroy') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="confirm" value="1">
                <button type="submit" class="btn danger" data-label="حذف كل العملاء والسجلات" data-confirm="اضغط مجددًا لتأكيد حذف كل السجلات">حذف كل العملاء والسجلات</button>
            </form>
        </div>
    </div>
</x-layouts.app>
