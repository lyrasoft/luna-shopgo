<?php

declare(strict_types=1);

namespace App\view;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        object          The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Form\Form;

/**
 * @var $attrFieldsets string[]
 * @var $form          Form
 */

?>

<x-card class="l-product-attributes-tab">
    <div class="row">
        <div class="col-lg-8">
            @foreach ($attrFieldsets as $attrFieldset)
                    <?php
                    $fieldset = $form->getFieldset($attrFieldset);

                    if (!$fieldset) {
                        continue;
                    }
                    ?>
                <div>
                    <x-fieldset :form="$form" :name="$attrFieldset"
                        class="mb-5"
                        horizon="3:9"
                        is="div"
                        no-title
                    ></x-fieldset>
                </div>
            @endforeach
        </div>
    </div>
</x-card>
