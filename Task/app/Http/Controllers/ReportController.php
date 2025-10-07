<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\WorkTime;
use App\Models\Employee;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('name')->get(['id','name']);
        return view('report', [
            'projects' => $projects,
            'selected' => [],
            'all' => true,
            'employees' => collect(),
            'projectStats' => collect(),
            'timeLogs' => collect(),
            'moduleLogs' => collect(),
            'pivot' => [ 'dates' => [], 'rows' => [] ],
        ]);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'project_id' => ['nullable'],
        ]);

        $chosen = $request->input('project_id');
        $all = ($chosen === 'all' || $chosen === null || $chosen === '');
        $selected = $all ? [] : [(int) $chosen];
        $employees = Employee::select('id','name','salary')->orderBy('id')->get();
        $projQuery = Project::select('id','name','start_date','end_date');
        if (! $all && ! empty($selected)) {
            $projQuery->whereIn('id', $selected);
        }
        $projectStats = $projQuery->orderBy('id')->get()->map(function ($p) {
            if (!$p->start_date || !$p->end_date) {
                $workTimes = WorkTime::where('project_id', $p->id);
                if (!$p->start_date) {
                    $p->start_date = $workTimes->min('date');
                }
                if (!$p->end_date) {
                    $p->end_date = $workTimes->max('date');
                }
            }
            $start = $p->start_date ? Carbon::parse($p->start_date) : null;
            $end = $p->end_date ? Carbon::parse($p->end_date) : null;
            $days = ($start && $end) ? $start->diffInDays($end) : null;
            
            return [
                'id' => $p->id,
                'name' => $p->name,
                'start_date' => $p->start_date,
                'end_date' => $p->end_date,
                'total_days' => $days,
            ];
        });

        $filtered = WorkTime::query();
        if (! $all && ! empty($selected)) {
            $filtered->whereIn('project_id', $selected);
        }

        $hoursPerEmpProject = (clone $filtered)
            ->selectRaw('project_id, emp_id, SUM(hours) as sum_hours')
            ->groupBy('project_id','emp_id')
            ->get();
        $empSet = [];
        $projectCost = [];
        foreach ($hoursPerEmpProject as $row) {
            $empSet[$row->project_id] = $empSet[$row->project_id] ?? [];
            $empSet[$row->project_id][$row->emp_id] = true;
            $empHourCost = $employees->find($row->emp_id)->hour_cost ?? 0;
            $projectCost[$row->project_id] = ($projectCost[$row->project_id] ?? 0) + ($row->sum_hours * $empHourCost);
        }
        
        $projectStats = $projectStats->map(function ($p) use ($empSet, $projectCost) {
            $p['total_employees'] = isset($empSet[$p['id']]) ? count($empSet[$p['id']]) : 0;
            $p['total_cost'] = (int)($projectCost[$p['id']] ?? 0);
            return $p;
        })->values();

        $timeLogs = (clone $filtered)
            ->with(['employee:id,name', 'project:id,name', 'modul:id,name'])
            ->orderBy('date')
            ->get()
            ->map(function ($w) {
                return [
                    'date' => $w->date,
                    'employee' => optional($w->employee)->name,
                    'project' => optional($w->project)->name,
                    'hours' => (int)($w->hours),  
                    'modul' => optional($w->modul)->name,
                ];
            });

        $moduleLogs = $timeLogs->sortBy(['modul','project','employee'])->values();
        $dates = (clone $filtered)->select('date')->distinct()->orderBy('date')->limit(6)->pluck('date')->toArray();
        $empHoursByDate = (clone $filtered)
            ->selectRaw('emp_id, date, SUM(hours) as sum_hours')
            ->groupBy('emp_id','date')
            ->get();
        $empIdToName = Employee::pluck('name','id');
        $pivotRows = [];
        foreach ($empIdToName as $empId => $empName) {
            $row = ['employee' => $empName];
            foreach ($dates as $d) {
                $match = $empHoursByDate->firstWhere(fn($r) => $r->emp_id == $empId && $r->date == $d);
                $row[$d] = $match ? (int)($match->sum_hours) : 0;  
            }
            $pivotRows[] = $row;
        }
        $projectsList = Project::orderBy('name')->get(['id','name']);
        return view('report', [
            'projects' => $projectsList,
            'selected' => $selected,
            'all' => $all,
            'employees' => $employees,
            'projectStats' => $projectStats,
            'timeLogs' => $timeLogs,
            'moduleLogs' => $moduleLogs,
            'pivot' => [ 'dates' => $dates, 'rows' => $pivotRows ],
        ]);
    }
}
