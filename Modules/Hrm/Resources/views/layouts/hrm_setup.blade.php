<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        @permission('branch manage')
            <a href="{{route('branch.index')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('branch*') ? 'active' : '')}}">{{__('Branch')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('department manage')
            <a href="{{ route('department.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('department*') ? 'active' : '')}}">{{__('Department')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('designation manage')
            <a href="{{ route('designation.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('designation*') ? 'active' : '')}}">{{__('Designation')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('allowanceoption manage')
            <a href="{{ route('allowanceoption.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('allowanceoption*') ? 'active' : '')}}">{{__('Allowance Option')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('loanoption manage')
            <a href="{{ route('loanoption.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('loanoption*') ? 'active' : '')}}">{{__('Loan Option')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('deductionoption manage')
            <a href="{{ route('deductionoption.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('deductionoption*') ? 'active' : '')}}">{{__('Deduction Option')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('documenttype manage')
            <a href="{{ route('document-type.index') }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'document-type.index' ? 'active' : '')}}">{{__('Document Type')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('paysliptype manage')
            <a href="{{ route('paysliptype.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('paysliptype*') ? 'active' : '')}}">{{__('Payslip Type')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('leavetype manage')
            <a href="{{ route('leavetype.index') }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'leavetype.index' ? 'active' : '')}}">{{__('Leave Type')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('awardtype manage')
            <a href="{{ route('awardtype.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('awardtype*') ? 'active' : '')}}">{{__('Award Type')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('terminationtype manage')
            <a href="{{ route('terminationtype.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('terminationtype*') ? 'active' : '')}}">{{__('Termination Type')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @if(module_is_active('Performance'))
            @permission('performancetype manage')
                    <a href="{{ route('performanceType.index') }}" class="list-group-item list-group-item-action border-0 {{ request()->is('performanceType*') ? 'active' : '' }}">{{__('Performance Type')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
            @endpermission

            @permission('goal type manage')
                    <a href="{{ route('goaltype.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('goaltype*') ? 'active' : '')}}">{{__('Goal Type')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
            @endpermission
        @endif

        @if(module_is_active('Training'))
            @permission('trainingtype manage')
                <a href="{{ route('trainingtype.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('trainingtype*') ? 'active' : '')}}">{{__('Training Type')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
            @endpermission
        @endif

        @if(module_is_active('Performance'))
            @permission('competencies manage')
                <a href="{{ route('competencies.index') }}" class="list-group-item list-group-item-action border-0 {{ request()->is('competencies*') ? 'active' : '' }}">{{__('Competencies')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
            @endpermission
        @endif
        
        @permission('tax bracket manage')
            <a href="{{ route('taxbracket.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('taxbracket*') ? 'active' : '')}}">{{__('Tax Brackets')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission
        
        @permission('tax rebate manage')
            <a href="{{ route('taxrebate.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('taxrebate*') ? 'active' : '')}}">{{__('Tax Rebates')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission
        
        @permission('tax threshold manage')
            <a href="{{ route('taxthreshold.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('taxthreshold*') ? 'active' : '')}}">{{__('Tax Thresholds')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission
        
        @permission('allowance tax manage')
            <a href="{{ route('allowancetax.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('allowancetax*') ? 'active' : '')}}">{{__('Allowance Tax')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission
    </div>
</div>
