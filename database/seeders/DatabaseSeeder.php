<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProposalStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed a demo freelancer workspace (login: demo@madar.test / password).
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'أحمد غالب',
            'email' => 'demo@madar.test',
            'business_name' => 'استوديو أحمد غالب للتصميم',
            'job_title' => 'مصمم هويات بصرية',
            'handle' => 'ahmadghaleb',
            'bio' => 'أصمم هويات بصرية ومواقع للعلامات السعودية الناشئة.',
        ]);

        $darah = $user->clients()->create(['name' => 'نورة السبيعي', 'company' => 'مقهى دارة', 'email' => 'noura@darah.sa', 'phone' => '0551234567', 'notes' => 'تفضّل التواصل عبر واتساب']);
        $masar = $user->clients()->create(['name' => 'فهد العتيبي', 'company' => 'عقارات المسار', 'email' => 'fahad@masar.sa', 'phone' => '0509876543']);
        $naqsh = $user->clients()->create(['name' => 'ريم القحطاني', 'company' => 'متجر نقش', 'email' => 'reem@naqsh.store', 'phone' => '0532221100', 'notes' => 'متجر سلة']);

        $identityProposal = $user->proposals()->create(['client_id' => $darah->id, 'title' => 'هوية بصرية كاملة لمقهى دارة', 'status' => ProposalStatus::Accepted, 'issued_on' => today()->subDays(40), 'valid_until' => today()->subDays(26), 'items' => [['description' => 'شعار وهوية', 'quantity' => 1, 'price' => 6500], ['description' => 'تصميم المنيو والأكواب', 'quantity' => 1, 'price' => 2500]], 'notes' => 'دفعتان: 50% عند البدء و50% عند التسليم']);
        $user->proposals()->create(['client_id' => $masar->id, 'title' => 'موقع تعريفي لعقارات المسار', 'status' => ProposalStatus::Sent, 'issued_on' => today()->subDays(5), 'valid_until' => today()->addDays(9), 'items' => [['description' => 'تصميم واجهات الموقع (8 صفحات)', 'quantity' => 8, 'price' => 900], ['description' => 'نسخة الجوال', 'quantity' => 1, 'price' => 1800]]]);
        $user->proposals()->create(['client_id' => $naqsh->id, 'title' => 'تصاميم إعلانات رمضان', 'status' => ProposalStatus::Draft, 'issued_on' => today()->subDay(), 'valid_until' => today()->addDays(14), 'items' => [['description' => 'تصميم إعلان سوشال', 'quantity' => 12, 'price' => 250]]]);

        $user->projects()->create(['client_id' => $darah->id, 'proposal_id' => $identityProposal->id, 'title' => 'هوية مقهى دارة', 'status' => ProjectStatus::Review, 'progress' => 85, 'budget' => 9000, 'starts_on' => today()->subDays(35), 'due_on' => today()->addDays(4), 'notes' => 'بانتظار ملاحظات العميل على المنيو']);
        $user->projects()->create(['client_id' => $naqsh->id, 'title' => 'إعادة تصميم متجر نقش', 'status' => ProjectStatus::Doing, 'progress' => 40, 'budget' => 5500, 'starts_on' => today()->subDays(14), 'due_on' => today()->addDays(16)]);
        $user->projects()->create(['client_id' => $masar->id, 'title' => 'بروشور مشروع المسار', 'status' => ProjectStatus::Done, 'progress' => 100, 'budget' => 3200, 'starts_on' => today()->subDays(90), 'due_on' => today()->subDays(60)]);
        $user->projects()->create(['client_id' => $naqsh->id, 'title' => 'دليل الهوية لنقش', 'status' => ProjectStatus::New, 'progress' => 0, 'budget' => 2400, 'starts_on' => today()->addDays(10), 'due_on' => today()->addDays(30)]);

        $user->invoices()->create(['client_id' => $masar->id, 'status' => InvoiceStatus::Paid, 'issued_on' => today()->subDays(75), 'due_on' => today()->subDays(61), 'paid_on' => today()->subDays(63), 'has_vat' => true, 'items' => [['description' => 'تصميم بروشور مشروع المسار', 'quantity' => 1, 'price' => 3200]]]);
        $user->invoices()->create(['client_id' => $darah->id, 'status' => InvoiceStatus::Paid, 'issued_on' => today()->subDays(35), 'due_on' => today()->subDays(21), 'paid_on' => today()->subDays(30), 'has_vat' => true, 'items' => [['description' => 'دفعة أولى - هوية مقهى دارة', 'quantity' => 1, 'price' => 4500]]]);
        $user->invoices()->create(['client_id' => $naqsh->id, 'status' => InvoiceStatus::Sent, 'issued_on' => today()->subDays(14), 'due_on' => today()->subDays(2), 'has_vat' => true, 'items' => [['description' => 'دفعة أولى - إعادة تصميم المتجر', 'quantity' => 1, 'price' => 2750]]]);
        $user->invoices()->create(['client_id' => $darah->id, 'status' => InvoiceStatus::Sent, 'issued_on' => today()->subDays(2), 'due_on' => today()->addDays(12), 'has_vat' => true, 'items' => [['description' => 'دفعة نهائية - هوية مقهى دارة', 'quantity' => 1, 'price' => 4500]]]);

        $user->appointments()->create(['client_id' => $darah->id, 'title' => 'مراجعة المنيو مع نورة', 'scheduled_on' => today()->addDay(), 'starts_at' => '11:00', 'duration_minutes' => 45, 'type' => 'اجتماع مرئي', 'location' => 'Google Meet']);
        $user->appointments()->create(['client_id' => $masar->id, 'title' => 'اجتماع تعارف - عقارات المسار', 'scheduled_on' => today()->addDays(3), 'starts_at' => '16:30', 'duration_minutes' => 60, 'type' => 'حضوري', 'location' => 'مكتبهم - حي الملقا']);
        $user->appointments()->create(['client_id' => $naqsh->id, 'title' => 'تسليم مسودة الصفحة الرئيسية', 'scheduled_on' => today()->addDays(6), 'starts_at' => '13:00', 'duration_minutes' => 30, 'type' => 'مكالمة']);

        $user->links()->create(['title' => 'معرض أعمالي على بيهانس', 'url' => 'https://behance.net/', 'position' => 1]);
        $user->links()->create(['title' => 'اطلب عرض سعر', 'url' => 'https://wa.me/', 'position' => 2]);
        $user->links()->create(['title' => 'حسابي على إنستقرام', 'url' => 'https://instagram.com/', 'position' => 3]);
    }
}
