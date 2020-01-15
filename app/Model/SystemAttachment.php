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

    	$attachmentId = $this->getAttachmentIdByCrc32($data['crc32']);
    	if (!empty($attachmentId)) return $attachmentId;

    	$insert = [
    		'filename' => $data['filename'],
		  	'filetype' => $data['filetype'],,
		  	'file_url' => $data['file_url'],,
		  	'crc32' => $data['crc32'],,
		  	'created_at' => \Carbon\Carbon::now(),
    	];

    	return $this->insertGetId($insert);
    }

    /**
     * @method 查看文件唯一码是否已上传
     * @author Victoria
     * @date   2020-01-15
     * @return integer
     */
    public function getAttachmentIdByCrc32($crc32)
    {
    	if (empty($crc32)) return false;

    	$result = $this->where('crc32', $crc32)
    				   ->select('attachment_id')
    				   ->first();
    	return $result['attachment_id'] ?? 0;
    }
}
