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

    /**
     * @method 文件是否存在
     * @author Victoria
     * @date   2020-01-15
     * @param  string    $checksum 
     * @return boolean             
     */
    public function isExitsHash($checksum)
    {
    	return $this->baseModel->isExitsHash($checksum);
    }

    /**
     * @method 根据hash获取文件信息
     * @author Victoria
     * @date   2020-01-15
     * @return array
     */
    public function getAttachmentByHash($checksum)
    {
    	return $this->baseModel->getAttachmentByHash($checksum);
    }
}