<?php

namespace JscDev\LaravelTable\BulkActions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Component;
use JscDev\LaravelTable\Abstracts\AbstractBulkAction;

class CancelEmailVerificationBulkAction extends AbstractBulkAction
{
    public function __construct(public string $attribute)
    {
        //
    }

    protected function identifier(): string
    {
        return 'bulk_action_cancel_email_verification';
    }

    protected function label(array $allowedModelKeys): string
    {
        return __('Unverify Email') . ' (' . count($allowedModelKeys) . ')';
    }

    protected function defaultConfirmationQuestion(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        $allowedLinesCount = count($allowedModelKeys);
        $allowedLinesSentence = $allowedLinesCount > 1
            ? __('Are you sure you want to execute the action :action on the :count selected lines?', [
                'action' => __('Unverify Email'),
                'count' => count($allowedModelKeys),
            ])
            : __('Are you sure you want to execute the action :action on the line #:primary?', [
                'action' => __('Unverify Email'),
                'primary' => Arr::first($allowedModelKeys),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow the action :action and will not be affected.', [
                    'count' => $disallowedLinesCount,
                    'action' => __('Unverify Email'),
                ])
                : __('The line #:primary does not allow the action :action and will not be affected.', [
                    'primary' => Arr::first($disallowedModelKeys),
                    'action' => __('Unverify Email'),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }

    protected function defaultFeedbackMessage(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        $allowedLinesCount = count($allowedModelKeys);
        $allowedLinesSentence = $allowedLinesCount > 1
            ? __('The action :action has been executed on the :count selected lines.', [
                'action' => __('Unverify Email'),
                'count' => count($allowedModelKeys),
            ])
            : __('The action :action has been executed on the line #:primary.', [
                'action' => __('Unverify Email'),
                'primary' => Arr::first($allowedModelKeys),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow the action :action and were not affected.', [
                    'count' => $disallowedLinesCount,
                    'action' => __('Unverify Email'),
                ])
                : __('The line #:primary does not allow the action :action and was not affected.', [
                    'primary' => Arr::first($disallowedModelKeys),
                    'action' => __('Unverify Email'),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }

    public function action(Collection $models, Component $livewire): void
    {
        foreach ($models as $model) {
            // Update attribute even if it not in model `$fillable`
            $model->forceFill([$this->attribute => null])->save();
        }
    }
}
