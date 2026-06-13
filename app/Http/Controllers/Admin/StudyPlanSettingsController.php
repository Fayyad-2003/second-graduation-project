<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GpaCreditRule;
use Illuminate\Http\Request;

class StudyPlanSettingsController extends Controller
{
    /** Display the settings page with all existing rules. */
    public function index()
    {
        $rules = GpaCreditRule::orderByDesc('min_gpa')->get();

        return view('admin.study-plan-settings.index', compact('rules'));
    }

    /** Store a new GPA credit rule. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'label'       => 'required|string|max:100',
            'min_gpa'     => 'required|numeric|min:0|max:4',
            'max_gpa'     => 'required|numeric|min:0|max:4|gte:min_gpa',
            'max_credits' => 'required|integer|min:1|max:50',
        ]);

        // Check for overlap with existing rules
        $overlap = GpaCreditRule::where(function ($q) use ($data) {
            $q->whereBetween('min_gpa', [$data['min_gpa'], $data['max_gpa']])
              ->orWhereBetween('max_gpa', [$data['min_gpa'], $data['max_gpa']])
              ->orWhere(function ($q2) use ($data) {
                  $q2->where('min_gpa', '<=', $data['min_gpa'])
                     ->where('max_gpa', '>=', $data['max_gpa']);
              });
        })->exists();

        if ($overlap) {
            return back()->withErrors(['min_gpa' => __('This GPA range overlaps with an existing rule.')])->withInput();
        }

        GpaCreditRule::create($data);

        return back()->with('success', __('Rule added successfully.'));
    }

    /** Update an existing GPA credit rule. */
    public function update(Request $request, GpaCreditRule $gpa_credit_rule)
    {
        $data = $request->validate([
            'label'       => 'required|string|max:100',
            'min_gpa'     => 'required|numeric|min:0|max:4',
            'max_gpa'     => 'required|numeric|min:0|max:4|gte:min_gpa',
            'max_credits' => 'required|integer|min:1|max:50',
        ]);

        // Check overlap excluding current rule
        $overlap = GpaCreditRule::where('id', '!=', $gpa_credit_rule->id)
            ->where(function ($q) use ($data) {
                $q->whereBetween('min_gpa', [$data['min_gpa'], $data['max_gpa']])
                  ->orWhereBetween('max_gpa', [$data['min_gpa'], $data['max_gpa']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('min_gpa', '<=', $data['min_gpa'])
                         ->where('max_gpa', '>=', $data['max_gpa']);
                  });
            })->exists();

        if ($overlap) {
            return back()->withErrors(['min_gpa' => __('This GPA range overlaps with an existing rule.')])->withInput();
        }

        $gpa_credit_rule->update($data);

        return back()->with('success', __('Rule updated successfully.'));
    }

    /** Delete a GPA credit rule. */
    public function destroy(GpaCreditRule $gpa_credit_rule)
    {
        $gpa_credit_rule->delete();

        return back()->with('success', __('Rule deleted successfully.'));
    }

    /** Reset to default rules from config/system.php. */
    public function resetDefaults()
    {
        GpaCreditRule::truncate();

        $defaults = config('system.max_credits.gpa_rules', [
            ['min' => 3.51, 'max' => 4.00, 'credits' => 24, 'label' => 'Excellent (3.51 – 4.00)'],
            ['min' => 3.01, 'max' => 3.50, 'credits' => 22, 'label' => 'Very Good (3.01 – 3.50)'],
            ['min' => 2.51, 'max' => 3.00, 'credits' => 20, 'label' => 'Good (2.51 – 3.00)'],
            ['min' => 2.00, 'max' => 2.50, 'credits' => 18, 'label' => 'Satisfactory (2.00 – 2.50)'],
            ['min' => 0.00, 'max' => 1.99, 'credits' => 14, 'label' => 'Below Standard (0.00 – 1.99)'],
        ]);

        foreach ($defaults as $rule) {
            GpaCreditRule::create([
                'label'       => $rule['label'] ?? "{$rule['min']} – {$rule['max']}",
                'min_gpa'     => $rule['min'],
                'max_gpa'     => $rule['max'],
                'max_credits' => $rule['credits'],
            ]);
        }

        return back()->with('success', __('Rules reset to system defaults successfully.'));
    }
}
