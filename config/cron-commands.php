<?php
/**
 * @package ${NAMESPACE}
 */
return [
    [
        'command' => 'stripe:pull-invoices',
        'label' => 'Pull invoices from Stripe',
        'target_type' => \Modules\Invoice\Models\Invoice::class,
    ]
];
