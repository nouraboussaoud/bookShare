<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsDashboardController extends Controller
{
    /**
     * Afficher le tableau de bord des signalements
     */
    public function index(Request $request)
    {
        // Filtres de date
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $status = $request->get('status');
        $priority = $request->get('priority');

        // Statistiques générales
        $totalReports = Report::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $pendingReports = Report::pending()->whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $processedReports = Report::processed()->whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $rejectedReports = Report::rejected()->whereBetween('created_at', [$dateFrom, $dateTo])->count();
        
        // Statistiques par priorité
        $urgentReports = Report::urgent()->pending()->whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $highPriorityReports = Report::where('priority_level', Report::PRIORITY_HAUTE)
            ->pending()
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();
        
        // Statistiques par type
        $conflitReports = Report::where('type', Report::TYPE_CONFLIT_ECHANGE)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();
        $comportementReports = Report::where('type', Report::TYPE_COMPORTEMENT)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        // Temps moyen de résolution
        $avgResolutionTime = $this->calculateAverageResolutionTime($dateFrom, $dateTo);

        // Évolution temporelle (derniers 7 jours)
        $timeline = $this->getReportsTimeline(7);

        // Distribution par type (pour le graphique)
        $typeDistribution = [
            'CONFLIT_ECHANGE' => $conflitReports,
            'COMPORTEMENT' => $comportementReports,
        ];

        // Distribution par statut
        $statusDistribution = [
            'EN_ATTENTE' => $pendingReports,
            'TRAITE' => $processedReports,
            'REJETE' => $rejectedReports,
        ];

        // Distribution par priorité
        $priorityDistribution = $this->getPriorityDistribution($dateFrom, $dateTo);

        // Top 5 utilisateurs signalés
        $topReportedUsers = $this->getTopReportedUsers($dateFrom, $dateTo, 5);

        // Top 5 reporters
        $topReporters = $this->getTopReporters($dateFrom, $dateTo, 5);

        // Récidivistes
        $recurringOffenders = Report::where('is_recurring_offender', true)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('reportedUser')
            ->get()
            ->unique('reported_user_id')
            ->count();

        // Signalements récents nécessitant une attention urgente
        $urgentRecentReports = Report::urgent()
            ->pending()
            ->with(['reporter', 'reportedUser', 'exchange'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Performance des modérateurs (si reviewed_at existe)
        $moderatorPerformance = $this->getModeratorPerformance($dateFrom, $dateTo);

        // Taux de résolution
        $resolutionRate = $totalReports > 0 ? round(($processedReports / $totalReports) * 100, 1) : 0;

        return view('admin.reports.dashboard', compact(
            'totalReports',
            'pendingReports',
            'processedReports',
            'rejectedReports',
            'urgentReports',
            'highPriorityReports',
            'conflitReports',
            'comportementReports',
            'avgResolutionTime',
            'timeline',
            'typeDistribution',
            'statusDistribution',
            'priorityDistribution',
            'topReportedUsers',
            'topReporters',
            'recurringOffenders',
            'urgentRecentReports',
            'moderatorPerformance',
            'resolutionRate',
            'dateFrom',
            'dateTo',
            'status',
            'priority'
        ));
    }

    /**
     * Calculer le temps moyen de résolution (en heures)
     */
    private function calculateAverageResolutionTime($dateFrom, $dateTo)
    {
        $resolvedReports = Report::whereNotNull('resolved_at')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        if ($resolvedReports->isEmpty()) {
            return 0;
        }

        $totalHours = 0;
        foreach ($resolvedReports as $report) {
            $totalHours += $report->created_at->diffInHours($report->resolved_at);
        }

        return round($totalHours / $resolvedReports->count(), 1);
    }

    /**
     * Obtenir l'évolution temporelle des signalements
     */
    private function getReportsTimeline($days = 7)
    {
        $timeline = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $timeline[] = [
                'date' => Carbon::parse($date)->format('d/m'),
                'count' => Report::whereDate('created_at', $date)->count(),
                'pending' => Report::pending()->whereDate('created_at', $date)->count(),
                'processed' => Report::processed()->whereDate('created_at', $date)->count(),
            ];
        }
        return $timeline;
    }

    /**
     * Distribution par priorité
     */
    private function getPriorityDistribution($dateFrom, $dateTo)
    {
        return [
            'normale' => Report::where('priority_level', Report::PRIORITY_NORMALE)
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count(),
            'moyenne' => Report::where('priority_level', Report::PRIORITY_MOYENNE)
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count(),
            'haute' => Report::where('priority_level', Report::PRIORITY_HAUTE)
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count(),
            'critique' => Report::where('priority_level', Report::PRIORITY_CRITIQUE)
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count(),
        ];
    }

    /**
     * Top utilisateurs signalés
     */
    private function getTopReportedUsers($dateFrom, $dateTo, $limit = 5)
    {
        return Report::select('reported_user_id', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNotNull('reported_user_id')
            ->groupBy('reported_user_id')
            ->orderByDesc('count')
            ->with('reportedUser')
            ->limit($limit)
            ->get();
    }

    /**
     * Top reporters
     */
    private function getTopReporters($dateFrom, $dateTo, $limit = 5)
    {
        return Report::select('reporter_id', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNotNull('reporter_id')
            ->groupBy('reporter_id')
            ->orderByDesc('count')
            ->with('reporter')
            ->limit($limit)
            ->get();
    }

    /**
     * Performance des modérateurs
     */
    private function getModeratorPerformance($dateFrom, $dateTo)
    {
        return Report::select(
                'moderator_id',
                DB::raw('COUNT(*) as total_handled'),
                DB::raw('COUNT(CASE WHEN status = "TRAITE" THEN 1 END) as processed'),
                DB::raw('COUNT(CASE WHEN status = "REJETE" THEN 1 END) as rejected'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_resolution_hours')
            )
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNotNull('moderator_id')
            ->whereNotNull('resolved_at')
            ->groupBy('moderator_id')
            ->with('moderator')
            ->get();
    }

    /**
     * Exporter les données en CSV
     */
    public function export(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $reports = Report::with(['reporter', 'reportedUser', 'moderator'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $filename = 'reports_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, [
                'ID',
                'Type',
                'Description',
                'Statut',
                'Priorité',
                'Score',
                'Reporter',
                'Utilisateur signalé',
                'Modérateur',
                'Date création',
                'Date résolution',
                'Temps résolution (h)',
                'Action prise'
            ]);

            // Données
            foreach ($reports as $report) {
                $resolutionTime = $report->resolved_at 
                    ? $report->created_at->diffInHours($report->resolved_at) 
                    : null;

                fputcsv($file, [
                    $report->id,
                    $report->type,
                    $report->description,
                    $report->status,
                    $report->priority_level,
                    $report->priority_score,
                    $report->reporter ? $report->reporter->name : 'N/A',
                    $report->reportedUser ? $report->reportedUser->name : 'N/A',
                    $report->moderator ? $report->moderator->name : 'N/A',
                    $report->created_at->format('Y-m-d H:i:s'),
                    $report->resolved_at ? $report->resolved_at->format('Y-m-d H:i:s') : 'N/A',
                    $resolutionTime ?? 'N/A',
                    $report->action_taken ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * API endpoint pour obtenir les données de timeline en JSON
     */
    public function timelineData(Request $request)
    {
        $days = $request->get('days', 7);
        $timeline = $this->getReportsTimeline($days);
        
        return response()->json($timeline);
    }
}
