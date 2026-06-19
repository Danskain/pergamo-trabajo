<?php

use App\Modules\Accounting\Models\AccountingAccount;
use App\Modules\Accounting\Models\AccountClass;
use App\Modules\Accounting\Models\AccountingDocument;
use App\Modules\Accounting\Models\AccountingEvent;
use App\Modules\Accounting\Models\AccountingEntryHeader;
use App\Modules\Accounting\Models\AccountingMoment;
use App\Modules\Accounting\Models\AccountingGroup;
use App\Modules\Accounting\Models\AccountingNature;
use App\Modules\Accounting\Models\AccountingStandard;
use App\Modules\Accounting\Models\BusinessStructure;
use App\Modules\Accounting\Models\Campus;
use App\Modules\Accounting\Models\ChartAccount;
use App\Modules\Accounting\Models\Coin;
use App\Modules\Accounting\Models\CostCenter;
use App\Modules\Accounting\Models\CostCenterClass;
use App\Modules\Accounting\Models\CostCenterNature;
use App\Modules\Accounting\Models\CostCenterType;
use App\Modules\Accounting\Models\Country;
use App\Modules\Accounting\Models\DocumentSource;
use App\Modules\Accounting\Models\DocumentSourceType;
use App\Modules\Accounting\Models\Enterprise;
use App\Modules\Accounting\Models\ExerciseVariation;
use App\Modules\Accounting\Models\FinancialStatement;
use App\Modules\Accounting\Models\KeyOperation;
use App\Modules\Accounting\Models\Module;
use App\Modules\Accounting\Models\Reference;
use App\Modules\Accounting\Models\TypeAccount;
use App\Modules\Accounting\Models\TypePlan;

return [
    'route_prefix' => env('ACCOUNTING_ROUTE_PREFIX', 'api/v1/accounting'),
    'middleware' => ['api', 'force.json'],
    'select_options' => [
        'catalogs' => [
            'accounting_standard' => [
                'model' => AccountingStandard::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'types_plans' => [
                'model' => TypePlan::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'chart_accounts' => [
                'model' => ChartAccount::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'exercise_variations' => [
                'model' => ExerciseVariation::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'accounting_groups' => [
                'model' => AccountingGroup::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'types_accounts' => [
                'model' => TypeAccount::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'accounting_accounts' => [
                'model' => AccountingAccount::class,
                'value_field' => 'id',
                'label_fields' => ['account', 'name'],
                'meta_fields' => [
                    'id',
                    'account',
                    'name',
                    'chart_account_id',
                    'account_class_id',
                    'types_account_id',
                    'accounting_group_id',
                    'allows_manual_transactions',
                    'associated_account',
                    'accepts_taxes',
                    'foreign_currency',
                ],
                'search_fields' => ['account', 'name'],
                'order_by' => 'name',
            ],
            'account_class' => [
                'model' => AccountClass::class,
                'value_field' => 'id',
                'label_fields' => ['name'],
                'meta_fields' => ['id', 'name', 'accounting_nature_id', 'description'],
                'search_fields' => ['name', 'description'],
                'order_by' => 'name',
                'enriched_with' => ['accountingNature'],
            ],
            'accounting_nature' => [
                'model' => AccountingNature::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'accounting_moments' => [
                'model' => AccountingMoment::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'accounting_events' => [
                'model' => AccountingEvent::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'accounting_moment_id', 'origin'],
                'search_fields' => ['code', 'name', 'origin'],
                'order_by' => 'name',
            ],
            'country' => [
                'model' => Country::class,
                'value_field' => 'id',
                'label_fields' => ['name'],
                'meta_fields' => ['id', 'name', 'status_id'],
                'search_fields' => ['name'],
                'order_by' => 'name',
            ],
            'coins' => [
                'model' => Coin::class,
                'value_field' => 'id',
                'label_fields' => ['alphabetic_code', 'name'],
                'meta_fields' => ['id', 'name', 'alphabetic_code', 'numeric_code', 'subunit', 'subunit_ratio', 'decimal_digits'],
                'search_fields' => ['name', 'alphabetic_code', 'numeric_code'],
                'order_by' => 'name',
            ],
            'enterprises' => [
                'model' => Enterprise::class,
                'value_field' => 'id',
                'label_fields' => ['name'],
                'meta_fields' => ['id', 'name', 'business_group_id'],
                'search_fields' => ['name'],
                'order_by' => 'name',
            ],
            'campus' => [
                'model' => Campus::class,
                'value_field' => 'id',
                'label_fields' => ['enable_code', 'name'],
                'meta_fields' => ['id', 'name', 'address', 'enable_code', 'status_id'],
                'search_fields' => ['name', 'address', 'enable_code'],
                'order_by' => 'name',
            ],
            'business_structure' => [
                'model' => BusinessStructure::class,
                'value_field' => 'id',
                'label_fields' => ['id', 'enterprise_id', 'country_id'],
                'meta_fields' => ['id', 'enterprise_id', 'country_id', 'coin_id', 'exercise_variation_id', 'chart_account_id'],
                'search_fields' => ['id', 'enterprise_id', 'country_id'],
                'order_by' => 'id',
                'enriched_with' => ['enterprise', 'country', 'coin', 'exerciseVariation'],
            ],
            'modules' => [
                'model' => Module::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'document_source_type' => [
                'model' => DocumentSourceType::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'financial_statements' => [
                'model' => FinancialStatement::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'key_operations' => [
                'model' => KeyOperation::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'module_id', 'accounting_nature_id', 'affects_taxes'],
                'search_fields' => ['code', 'name'],
                'order_by' => 'name',
            ],
            'reference' => [
                'model' => Reference::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'accounting_document' => [
                'model' => AccountingDocument::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'documents_source' => [
                'model' => DocumentSource::class,
                'value_field' => 'id',
                'label_fields' => ['number_document_source', 'exercise'],
                'meta_fields' => [
                    'id',
                    'number_document_source',
                    'exercise',
                    'description',
                    'document_source_type_id',
                    'reference_id',
                    'accounting_document_id',
                ],
                'search_fields' => ['number_document_source', 'exercise', 'description'],
                'order_by' => 'number_document_source',
                'enriched_with' => ['documentSourceType', 'reference', 'accountingDocument'],
            ],
            'accounting_entry_header' => [
                'model' => AccountingEntryHeader::class,
                'value_field' => 'id',
                'label_fields' => ['reference_document', 'accounting_period'],
                'meta_fields' => ['id', 'reference_document', 'accounting_period', 'description'],
                'search_fields' => ['reference_document', 'accounting_period', 'description'],
                'order_by' => 'reference_document',
            ],
            'cost_center_type' => [
                'model' => CostCenterType::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'cost_center_class' => [
                'model' => CostCenterClass::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'cost_center_nature' => [
                'model' => CostCenterNature::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
            'cost_center' => [
                'model' => CostCenter::class,
                'value_field' => 'id',
                'label_fields' => ['code', 'name'],
                'meta_fields' => ['id', 'code', 'name', 'description'],
                'search_fields' => ['code', 'name', 'description'],
                'order_by' => 'name',
            ],
        ],
    ],
];
