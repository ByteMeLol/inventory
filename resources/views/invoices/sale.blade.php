<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Invoice') }} - {{ $sale->invoice_number }}</title>
    <style>
        @page { margin: 24px; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #0f172a;
            margin: 0;
        }
        .header, .footer { width: 100%; }
        .header { margin-bottom: 18px; }
        .title { font-size: 24px; font-weight: bold; margin: 0; }
        .muted { color: #64748b; }
        .panel {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 14px;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 8px; border-bottom: 1px solid #e2e8f0; }
        th { text-align: left; background: #f8fafc; font-size: 11px; text-transform: uppercase; letter-spacing: .04em; }
        .right { text-align: right; }
        .total-row td { border-bottom: none; font-weight: bold; }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 10px;
            text-transform: uppercase;
            background: #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <p class="title">{{ $company->name }}</p>
                    <div class="muted">{{ $settings['branch_name'] }} | {{ $settings['branch_code'] }}</div>
                    <div class="muted">{{ $settings['company_phone'] }}</div>
                </td>
                <td class="right">
                    <div class="badge">{{ __($sale->status) }}</div>
                    <p style="margin: 10px 0 0 0;"><strong>{{ __('Invoice:') }}</strong> {{ $sale->invoice_number }}</p>
                    <div class="muted">{{ __('Date:') }} {{ $sale->created_at?->format('d M Y, h:i A') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="panel">
        <table>
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <strong>{{ __('Bill To') }}</strong><br>
                    {{ $sale->customer->company_name }}<br>
                    @if($sale->customer->phone)
                        <span class="muted">{{ $sale->customer->phone }}</span><br>
                    @endif
                    @if($sale->customer->email)
                        <span class="muted">{{ $sale->customer->email }}</span>
                    @endif
                </td>
                <td style="width: 50%; vertical-align: top;" class="right">
                    <strong>{{ __('Sale Details') }}</strong><br>
                    <span class="muted">{{ __('Billing Terms:') }} {{ __($sale->billing_terms) }}</span><br>
                    <span class="muted">{{ __('Currency:') }} {{ $settings['currency'] }}</span><br>
                    <span class="muted">{{ __('Timezone:') }} {{ $settings['timezone'] }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="panel">
        <table>
            <thead>
                <tr>
                    <th>{{ __('Description') }}</th>
                    <th class="right">{{ __('Qty') }}</th>
                    <th class="right">{{ __('Unit Price') }}</th>
                    <th class="right">{{ __('Line Total') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $sale->item_summary }}
                        @if($sale->inventory)
                            <div class="muted" style="font-size: 10px; margin-top: 4px;">{{ __('SKU:') }} {{ $sale->inventory->sku }}</div>
                        @endif
                    </td>
                    <td class="right">{{ $sale->quantity }}</td>
                    <td class="right">{{ $settings['currency'] }} {{ number_format($sale->unit_price, 2) }}</td>
                    <td class="right">{{ $settings['currency'] }} {{ number_format($sale->total_amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" class="right">{{ __('Subtotal') }}</td>
                    <td class="right">{{ $settings['currency'] }} {{ number_format($sale->total_amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" class="right">{{ __('VAT / Tax') }} ({{ $settings['tax_rate'] }}%)</td>
                    <td class="right">{{ __('Included in system pricing') }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" class="right">{{ __('Grand Total') }}</td>
                    <td class="right">{{ $settings['currency'] }} {{ number_format($sale->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer muted" style="margin-top: 18px; font-size: 10px;">
        {{ __('Generated for :company in :timezone.', ['company' => $company->name, 'timezone' => $settings['timezone']]) }}
    </div>
</body>
</html>
