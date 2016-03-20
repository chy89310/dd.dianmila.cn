<?php 
namespace Proxy\Model;
use Think\Model\MongoModel;

/**
* 
*/
class ProxyModel extends MongoModel
{
	protected $tableName = "Proxy";
	protected $trueTableName = "Proxy";

	public function getRecordsofPage($p)
	{
		// return $this->where(array("OKCount"=>0))->select();
		return $this->where(array("OKCount"=>0))->field(array("IP"=>true, "Port"=>true, "_id"=>false))->order('_id asc')->select();
	}
}
 ?>