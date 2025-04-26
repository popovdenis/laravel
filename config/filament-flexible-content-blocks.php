<?php

// config for Statikbe/FilamentFlexibleContentBlocks
use Spatie\Image\Enums\Fit;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CallToActionBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CardsBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\HtmlBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\ImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\OverviewBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\QuoteBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TemplateBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\VideoBlock;

return [
    'supported_locales' => [
        'nl',
        'en',
    ],

    'default_flexible_blocks' => [
        TextBlock::class,
        VideoBlock::class,
        ImageBlock::class,
        HtmlBlock::class,
        TextImageBlock::class,
        OverviewBlock::class,
        QuoteBlock::class,
        CallToActionBlock::class,
        CardsBlock::class,
        TemplateBlock::class,
    ],

    'theme' => 'tailwind',

    'image_conversions' => [
        'models' => [
            'default' => [
//                'seo_image' => [
//                    'seo_image' => function (\Spatie\MediaLibrary\Conversions\Conversion $conversion) {
//                        return $conversion->fit(Fit::Crop, 1200, 630)
//                            ->withResponsiveImages();
//                    },
//                ],
                'overview_image' => [
                    'overview_image' => [
                        'fit' => Fit::Crop,
                        'width' => 500,
                        'height' => 500,
                        'responsive' => true,
                    ],
                ],
                'hero_image' => [
                    'hero_image' => [
                        'fit' => Fit::Crop,
                        'width' => 1200,
                        'height' => 630,
                        'responsive' => true,
                    ],
                    'extra_conversions' => [
                        'hero_image_square' => [
                            'fit' => Fit::Crop,
                            'width' => 400,
                            'height' => 400,
                            'responsive' => true,
                        ],
                    ],
                ],
            ],
            'specific' => [
                /*Page::class => [
                    'overview_image' => [
                        'thumb' => [
                            'fit' => Fit::Crop,
                            'width' => 400,
                            'height' => 400,
                            'responsive' => true,
                        ],
                    ],
                ],*/
            ],
        ],
        'flexible_blocks' => [
            'default' => [],
            'specific' => [],
        ],
    ],

    'image_editor' => [
        'enabled' => false,
        'aspect_ratios' => [
            null,
            '16:9',
            '4:3',
            '1:1',
        ],
        'mode' => 1, // see https://github.com/fengyuanchen/cropperjs#viewmode
        'empty_fill_colour' => null,  // e.g. #000000
        'viewport' => [
            'width' => 1920,
            'height' => 1080,
        ],
    ],

    'overview_models' => [
        // e.g. 'App\Models\FlexiblePage',
    ],

    'call_to_action_models' => [
        // e.g. 'App\Models\FlexiblePage',
        // Or if you want to implement a custom CTA type, e.g. for the asset manager see https://github.com/statikbe/laravel-filament-flexible-blocks-asset-manager:
        /*[
            'model' => \Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset::class,
            'call_to_action_type' => \Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields\Blocks\Type\AssetCallToActionType::class,
        ],*/
        \App\Models\TranslatablePage::class,
    ],

    'link_routes' => [
        'allowed' => [
            '*',
        ],
        'denied' => [
            'debugbar*',
            'filament.*',
            'livewire.*',
            'ignition.*',
            'api*',
            'login_authorize',
            'login_create',
        ],
    ],

    'image_position' => [
        'options' => [
            'left' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.left',
            'center' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.center',
            'right' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.right',
        ],
        'default' => 'left',
    ],

    'image_width' => [
        'options' => [
            '100%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.100%',
                'class' => 'w-full',
            ],
            '75%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.75%',
                'class' => 'md:w-3/4',
            ],
            '50%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.50%',
                'class' => 'md:w-1/2',
            ],
            '33%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.33%',
                'class' => 'md:w-1/3',
            ],
            '25%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.25%',
                'class' => 'md:w-1/4',
            ],
        ],
        'default' => '100%',
    ],

    'call_to_action_buttons' => [
        'options' => [
            'primary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.primary',
                'class' => 'btn btn--primary',
            ],
            'primary_chevron' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.primary_chevron',
                'class' => 'btn btn--primary btn--ext',
            ],
            'secondary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.secondary',
                'class' => 'btn btn--secondary',
            ],
            'secondary_chevron' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.secondary_chevron',
                'class' => 'btn btn--secondary btn--ext',
            ],
            'ghost' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.ghost',
                'class' => 'btn btn--ghost',
            ],
            'ghost_chevron' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.ghost_chevron',
                'class' => 'btn btn--ghost btn--ext',
            ],
            'link' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.link',
                'class' => 'link',
            ],
            'link_chevron' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.link_chevron',
                'class' => 'link link--ext',
            ],
        ],
        'default' => 'primary',
    ],

    'background_colours' => [
        'options' => [
            'default' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.default',
                'class' => 'section--default',
            ],
            'primary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.primary',
                'class' => 'section--primary',
            ],
            'secondary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.secondary',
                'class' => 'section--secondary',
            ],
            'light' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.light',
                'class' => 'section--light',
            ],
        ],
        'default' => 'default',
    ],

    'block_styles' => [
        'enabled_for_all_blocks' => true,
        'options' => [
            'default' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.block_styles.default',
        ],
        'default' => 'default',
    ],

    'grid_columns' => [
        1, 2, 3, 4,
    ],

    'templates' => [
        'partials.footer-nav' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.templates_options.footer',
    ],

    'formatting' => [
        'publishing_dates' => 'd/m/Y G:i',
    ],

    'author_model' => 'Modules\User\Models\User',

    'block_specific' => [
        /*
        //Examples:
        TextImageBlock::class => [
            'image_position' => [
                'options' => [
                    'left' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.left',
                    'right' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.right',
                ],
                'default' => 'left',
            ],
        ],
        TextBlock::class => [
            'block_styles' => [
                'enabled' => true,
                'options' => [
                    'default' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.block_styles.default',
                    'better' => 'better',
                    'nice' => 'nice',
                ],
            ],
        ],*/
    ],

    'text_parameter_replacer' => null,
];
