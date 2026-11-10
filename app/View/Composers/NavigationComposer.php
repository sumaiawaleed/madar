<?php

namespace App\View\Composers;

use App\Enums\InvoiceStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProposalStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavigationComposer
{
    public function __construct(private Request $request) {}

    /**
     * Share the rail navigation, with live counts of items needing attention.
     */
    public function compose(View $view): void
    {
        $user = $this->request->user();

        $view->with('navigation', [
            ['route' => 'dashboard', 'active' => 'dashboard', 'icon' => 'home', 'label' => 'الرئيسية', 'count' => null],
            ['route' => 'clients.index', 'active' => 'clients.*', 'icon' => 'clients', 'label' => 'العملاء', 'count' => $user->clients()->count()],
            ['route' => 'proposals.index', 'active' => 'proposals.*', 'icon' => 'proposals', 'label' => 'العروض', 'count' => $user->proposals()->where('status', ProposalStatus::Sent)->count()],
            ['route' => 'projects.index', 'active' => 'projects.*', 'icon' => 'projects', 'label' => 'المشاريع', 'count' => $user->projects()->where('status', '!=', ProjectStatus::Done)->count()],
            ['route' => 'invoices.index', 'active' => 'invoices.*', 'icon' => 'invoices', 'label' => 'الفواتير', 'count' => $user->invoices()->where('status', InvoiceStatus::Sent)->count()],
            ['route' => 'appointments.index', 'active' => 'appointments.*', 'icon' => 'appointments', 'label' => 'المواعيد', 'count' => $user->appointments()->whereDate('scheduled_on', '>=', today())->count()],
            ['route' => 'earnings', 'active' => 'earnings', 'icon' => 'earnings', 'label' => 'الأرباح', 'count' => null],
            ['route' => 'links.index', 'active' => 'links.*', 'icon' => 'links', 'label' => 'صفحة الروابط', 'count' => null],
            ['route' => 'profile.edit', 'active' => 'profile.*', 'icon' => 'settings', 'label' => 'الإعدادات', 'count' => null],
        ]);
    }
}
