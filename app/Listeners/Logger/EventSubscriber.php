<?php

namespace App\Listeners\Logger;

use App\Events\Event;

class EventSubscriber
{
    /**
     * 生成日志
     */
    public function create($event)
    {
        $targetId = $event->payload->getTargetId();  //目标ID
        $entityId = $event->payload->getEntityId(); // 实体ID
        $rawData = $event->payload->getRawData(); //内容
        $typeId = $event->payload->getTypeId(); //类型

        $data = [
            'target_id' => $targetId,
            'entity_id' => $entityId,
            'type_id' => $typeId,
            'raw_data' => $rawData,
        ];

        $loggerService = \App::make('App\Service\Logger\BaseLoggerService');
        $loggerService->create($data);
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Logger\CreateEvent',
            'App\Listeners\Logger\EventSubscriber@create'
        );
    }
}
