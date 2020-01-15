<?php

namespace App\Model;

use App\Model\Base as BaseModel;

class SystemAttachment extends BaseModel
{
	//表名
    protected $table = 'system_attachment';

    //主键
    protected $primaryKey = 'attachment_id';

    //不能被重置的字段
    protected $guarded = ['attachment_id'];

	/**
	 * @method 新建系统文件记录
	 * @author Victoria
	 * @date   2020-01-15
	 * @return integer 文件记录ID attachment_id
	 */
    public function create($data)
    {
    	if (empty($data['file_url']) || empty($data['checksum'])) return false;

    	if ($this->isExitsHash($data['checksum'])) return false;

    	$insert = [
    		'filename' => $data['filename'],
		  	'filetype' => $data['filetype'],
		  	'file_url' => $data['file_url'],
		  	'checksum' => $data['checksum'],
		  	'created_at' => \Carbon\Carbon::now(),
    	];

    	return $this->insertGetId($insert);
    }

    /**
     * @method 根据hash获取文件信息
     * @author Victoria
     * @date   2020-01-15
     * @return array
     */
    public function getAttachmentByHash($checksum)
    {
    	if (empty($checksum)) return false;

    	$result = $this->where('checksum', $checksum)
    				   ->first();
    	return $result ?? [];
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
    	return $this->where('checksum', $checksum)->count() > 0;
    }
}
