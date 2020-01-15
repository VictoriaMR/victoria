<?php 

namespace App\Service\Common;

use App\Service\Base as BaseService;
use App\Model\SystemAttachment;

/**
 * 	文件上传类
 */
class SystemAttachmentService extends BaseService
{
	protected static $constantMap = [
        'base' => SystemAttachment::class,
    ];

	public function __construct(SystemAttachment $model)
	{
		$this->baseModel = $model;
	}

	/**
	 * @method 新建系统文件记录
	 * @author Victoria
	 * @date   2020-01-15
	 * @return integer 文件记录ID attachment_id
	 */
    public function create($data)
    {
    	return $this->baseModel->create($data);
    }
}