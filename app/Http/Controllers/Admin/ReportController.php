<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('user');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $reports = $query->orderBy('created_at', 'desc')
            ->paginate(config('siakad.pagination', 15))
            ->withQueryString();

        return view('admin.report.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load('user');
        return view('admin.report.show', compact('report'));
    }

    public function reply(Request $request, Report $report)
    {
        $request->validate([
            'admin_reply' => 'required|string',
        ]);

        $report->update([
            'admin_reply' => $request->admin_reply,
            'status' => 'replied',
            'replied_by' => Auth::id(),
            'replied_at' => now(),
        ]);

        return redirect()->route('admin.report.show', $report)
            ->with('success', __('Reply successfully sent.'));
    }

    public function close(Report $report)
    {
        $report->update(['status' => 'closed']);

        return redirect()->route('admin.report.index')
            ->with('success', __('Report successfully closed.'));
    }
}
