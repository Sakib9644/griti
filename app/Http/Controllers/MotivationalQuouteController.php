<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MotivationalQuote;
use App\Models\MotivationalQuoute;
use Yajra\DataTables\Facades\DataTables;

class MotivationalQuouteController extends Controller
{
    /**
     * Display DataTable or View
     */
   public function index(Request $request)
{
    if ($request->ajax()) {
        $quotes = MotivationalQuoute::orderBy('id', 'desc')->get();

        return DataTables::of($quotes)
            ->addIndexColumn()
            ->addColumn('quote', function ($row) {
                return '<span title="'.e($row->quote).'">'.\Illuminate\Support\Str::limit($row->quote, 50).'</span>';
            })
            ->addColumn('status', function ($row) {
                // Determine background color and slider position
                $backgroundColor = $row->status ? '#4CAF50' : '#ccc';
                $sliderTranslateX = $row->status ? '26px' : '2px';

                // Build custom switch HTML
                $status = '<div class="d-flex justify-content-center align-items-center">';
                $status .= '<div class="form-check form-switch" style="position: relative; width: 50px; height: 24px; background-color: '.$backgroundColor.'; border-radius: 12px; transition: background-color 0.3s ease; cursor: pointer;">';
                $status .= '<input type="checkbox" class="form-check-input" onchange="toggleStatus('.$row->id.')" style="position: absolute; width: 100%; height: 100%; opacity: 0; z-index: 2; cursor: pointer;" '.($row->status ? 'checked' : '').'>';
                $status .= '<span style="position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: transform 0.3s ease; transform: translateX('.$sliderTranslateX.');"></span>';
                $status .= '<label class="form-check-label" style="margin-left: 10px;"></label>';
                $status .= '</div>';
                $status .= '</div>';

                return $status;
            })
            ->addColumn('action', function ($row) {
                return '<button onclick="editQuote('.$row->id.')" class="btn btn-primary btn-sm">Edit</button>
                        <button onclick="deleteQuote('.$row->id.')" class="btn btn-danger btn-sm">Delete</button>';
            })
            ->rawColumns(['quote','status','action'])
            ->make(true);
    }

    return view('backend.layouts.quote.index'); // Blade page
}

    /**
     * Store a new quote
     */
    public function store(Request $request)
    {
        $request->validate([
            'quote' => 'required|string|max:1000',
            'status' => 'nullable|boolean',
        ]);

        $quote = new MotivationalQuoute();
        $quote->quote = $request->quote;
        $quote->status = $request->status ?? 1;
        $quote->save();

        return response()->json(['success' => true, 'message' => 'Quote added successfully']);
    }

    /**
     * Get quote for editing
     */
    public function edit($id)
    {
        $quote = MotivationalQuoute::findOrFail($id);
        return response()->json($quote);
    }

    /**
     * Update quote
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quote' => 'required|string|max:1000',
            'status' => 'nullable|boolean',
        ]);

        $quote = MotivationalQuoute::findOrFail($id);
        $quote->quote = $request->quote;
        $quote->status = $request->status ?? 0;
        $quote->save();

        return response()->json(['success' => true, 'message' => 'Quote updated successfully']);
    }

    /**
     * Delete quote
     */
    public function destroy($id)
    {
        $quote = MotivationalQuoute::findOrFail($id);
        $quote->delete();

        return response()->json(['success' => true, 'message' => 'Quote deleted successfully']);
    }

    /**
     * Toggle status
     */
    public function toggleStatus($id)
    {
        $quote = MotivationalQuoute::findOrFail($id);
        $quote->status = !$quote->status;
        $quote->save();

        return response()->json([
            'success' => true,
            'status' => $quote->status ? 'Active' : 'Inactive'
        ]);
    }
}
