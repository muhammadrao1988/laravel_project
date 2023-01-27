<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Cron\CronExpression;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\CallbackEvent;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleListCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List when scheduled commands are executed.';

    /**
     * @var Schedule
     */
    protected $schedule;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    public function __construct(Schedule $schedule)
    {
        parent::__construct();
        $this->schedule = $schedule;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $events = array_map(function ($event) {
            $command = $this->formatCommand($event);

            return [
                'cron' => $event->expression,
                'command' => $command,
                'next' => $event->nextRunDate()->toDateTimeString(),
                'previous' => static::previousRunDate($event->expression)->toDateTimeString(),
            ];
        }, $this->schedule->events());

        $this->table(
            ['Cron', 'Command', 'Next Run', 'Previous Run'],
            $events
        );
    }

    /**
     * If it's an artisan command, strip off the PHP
     *
     * @param  $command
     *
     * @return string
     */
    protected static function fixupCommand($command)
    {
        $parts = explode(' ', $command);
        if (count($parts) > 2 && $parts[1] === "'artisan'") {
            array_shift($parts);
        }

        return implode(' ', $parts);
    }

    /**
     * Determine the previous run date for an event.
     *
     * @param string $expression
     *
     * @return \Carbon\Carbon
     */
    protected static function previousRunDate(string $expression)
    {
        return Carbon::instance(
            CronExpression::factory($expression)->getPreviousRunDate()
        );
    }

    private function formatCommand($event)
    {
        if ($event instanceof CallbackEvent) {
            return $event->getSummaryForDisplay();
        }

        return $event->command ? static::fixupCommand($event->command) : '?';
    }
}