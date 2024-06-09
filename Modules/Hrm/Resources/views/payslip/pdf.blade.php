@php
    $company_settings = getCompanyAllSetting();
@endphp
<div class="modal-body">
    <div class="row">
        <div class="col-form-label">
            <div class="text-md-end">
                <a class="btn btn-sm btn-primary text-white" data-bs-toggle="tooltip" data-bs-toggle="bottom"
                    title="{{ __('Download') }}" onclick="saveAsPDF()"><span class="ti ti-download"></span></a>

                <a href="{{ route('payslip.send', [$employee->id, $payslip->salary_month]) }}"
                    class="btn btn-sm btn-warning"><span class="ti ti-send"></span></a>
            </div>
            <div class="invoice" id="printableArea">
                <div class="invoice-number">
                    <img src="{{ get_file(sidebar_logo()) }}" width="170px;">
                </div>
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                            </div>
                            <hr>
                            <div class="row text-sm">
                                <div class="col-md-6">
                                    <address>
                                        <strong>{{ __('Name') }} :</strong> {{ $employee->name }}<br>
                                        <strong>{{ __('Position') }} :</strong> {{ __('Employee') }}<br>
                                        <strong>{{ __('Salary Date') }} :</strong>
                                        {{ company_date_formate($payslip->created_at) }}<br>
                                    </address>
                                </div>
                                <div class="col-md-6 text-end">
                                    <address>
                                        <strong>{{ !empty($company_settings['company_name']) ? $company_settings['company_name'] : '' }}
                                        </strong><br>
                                        {{ !empty($company_settings['company_address']) ? $company_settings['company_address'] : '' }}
                                        ,
                                        {{ !empty($company_settings['company_city']) ? $company_settings['company_city'] : '' }},<br>
                                        {{ !empty($company_settings['company_state']) ? $company_settings['company_state'] : '' }}<br>
                                        <strong>{{ __('Salary Slip') }} :</strong>
                                        {{ company_date_formate($payslip->salary_month) }}<br>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table  table-md">
                                    <tbody>
                                        <tr class="font-bold">
                                            <th>{{ __('Earning') }}</th>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th class="text-right">{{ __('Amount') }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Basic Salary') }}</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td class="text-right">
                                                {{ currency_format($payslip->basic_salary) }}</td>
                                        </tr>
                                        @php
                                            $allowances = json_decode($payslipDetail['payslip']->allowance);
                                        @endphp
                                        @foreach ($allowances as $allowance)
                                            <tr>
                                                <td>{{ __('Allowance') }}</td>
                                                <td>{{ $allowance->title }}</td>
                                                <td>{{ ucfirst($allowance->type) }}</td>
                                                @if ($allowance->type != 'percentage')
                                                    <td class="text-right">
                                                        {{ currency_format($allowance->amount) }}</td>
                                                @else
                                                    <td class="text-right">{{ $allowance->amount }}%
                                                        ({{ currency_format(($allowance->amount * $payslip->basic_salary) / 100) }})
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        @php
                                            $commissions = json_decode($payslipDetail['payslip']->commission);
                                        @endphp
                                        @foreach ($commissions as $commission)
                                            <tr>
                                                <td>{{ __('Commission') }}</td>
                                                <td>{{ $commission->title }}</td>
                                                <td>{{ ucfirst($commission->type) }}</td>
                                                @if ($commission->type != 'percentage')
                                                    <td class="text-right">
                                                        {{ currency_format($commission->amount) }}</td>
                                                @else
                                                    <td class="text-right">{{ $commission->amount }}%
                                                        ({{ currency_format(($commission->amount * $payslip->basic_salary) / 100) }})
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        @php
                                            $other_payments = json_decode($payslipDetail['payslip']->other_payment);
                                        @endphp
                                        @foreach ($other_payments as $other_payment)
                                            <tr>
                                                <td>{{ __('Other Payment') }}</td>
                                                <td>{{ $other_payment->title }}</td>
                                                <td>{{ ucfirst($other_payment->type) }}</td>
                                                @if ($other_payment->type != 'percentage')
                                                    <td class="text-right">
                                                        {{ currency_format($other_payment->amount) }}</td>
                                                @else
                                                    <td class="text-right">{{ $other_payment->amount }}%
                                                        ({{ currency_format(($other_payment->amount * $payslip->basic_salary) / 100) }})
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        @php
                                            $overtimes = json_decode($payslipDetail['payslip']->overtime);
                                        @endphp
                                        @foreach ($overtimes as $overtime)
                                            <tr>
                                                <td>{{ __('OverTime') }}</td>
                                                <td>{{ $overtime->title }}</td>
                                                <td>-</td>
                                                <td class="text-right">
                                                    {{ currency_format($overtime->number_of_days * $overtime->hours * $overtime->rate) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        @php
                                            $company_contributions = json_decode($payslipDetail['payslip']->company_contribution);
                                        @endphp
                                        @foreach ($company_contributions as $company_contribution)
                                            <tr>
                                                <td>{{ __('Company Contribution') }}</td>
                                                <td>{{ $company_contribution->title }}</td>
                                                <td>{{ ucfirst($company_contribution->type) }}</td>
                                                @if ($company_contribution->type != 'percentage')
                                                    <td class="text-right">
                                                        {{ currency_format($company_contribution->amount) }}</td>
                                                @else
                                                    <td class="text-right">{{ $company_contribution->amount }}%
                                                        ({{ currency_format(($company_contribution->amount * $payslip->basic_salary) / 100) }})
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tbody>
                                        <tr class="font-bold">
                                            <th>{{ __('Deduction') }}</th>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('type') }}</th>
                                            <th class="text-right">{{ __('Amount') }}</th>
                                        </tr>

                                        @php
                                            $loans = json_decode($payslipDetail['payslip']->loan);
                                        @endphp
                                        @foreach ($loans as $loan)
                                            <tr>
                                                <td>{{ __('Loan') }}</td>
                                                <td>{{ $loan->title }}</td>
                                                <td>{{ ucfirst($loan->type) }}</td>
                                                @if ($loan->type != 'percentage')
                                                    <td class="text-right">
                                                        {{ currency_format($loan->amount) }}</td>
                                                @else
                                                    <td class="text-right">{{ $loan->amount }}%
                                                        ({{ currency_format(($loan->amount * $payslip->basic_salary) / 100) }})
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        @php
                                            $saturation_deductions = json_decode(
                                                $payslipDetail['payslip']->saturation_deduction,
                                            );
                                        @endphp
                                        @foreach ($saturation_deductions as $saturation_deduction)
                                            <tr>
                                                <td>{{ __('Saturation Deduction') }}</td>
                                                <td>{{ $saturation_deduction->title }}</td>
                                                <td>{{ ucfirst($saturation_deduction->type) }}</td>
                                                @if ($saturation_deduction->type != 'percentage')
                                                    <td class="text-right">
                                                        {{ currency_format($saturation_deduction->amount) }}
                                                    </td>
                                                @else
                                                    <td class="text-right">{{ $saturation_deduction->amount }}%
                                                        ({{ currency_format(($saturation_deduction->amount * $payslip->basic_salary) / 100) }})
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-8">

                                </div>
                                <div class="col-lg-4 text-right text-sm">
                                    <div class="invoice-detail-item pb-2">
                                        <div class="invoice-detail-name font-weight-bold">{{ __('Total Earning') }}
                                        </div>
                                        <div class="invoice-detail-value">
                                            {{ currency_format($payslipDetail['totalEarning']) }}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name font-weight-bold">{{ __('Total Deduction') }}
                                        </div>
                                        <div class="invoice-detail-value">
                                            {{ currency_format($payslipDetail['totalDeduction']) }}</div>
                                    </div>
                                    <hr class="mt-2 mb-2">

                                    <div class="invoice-detail-item pb-2">
                                        <div class="invoice-detail-name font-weight-bold">{{ __('Taxable Earning') . ' = ' . __('Total Earning − Total Deduction') }}
                                        </div>
                                        <div class="invoice-detail-value">
                                            {{ currency_format($payslipDetail['taxable_earning']) }}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name font-weight-bold">{{ __('Tax') . ' = ' . __('Total Earning × ') . $payslipDetail['tax_rate'] . '%' }}
                                        </div>
                                        <div class="invoice-detail-value">
                                            {{ currency_format($payslipDetail['tax_amount']) }}</div>
                                    </div>
                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name font-weight-bold">{{ __('Net Salary') . ' = ' . __('Taxable Earning − Tax') }}</div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">
                                            {{ currency_format_with_sym($payslip->net_payble) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-md-right pb-2 text-sm">
                    <div class="float-lg-left mb-lg-0 mb-3 ">
                        <p class="mt-2">{{ __('Employee Signature') }}</p>
                    </div>
                    <p class="mt-2 "> {{ __('Paid By') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="{{ asset('Modules/Hrm/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>
<script>
    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var opt = {
            margin: 0.2,
            filename: '{{ $employee->name }}',
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 4,
                dpi: 72,
                letterRendering: true
            },
            jsPDF: {
                unit: 'in',
                format: 'A4'
            }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>
