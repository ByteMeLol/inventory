<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\InsuranceClaim;
use App\Models\InsurancePolicy;
use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\InventoryUnit;
use App\Models\Role;
use App\Models\Sale;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth; //
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        // Target your exact layout file path (e.g., components.layouts.app or layouts.app)
        View::composer('components.layout', function ($view) {
            // Fetch all active companies from the database
            $companies = Company::orderBy('name')->get();

            // Pass the $companies variable directly into the layout file
            $view->with('companies', $companies);
        });

        View::composer('components.customers', function ($view) {
            $companyId = session('company_id');

            $customers = Customer::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->orderBy('company_name')->paginate(10);
            // counting number of customers
            // Pass the $companies variable directly into the layout file
            $view->with('customers', $customers);
        });

        View::composer('components.expenses', function ($view) {
            // Fetch all active companies from the database
            $expenses = Expense::orderBy('expense_date')->paginate(10);
            // sum of expenses for curren month with settlement status cleared
            $currentMonthClearedExpenses = $expenses->where('expense_date', '>=', now()->startOfMonth())->where('expense_date', '<=', now()->endOfMonth())->where('status', 'cleared')->sum('amount');
            // dd($currentMonthClearedExpenses);
            $currentMonthExpenses = $expenses->where('expense_date', '>=', now()->startOfMonth())->where('expense_date', '<=', now()->endOfMonth());
            // counting number of customers
            // Pass the $companies variable directly into the layout file
            // total expenses cleared last month
            $lastMonthClearedExpenses = $expenses->where('expense_date', '>=', now()->subMonth()->startOfMonth())->where('expense_date', '<=', now()->subMonth()->endOfMonth())->where('status', 'cleared')->sum('amount');
            $view->with('currentMonthClearedExpenses', $currentMonthClearedExpenses);
            $view->with('currentMonthExpenses', $currentMonthExpenses);
            $view->with('lastMonthClearedExpenses', $lastMonthClearedExpenses);
            $view->with('expenses', $expenses);
        });

        View::composer('components.sales', function ($view) {
            $companyId = session('company_id');

            // fetch customers and inventory for the active company
            $customers = Customer::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->orderBy('company_name')->get();

            $inventoryItems = Inventory::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->orderBy('description')->get();

            // sales records
            $sales = Sale::where('company_id', $companyId)->orderBy('created_at', 'desc')->paginate(10);
            // paid sales in a current month

            $paidsales = $sales->where('created_at', '>=', now()->startOfMonth())->where('created_at', '<=', now()->endOfMonth())->where('status', 'paid')->sum('total_amount');

            $view->with('customers', $customers);
            $view->with('inventoryItems', $inventoryItems);
            $view->with('sales', $sales);
            $view->with('paidsales', $paidsales);
        });

        View::composer('components.inventory', function ($view) {
            $companyId = session('company_id') ?: Auth::user()?->company_id;

            $inventory = Inventory::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->orderBy('created_at', 'desc')->paginate(10);

            $lowStockCount = Inventory::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->whereColumn('stock_count', '<=', 'safety_threshold')->count();

            $view->with('lowStockCount', $lowStockCount);
            $view->with('inventory', $inventory);
            $view->with('inventoryCategories', InventoryCategory::where('company_id', $companyId)->where('is_active', true)->orderBy('name')->get());
            $view->with('inventoryUnits', InventoryUnit::where('company_id', $companyId)->where('is_active', true)->orderBy('name')->get());
            $warehouses = Warehouse::with('locations')
                ->where('company_id', $companyId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
            $view->with('warehouses', $warehouses);
        });

        View::composer('components.dashboard', function ($view) {
            $companyId = session('company_id');
            $currentCompany = $companyId ? Company::find($companyId) : null;

            $activeInventoryCount = Inventory::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->sum('stock_count');

            $lowStockCount = Inventory::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->whereColumn('stock_count', '<=', 'safety_threshold')->count();

            $monthlyOrders = Sale::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount');

            $teamAccounts = User::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->count();

            $revenueTrendLabels = [];
            $revenueTrendValues = [];

            for ($monthsBack = 5; $monthsBack >= 0; $monthsBack--) {
                $monthStart = now()->copy()->subMonthsNoOverflow($monthsBack)->startOfMonth();
                $monthEnd = now()->copy()->subMonthsNoOverflow($monthsBack)->endOfMonth();

                $revenueTrendLabels[] = $monthStart->format('M');
                $revenueTrendValues[] = Sale::when($companyId, function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })->whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_amount');
            }

            $inventoryShare = Inventory::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->get()
                ->groupBy('category')
                ->map(function ($items) {
                    return $items->sum('stock_count');
                })
                ->sortDesc();

            $inventoryShareLabels = $inventoryShare->keys()->values()->all();
            $inventoryShareValues = $inventoryShare->values()->all();

            $lowStockItems = Inventory::with('company')
                ->when($companyId, function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })
                ->whereColumn('stock_count', '<=', 'safety_threshold')
                ->orderBy('stock_count')
                ->limit(5)
                ->get();

            $recentSales = Sale::with('customer')
                ->when($companyId, function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            $view->with('currentCompany', $currentCompany);
            $view->with('activeInventoryCount', $activeInventoryCount);
            $view->with('lowStockCount', $lowStockCount);
            $view->with('monthlyOrders', $monthlyOrders);
            $view->with('teamAccounts', $teamAccounts);
            $view->with('revenueTrendLabels', $revenueTrendLabels);
            $view->with('revenueTrendValues', $revenueTrendValues);
            $view->with('inventoryShareLabels', $inventoryShareLabels);
            $view->with('inventoryShareValues', $inventoryShareValues);
            $view->with('lowStockItems', $lowStockItems);
            $view->with('recentSales', $recentSales);
        });

        View::composer('components.insurance', function ($view) {
            $companyId = session('company_id');
            $isAdmin = Auth::check() && Auth::user()->role_id == 1;

            $customers = Customer::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->orderBy('company_name')->get();

            $policyOptions = InsurancePolicy::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->orderBy('policy_number')->get();

            $policies = InsurancePolicy::with('customer')
                ->when($companyId, function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $claims = InsuranceClaim::with('insurancePolicy.customer')
                ->when($companyId, function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'claims_page');

            $activePolicies = InsurancePolicy::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('status', 'active')->count();

            $inactivePolicies = InsurancePolicy::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->whereIn('status', ['inactive', 'expired', 'cancelled', 'lapsed'])->count();

            $totalCoverage = InsurancePolicy::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->sum('coverage_amount');

            $openClaims = InsuranceClaim::when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->whereIn('status', ['pending', 'reviewing'])->count();

            $view->with('customers', $customers);
            $view->with('policyOptions', $policyOptions);
            $view->with('policies', $policies);
            $view->with('claims', $claims);
            $view->with('activePolicies', $activePolicies);
            $view->with('inactivePolicies', $inactivePolicies);
            $view->with('totalCoverage', $totalCoverage);
            $view->with('openClaims', $openClaims);
            $view->with('isAdmin', $isAdmin);
        });

        View::composer('components.staff', function ($view) {
            /** @var User|null $user */
            $user = Auth::user();

            if (! $user || ! $user->canManageUsers()) {
                abort(403, 'Unauthorized user management request.');
            }

            $companies = $user->isSuperAdmin()
                ? Company::orderBy('name')->get()
                : Company::where('id', $user->company_id)->orderBy('name')->get();

            $view->with('staffMembers', User::with(['company', 'role'])->orderBy('created_at', 'desc')->paginate(10));
            $view->with('roles', Role::orderBy('name')->get());
            $view->with('companies', $companies);
            $view->with('canManageUsers', true);
            $view->with('currentUser', $user);
        });

        View::composer('components.reports', function ($view) {
            $selectedCompanyId = request('company_id') ?: session('company_id');
            $selectedStatementClass = request('statement_class', 'sales_summary');
            $isAdmin = Auth::check() && Auth::user()->role_id == 1;

            $companies = Company::orderBy('name')->get();
            $selectedCompany = $selectedCompanyId ? Company::find($selectedCompanyId) : null;

            $companySales = Sale::with('customer')
                ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                    $query->where('company_id', $selectedCompanyId);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $companyExpenses = Expense::when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            })->orderBy('expense_date', 'desc')->get();

            $companyInventory = Inventory::when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            })->orderBy('created_at', 'desc')->get();

            $salesTotal = $companySales->sum('total_amount');
            $expensesTotal = $companyExpenses->sum('amount');
            $inventoryValue = $companyInventory->sum(function ($item) {
                return ($item->unit_price ?? 0) * ($item->stock_count ?? 0);
            });

            $insurancePolicies = InsurancePolicy::with('customer')
                ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                    $query->where('company_id', $selectedCompanyId);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $insuranceClaims = InsuranceClaim::with('insurancePolicy.customer')
                ->when($selectedCompanyId, function ($query) use ($selectedCompanyId) {
                    $query->where('company_id', $selectedCompanyId);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $statementRows = match ($selectedStatementClass) {
                'expense_outlays' => $companyExpenses->map(function ($expense) {
                    return (object) [
                        'pointer' => $expense->reference,
                        'compiled_by' => $expense->expense_name,
                        'scope' => $expense->category,
                        'output' => 'TZS '.number_format($expense->amount, 2),
                    ];
                }),
                'inventory_valuation' => $companyInventory->map(function ($item) {
                    return (object) [
                        'pointer' => $item->sku,
                        'compiled_by' => $item->description,
                        'scope' => $item->category,
                        'output' => 'TZS '.number_format(($item->unit_price ?? 0) * ($item->stock_count ?? 0), 2),
                    ];
                }),
                'insurance_summary' => $insurancePolicies->map(function ($policy) {
                    return (object) [
                        'pointer' => $policy->policy_number,
                        'compiled_by' => $policy->customer->company_name ?? 'Policy Holder',
                        'scope' => $policy->policy_type.' · '.$policy->status,
                        'output' => 'TZS '.number_format($policy->premium_amount, 2),
                    ];
                }),
                default => $companySales->map(function ($sale) {
                    return (object) [
                        'pointer' => $sale->invoice_number,
                        'compiled_by' => $sale->customer->company_name ?? 'Customer',
                        'scope' => 'Sales',
                        'output' => 'TZS '.number_format($sale->total_amount, 2),
                    ];
                }),
            };

            $statementClassLabel = match ($selectedStatementClass) {
                'expense_outlays' => 'Factory Overheads & Expenses',
                'inventory_valuation' => 'Stock Item Valuation Ledger',
                'insurance_summary' => 'Insurance Policy & Claims Summary',
                default => 'Sales Orders Ledger Summary',
            };

            $view->with('companies', $companies);
            $view->with('selectedCompany', $selectedCompany);
            $view->with('selectedCompanyId', $selectedCompanyId);
            $view->with('selectedStatementClass', $selectedStatementClass);
            $view->with('statementClassLabel', $statementClassLabel);
            $view->with('isAdmin', $isAdmin);
            $view->with('companySales', $companySales);
            $view->with('companyExpenses', $companyExpenses);
            $view->with('companyInventory', $companyInventory);
            $view->with('insurancePolicies', $insurancePolicies);
            $view->with('insuranceClaims', $insuranceClaims);
            $view->with('statementRows', $statementRows);
            $view->with('salesTotal', $salesTotal);
            $view->with('expensesTotal', $expensesTotal);
            $view->with('inventoryValue', $inventoryValue);
        });
    }
}
