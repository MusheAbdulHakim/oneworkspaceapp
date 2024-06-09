

<div class="form-group col-md-6">
    {{ Form::label('sale_chartaccount_id', __('Income Account'),['class'=>'form-label']) }}
    <select name="sale_chartaccount_id" class="form-control">
        @foreach ($incomeChartAccounts as $key => $chartAccount)
            <option value="{{ $key }}" class="subAccount">{{ $chartAccount }}</option>
            @foreach ($incomeSubAccounts as $subAccount)
                @if ($key == $subAccount['account'])
                    <option value="{{ $subAccount['id'] }}" class="ms-5"> &nbsp; &nbsp;&nbsp; {{ $subAccount['code'] }} - {{ $subAccount['name'] }}</option>
                @endif
            @endforeach
        @endforeach
    </select>
</div>

<div class="form-group col-md-6">
    {{ Form::label('expense_chartaccount_id', __('Expense Account'),['class'=>'form-label']) }}
    <select name="expense_chartaccount_id" class="form-control">
        @foreach ($expenseChartAccounts as $key => $chartAccount)
            <option value="{{ $key }}" class="subAccount">{{ $chartAccount }}</option>
            @foreach ($expenseSubAccounts as $subAccount)
                @if ($key == $subAccount['account'])
                    <option value="{{ $subAccount['id'] }}" class="ms-5"> &nbsp; &nbsp;&nbsp; {{ $subAccount['code'] }} -  {{ $subAccount['name'] }}</option>
                @endif
            @endforeach
        @endforeach
    </select>
</div>
