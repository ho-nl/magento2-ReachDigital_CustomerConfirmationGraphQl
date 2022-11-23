<?php

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'ReachDigital_CustomerConfirmationGraphQl',
    __DIR__.'/src'
);
