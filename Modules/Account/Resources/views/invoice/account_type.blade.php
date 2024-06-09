
<option value="Account" {{(isset($_GET['account_type']) && $_GET['account_type'] == 'Account') ? 'selected' : '' }}>
    {{ __('Accounting') }}
</option>
