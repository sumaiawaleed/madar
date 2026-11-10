<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Enums\ProposalStatus;
use App\Models\Invoice;
use App\Models\Proposal;
use App\Support\MonthlyEarnings;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $months = MonthlyEarnings::for($user, 6);
        $outstanding = $user->invoices()->outstanding()->get();
        $activeProjects = $user->projects()->with('client')->where('status', '!=', ProjectStatus::Done)->orderBy('due_on')->get();
        $pendingProposals = $user->proposals()->where('status', ProposalStatus::Sent)->get();

        return view('dashboard', [
            'months' => $months,
            'thisMonth' => $months->last()['total'],
            'lastMonth' => $months->slice(-2, 1)->first()['total'],
            'outstandingTotal' => $outstanding->sum(fn (Invoice $invoice): float => $invoice->total()),
            'outstandingCount' => $outstanding->count(),
            'overdueCount' => $outstanding->filter(fn (Invoice $invoice): bool => $invoice->isOverdue())->count(),
            'activeProjects' => $activeProjects,
            'pendingProposalsCount' => $pendingProposals->count(),
            'pendingProposalsValue' => $pendingProposals->sum(fn (Proposal $proposal): float => $proposal->subtotal()),
            'upcomingAppointments' => $user->appointments()->upcoming()->limit(4)->get(),
            'latestInvoices' => $user->invoices()->with('client')->latest('number')->limit(5)->get(),
        ]);
    }
}
