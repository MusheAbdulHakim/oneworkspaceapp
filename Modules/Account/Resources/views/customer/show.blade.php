@extends('layouts.main')
@section('page-title')
{{ __('Customer-Detail') }}
@endsection
@section('page-breadcrumb')
{{ __('Customer') }},{{ $customer['name'] }}
@endsection
@push('css')
<style>
   .cus-card {
   min-height: 204px;
   }
</style>
@endpush
@push('scripts')
<script>
   $(document).on('click', '#billing_data', function() {
       $("[name='shipping_name']").val($("[name='billing_name']").val());
       $("[name='shipping_country']").val($("[name='billing_country']").val());
       $("[name='shipping_state']").val($("[name='billing_state']").val());
       $("[name='shipping_city']").val($("[name='billing_city']").val());
       $("[name='shipping_phone']").val($("[name='billing_phone']").val());
       $("[name='shipping_zip']").val($("[name='billing_zip']").val());
       $("[name='shipping_address']").val($("[name='billing_address']").val());
   })
</script>
<script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
<script>
   var filename = $('#filename').val();

   function saveAsPDF() {
       var element = document.getElementById('printableArea');
       var opt = {
           margin: 0.3,
           filename: filename,
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
<script>
   $(".apply_btn").click(function() {
       var from_date = $('.from_date').val();
       var until_date = $('.until_date').val();
       var id = "{{ $customer['id'] }}";
       var type = "statement_tab";
       $.ajax({
           url: '{{ route('customer.statement', $customer['id']) }}',
           type: 'POST',
           data: {
               "id": id,
               "from_date": from_date,
               "until_date": until_date,
               "type": type,
               "_token": "{{ csrf_token() }}",
           },
           success: function(data) {
               $("#statement-history .list").empty();

               // Initialize the total amount
               var totalAmount = 0;

               // Iterate over the data array and build the rows
               data.data.forEach(function(item) {


                   var rowHtml = '<tr>' +
                       '<td>' + item.date + '</td>' +
                       '<td>' + item.invoiceno + '</td>' +
                       '<td>' + item.payment_type + '</td>' +
                       '<td>' + data.currencySymbol + item.amount + '</td>' +
                       '</tr>';
                   $("#statement-history .list").append(rowHtml);

                   // Update the total amount
                   totalAmount += parseFloat(item.amount);
               });

               // Display the total amount in the total row
               $("#total-amount").html('<strong>' + data.currencySymbol + totalAmount.toFixed(2) +
                   '</strong>');
           }
       });

   });
</script>
@endpush
@php
$company_settings = getCompanyAllSetting();
$company_settings['invoice_shipping_display'] = isset($company_settings['invoice_shipping_display']) ? $company_settings['invoice_shipping_display'] : 'off';
$company_settings['proposal_shipping_display'] = isset($company_settings['proposal_shipping_display']) ? $company_settings['proposal_shipping_display'] : 'off';
@endphp
@section('page-action')
<div>
   @php
   $user_id = !empty($customer->user_id) ? $customer->user_id : null;
   @endphp
   @permission('invoice create')
   <a href="{{ route('invoice.create', $customer->id) }}" class="btn btn-sm btn-primary">
   {{ __('Create Invoice') }}
   </a>
   @endpermission
   @permission('customer create')
   @if (!empty($user_id))
   <a href="{{ route('proposal.create', $customer->id) }}" class="btn btn-sm btn-primary">
   {{ __('Create Proposal') }}
   </a>
   @endif
   @endpermission
   @if (module_is_active('Retainer'))
   <a href="{{ route('retainer.create', $customer->id) }}" class="btn btn-sm btn-primary">
   {{ __('Create retainer') }}
   </a>
   @endif
   @if ($customer->user->is_disable == 1)
   @permission('customer edit')
   @if (!empty($user_id))
   <a class="btn btn-sm btn-primary action-btn px-1" data-url="{{ route('customer.edit', $user_id) }}"
      data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title=""
      data-title="{{ __('Edit Customer') }}" data-bs-original-title="{{ __('Edit') }}">
   <i class="ti ti-pencil"></i>
   </a>
   @endif
   @endpermission
   @endif
</div>
@endsection
@section('content')
<div class="page-header">
   <div class="page-block">
      <div class="row align-items-center">
         <div class="col-md-4">
         </div>
         <div class="col-md-8 mt-4">
            <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
               <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="customer-details-tab" data-bs-toggle="pill"
                     data-bs-target="#customer-details" type="button">{{ __('Details') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="customer-proposal-tab" data-bs-toggle="pill"
                     data-bs-target="#customer-proposal" type="button">{{ __('Proposals') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="customer-invoice-tab" data-bs-toggle="pill"
                     data-bs-target="#customer-invoice" type="button">{{ __('Invoices') }}</button>
               </li>
               @stack('customer_retainer_tab')
               <li class="nav-item" role="presentation">
                  <button class="nav-link " id="customer-revenue-tab" data-bs-toggle="pill"
                     data-bs-target="#customer-revenue" type="button">{{ __('Revenue') }}</button>
               </li>
               @stack('customer_project_tab')
               <li class="nav-item" role="presentation">
                  <button class="nav-link " id="statement-tab" data-bs-toggle="pill" data-bs-target="#statement"
                     type="button">{{ __('Statement') }}</button>
               </li>
            </ul>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-sm-12 ">
      <div class="row">
         <div class="col-lg-12">
            <div class="tab-content" id="pills-tabContent">
               <div class="tab-pane fade active show" id="customer-details" role="tabpanel"
                  aria-labelledby="pills-user-tab-1">
                  <div class="row">
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card customer-detail-box">
                           <div class="card-body cus-card">
                              <h5 class="card-title">{{ __('Customer Info') }}</h5>
                              <p class="card-text mb-0">{{ $customer['name'] }}</p>
                              <p class="card-text mb-0">{{ $customer['email'] }}</p>
                              <p class="card-text mb-0">{{ $customer['contact'] }}</p>
                              @if (!empty($customFields) && count($customer->customField) > 0)
                              @foreach ($customFields as $field)
                              <p class="card-text mb-0">
                                 <strong>{{ $field->name }} :
                                 </strong>{{ !empty($customer->customField[$field->id]) ? $customer->customField[$field->id] : '-' }}
                              </p>
                              @endforeach
                              @endif
                              @stack('show_electronic_address')
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card customer-detail-box">
                           <div class="card-body cus-card">
                              <h5 class="card-title">{{ __('Billing Info') }}</h5>
                              <p class="card-text mb-0">{{ $customer['billing_name'] }}</p>
                              <p class="card-text mb-0">{{ $customer['billing_phone'] }}</p>
                              <p class="card-text mb-0">{{ $customer['billing_address'] }}</p>
                              <p class="card-text mb-0">
                                 {{ $customer['billing_city'] . ', ' . $customer['billing_state'] . ', ' . $customer['billing_country'] }}
                              </p>
                              <p class="card-text mb-0">{{ $customer['billing_zip'] }}</p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card customer-detail-box">
                           <div class="card-body cus-card">
                              <h5 class="card-title">{{ __('Shipping Info') }}</h5>
                              @if ($company_settings['invoice_shipping_display'] == 'on' || $company_settings['proposal_shipping_display'] == 'on')
                              <p class="card-text mb-0">{{ $customer['shipping_name'] }}</p>
                              <p class="card-text mb-0">{{ $customer['shipping_phone'] }}</p>
                              <p class="card-text mb-0">{{ $customer['shipping_address'] }}</p>
                              <p class="card-text mb-0">
                                 {{ $customer['shipping_city'] . ', ' . $customer['shipping_state'] . ', ' . $customer['shipping_country'] }}
                              </p>
                              <p class="card-text mb-0">{{ $customer['shipping_zip'] }}</p>
                              @endif
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="card pb-0">
                           <div class="card-body">
                              <h5 class="card-title">{{ __('Company Info') }}</h5>
                              <div class="row">
                                 @php
                                 $totalInvoiceSum = $customer->customerTotalInvoiceSum($customer['id']);
                                 $totalInvoice = $customer->customerTotalInvoice($customer['id']);
                                 $averageSale = $totalInvoiceSum != 0 ? $totalInvoiceSum / $totalInvoice : 0;
                                 @endphp
                                 <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                       <p class="card-text mb-0">{{ __('Customer Id') }}</p>
                                       <h6 class="report-text mb-3">
                                          {{ Modules\Account\Entities\Customer::customerNumberFormat($customer['customer_id']) }}
                                       </h6>
                                       <p class="card-text mb-0">{{ __('Total Sum of Invoices') }}</p>
                                       <h6 class="report-text mb-0">
                                          {{ currency_format_with_sym($totalInvoiceSum) }}
                                       </h6>
                                    </div>
                                 </div>
                                 <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                       <p class="card-text mb-0">{{ __('Date of Creation') }}</p>
                                       <h6 class="report-text mb-3">
                                          {{ company_date_formate($customer['created_at']) }}
                                       </h6>
                                       <p class="card-text mb-0">{{ __('Quantity of Invoice') }}</p>
                                       <h6 class="report-text mb-0">{{ $totalInvoice }}</h6>
                                    </div>
                                 </div>
                                 <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                       <p class="card-text mb-0">{{ __('Balance') }}</p>
                                       <h6 class="report-text mb-3">
                                          {{ currency_format_with_sym($customer['balance']) }}
                                       </h6>
                                       <p class="card-text mb-0">{{ __('Average Sales') }}</p>
                                       <h6 class="report-text mb-0">
                                          {{ currency_format_with_sym($averageSale) }}
                                       </h6>
                                    </div>
                                 </div>
                                 <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                       <p class="card-text mb-0">{{ __('Overdue') }}</p>
                                       <h6 class="report-text mb-3">
                                          {{ currency_format_with_sym($customer->customerOverdue($customer['id'])) }}
                                       </h6>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="customer-proposal" role="tabpanel"
                  aria-labelledby="pills-user-tab-2">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Proposal') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="vendor_project">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Proposal') }}</th>
                                          <th>{{ __('Issue Date') }}</th>
                                          <th>{{ __('Amount') }}</th>
                                          <th>{{ __('Status') }}</th>
                                          @if (Laratrust::hasPermission('proposal edit') ||
                                          Laratrust::hasPermission('proposal delete') ||
                                          Laratrust::hasPermission('proposal show'))
                                          <th width="10%"> {{ __('Action') }}</th>
                                          @endif
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @forelse ($customer->customerProposal($customer->user_id) as $proposal)
                                       <tr>
                                          <td class="Id">
                                             @permission('proposal show')
                                             <a href="{{ route('proposal.show', \Crypt::encrypt($proposal->id)) }}"
                                                class="btn btn-outline-primary">{{ \App\Models\Proposal::proposalNumberFormat($proposal->proposal_id) }}
                                             </a>
                                             @else
                                             <a class="btn btn-outline-primary">{{ \App\Models\Proposal::proposalNumberFormat($proposal->proposal_id) }}
                                             </a>
                                             @endpermission
                                          </td>
                                          <td>{{ company_date_formate($proposal->issue_date) }}</td>
                                          <td>{{ currency_format_with_sym($proposal->getTotal()) }}
                                          </td>
                                          <td>
                                             @if ($proposal->status == 0)
                                             <span
                                                class="badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                             @elseif($proposal->status == 1)
                                             <span
                                                class="badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                             @elseif($proposal->status == 2)
                                             <span
                                                class="badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                             @elseif($proposal->status == 3)
                                             <span
                                                class="badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                             @elseif($proposal->status == 4)
                                             <span
                                                class="badge bg-success p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                             @endif
                                          </td>
                                          @if (Laratrust::hasPermission('proposal edit') ||
                                          Laratrust::hasPermission('proposal delete') ||
                                          Laratrust::hasPermission('proposal show'))
                                          <td class="Action">
                                             <span>
                                                @if ($proposal->is_convert == 0)
                                                @permission('proposal convert invoice')
                                                <div class="action-btn bg-success ms-2">
                                                   {!! Form::open([
                                                   'method' => 'get',
                                                   'route' => ['proposal.convert', $proposal->id],
                                                   'id' => 'proposal-form-' . $proposal->id,
                                                   ]) !!}
                                                   <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                      data-bs-toggle="tooltip"
                                                      title=""
                                                      data-bs-original-title="{{ __('Convert to Invoice') }}"
                                                      aria-label="Delete"
                                                      data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                      data-confirm-yes="proposal-form-{{ $proposal->id }}">
                                                   <i
                                                      class="ti ti-exchange text-white"></i>
                                                   </a>
                                                   {{ Form::close() }}
                                                </div>
                                                @endpermission
                                                @else
                                                @permission('invoice show')
                                                <div class="action-btn bg-success ms-2">
                                                   <a href="{{ route('invoice.show', \Crypt::encrypt($proposal->converted_invoice_id)) }}"
                                                      class="mx-3 btn btn-sm  align-items-center"
                                                      data-bs-toggle="tooltip"
                                                      title="{{ __('Already convert to Invoice') }}">
                                                   <i
                                                      class="ti ti-eye text-white"></i>
                                                   </a>
                                                </div>
                                                @endpermission
                                                @endif
                                                @permission('duplicate proposal')
                                                <div class="action-btn bg-secondary ms-2">
                                                   {!! Form::open([
                                                   'method' => 'get',
                                                   'route' => ['proposal.duplicate', $proposal->id],
                                                   'id' => 'duplicate-form-' . $proposal->id,
                                                   ]) !!}
                                                   <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                      data-bs-toggle="tooltip"
                                                      title=""
                                                      data-bs-original-title="{{ __('Duplicate') }}"
                                                      aria-label="Delete"
                                                      data-text="{{ __('You want to confirm duplicate this invoice. Press Yes to continue or Cancel to go back') }}"
                                                      data-confirm-yes="duplicate-form-{{ $proposal->id }}">
                                                   <i
                                                      class="ti ti-copy text-white text-white"></i>
                                                   </a>
                                                   {{ Form::close() }}
                                                </div>
                                                @endpermission
                                                @permission('proposal show')
                                                @if (\Auth::user()->type == 'client')
                                                <div class="action-btn bg-warning ms-2">
                                                   <a href="{{ route('customer.proposal.show', $proposal->id) }}"
                                                      class="mx-3 btn btn-sm align-items-center"
                                                      data-bs-toggle="tooltip"
                                                      title="{{ __('Show') }}"
                                                      data-original-title="{{ __('Detail') }}">
                                                   <i
                                                      class="ti ti-eye text-white text-white"></i>
                                                   </a>
                                                </div>
                                                @else
                                                <div class="action-btn bg-warning ms-2">
                                                   <a href="{{ route('proposal.show', \Crypt::encrypt($proposal->id)) }}"
                                                      class="mx-3 btn btn-sm  align-items-center"
                                                      data-bs-toggle="tooltip"
                                                      title="{{ __('Show') }}"
                                                      data-original-title="{{ __('Detail') }}">
                                                   <i
                                                      class="ti ti-eye text-white text-white"></i>
                                                   </a>
                                                </div>
                                                @endif
                                                @endpermission
                                                @permission('proposal edit')
                                                <div class="action-btn bg-info ms-2">
                                                   <a href="{{ route('proposal.edit', \Crypt::encrypt($proposal->id)) }}"
                                                      class="mx-3 btn btn-sm  align-items-center"
                                                      data-bs-toggle="tooltip"
                                                      data-bs-original-title="{{ __('Edit') }}">
                                                   <i class="ti ti-pencil text-white"></i>
                                                   </a>
                                                </div>
                                                @endpermission
                                                @permission('proposal delete')
                                                <div class="action-btn bg-danger ms-2">
                                                   {{ Form::open(['route' => ['proposal.destroy', $proposal->id], 'class' => 'm-0']) }}
                                                   @method('DELETE')
                                                   <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                      data-bs-toggle="tooltip"
                                                      title=""
                                                      data-bs-original-title="Delete"
                                                      aria-label="Delete"
                                                      data-confirm="{{ __('Are You Sure?') }}"
                                                      data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                      data-confirm-yes="delete-form-{{ $proposal->id }}"><i
                                                      class="ti ti-trash text-white text-white"></i></a>
                                                   {{ Form::close() }}
                                                </div>
                                                @endpermission
                                             </span>
                                          </td>
                                          @endif
                                       </tr>
                                       @empty
                                       @include('layouts.nodatafound')
                                       @endforelse
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="customer-invoice" role="tabpanel"
                  aria-labelledby="pills-user-tab-3">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="invoice_project">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Invoice') }}</th>
                                          <th>{{ __('Issue Date') }}</th>
                                          <th>{{ __('Due Date') }}</th>
                                          <th>{{ __('Due Amount') }}</th>
                                          <th>{{ __('Status') }}</th>
                                          @if (Laratrust::hasPermission('invoice edit') ||
                                          Laratrust::hasPermission('invoice delete') ||
                                          Laratrust::hasPermission('invoice show'))
                                          <th width="10%"> {{ __('Action') }}</th>
                                          @endif
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @forelse ($customer->customerInvoice($customer->id) as $invoice)
                                       <tr>
                                          <td class="Id">
                                             @permission('invoice show')
                                             <a href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}"
                                                class="btn btn-outline-primary">{{ \App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id) }}
                                             </a>
                                             @else
                                             <a class="btn btn-outline-primary">{{ \App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id) }}
                                             </a>
                                             @endpermission
                                          </td>
                                          <td>{{ company_date_formate($invoice->issue_date) }}</td>
                                          <td>
                                             @if ($invoice->due_date < date('Y-m-d'))
                                             <p class="text-danger">
                                                {{ company_date_formate($invoice->due_date) }}
                                             </p>
                                             @else
                                             {{ company_date_formate($invoice->due_date) }}
                                             @endif
                                          </td>
                                          <td>{{ currency_format_with_sym($invoice->getDue()) }}
                                          </td>
                                          <td>
                                             @if ($invoice->status == 0)
                                             <span
                                                class="badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                             @elseif($invoice->status == 1)
                                             <span
                                                class="badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                             @elseif($invoice->status == 2)
                                             <span
                                                class="badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                             @elseif($invoice->status == 3)
                                             <span
                                                class="badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                             @elseif($invoice->status == 4)
                                             <span
                                                class="badge bg-success p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                             @endif
                                          </td>
                                          @if (Laratrust::hasPermission('invoice edit') ||
                                          Laratrust::hasPermission('invoice delete') ||
                                          Laratrust::hasPermission('invoice show'))
                                          <td class="Action">
                                             <span>
                                                @permission('duplicate invoice')
                                                <div class="action-btn bg-secondary ms-2">
                                                   {!! Form::open([
                                                   'method' => 'get',
                                                   'route' => ['invoice.duplicate', $invoice->id],
                                                   'id' => 'invoice-duplicate-form-' . $invoice->id,
                                                   ]) !!}
                                                   <a class="mx-3 btn btn-sm align-items-center bs-pass-para"
                                                      data-bs-toggle="tooltip"
                                                      title="{{ __('Duplicate Invoice') }}"
                                                      data-original-title="{{ __('Duplicate') }}"
                                                      data-confirm="{{ __('You want to confirm this action. Press Yes to continue or Cancel to go back') }}"
                                                      data-confirm-yes="document.getElementById('invoice-duplicate-form-{{ $invoice->id }}').submit();">
                                                   <i
                                                      class="ti ti-copy text-white text-white"></i>
                                                   </a>
                                                   {!! Form::close() !!}
                                                </div>
                                                @endpermission
                                                @permission('invoice show')
                                                @if (\Auth::user()->type == 'client')
                                                <div class="action-btn bg-warning ms-2">
                                                   <a href="{{ route('customer.invoice.show', \Crypt::encrypt($invoice->id)) }}"
                                                      class="mx-3 btn btn-sm align-items-center"
                                                      data-bs-toggle="tooltip"
                                                      title="{{ __('Show') }}"
                                                      data-original-title="{{ __('Detail') }}">
                                                   <i
                                                      class="ti ti-eye text-white text-white"></i>
                                                   </a>
                                                </div>
                                                @else
                                                <div class="action-btn bg-warning ms-2">
                                                   <a href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}"
                                                      class="mx-3 btn btn-sm align-items-center"
                                                      data-bs-toggle="tooltip"
                                                      title="{{ __('View') }}">
                                                   <i
                                                      class="ti ti-eye text-white text-white"></i>
                                                   </a>
                                                </div>
                                                @endif
                                                @endpermission
                                                @permission('invoice edit')
                                                <div class="action-btn bg-info ms-2">
                                                   <a href="{{ route('invoice.edit', \Crypt::encrypt($invoice->id)) }}"
                                                      class="mx-3 btn btn-sm  align-items-center"
                                                      data-bs-toggle="tooltip"
                                                      data-bs-original-title="{{ __('Edit') }}">
                                                   <i class="ti ti-pencil text-white"></i>
                                                   </a>
                                                </div>
                                                @endpermission
                                                @permission('invoice delete')
                                                <div class="action-btn bg-danger ms-2">
                                                   {{ Form::open(['route' => ['invoice.destroy', $invoice->id], 'class' => 'm-0']) }}
                                                   @method('DELETE')
                                                   <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                      data-bs-toggle="tooltip"
                                                      title=""
                                                      data-bs-original-title="Delete"
                                                      aria-label="Delete"
                                                      data-confirm="{{ __('Are You Sure?') }}"
                                                      data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                      data-confirm-yes="delete-form-{{ $invoice->id }}">
                                                   <i
                                                      class="ti ti-trash text-white text-white"></i>
                                                   </a>
                                                   {{ Form::close() }}
                                                </div>
                                                @endpermission
                                             </span>
                                          </td>
                                          @endif
                                       </tr>
                                       @empty
                                       @include('layouts.nodatafound')
                                       @endforelse
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               @stack('customer_retainer_div')
               <div class="tab-pane fade" id="customer-revenue" role="tabpanel"
                  aria-labelledby="pills-user-tab-4">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="revenue_customer">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Date') }}</th>
                                          <th>{{ __('Amount') }}</th>
                                          <th>{{ __('Account') }}</th>
                                          <th>{{ __('Category') }}</th>
                                          <th>{{ __('Reference') }}</th>
                                          <th>{{ __('Description') }}</th>
                                          <th>{{ __('Payment Receipt') }}</th>
                                          @if (Laratrust::hasPermission('revenue edit') || Laratrust::hasPermission('revenue delete'))
                                          <th width="10%"> {{ __('Action') }}</th>
                                          @endif
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @forelse ($customer->customerRevenue($customer->id) as $revenue)
                                       <tr class="font-style">
                                          <td>{{ company_date_formate($revenue->date) }}</td>
                                          <td>{{ currency_format_with_sym($revenue->amount) }}</td>
                                          <td>{{ !empty($revenue->bankAccount) ? $revenue->bankAccount->bank_name . ' ' . $revenue->bankAccount->holder_name : '' }}
                                          </td>
                                          @if (module_is_active('ProductService'))
                                          <td>{{ !empty($revenue->category) ? $revenue->category->name : '-' }}
                                          </td>
                                          @else
                                          <td>-</td>
                                          @endif
                                          <td>{{ !empty($revenue->reference) ? $revenue->reference : '-' }}
                                          </td>
                                          <td>
                                             <p
                                                style="white-space: nowrap;
                                                width: 200px;
                                                overflow: hidden;
                                                text-overflow: ellipsis;">
                                                {{ !empty($revenue->description) ? $revenue->description : '' }}
                                             </p>
                                          </td>
                                          <td>
                                             @if (!empty($revenue->add_receipt))
                                             <div class="action-btn bg-primary ms-2">
                                                <a href="{{ get_file($revenue->add_receipt) }}"
                                                   download=""
                                                   class="mx-3 btn btn-sm align-items-center"
                                                   data-bs-toggle="tooltip"
                                                   title="{{ __('Download') }}"
                                                   target="_blank">
                                                <i class="ti ti-download text-white"></i>
                                                </a>
                                             </div>
                                             <div class="action-btn bg-secondary ms-2">
                                                <a href="{{ get_file($revenue->add_receipt) }}"
                                                   class="mx-3 btn btn-sm align-items-center"
                                                   data-bs-toggle="tooltip"
                                                   title="{{ __('Show') }}"
                                                   target="_blank">
                                                <i class="ti ti-crosshair text-white"></i>
                                                </a>
                                             </div>
                                             @else
                                             -
                                             @endif
                                          </td>
                                          @if (Laratrust::hasPermission('revenue edit') || Laratrust::hasPermission('revenue delete'))
                                          <td class="Action">
                                             <span>
                                                @permission('revenue edit')
                                                <div class="action-btn bg-info ms-2">
                                                   <a class="mx-3 btn btn-sm align-items-center"
                                                      data-url="{{ route('revenue.edit', $revenue->id) }}"
                                                      data-ajax-popup="true" data-size="lg"
                                                      data-bs-toggle="tooltip" t
                                                      title="{{ __('Edit') }}"
                                                      data-title="{{ __('Edit Revenue') }}">
                                                   <i class="ti ti-pencil text-white"></i>
                                                   </a>
                                                </div>
                                                @endpermission
                                                @permission('revenue delete')
                                                <div class="action-btn bg-danger ms-2">
                                                   {{ Form::open(['route' => ['revenue.destroy', $revenue->id], 'class' => 'm-0']) }}
                                                   @method('DELETE')
                                                   <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                      data-bs-toggle="tooltip"
                                                      title=""
                                                      data-bs-original-title="Delete"
                                                      aria-label="Delete"
                                                      data-confirm="{{ __('Are You Sure?') }}"
                                                      data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                      data-confirm-yes="delete-form-{{ $revenue->id }}"><i
                                                      class="ti ti-trash text-white text-white"></i></a>
                                                   {{ Form::close() }}
                                                </div>
                                                @endpermission
                                             </span>
                                          </td>
                                          @endif
                                       </tr>
                                       @empty
                                       @include('layouts.nodatafound')
                                       @endforelse
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               @stack('customer_project_div')
               <div class="tab-pane fade" id="statement" role="tabpanel" aria-labelledby="pills-user-tab-4">
                  <div class="row">
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card bg-none invo-tab">
                           <div class="card-body">
                              <h3 class="small-title">{{ $customer['name'] . ' ' . __('Statement') }}</h3>
                              <div class="row issue_date">
                                 <div class="col-md-12">
                                    <div class="issue_date_main">
                                       <div class="form-group">
                                          {{ Form::label('from_date', __('From Date'), ['class' => 'form-label']) }}<span
                                             class="text-danger">*</span>
                                          {{ Form::date('from_date', isset($data['from_date']) ? $data['from_date'] : null, ['class' => 'form-control from_date ', 'required' => 'required']) }}
                                       </div>
                                    </div>
                                    <div class="issue_date_main">
                                       <div class="form-group">
                                          {{ Form::label('until_date', __('Until Date'), ['class' => 'form-label']) }}<span
                                             class="text-danger">*</span>
                                          {{ Form::date('until_date', isset($data['until_date']) ? $data['until_date'] : null, ['class' => 'form-control until_date', 'required' => 'required']) }}
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-12 text-end">
                                 <input type="submit" value="{{ __('Apply') }}"
                                    class="btn btn-sm btn-primary apply_btn">
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-8 col-lg-8 col-xl-8">
                        <span>
                           <div class="card">
                              <div class="text-end p-3">
                                 <a class="btn btn-sm btn-primary" onclick="saveAsPDF()"
                                    data-bs-toggle="tooltip"
                                    data-bs-original-title="{{ __('Download') }}">
                                 <i class="ti ti-download"></i>
                                 </a>
                              </div>
                              <div class="card-body" id="printableArea">
                                 <div class="invoice">
                                    <div class="invoice-print">
                                       <div class="row invoice-title mt-2">
                                          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                             <img src="{{ get_file(sidebar_logo()) }}"
                                                alt="{{ config('app.name', 'WorkDo') }}"
                                                class="logo logo-lg" style="max-width: 250px">
                                          </div>
                                          <div
                                             class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                             <strong>{{ __('My Company') }}</strong><br>
                                             <h6 class="invoice-number">{{ \Auth::user()->email }}
                                             </h6>
                                             <p>
                                                @if (!empty($settings['company_address']))
                                                {{ $settings['company_address'] }}
                                                @endif
                                                @if (!empty($settings['company_city']))
                                                <br> {{ $settings['company_city'] }},
                                                @endif
                                                @if (!empty($settings['company_state']))
                                                {{ $settings['company_state'] }}
                                                @endif
                                                @if (!empty($settings['company_country']))
                                                <br>{{ $settings['company_country'] }}
                                                @endif
                                                @if (!empty($settings['company_zipcode']))
                                                - {{ $settings['company_zipcode'] }}
                                                @endif
                                             </p>
                                          </div>
                                          <div
                                             class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-12 text-center">
                                             <strong>
                                                <h5>{{ __('Statement of Account') }}</h5>
                                             </strong>
                                             <strong>{{ $data['from_date'] . '  ' . 'to' . '  ' . $data['until_date'] }}</strong>
                                          </div>
                                          <div class="col-12">
                                             <hr>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-8">
                                             <h5 class="invoice-number">{{ $customer['name'] }}</h5>
                                          </div>
                                          <div class="col-md-4">
                                             <h5 class="invoice-number">{{ __('Account summary') }}
                                             </h5>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-12">
                                             <div class="row">
                                                @if (!empty($customer->billing_name))
                                                <div class="col-md-4">
                                                   <small class="font-style">
                                                   <strong>{{ __('Billed To') }}
                                                   :</strong><br>
                                                   {{ !empty($customer->billing_name) ? $customer->billing_name : '' }}<br>
                                                   {{ !empty($customer->billing_address) ? $customer->billing_address : '' }}<br>
                                                   {{ !empty($customer->billing_city) ? $customer->billing_city . ' ,' : '' }}
                                                   {{ !empty($customer->billing_state) ? $customer->billing_state . ' ,' : '' }}
                                                   {{ !empty($customer->billing_zip) ? $customer->billing_zip : '' }}<br>
                                                   {{ !empty($customer->billing_country) ? $customer->billing_country : '' }}<br>
                                                   {{ !empty($customer->billing_phone) ? $customer->billing_phone : '' }}<br>
                                                   <strong>{{ __('Tax Number ') }} :
                                                   </strong>{{ !empty($customer->tax_number) ? $customer->tax_number : '' }}
                                                   </small>
                                                </div>
                                                @endif
                                                @if ($company_settings['invoice_shipping_display'] == 'on' || $company_settings['proposal_shipping_display'] == 'on')
                                                <div class="col-md-4 text-end">
                                                   <small>
                                                   <strong>{{ __('Shipped To') }}
                                                   </strong><br>
                                                   {{ !empty($customer->shipping_name) ? $customer->shipping_name : '' }}<br>
                                                   {{ !empty($customer->shipping_address) ? $customer->shipping_address : '' }}<br>
                                                   {{ !empty($customer->shipping_city) ? $customer->shipping_city . ' ,' : '' }}
                                                   {{ !empty($customer->shipping_state) ? $customer->shipping_state . ' ,' : '' }}
                                                   {{ !empty($customer->shipping_zip) ? $customer->shipping_zip : '' }}<br>
                                                   {{ !empty($customer->shipping_country) ? $customer->shipping_country : '' }}<br>
                                                   {{ !empty($customer->shipping_phone) ? $customer->shipping_phone : '' }}<br>
                                                   <strong>{{ __('Tax Number ') }} :
                                                   </strong>{{ !empty($customer->tax_number) ? $customer->tax_number : '' }}
                                                   </small>
                                                </div>
                                                @endif
                                             </div>
                                          </div>

                                          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-12">
                                            @php
                                            $total = 0;
                                            @endphp

                                            @foreach ($invoice_payment as $payment)
                                            @php
                                                $total += $payment->amount;
                                            @endphp
                                            @endforeach
                                            @php
                                                $duebalance = $totalInvoiceSum - $total;
                                            @endphp
                                                <p class="card-text mb-0">{{ __('Balance') }} :  <span> {{ currency_format_with_sym($customer['balance']) }}</span></p>
                                                <p class="card-text mb-0">{{ __('Invoiced amount') }} :  <span> {{ currency_format_with_sym($totalInvoiceSum) }}</span></p>
                                                <p class="card-text mb-0">{{ __('Amount Paid') }} :  <span>{{ currency_format_with_sym($total) }}</span></p>
                                                <p class="card-text mb-0">{{ __('Balance Due') }} :  <span>{{ currency_format_with_sym($duebalance) }}</span></p>

                                          </div>
                                       </div>
                                       <div class="card mt-4">
                                          <div class="card-body table-border-styletable-border-style">
                                             <div class="table-responsive">
                                                <div id="statement-history">
                                                   <table
                                                      class="table align-items-center table_header">
                                                      <thead>
                                                         <tr>
                                                            <th scope="col">
                                                               {{ __('Date') }}
                                                            </th>
                                                            <th scope="col">
                                                               {{ __('Invoice') }}
                                                            </th>
                                                            <th scope="col">
                                                               {{ __('Payment Type') }}
                                                            </th>
                                                            <th scope="col">
                                                               {{ __('Amount') }}
                                                            </th>
                                                         </tr>
                                                      </thead>
                                                      <tbody class="list">
                                                         @php
                                                         $total = 0;
                                                         @endphp
                                                         @forelse($invoice_payment as $payment)
                                                         <tr>
                                                            <td>{{ company_date_formate($payment->date) }}
                                                            </td>
                                                            <td>{{ \App\Models\Invoice::invoiceNumberFormat($payment->invoice_id) }}
                                                            </td>
                                                            <td>{{ $payment->payment_type }}
                                                            </td>
                                                            <td> {{ currency_format_with_sym($payment->amount) }}
                                                            </td>
                                                         </tr>
                                                         @empty
                                                         <tr>
                                                            <td colspan="6"
                                                               class="text-center text-dark">
                                                               <p>{{ __('No Data Found') }}
                                                               </p>
                                                            </td>
                                                         </tr>
                                                         @endforelse
                                                      </tbody>
                                                      <tfoot>
                                                         <tr class="total">
                                                            <td class="light_blue">
                                                               <span></span><strong>{{ __('TOTAL :') }}</strong>
                                                            </td>
                                                            <td class="light_blue"></td>
                                                            <td class="light_blue"></td>
                                                            @foreach ($invoice_payment as $key => $payment)
                                                            @php
                                                            $total += $payment->amount;
                                                            @endphp
                                                            @endforeach
                                                            <td class="light_blue"
                                                               id="total-amount">
                                                               <span></span><strong>{{ currency_format_with_sym($total) }}</strong>
                                                            </td>
                                                         </tr>
                                                      </tfoot>
                                                   </table>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
