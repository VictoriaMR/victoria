<?php 

namespace App\Service\Logger;

use App\Service\Base as BaseService;
use App\Model\Common\BaseLogger;

/**
 * 	日志记录中间层
 */
class BaseLoggerService extends BaseService
{
	public function __construct(BaseLogger $model)
	{
		$this->baseModel = $model;
	}
}