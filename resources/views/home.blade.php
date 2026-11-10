<x-layouts.base title="الرئيسية">
    <header class="site-head">
        <div class="in">
            <x-brand :tagline="null" />
            <nav aria-label="روابط الموقع">
                <a href="#services" class="hide-sm">الخدمات</a>
                <a href="#how" class="hide-sm">كيف يعمل</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn pri">لوحة التحكم</a>
                @else
                    <a href="{{ route('login') }}">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="btn pri">ابدأ مجانًا</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="wrap" style="padding:0 16px">
        <section class="hero">
            <div>
                <span class="eyebrow">مساحة عمل المستقل السعودي</span>
                <h1>أدِر عملك الحر <em>من مكان واحد</em></h1>
                <p class="lead">مدار يجمع عملاءك وعروض الأسعار والمشاريع والفواتير الضريبية ومواعيدك في لوحة واحدة بسيطة، لتتفرغ لعملك بدل الجداول المتفرقة.</p>
                <div class="cta">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn pri">افتح لوحة التحكم</a>
                    @else
                        <a href="{{ route('register') }}" class="btn pri">أنشئ حسابك مجانًا</a>
                        <a href="{{ route('login') }}" class="btn">لدي حساب</a>
                    @endauth
                </div>
            </div>
            <div class="hero-shot" aria-hidden="true">
                <div class="kpis">
                    <div class="kpi"><span>أرباح هذا الشهر</span><strong><x-money :amount="12450" /></strong><small>من الفواتير المدفوعة</small></div>
                    <div class="kpi"><span>مبالغ مستحقة</span><strong><x-money :amount="8337.5" /></strong><small>فاتورتان · <span style="color:var(--red)">1 متأخرة</span></small></div>
                </div>
                <div class="panel">
                    <h2>المشاريع الجارية</h2>
                    <div class="list">
                        <div class="row"><div class="grow"><div class="t">هوية بصرية لمقهى</div><div class="bar" style="margin-top:6px"><i style="width:85%"></i></div></div><x-pill label="مراجعة العميل" tone="p-amber" /></div>
                        <div class="row"><div class="grow"><div class="t">إعادة تصميم متجر إلكتروني</div><div class="bar" style="margin-top:6px"><i style="width:40%"></i></div></div><x-pill label="قيد التنفيذ" tone="p-blue" /></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="services">
            <h2 class="title">كل ما يحتاجه المستقل</h2>
            <p class="sub">ثماني أدوات مترابطة، كل واحدة تغذي الأخرى: العرض المقبول يصبح مشروعًا، والمشروع يصبح فاتورة، والفاتورة المدفوعة تظهر في أرباحك.</p>
            <div class="services">
                <div class="service"><div class="ic"><x-icon name="clients" size="22" /></div><h3>إدارة العملاء</h3><p>ملف لكل عميل بشعاره وبيانات التواصل، مع عدد مشاريعه وإجمالي ما دفعه لك.</p></div>
                <div class="service"><div class="ic"><x-icon name="proposals" size="22" /></div><h3>عروض الأسعار</h3><p>اكتب عرضًا مفصّلًا بالبنود والشروط، وتابع حالته من مسودة إلى مقبول، ثم حوّله إلى مشروع بضغطة.</p></div>
                <div class="service"><div class="ic"><x-icon name="projects" size="22" /></div><h3>لوحة المشاريع</h3><p>تابع كل مشروع على لوحة مراحل: جديد، قيد التنفيذ، مراجعة العميل، مكتمل، مع نسبة الإنجاز وموعد التسليم.</p></div>
                <div class="service"><div class="ic"><x-icon name="invoices" size="22" /></div><h3>فواتير ضريبية</h3><p>فواتير مرقّمة تلقائيًا مع ضريبة القيمة المضافة 15٪ ورقمك الضريبي والآيبان، جاهزة للطباعة أو الحفظ PDF.</p></div>
                <div class="service"><div class="ic"><x-icon name="appointments" size="22" /></div><h3>المواعيد</h3><p>اجتماعاتك المرئية والحضورية ومكالماتك مع العملاء مرتبة حسب اليوم، فلا يفوتك موعد.</p></div>
                <div class="service"><div class="ic"><x-icon name="earnings" size="22" /></div><h3>تقارير الأرباح</h3><p>دخلك الشهري لآخر 12 شهرًا، أفضل شهر، الضريبة المحصّلة، وترتيب العملاء حسب الدخل.</p></div>
                <div class="service"><div class="ic"><x-icon name="links" size="22" /></div><h3>صفحة الروابط</h3><p>صفحة عامة باسمك تجمع معرض أعمالك وواتساب وحساباتك، شاركها في البايو بدل الروابط المتعددة.</p></div>
                <div class="service"><div class="ic"><x-icon name="home" size="22" /></div><h3>لوحة تحكم واحدة</h3><p>نظرة يومية على أرباح الشهر والمبالغ المستحقة والمشاريع النشطة والعروض بانتظار الرد.</p></div>
                <div class="service"><div class="ic"><x-icon name="settings" size="22" /></div><h3>هويتك في كل مستند</h3><p>ارفع شعارك مرة واحدة ليظهر في الفواتير وعروض الأسعار وصفحة الروابط تلقائيًا.</p></div>
            </div>
        </section>

        <section class="section" id="how">
            <h2 class="title">كيف يعمل مدار</h2>
            <p class="sub">ثلاث خطوات من أول تواصل حتى استلام الدفعة.</p>
            <div class="steps">
                <div class="step"><b>أضف عميلك وأرسل عرضك</b><span>سجّل بيانات العميل واكتب عرض سعر بالبنود في دقائق.</span></div>
                <div class="step"><b>حوّل العرض المقبول إلى مشروع</b><span>تابع التنفيذ على لوحة المراحل وجدول مواعيد التسليم.</span></div>
                <div class="step"><b>أصدر الفاتورة واستلم دفعتك</b><span>فاتورة ضريبية بضغطة، وعلّمها مدفوعة لتظهر في أرباحك.</span></div>
            </div>
        </section>

        <section class="section">
            <div class="cta-band">
                <h2>جاهز تنظّم عملك الحر؟</h2>
                <p>أنشئ حسابك الآن وابدأ بإضافة أول عميل.</p>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn">افتح لوحة التحكم</a>
                @else
                    <a href="{{ route('register') }}" class="btn">ابدأ مجانًا</a>
                @endauth
            </div>
        </section>
    </main>

    <footer class="site-foot">© {{ now()->year }} مدار للمستقلين</footer>
</x-layouts.base>
