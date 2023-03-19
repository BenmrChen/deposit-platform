<?php

$baseRules = [
    '@PSR2' => true,
    'psr_autoloading' => true,
    'array_syntax' => ['syntax' => 'short'],
    'array_indentation' => true,
    'trailing_comma_in_multiline' => ['elements' => ['arrays']],
    'trim_array_spaces' => true,
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => [
        'statements' => [
            'break',
            'continue',
            'declare',
            'return',
            'throw',
            'try',
            'if',
            'switch',
        ],
    ],
    'cast_spaces' => ['space' => 'single'],
    'class_attributes_separation' => [
        'elements' => [
            'method' => 'one',
        ],
    ],
    'lowercase_cast' => true,
    'native_function_casing' => true,
    'compact_nullable_typehint' => true,
    'concat_space' => ['spacing' => 'one'],
    'ordered_class_elements' => true,
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'single_quote' => true,
    'object_operator_without_whitespace' => true,
    'no_leading_namespace_whitespace' => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_extra_blank_lines' => true,
    'no_spaces_inside_parenthesis' => true,
    'no_empty_comment' => true,
    'no_empty_phpdoc' => true,
    'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'combine_consecutive_issets' => true,
    'combine_consecutive_unsets' => true,
    'doctrine_annotation_indentation' => true,
    'explicit_string_variable' => true,
    'function_typehint_space' => true,
    'method_chaining_indentation' => true,
    'no_empty_statement' => true,
    'nullable_type_declaration_for_default_null_value' => true,
    'return_assignment' => true,
    'ternary_operator_spaces' => true,
    'ternary_to_null_coalescing' => true,
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/app')
    ->exclude('cache')
    ->in(__DIR__ . '/config')
    ->in(__DIR__ . '/database')
    ->in(__DIR__ . '/lang')
    ->in(__DIR__ . '/tests')
    ->in(__DIR__ . '/Modules')
    ->files()
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setRules($baseRules)
    ->setFinder($finder);
