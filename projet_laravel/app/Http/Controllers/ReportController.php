<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Exchange;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
    use AuthorizesRequests;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of reports created by the authenticated user.
     */
    public function index(Request $request)
    {
        $query = Report::where('reporter_id', Auth::id())
            ->with(['reportedUser', 'exchange', 'exchange.bookDemande']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type if provided
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create(Request $request)
    {
        $reportedUserId = $request->input('reported_user_id');
        $exchangeId = $request->input('exchange_id');
        
        $reportedUser = null;
        $exchange = null;

        if ($reportedUserId) {
            $reportedUser = User::findOrFail($reportedUserId);
        }

        if ($exchangeId) {
            $exchange = Exchange::with(['bookDemande', 'initiateur', 'recepteur'])
                ->findOrFail($exchangeId);
        }

        return view('reports.create', compact('reportedUser', 'exchange'));
    }

    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        // Log the incoming request data for debugging
        Log::info('Report submission data', [
            'all_data' => $request->all(),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'reported_user_id' => $request->input('reported_user_id'),
            'exchange_id' => $request->input('exchange_id')
        ]);

        $validated = $request->validate([
            'type' => 'required|in:' . Report::TYPE_CONFLIT_ECHANGE . ',' . Report::TYPE_COMPORTEMENT,
            'description' => 'required|string|min:10|max:1000',
            'reported_user_id' => 'nullable|exists:users,id',
            'exchange_id' => 'nullable|exists:exchanges,id',
        ]);

        // Additional validation
        $errors = [];
        
        if ($validated['type'] === Report::TYPE_CONFLIT_ECHANGE && !$validated['exchange_id']) {
            $errors['exchange_id'] = 'Un échange doit être sélectionné pour un rapport de conflit d\'échange.';
        }

        if ($validated['type'] === Report::TYPE_COMPORTEMENT && !$validated['reported_user_id']) {
            $errors['reported_user_id'] = 'Un utilisateur doit être sélectionné pour un rapport de comportement.';
        }

        // Prevent self-reporting
        if ($validated['reported_user_id'] === Auth::id()) {
            $errors['reported_user_id'] = 'Vous ne pouvez pas vous signaler vous-même.';
        }

        // Check if user is already involved in the exchange (if exchange report)
        if ($validated['exchange_id']) {
            $exchange = Exchange::findOrFail($validated['exchange_id']);
            if ($exchange->userInitiateurId !== Auth::id() && $exchange->userRecepteurId !== Auth::id()) {
                $errors['exchange_id'] = 'Vous ne pouvez signaler que les échanges auxquels vous participez.';
            }
        }

        // Handle validation errors
        if (!empty($errors)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $errors
                ], 422);
            }
            return back()->withErrors($errors)->withInput();
        }

        $report = Report::create([
            'type' => $validated['type'],
            'description' => $validated['description'],
            'reporter_id' => Auth::id(),
            'reported_user_id' => $validated['reported_user_id'] ?? null,
            'exchange_id' => $validated['exchange_id'] ?? null,
            'status' => Report::STATUS_EN_ATTENTE,
        ]);

        // Notify all admins about the new report
        $this->notificationService->notifyAdminsOfNewReport($report);

        // Handle AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Votre signalement a été envoyé avec succès. Les administrateurs en ont été informés.',
                'report_id' => $report->id
            ]);
        }

        return redirect()->route('reports.index')
            ->with('success', 'Votre signalement a été envoyé avec succès. Les administrateurs en ont été informés.');
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        // Ensure the user can only view their own reports
        $this->authorize('view', $report);

        $report->load(['reportedUser', 'exchange', 'exchange.bookDemande', 'exchange.initiateur', 'exchange.recepteur']);

        return view('reports.show', compact('report'));
    }
}