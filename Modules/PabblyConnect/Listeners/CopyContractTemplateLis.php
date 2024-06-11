<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ContractTemplate\Events\CopyContractTemplate;
use Modules\PabblyConnect\Entities\PabblySend;

class CopyContractTemplateLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CopyContractTemplate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $contract_template = $event->contract_template;

            $pabbly_array = [
                'Contract Template Subject' => $contract_template->subject,
                'Contract Template Type' => $contract_template->contract_type,
                'Contract Template Description' => strip_tags($contract_template->description),
            ];

            $action = 'Duplicate Contract Template';
            $module = 'ContractTemplate';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
