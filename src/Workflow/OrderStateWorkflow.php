<?php

/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Workflow;

use Lyraoft\ShopGo\Service\OrderStateService;
use Unicorn\Attributes\StateMachine;
use Unicorn\Workflow\AbstractWorkflow;
use Unicorn\Workflow\WorkflowController;

/**
 * The OrderStateWorkflow class.
 */
#[StateMachine(
    field: 'state',
    // Set to FALSE to allow free transition.
    strict: false
)]
class OrderStateWorkflow extends AbstractWorkflow
{
    public function __construct(protected OrderStateService $orderStateService)
    {
    }

    public function configure(WorkflowController $workflow): void
    {
        $states = $this->orderStateService->getOrderStates();

        // /** @var OrderState[] $states */
        // foreach ($states as $state) {
        //     $workflow->addState(
        //         (string) $state->getId(),
        //         $state->getTitle(),
        //         true
        //     );
        // }

        // $workflow->onAfterChanged(
        //     [],
        //     [],
        //     function () {
        //         //
        //     }
        // );
    }
}
