<?php 

namespace App\Service\Logger;

use App\Service\Base as BaseService;
use App\Model\Stat\BaseLogger;

/**
 * 	日志记录中间层
 */
class BaseLoggerService extends BaseService
{
	protected static $constantMap = [
        'base' => BaseLogger::class,
    ];

	public function __construct(BaseLogger $model)
	{
		$this->baseModel = $model;
	}

	/**
	 * @method 新增日志
	 * @author Victoria
	 * @date   2020-01-12
	 * @return boolean
	 */
	public function create($data)
	{
		if (empty($data['target_id'])) return false;

		if (!empty($data['raw_data']) && !is_array($data['raw_data'])) $data['raw_data'] = ['text' => $data['raw_data']];

		return $this->baseModel->create($data);
	}
}