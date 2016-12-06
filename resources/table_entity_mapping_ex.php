<?php

return [
    's_article_configurator_groups_attributes' => [
        'readOnly' => false,
        'model' => 'Shopware\Models\Attribute\ConfiguratorGroup',
        'identifiers' => ['id', 'groupid'],
        'foreignKey' => 'groupid',
        'coreAttributes' => [],
        'dependingTables' => []
    ],
    's_article_configurator_options_attributes' => [
        'readOnly' => false,
        'model' => 'Shopware\Models\Attribute\ConfiguratorOption',
        'identifiers' => ['id', 'optionid'],
        'foreignKey' => 'optionid',
        'coreAttributes' => [],
        'dependingTables' => []
    ]
];
