<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;
use Exception;
use Illuminate\Support\Facades\Log;
use Stripe\Product as StripeProduct;

class PlanController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $plans = Plan::query();

            return DataTables::of($plans)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('subscriptions-plans.edit', $row->id);
                    $deleteUrl = route('subscriptions-plans.destroy', $row->id);

                    return '
    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <a href="' . $editUrl . '" class="btn btn-primary fs-14 text-white" title="Edit">
            <i class="fe fe-edit"></i>
        </a>
        <button type="button" onclick="confirmDelete(' . $row->id . ')" class="btn btn-danger fs-14 text-white" title="Delete">
            <i class="fe fe-trash"></i>
        </button>
        <form id="delete-form-' . $row->id . '" action="' . $deleteUrl . '" method="POST" style="display: none;">
            ' . csrf_field() . '
            ' . method_field('DELETE') . '
        </form>
    </div>';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.layouts.plans.index');
    }


  public function store(Request $request)
{
    try {

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'interval' => 'required|string|in:day,week,month,year',
        ]);

        // Create Stripe Product
        $stripeProduct = Product::create([
            'name' => $request->name,
        ]);

        // Create Stripe Price with 3 trial days
        $stripePrice = Price::create([
            'product' => $stripeProduct->id,
            'unit_amount' => $request->price * 100, // cents
            'currency' => 'eur',
            'recurring' => [
                'interval' => $request->interval,
                'trial_period_days' => 3, // 🔥 Add trial days here
            ],
        ]);

        // Save in database
        $plan = new Plan();
        $plan->name = $request->name;
        $plan->stripe_product_id = $stripeProduct->id;
        $plan->stripe_price_id = $stripePrice->id;
        $plan->price = $request->price;
        $plan->interval = $request->interval;
        $plan->save();

        return redirect()->route('subscriptions-plans.index')
            ->with('success', 'Plan created successfully with 3 trial days.');

    } catch (Exception $e) {

        Log::error('Plan creation failed: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->withErrors('Failed to create plan: ' . $e->getMessage());
    }
}


    public function edit($plan)
    {
        $plan = Plan::find($plan);
        return view('backend.layouts.plans.edit', compact('plan'));
    }

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|min:0',
        'interval' => 'required|string|in:day,week,month,year',
    ]);

    try {
        $plan = Plan::findOrFail($id);

        // Update product on Stripe
        if ($plan->stripe_product_id) {
            $product = \Stripe\Product::retrieve($plan->stripe_product_id);
            $product->name = $request->name;
            $product->save();
        }

        // Create a new price on Stripe with trial days
        $stripePrice = \Stripe\Price::create([
            'product' => $plan->stripe_product_id,
            'unit_amount' => $request->price * 100,
            'currency' => 'eur',
            'recurring' => [
                'interval' => $request->interval,
                'trial_period_days' => 3, // 🔥 Add trial days here
            ],
        ]);

        // Update database record
        $plan->update([
            'name' => $request->name,
            'stripe_price_id' => $stripePrice->id,
            'price' => $request->price,
            'interval' => $request->interval,
        ]);

        return redirect()->route('subscriptions-plans.index')
            ->with('t-success', 'Plan updated successfully with 3 trial days.');
    } catch (\Exception $e) {

        Log::error('Plan update failed: ' . $e->getMessage());

        return redirect()
            ->back()
            ->withInput()
            ->with('t-error', '❌ Failed to update plan: ' . $e->getMessage());
    }
}


    public function destroy($id)
    {
        try {
            $plan = Plan::findOrFail($id);

            if ($plan->stripe_product_id) {
                StripeProduct::update($plan->stripe_product_id, ['active' => false]);
            }

            $plan->delete();

            return redirect()->route('subscriptions-plans.index')
                ->with('success', 'Plan deleted successfully and archived on Stripe.');
        } catch (Exception $e) {
            Log::error('Plan deletion failed: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to delete plan: ' . $e->getMessage());
        }
    }
}
