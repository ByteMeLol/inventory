<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Sale;
use App\Models\Company;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalesController extends Controller
{
    //
    public function store(Request $request){
        //dd($request->all());
        $validatedData = $request->validate([
            'customer_id'=>[
                'required',
                Rule::exists('customers', 'id')->where(function ($query) {
                    return $query->where('company_id', session('company_id'));
                }),
            ],
            'inventory_id'=>[
                'required',
                Rule::exists('inventory', 'id')->where(function ($query) {
                    return $query->where('company_id', session('company_id'));
                }),
            ],
            'invoice_number'=>[
                'required',
                'string',
                'max:255',
                Rule::unique('sales', 'invoice_number')->where(function ($query) {
                    return $query->where('company_id', session('company_id'));
                }),
            ],
            'billing_terms'=>['required','string','max:255'],
            'items_summary'=>['nullable','string','max:255'],
            'quantity'=>['required','integer','min:1'],
            'unit_price'=>['required','numeric','min:0'],
            'total_amount'=>['required','numeric'],
            
        ]);
        //checking status of a sale
        if($validatedData['billing_terms'] == 'immediate'||$validatedData['billing_terms'] == 'cash'){
            $status = 'paid';
        }
        else{
            $status = 'pending';
        }
        DB::transaction(function () use ($validatedData, $status) {
            $inventoryItem = Inventory::where('company_id', session('company_id'))
                ->where('id', $validatedData['inventory_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($inventoryItem->stock_count < $validatedData['quantity']) {
                abort(422, 'Not enough stock available for this item.');
            }

            $inventoryItem->decrement('stock_count', $validatedData['quantity']);

            Sale::create([
                'customer_id' => $validatedData['customer_id'],
                'company_id' => session('company_id'),
                'inventory_id' => $validatedData['inventory_id'],
                'invoice_number' => $validatedData['invoice_number'],
                'billing_terms' => $validatedData['billing_terms'],
                'quantity' => $validatedData['quantity'],
                'unit_price' => $validatedData['unit_price'],
                'item_summary' => $validatedData['items_summary'] ?: $inventoryItem->description,
                'total_amount' => $validatedData['total_amount'],
                'status' => $status,
            ]);
        });
        return redirect()->back()->with('success', 'Sale record created successfully.');
    }
    public function index(){
        $sales = Sale::with(['customer', 'inventory'])->where('company_id', session('company_id'))->get();
        return view('sales', compact('sales'));
    }
    public function updateStatus(Request $request, Sale $sale)
    {
        

        // Ensure the sale belongs to the current company
        if ($sale->company_id !== session('company_id')) {
            abort(403, 'Unauthorized action.');
        }

        $sale->status = 'paid';
        $sale->save();

        return redirect()->back()->with('success', 'Sale status updated successfully.');
    }

    public function downloadInvoice(Sale $sale)
    {
        if ($sale->company_id !== session('company_id')) {
            abort(403, 'Unauthorized action.');
        }

        $sale->load(['customer', 'inventory', 'company']);
        $company = Company::findOrFail(session('company_id'));
        $settings = array_merge([
            'currency' => 'TZS',
            'tax_rate' => 18,
            'timezone' => 'Africa/Dar_es_Salaam',
            'branch_name' => 'Dar es Salaam HQ',
            'branch_code' => 'TZ-DSM-001',
            'company_phone' => '+255 754 000 000',
        ], $company->settings ?? []);

        $filename = 'invoice-' . preg_replace('/[^A-Za-z0-9\-_]/', '-', $sale->invoice_number) . '.pdf';

        return Pdf::loadView('invoices.sale', [
            'sale' => $sale,
            'company' => $company,
            'settings' => $settings,
        ])->download($filename);
    }
}
