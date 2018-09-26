<?php
include_once  WEB_ROOT."util/config.php";

class Model{

	private $pdo;
	private $rs;

	public function __construct($ut = "gb2312"){
		try{
		    $this->connect();
        }catch(PDOException $e){  
            echo $e->getMessage();  
        }
	}
	
	public function errorInfo(){
	    return $this->pdo->errorInfo();
	}
	
	private function connect(){
	    $user = $GLOBALS ['database'] ['db_user'];
		$passwd = $GLOBALS ['database'] ['passwd'];
		$dbname = $GLOBALS ['database'] ['dbname'];
		$host_name = $GLOBALS ['database'] ['hostname'];
		$para = array(
               PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';",
           //这里连接时一定要指定异常 否则下面无法捕获异常
               PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
               PDO::ATTR_PERSISTENT => true
            );
	    $this->pdo=new PDO("mysql:host=".$host_name.";dbname=".$dbname,$user,$passwd,$para);
	}
	/**
	 *
	 * @param string $sql
	 * @throws Exception
	 */
	public function query($sql) {
		try{
		    $this->rs = $this->pdo -> query($sql);
			return $this->rs;
		}catch (Exception $e){
			echo $e->getMessage(), '<br/>';
			echo '<pre>', $e->getTraceAsString(), '</pre>';
			echo '<strong>Query: </strong> ' . $sql;
		}
	}

	public function query_with_array($sql,$array){
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($array);
		return $stmt->fetchAll();
	}
	
	public function exec($sql){
	    try{
	        return $this->pdo->exec($sql);
	    }catch(Exception $e){
	        if(strpos($e->getMessage(), 'MySQL server has gone away')!==false){
                $this->connect();
                return $this->pdo->exec($sql);
            }
	    }
	}
	/**
	 *
	 * @param string $tablename
	 * @param string $fields
	 * @param string $condition
	 * @return array
	 */
	public function select($table,$fields="*",$condition = "1=1"){
	    $tmp_sql = "";
		try{
			if (empty($table) || empty($fields) || empty($condition))
			{
				throw new Exception('查询数据的表名，字段，条件不能为空', 444);
			}
			$tmp_sql = "SELECT {$fields} FROM `{$table}` WHERE {$condition}";
			$this->rs = $this->query($tmp_sql);
			return $this->fetch_all();

		}catch (Exception $e){
			echo $e->getMessage(), '<br/>';
			echo '<pre>', $e->getTraceAsString(), '</pre>';
			echo '<strong>Query: </strong>[select] ', (!empty($tmp_sql)) && $tmp_sql;
		}
	}

	/**
	 * @param string $table
	 * @param array $data
	 * @param string $condition
	 * @return int
	 */
	public  function update($table, $data, $condition)
	{
	    $tmp_sql = "";
		try
		{
			if (empty($table) || empty($data) || empty($condition))
			throw new Exception('更新数据的表名，数据，条件不能为空', 444);

			if(!is_array($data))
			throw new Exception('更新数据必须是数组', 444);

			$set = '';
			foreach ($data as $k => $v)
			$set .= empty($set) ? ("`{$k}` = '{$v}'") : (", `{$k}` = '{$v}'");

			if (empty($set)) throw new Exception('更新数据格式化失败', 444);

			$tmp_sql = "UPDATE `{$table}` SET {$set} WHERE {$condition}";
			$result = $this->pdo->exec($tmp_sql);
			// 返回影响行数
			return $result;
		}
		catch (Exception $e)
		{
			echo $e->getMessage(), '<br/>';
			echo '<pre>', $e->getTraceAsString(), '</pre>';
			echo '<strong>Query: </strong>[update]' . (!empty($tmp_sql)) && $tmp_sql;
		}
	}

	/**
	 * 插入数据
	 *
	 * @param string $table
	 * @param array $fields
	 * @param array $data
	 * @return boolean
	 */
	public function insert($table, $fields, $data)
	{
	    $tmp_sql = "";
		try
		{
			if (empty($table) || empty($fields) || empty($data)) {
				throw new Exception('插入数据的表名，字段、数据不能为空', 444);
			}

			if (!is_array($fields) || !is_array($data))
			{
				throw new Exception('插入数据的字段和数据必须是数组', 444);
			}

			// 格式化字段
			$_fields = '`' . implode('`, `', $fields) . '`';

			// 格式化需要插入的数据
			$_data = $this->format_insert_data($data);

			if (empty($_fields) || empty($_data))
			{
				throw new Exception('插入数据的字段和数据必须是数组', 444);
			}

			$tmp_sql = "INSERT INTO `{$table}` ({$_fields}) VALUES {$_data}";
			$result = $this->pdo->exec($tmp_sql);

			return $result;
		}
		catch (Exception $e)
		{
			echo $e->getMessage(), '<br/>';
			echo '<pre>', $e->getTraceAsString(), '</pre>';
			echo '<strong>Query: </strong>[insert] ' . (!empty($tmp_sql)) && $tmp_sql;

		}
	}



	/**
	 * 格式化 insert 数据，将数组（二维数组）转换成向数据库插入记录时接受的字符串
	 *
	 * @param array $data
	 * @return string
	 */
	protected  function format_insert_data($data)
	{
		if (!is_array($data) || empty($data))
		{
			throw new Exception('数据的类型不是数组', 445);
		}

		$output = '';
		foreach ($data as $value)
		{
			// 如果是二维数组
			if (is_array($value))
			{
				$tmp = '(\'' . implode("', '", $value) . '\')';
				$output .= !empty($output) ? ", {$tmp}" : $tmp;
				unset($tmp);
			}
			else
			{
				$output = '(\'' . implode("', '", $data) . '\')';
			}
		} //foreach

		return $output;
	}


	/**
	 * 删除记录
	 *
	 * @param string $table
	 * @param string $condition
	 * @return num
	 */
	public function delete($table, $condition)
	{
	    $tmp_sql = "";
		try
		{
			if (empty($table) || empty($condition))
			{
				throw new Exception('表名和条件不能为空', 444);
			}

			$tmp_sql = "DELETE FROM `{$table}` WHERE {$condition}";
			$result = $this->pdo->exec($tmp_sql);

			return $result;
		}
		catch (Exception $e)
		{
			echo $e->getMessage(), '<br/>';
			echo '<pre>', $e->getTraceAsString(), '</pre>';
			echo '<strong>Query: </strong>[delete] ' . (!empty($tmp_sql)) && $tmp_sql;
		}
	}


	/**
	 * 查询记录数
	 *
	 * @param string $table
	 * @param string $condition
	 * @return int
	 */
	public  function get_rows_num($table, $condition)
	{
	    $tmp_sql = "";
		try
		{
			if (empty($table) || empty($condition))
			throw new Exception('查询记录数的表名，字段，条件不能为空', 444);

			$tmp_sql = "SELECT count(*) AS total FROM {$table} WHERE {$condition}";
			$this->rs = $this->query($tmp_sql);

			$tmp = $this->fetch_one();
			return (empty($tmp)) ? false : $tmp['total'];
		}
		catch (Exception $e)
		{
			echo $e->getMessage(), '<br/>';
			echo '<pre>', $e->getTraceAsString(), '</pre>';
			echo '<strong>Query: </strong>[rows_num] ' . (!empty($tmp_sql)) && $tmp_sql;
		}
	}

	/**
	 * (读)返回单条记录数据
	 *
	 * @param  int   $result_type
	 * @return array
	 */
	public  function fetch_one ()
	{
	    if($this->rs){
	        return $this->rs -> fetch();
	    }else{
	        return null;
	    }
	}

	/**
	 * (读)返回多条记录数据
	 *
	 * @param   int   $result_type
	 * @return  array
	 */
	public  function fetch_all ()
	{
		$row = $rows = array();
		while ($this->rs && $row = $this->rs -> fetch())
		{
			$rows[] = $row;
		}
		if (empty($rows))
		{
			return false;
		}
		else
		{
			return $rows;
		}
	}
	
	function __destruct() {
		/*if($this->link){
			mysql_close ( $this->link );
		}*/
	}
	
        /**
 * 检查连接是否可用
 * @param  Link $dbconn 数据库连接
 * @return Boolean
 */
    function pdo_ping($dbconn){
        try{
            $this->pdo->getAttribute(PDO::ATTR_SERVER_INFO);
        } catch (PDOException $e) {
            if(strpos($e->getMessage(), 'MySQL server has gone away')!==false){
                return false;
            }
        }
      return true;
    }   

}

?>