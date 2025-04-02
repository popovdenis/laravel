<?php
declare(strict_types=1);

namespace App\FlexibleContentBlocks;

use Closure;
use Filament\Forms;
use Illuminate\View\View;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;

class HeadingBlock extends AbstractContentBlock
{
    public static function getName(): string
    {
        return 'heading';
    }

    public static function getLabel(): string
    {
        return 'Heading';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-heading';
    }

    public static function getFieldLabel(string $field): string
    {
        return match ($field) {
            'text' => 'Heading Text',
            default => ucfirst($field),
        };
    }

    protected static function makeFilamentSchema(): array|Closure
    {
        return [
            Forms\Components\TextInput::make('text')
                ->label(static::getFieldLabel('text'))
                ->required(),
        ];
    }

    public function render(): View|string
    {
        return view('content-blocks.heading', [
            'text' => $this->blockData['text'] ?? '',
        ]);
    }

    public function getSearchableContent(): array
    {
        return [$this->blockData['text'] ?? ''];
    }
}
