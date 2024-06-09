<h5 class="h4 d-inline-block font-weight-400 my-2">{{ __('Credit Note Summary') }}</h5>
<div class="card">
    <div class="card-body table-border-style table-border-style">
        <div class="table-responsive">
            <table class="table mb-0 pc-dt-simple" id="credir-note">
                <thead>
                    <tr>
                        <th class="text-dark">{{ __('Date') }}</th>
                        <th class="text-dark" class="">{{ __('Amount') }}</th>
                        <th class="text-dark" class="">{{ __('Description') }}</th>
                        @if (Laratrust::hasPermission('creditnote edit') || Laratrust::hasPermission('creditnote delete'))
                            <th class="text-dark">{{ __('Action') }}</th>
                        @endif
                    </tr>
                </thead>
                @forelse($invoice->creditNote as $key =>$creditNote)
                    <tr>
                        <td>{{ company_date_formate($creditNote->date) }}</td>
                        <td class="">{{ currency_format_with_sym($creditNote->amount) }}</td>
                        <td class="">{{ $creditNote->description }}</td>
                        @if (Laratrust::hasPermission('creditnote edit') || Laratrust::hasPermission('creditnote delete'))
                            <td>
                                @permission('creditnote edit')
                                    <div class="action-btn bg-primary ms-2">
                                        <a data-url="{{ route('invoice.edit.credit.note', [$creditNote->invoice, $creditNote->id]) }}"
                                            data-ajax-popup="true" title="{{ __('Edit') }}"
                                            data-original-title="{{ __('Credit Note') }}"
                                            class="mx-3 btn btn-sm align-items-center"
                                            data-title="{{ __('Edit Credit Note') }}" data-bs-toggle="tooltip">
                                            <i class="ti ti-edit text-white"></i>
                                        </a>
                                    </div>
                                @endpermission
                                @permission('creditnote delete')
                                    <div class="action-btn bg-danger ms-2">
                                        {{ Form::open(['route' => ['invoice.delete.credit.note', $creditNote->invoice, $creditNote->id], 'class' => 'm-0']) }}
                                        @method('DELETE')
                                        <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                            aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="delete-form-{{ $creditNote->id }}"><i
                                                class="ti ti-trash text-white text-white"></i></a>
                                        {{ Form::close() }}
                                    </div>
                                @endpermission
                            </td>
                        @endif
                    </tr>
                @empty
                    @include('layouts.nodatafound')
                @endforelse
            </table>
        </div>
    </div>
</div>
