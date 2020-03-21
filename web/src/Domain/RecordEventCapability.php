<?php
declare(strict_types=1);

namespace HelloFresh\Domain;

trait RecordEventCapability
{
    /** @var array $latestRecordedEvents */
    protected $latestRecordedEvents = [];

    public function getRecordedEvents(): array
    {
        return $this->latestRecordedEvents;
    }

    public function clearRecordedEvents(): void
    {
        $this->latestRecordedEvents = [];
    }

    public function record($event): void
    {
        $this->latestRecordedEvents[] = $event;
        $this->apply($event);
    }

    private function apply($event)
    {
        $eventName = explode('\\', get_class($event));
        $method = 'apply' . array_pop($eventName);

        if (method_exists($this, $method)) {
            $this->$method($event);
        }
    }
}
