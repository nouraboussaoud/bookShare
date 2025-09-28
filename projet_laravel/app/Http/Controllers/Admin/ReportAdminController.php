<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportAdminController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of all reports.
     */
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reportedUser', 'exchange', 'exchange.bookDemande']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search by reporter or reported user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('reporter', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('reportedUser', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Report::count(),
            'pending' => Report::pending()->count(),
            'processed' => Report::processed()->count(),
            'rejected' => Report::rejected()->count(),
        ];

        return view('admin.reports.index', compact('reports', 'stats'));
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        $report->load([
            'reporter',
            'reportedUser',
            'exchange',
            'exchange.bookDemande',
            'exchange.initiateur',
            'exchange.recepteur'
        ]);

        return view('admin.reports.show', compact('report'));
    }

    /**
     * Update the status of the specified report.
     */
    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . Report::STATUS_TRAITE . ',' . Report::STATUS_REJETE,
            'admin_note' => 'nullable|string|max:500',
        ]);

        $oldStatus = $report->status;
        $report->update([
            'status' => $validated['status'],
        ]);

        // Log admin action (you might want to create an admin_actions table for this)
        // For now, we'll just notify the reporter

        // Notify the reporter about the status change
        $this->notificationService->notifyReportStatusChange($report, $oldStatus, $validated['status']);

        $statusText = $validated['status'] === Report::STATUS_TRAITE ? 'traité' : 'rejeté';

        return redirect()->route('admin.reports.show', $report)
            ->with('success', "Le signalement a été marqué comme {$statusText}.");
    }

    /**
     * Remove the specified report from storage.
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Le signalement a été supprimé avec succès.');
    }

    /**
     * Bulk update reports status.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'reports' => 'required|array',
            'reports.*' => 'exists:reports,id',
            'status' => 'required|in:' . Report::STATUS_TRAITE . ',' . Report::STATUS_REJETE,
        ]);

        $reports = Report::whereIn('id', $validated['reports'])->get();

        foreach ($reports as $report) {
            $oldStatus = $report->status;
            $report->update(['status' => $validated['status']]);
            
            // Notify each reporter
            $this->notificationService->notifyReportStatusChange($report, $oldStatus, $validated['status']);
        }

        $count = count($validated['reports']);
        $statusText = $validated['status'] === Report::STATUS_TRAITE ? 'traités' : 'rejetés';

        return redirect()->route('admin.reports.index')
            ->with('success', "{$count} signalement(s) ont été marqués comme {$statusText}.");
    }
}