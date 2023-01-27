<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronJob extends BaseModel
{
    const FREQUENCY_EVERY_MINUTE = 'everyMinute';
    const FREQUENCY_EVERY_TWO_MINUTES = 'everyTwoMinutes';
    const FREQUENCY_EVERY_THIRTY_MINUTES = 'everyThirtyMinutes';
    const FREQUENCY_HOURLY = 'hourly';
    const FREQUENCY_DAILY = 'daily';
    const FREQUENCY_DAILY_AT = 'dailyAt';
    const FREQUENCY_TWICE_DAILY = 'twiceDaily';
    const FREQUENCY_WEEKLY = 'weekly';
    const FREQUENCY_MONTHLY = 'monthly';
    const FREQUENCY_YEARLY = 'yearly';
    const FREQUENCY_QUARTERLY = 'quarterly';
    const FREQUENCY_EVERY_SIX_HOURS = 'everySixHours';

    public $table = 'cron_jobs';

    public static $module = 'CronJob';

    public $fillable = [
        'function',
        'function_arg_1',
        'function_arg_2',
        'frequency_func',
        'frequency_func_arg_1',
        'frequency_func_arg_2',
        'model_type',
        'model_id',
        'total_execution',
        'active',
    ];

    public static function validationRules($id = 0)
    {
        $rules = [
            'function' => ['required', 'string'],
            'function_arg_1' => ['nullable', 'string'],
            'function_arg_2' => ['nullable', 'string'],
            'frequency_func' => ['required', 'string'],
            'frequency_func_arg_1' => ['nullable', 'string'],
            'frequency_func_arg_2' => ['nullable'],
            'model_type' => ['nullable', 'string'],
            'model_id' => ['nullable', 'integer']
        ];

        return $rules;
    }

    public static function getList()
    {
        $return = self::select('cron_jobs.*')->where('active', 1);
        return $return;
    }

    public static function actionButtons($data)
    {
        $return = '';

        if (\Common::canUpdate(static::$module)) {
            $return .= '<a class="btn" href="' . route('cronJob.edit', $data->id) . '" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
        }
        $return .= '</div>';
        return $return;
    }
}
