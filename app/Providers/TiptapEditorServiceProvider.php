<?php

namespace App\Providers;

use Filament\Forms\Components\RichEditor;
use Illuminate\Support\ServiceProvider;

class TiptapEditorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        RichEditor::configureUsing(function (RichEditor $editor) {
            $editor
                ->extraFileAttachmentsDisk('local')
                ->extraFileAttachmentsDirectory('attachments')
                ->extraInputAttributes(['style' => 'min-height: 200px;'])
                ->tools([
                    'align-left' => [
                        'label' => 'Align Left',
                        'icon' => 'heroicon-o-arrow-left',
                        'action' => 'editor.commands.setTextAlign("left")',
                    ],
                    'align-center' => [
                        'label' => 'Align Center',
                        'icon' => 'heroicon-o-arrow-up',
                        'action' => 'editor.commands.setTextAlign("center")',
                    ],
                    'align-right' => [
                        'label' => 'Align Right',
                        'icon' => 'heroicon-o-arrow-right',
                        'action' => 'editor.commands.setTextAlign("right")',
                    ],
                    'align-justify' => [
                        'label' => 'Align Justify',
                        'icon' => 'heroicon-o-arrows-right-left',
                        'action' => 'editor.commands.setTextAlign("justify")',
                    ],
                ]);
        });
    }
}