<?php
header('Content-Type: text/html;charset=utf-8'); //modified by shiyf master

$a = 1;
$b = 132;
$c = $a + $b;

$result = array('code'=>'000000','msg'=>'ok','data'=>['a'=>$a,'b'=>$b,'c'=>$c]);
echo json_encode($result);
return;

interface service {
	public static function getInstance();
	public function getReport();
}

class Process implements service
{
	public static function getInstance()
	{
		return new Process();
	}
	public function getReport()
	{
		return 'are you ok';
	}
	public function cbs_search()
	{
		echo 'in '.__METHOD__.'<br/>';
		return true;
	}
	
	public function cjd_search()
	{
		echo 'in '.__METHOD__.'<br/>';
		return false;
	}
	
	private function getChannel()
	{
		return ['cbs_search','cjd_search'];
	}
	
	public function testRef()
	{
		$arrMethod = $this->getChannel();
		foreach ($arrMethod as $method) {
			$result = $this->$method();
			if($result) {
				return 3002;				
			} else {
				//写调用第三方的日志
			}
		}
	}
	
	public function https_post($url,$data){
		//$header[] = 'Content-Type: application/x-www-form-urlencoded';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER  , 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		//curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
		$re = curl_exec($ch);
		curl_close($ch);
		return $re;
	}
	

}

//$process = new Process();
//$process->testRef();

/*
$count = count($a['temp']);
echo 'count:';
var_dump($count);
echo '<br/>';


$result = $process->https_post('http://ant.pubsource.site/Repair/test', ['json'=>json_encode($a)]);
echo 'arrive here,111';
var_dump($result);

echo '<hr/>';
$introduction = ['username'=>'shiyifei','gender'=>'male'];
$state = ['code'=>123,'error'=>'what are you doing now?','introduction'=>$introduction];
$inputArr = ['vin'=>'123456', 'callbackurl'=>'3333','data'=>$state];

echo json_encode($inputArr);
*/
/*
$result = $process->https_post('http://ant.pubsource.site/pub/api/getRepairReport', $inputArr);
echo 'arrive here,111';
var_dump($result);
*/
$myinfo = "in test.php,_GET:".var_export($_GET,TRUE)."\n";
file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);
echo '<br/>';

$input = 'a:3:{s:4:"info";a:2:{s:6:"status";s:1:"5";s:7:"message";s:18:"报告查询成功";}s:5:"basic";a:8:{s:3:"vin";s:17:"WBAMU3107CC440478";s:5:"model";s:29:"530i Touring [530i 旅行车]";s:4:"year";s:4:"2012";s:5:"brand";s:6:"宝马";s:12:"displacement";s:4:"3.0L";s:7:"gearbox";s:3:"AMT";s:14:"lastRepairTime";s:10:"2016-03-19";s:16:"lastMainTainTime";s:10:"2016-03-19";}s:19:"normalRepairRecords";a:24:{i:0;a:6:{s:7:"content";s:67:"新春畅行，长途无忧--28项整车安全免费检测；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:5:"32955";s:4:"type";s:12:"内部工时";s:4:"date";s:10:"2014-02-24";s:17:"repairrecordsdate";N;}i:1;a:6:{s:7:"content";s:362:"二手车百项检测；&nbsp;二手车百项检测；&nbsp;后杠喷漆；&nbsp;钣金拆装后杠；&nbsp;后杠外修；&nbsp;后保险杠喷漆；&nbsp;拆装喷漆附件钣金；&nbsp;前杠 外修；&nbsp;空调深度清洗 5系；&nbsp;动平衡前轮和后轮；&nbsp;底盘测量 KDS， 不加载进行高度测量；&nbsp;调整后桥和前桥；&nbsp;";s:8:"material";s:306:"008806/反光器 后部 右侧；&nbsp;空调清洗器；&nbsp;轿车车轮用平衡配重块；&nbsp;轿车车轮用平衡配重块；&nbsp;轿车车轮平衡配重块；&nbsp;片簧；&nbsp;轿车车轮用平衡配重块；&nbsp;轿车车轮用平衡配重块；&nbsp;轿车车轮平衡配重块；&nbsp;";s:7:"mileage";s:5:"37578";s:4:"type";s:12:"内部工时";s:4:"date";s:10:"2014-09-05";s:17:"repairrecordsdate";N;}i:2;a:6:{s:7:"content";s:60:"进行车辆测试；&nbsp;更新监控氧传感器；&nbsp;";s:8:"material";s:34:"008991/监控氧传感器；&nbsp;";s:7:"mileage";s:5:"38883";s:4:"type";s:27:"保修，客户不用付费";s:4:"date";s:10:"2014-09-11";s:17:"repairrecordsdate";N;}i:3;a:6:{s:7:"content";s:473:"感谢您对本店的惠顾，我是您的维修顾问翟广银 移动电话:***；&nbsp;尊敬的客户，为了您爱车的行驶安全性，我们已经按标准检查并调整了您车；&nbsp;尊敬的客户，为了检测维修质量，维修完毕后会对您的车辆进行场外试；&nbsp;长途后检查；&nbsp;长途后检查；&nbsp;客户反馈：倒车大方向刹车有异响，请检查；&nbsp;进行车辆测试；&nbsp;拆装打磨后刹车片；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:5:"42792";s:4:"type";s:12:"内部工时";s:4:"date";s:10:"2014-10-04";s:17:"repairrecordsdate";N;}i:4;a:6:{s:7:"content";s:363:"尊敬的客户，为了您爱车的行驶安全性，我们已经按标准检查并调整了您车；&nbsp;机油保养  二手车赠送；&nbsp;保养标准范围；&nbsp;发动机油保养；&nbsp;客户反映：凉车时低速打到90°时有磨铁的声音；&nbsp;客户反映：主驾驶储物盒车抖的时候有异响；&nbsp;进行车辆测试；&nbsp;";s:8:"material";s:59:"机油滤芯；&nbsp;嘉实多机油5w30 208升装；&nbsp;";s:7:"mileage";s:5:"43266";s:4:"type";s:12:"内部工时";s:4:"date";s:10:"2014-11-04";s:17:"repairrecordsdate";N;}i:5;a:6:{s:7:"content";s:247:"感谢您对本店的惠顾我是您的维修顾问李伟移动电话：***；&nbsp;支 钣金喷漆右后翼子板 后杠；&nbsp;钣金辅助工时；&nbsp;右后侧围喷漆；&nbsp;后保险杠喷漆；&nbsp;2014 BMW冬季免费检测；&nbsp;";s:8:"material";s:21:"油漆辅料；&nbsp;";s:7:"mileage";s:5:"44838";s:4:"type";s:21:"客户自费，现结";s:4:"date";s:10:"2015-01-17";s:17:"repairrecordsdate";N;}i:6;a:6:{s:7:"content";s:474:"尊敬的客户，为了您爱车的行驶安全性，我们已经按标准检查并调整了您车；&nbsp;尊敬的客户，为了检测维修质量，维修完毕后会对您的车辆进行场外试；&nbsp;检查空调滤芯是否需要更换；&nbsp;空调深度清洗 5系；&nbsp;更换制动液；&nbsp;制动液保养；&nbsp;经车间技师检测；前制动片3mm，建议更换，水箱脏，建议清洗！；&nbsp;售后赠送；1000元工时代金劵；&nbsp;";s:8:"material";s:63:"空调清洗器；&nbsp;制动液；&nbsp;CAR DISINFEC；&nbsp;";s:7:"mileage";s:5:"53290";s:4:"type";s:21:"客户自费，现结";s:4:"date";s:10:"2015-04-29";s:17:"repairrecordsdate";N;}i:7;a:6:{s:7:"content";s:46:"售后赠送；1000元工时代金劵；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:5:"53290";s:4:"type";s:21:"客户自费，现结";s:4:"date";s:10:"2015-04-29";s:17:"repairrecordsdate";N;}i:8;a:6:{s:7:"content";s:168:"尊敬的客户，为了您爱车的行驶安全性，我们已经按标准检查并调整了您车；&nbsp;前轮刹车片保养；&nbsp;前部制动器保养；&nbsp;";s:8:"material";s:125:"修理套件 制动摩擦片 不；&nbsp;车用磨损传感器；&nbsp;清洁剂；&nbsp;宝马制动辅助润滑剂；&nbsp;";s:7:"mileage";s:5:"53290";s:4:"type";s:21:"客户自费，现结";s:4:"date";s:10:"2015-05-17";s:17:"repairrecordsdate";N;}i:9;a:6:{s:7:"content";s:0:"";s:8:"material";s:18:"润滑油；&nbsp;";s:7:"mileage";s:5:"54154";s:4:"type";s:12:"内部工时";s:4:"date";s:10:"2015-05-31";s:17:"repairrecordsdate";N;}i:10;a:6:{s:7:"content";s:0:"";s:8:"material";s:39:"机油滤芯；&nbsp;润滑油；&nbsp;";s:7:"mileage";s:5:"54154";s:4:"type";s:21:"客户自费，现结";s:4:"date";s:10:"2015-06-23";s:17:"repairrecordsdate";N;}i:11;a:6:{s:7:"content";s:81:"更换机油机滤；&nbsp;保养标准范围；&nbsp;发动机油保养；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:5:"54154";s:4:"type";s:12:"内部工时";s:4:"date";s:10:"2015-06-26";s:17:"repairrecordsdate";N;}i:12;a:6:{s:7:"content";s:401:"尊敬的客户，为了您爱车的行驶安全性，我们已经按标准检查并调整了您车；&nbsp;尊敬的客户，为了检测维修质量，维修完毕后会对您的车辆进行场外试；&nbsp;后部拆解估价 国泰三者 取车结账；&nbsp;拆解估价；&nbsp;出厂检测；&nbsp;拆卸和安装后保险杠饰板；&nbsp;后保险杠喷漆；&nbsp;钣金辅助工时；&nbsp;";s:8:"material";s:257:"010692/饰板 保险杠 后部；&nbsp;轿车保险杠用支架；&nbsp;010692/适配器；&nbsp;010692/盖板 后部 中部；&nbsp;盖板 右后；&nbsp;轿车车身用盖板；&nbsp;010692/导向装置 中部 后部；&nbsp;轿车车身用塞子；&nbsp;";s:7:"mileage";s:5:"56582";s:4:"type";s:21:"客户自费，现结";s:4:"date";s:10:"2015-07-03";s:17:"repairrecordsdate";N;}i:13;a:6:{s:7:"content";s:42:"拆卸和安装冷却液冷却器；&nbsp;";s:8:"material";s:33:"水箱；&nbsp;防冻剂；&nbsp;";s:7:"mileage";s:5:"58238";s:4:"type";s:27:"保修，客户不用付费";s:4:"date";s:10:"2015-08-04";s:17:"repairrecordsdate";N;}i:14;a:6:{s:7:"content";s:297:"尊敬的客户，为了您爱车的行驶安全性，我们已经按标准；&nbsp;尊敬的客户，为了检测维修质量，维修完毕后会对您的车辆进行场外试；&nbsp;客户反映：机油温度老是维持在120℃，长途往返6000km，请检查；&nbsp;长途检查；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:5:"60201";s:4:"type";s:21:"客户自费，现结";s:4:"date";s:10:"2015-09-18";s:17:"repairrecordsdate";N;}i:15;a:6:{s:7:"content";s:465:"感谢您对本店的惠顾，我是您的维修顾问焦亚鹏移动电话：***；&nbsp;尊敬的客户，为了您爱车的行驶安全性，我们已经按标准；&nbsp;尊敬的客户，为了检测维修质量，维修完毕后会对您的车辆进行场外试；&nbsp;顾客反映；油表不准，请技师检查；&nbsp;进行车辆测试；&nbsp;检查轮胎磨损程度，是否需要换位；&nbsp;拆卸和安装或更新燃油显示传感器；&nbsp;";s:8:"material";s:24:"液位传感器；&nbsp;";s:7:"mileage";s:5:"58528";s:4:"type";s:21:"客户自费，现结";s:4:"date";s:10:"2015-09-23";s:17:"repairrecordsdate";N;}i:16;a:6:{s:7:"content";s:108:"SRP后刹车片；&nbsp;后部制动器保养；&nbsp;保养标准范围；&nbsp;发动机油保养；&nbsp;";s:8:"material";s:172:"修理套件 制动摩擦片 不；&nbsp;制动摩擦片；&nbsp;机油滤芯；&nbsp;嘉实多机油5W-30 1L；&nbsp;宝马制动辅助润滑剂；&nbsp;清洁剂；&nbsp;";s:7:"mileage";s:5:"32955";s:4:"type";s:12:"内部工时";s:4:"date";s:10:"2015-10-23";s:17:"repairrecordsdate";N;}i:17;a:6:{s:7:"content";s:261:"尊敬的客户，为了您爱车的行驶安全性，我们已经按标准；&nbsp;尊敬的客户，为了检测维修质量，维修完毕后会对您的车辆进行场外试；&nbsp;二手车百项检测！；&nbsp;二手车百项检测！；&nbsp;；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:5:"64693";s:4:"type";s:12:"内部工时";s:4:"date";s:10:"2015-10-30";s:17:"repairrecordsdate";N;}i:18;a:6:{s:7:"content";s:24:"火花塞保养；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:5:"65397";s:4:"type";s:21:"客户自费，现结";s:4:"date";s:10:"2015-11-12";s:17:"repairrecordsdate";N;}i:19;a:6:{s:7:"content";s:0:"";s:8:"material";s:18:"火花塞；&nbsp;";s:7:"mileage";s:5:"65397";s:4:"type";s:21:"客户自费，现结";s:4:"date";s:10:"2015-11-12";s:17:"repairrecordsdate";N;}i:20;a:6:{s:7:"content";s:0:"";s:8:"material";s:21:"点火线圈；&nbsp;";s:7:"mileage";s:5:"65397";s:4:"type";s:27:"保修，客户不用付费";s:4:"date";s:10:"2015-11-14";s:17:"repairrecordsdate";N;}i:21;a:6:{s:7:"content";s:249:"感谢您对本店的惠顾，我是您的维修顾问宗海龙移动电话：***；&nbsp;尊敬的客户，为了您爱车的行驶安全性，我们已经按标准；&nbsp;机油保养；&nbsp;保养标准范围；&nbsp;发动机油保养；&nbsp;";s:8:"material";s:39:"机油滤芯；&nbsp;润滑油；&nbsp;";s:7:"mileage";s:5:"64693";s:4:"type";s:12:"内部工时";s:4:"date";s:10:"2015-11-20";s:17:"repairrecordsdate";N;}i:22;a:6:{s:7:"content";s:69:"更新一个以上的点火线圈；&nbsp;进行车辆测试；&nbsp;";s:8:"material";s:21:"点火线圈；&nbsp;";s:7:"mileage";s:5:"65397";s:4:"type";s:12:"内部工时";s:4:"date";s:10:"2015-11-21";s:17:"repairrecordsdate";N;}i:23;a:6:{s:7:"content";s:397:"感谢您对本店的惠顾，我是您的维修顾问徐朋达 移动电话:***；&nbsp;尊敬的客户，为了您爱车的行驶安全性，我们已经按标准；&nbsp;前部拆解估价  候  人保直齐；&nbsp;前部拆解 估价；&nbsp;注意：车主要求 前杠 不喷漆!!!；&nbsp;出厂检测；&nbsp;更换前杠 中网 前围 杠铁 前杠缓冲器；&nbsp;前杠喷漆；&nbsp;";s:8:"material";s:312:"空气动力学套件 前部 已上底漆；&nbsp;轿车车身用装饰格栅；&nbsp;轿车车身用装饰格栅；&nbsp;轿车保险杠用支架；&nbsp;膨胀铆钉；&nbsp;牌照扣；&nbsp;减震器 上部；&nbsp;空气导管；&nbsp;轿车车身用空气导管；&nbsp;轿车车身用空气导管；&nbsp;";s:7:"mileage";s:5:"68834";s:4:"type";s:21:"客户自费，现结";s:4:"date";s:10:"2016-03-19";s:17:"repairrecordsdate";N;}}}';
var_dump($input);

$output = unserialize($input);
//var_dump($output);

$normalRepairRecords = $output['normalRepairRecords'];
var_dump($normalRepairRecords);

/*
$input = 'a:3:{s:4:"info";a:2:{s:6:"status";s:1:"5";s:7:"message";s:18:"报告查询成功";}s:5:"basic";a:6:{s:3:"vin";s:17:"LSVRR21T4A2552007";s:5:"model";s:6:"途安";s:4:"year";s:6:"201012";s:5:"brand";s:12:"上海大众";s:12:"displacement";s:4:"1.4T";s:7:"gearbox";s:4:"A/MT";}s:19:"normalRepairRecords";a:26:{i:0;a:5:{s:7:"content";s:30:"销售送座垫脚垫；&nbsp;";s:8:"material";s:75:"车用脚垫（TOURAN）；&nbsp;车用冬季座垫（简约款）；&nbsp;";s:7:"mileage";s:2:"66";s:10:"repairDate";s:10:"2011-01-25";s:4:"type";s:12:"普通修理";}i:1;a:5:{s:7:"content";s:36:"检查行驶机油灯报警；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:3:"206";s:10:"repairDate";s:10:"2011-02-20";s:4:"type";s:12:"普通修理";}i:2;a:5:{s:7:"content";s:36:"右侧地大边钣金喷漆；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:3:"211";s:10:"repairDate";s:10:"2011-02-21";s:4:"type";s:15:"事故车中保";}i:3;a:5:{s:7:"content";s:114:"拆装前杠、修复前杠喷漆；&nbsp;更换中网、左后视镜转向灯；&nbsp;更换水箱框架；&nbsp;";s:8:"material";s:190:"转向灯；&nbsp;通风栅罩(棉缎黑色)；&nbsp;通风格栅(棉缎黑色/亮铬色)；&nbsp;通风栅罩(棉缎黑色)；&nbsp;10后/带水冷却器支撑的锁架棉缎黑色；&nbsp;";s:7:"mileage";s:4:"2745";s:10:"repairDate";s:10:"2011-08-11";s:4:"type";s:15:"事故车中保";}i:4;a:5:{s:7:"content";s:153:"右侧底大边、右前门、右后门钣金喷漆；&nbsp;右后叶子板、后杠钣金喷漆；&nbsp;右侧后视镜喷漆；&nbsp;总工时；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:4:"2745";s:10:"repairDate";s:10:"2011-08-11";s:4:"type";s:15:"事故车中保";}i:5;a:5:{s:7:"content";s:126:"前杠及右前叶子板钣金喷漆；&nbsp;更换右前转向拉杆并四轮定位；&nbsp;更换前杠右侧托架；&nbsp;";s:8:"material";s:30:"TOURAN转向横拉杆；&nbsp;";s:7:"mileage";s:4:"3907";s:10:"repairDate";s:10:"2011-10-05";s:4:"type";s:15:"事故车中保";}i:6;a:5:{s:7:"content";s:654:"轮胎磨损及轮胎气压情况(检查)；&nbsp;车轮固定螺栓(检查)；&nbsp;刹车片厚度(检查)；&nbsp;发动机舱内各管路和接头(检查)；&nbsp;冷却液液位(检查)；&nbsp;机油液位(检查)；&nbsp;蓄电池电压；&nbsp;雨刮器和车窗清洗装置(检查)；&nbsp;安全气囊和安全带(检查)；&nbsp;车内外照明、仪表显示检查；&nbsp;手制动器(检查)；&nbsp;清洗车辆或发放免费洗车券(检查)；&nbsp;用户付款方式   现金□  刷卡□ 支票□；&nbsp;旧件处理方式  经销商处理□  用户带走□；&nbsp;用户等待方式   站内等待□   离站电联□；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:4:"3907";s:10:"repairDate";s:10:"2011-10-05";s:4:"type";s:12:"普通修理";}i:7;a:5:{s:7:"content";s:0:"";s:8:"material";s:0:"";s:7:"mileage";s:4:"4993";s:10:"repairDate";s:10:"2011-11-23";s:4:"type";s:15:"事故车中保";}i:8;a:5:{s:7:"content";s:712:"首次7500公里免费保养；&nbsp;免费检测；&nbsp;轮胎磨损及轮胎气压情况(检查)；&nbsp;车轮固定螺栓(检查)；&nbsp;刹车片厚度(检查)；&nbsp;发动机舱内各管路和接头(检查)；&nbsp;冷却液液位(检查)；&nbsp;机油液位(检查)；&nbsp;蓄电池电压；&nbsp;雨刮器和车窗清洗装置(检查)；&nbsp;安全气囊和安全带(检查)；&nbsp;车内外照明、仪表显示检查；&nbsp;手制动器(检查)；&nbsp;清洗车辆或发放免费洗车券(检查)；&nbsp;用户付款方式   现金□  刷卡□ 支票□；&nbsp;旧件处理方式  经销商处理□  用户带走□；&nbsp;用户等待方式   站内等待□   离站电联□；&nbsp;";s:8:"material";s:57:"CFB机油/途观；&nbsp;机油滤清器/CLS/CLP；&nbsp;";s:7:"mileage";s:4:"4993";s:10:"repairDate";s:10:"2011-11-23";s:4:"type";s:12:"首次保养";}i:9;a:5:{s:7:"content";s:0:"";s:8:"material";s:0:"";s:7:"mileage";s:4:"8517";s:10:"repairDate";s:10:"2012-02-02";s:4:"type";s:12:"免费检测";}i:10;a:5:{s:7:"content";s:368:"轮胎底盘检测包；&nbsp;按15000公里规范常规保养；&nbsp;车辆外观免费清洁 是□  否□；&nbsp;旧件处理方式  经销商处理□  用户带走□；&nbsp;用户等待方式   站内等待□   离站电联□；&nbsp;dsg200；&nbsp;用户付款方式   现金□  刷卡□ 支票□；&nbsp;回访时间   上午□     下午□；&nbsp;";s:8:"material";s:147:"机油滤清器/CLS；&nbsp;便携折叠包；&nbsp;CFB机油/途观；&nbsp;空滤器滤芯/新途安；&nbsp;汽油清净剂（小瓶）；&nbsp;";s:7:"mileage";s:4:"9937";s:10:"repairDate";s:10:"2012-04-10";s:4:"type";s:9:"预   约";}i:11;a:5:{s:7:"content";s:66:"配备7速DQ200自动变速箱车辆的软件升级活动；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:4:"9937";s:10:"repairDate";s:10:"2012-04-10";s:4:"type";s:9:"索   赔";}i:12;a:5:{s:7:"content";s:97:"更换机滤、机油(合成机油）；&nbsp;空调管路养护套装（换空调芯）；&nbsp;";s:8:"material";s:239:"机芯（途安、途观、新帕、LY09.5.1后）12/1；&nbsp;油底螺丝（B5、PLO）12/12/04；&nbsp;空调芯（TUAN、TIGUAN）12/07/31；&nbsp;空调系统清洗养护套装12/09/05；&nbsp;原装壳牌小桶机油12/10/23；&nbsp;";s:7:"mileage";s:5:"14892";s:10:"repairDate";s:10:"2012-12-07";s:4:"type";s:12:"快速保养";}i:13;a:5:{s:7:"content";s:63:"更换行李箱盖内饰板；&nbsp;免费洗车服务；&nbsp;";s:8:"material";s:124:"旋钮(纯米色)12.12.18；&nbsp;车盖锁用罩盖(TUAN、纯米色)；&nbsp;车盖锁用罩盖(TUAN、纯米色)；&nbsp;";s:7:"mileage";s:5:"15748";s:10:"repairDate";s:10:"2013-01-16";s:4:"type";s:6:"索赔";}i:14;a:5:{s:7:"content";s:36:"更换后备箱下部饰板；&nbsp;";s:8:"material";s:48:"后备门坎外饰板（TUAN）13/01/16；&nbsp;";s:7:"mileage";s:5:"15900";s:10:"repairDate";s:10:"2013-01-20";s:4:"type";s:6:"索赔";}i:15;a:5:{s:7:"content";s:32:"201301-1071配件索赔；&nbsp;";s:8:"material";s:116:"旋钮(纯米色)13/01/16；&nbsp;后备门坎外饰板（TUAN）13/01/30；&nbsp;旋钮(纯米色)13/01/16；&nbsp;";s:7:"mileage";s:5:"15910";s:10:"repairDate";s:10:"2013-01-20";s:4:"type";s:6:"索赔";}i:16;a:5:{s:7:"content";s:68:"更换行李箱锁架饰板；&nbsp;配件索赔201301-1071；&nbsp;";s:8:"material";s:48:"后备门坎外饰板（TUAN）13/01/22；&nbsp;";s:7:"mileage";s:5:"15887";s:10:"repairDate";s:10:"2013-01-23";s:4:"type";s:6:"索赔";}i:17;a:5:{s:7:"content";s:297:"前盖喷漆；&nbsp;前盖修复；&nbsp;前杠喷漆；&nbsp;右前门喷漆；&nbsp;右后门喷漆；&nbsp;后杠喷漆；&nbsp;左后叶子板喷漆；&nbsp;左侧底大边喷漆；&nbsp;右前叶子板喷漆；&nbsp;更换右后尾灯；&nbsp;右侧底大边喷；&nbsp;钣金拆装；&nbsp;";s:8:"material";s:161:"尾灯（2008.01.01后）；&nbsp;导向槽（新途安）；&nbsp;尾灯（2008.01.01后）；&nbsp;尾灯；&nbsp;尾灯；&nbsp;亮条；&nbsp;饰板；&nbsp;";s:7:"mileage";s:5:"17778";s:10:"repairDate";s:10:"2013-04-11";s:4:"type";s:12:"保险自费";}i:18;a:5:{s:7:"content";s:69:"DQ200自动变速箱更换机电控制单元召回行动(VW)；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:5:"21903";s:10:"repairDate";s:10:"2013-08-27";s:4:"type";s:12:"召回行动";}i:19;a:5:{s:7:"content";s:59:"合成油；&nbsp;按10000公里规范常规保养；&nbsp;";s:8:"material";s:237:"机芯（途安途观新帕、LY09.5.1后）13/06/18；&nbsp;空滤芯(途安 1.4T)13/08/06；&nbsp;汽芯（LY1.4T、TUAN1.4T TIGUAN）13/06/04；&nbsp;油底螺丝（B5、PLO）13/06/18；&nbsp;原装壳牌小桶机油13/07/23；&nbsp;";s:7:"mileage";s:5:"21903";s:10:"repairDate";s:10:"2013-08-27";s:4:"type";s:12:"常规保养";}i:20;a:5:{s:7:"content";s:349:"按30000公里规范常规保养；&nbsp;更换制动液(含ABS系统放空气)；&nbsp;清洗节气门体(工艺-MR05)；&nbsp;更换三滤、机油（合成机油）；&nbsp;三元催化器清洗；&nbsp;清洗燃烧室；&nbsp;线路养护套装；&nbsp;更换火花塞（4缸）；&nbsp;清洗进气道；&nbsp;清洗喷油嘴（4缸）；&nbsp;";s:8:"material";s:378:"机油滤清器/CLS；&nbsp;空滤器滤芯/新途安；&nbsp;燃油滤清器/CFB/TIGUAN；&nbsp;放油螺栓B5/POLO/朗逸；&nbsp;黑壳机油；&nbsp;进气系统养护套餐；&nbsp;燃油系统养护套餐；&nbsp;汽油清净剂（小瓶）；&nbsp;三元清洗剂；&nbsp;燃烧室清洗剂；&nbsp;XS长命火花塞/CFB；&nbsp;制动液；&nbsp;线路保护剂；&nbsp;";s:7:"mileage";s:5:"29550";s:10:"repairDate";s:10:"2014-03-04";s:4:"type";s:25:"常规保养;一般维修";}i:21;a:5:{s:7:"content";s:466:"按5000公里建议(更换机油、机滤)；&nbsp;拆装或更换蓄电池(工艺-MR08)；&nbsp;灰壳；&nbsp;检查轮胎、电脑检测气囊灯亮、有时发动机故障；&nbsp;车辆外观免费清洁 是□  否□；&nbsp;用户付款方式   现金□  刷卡□ 支票□；&nbsp;回访时间   上午□     下午□；&nbsp;旧件处理方式  经销商处理□  用户带走□；&nbsp;用户等待方式   站内等待□   离站电联□；&nbsp;";s:8:"material";s:160:"机油滤清器/CLS；&nbsp;黑壳机油；&nbsp;放油螺栓/途安；&nbsp;蓄电池/NEWPOLO/51AH/280A；&nbsp;1.5升车窗用清洁浓缩剂(-30℃)；&nbsp;";s:7:"mileage";s:5:"36679";s:10:"repairDate";s:10:"2015-01-26";s:4:"type";s:12:"常规保养";}i:22;a:5:{s:7:"content";s:119:"拆装或更换后雨刮片(每付)(工艺-MR09)；&nbsp;按10000公里规范常规保养；&nbsp;灰壳机油；&nbsp;";s:8:"material";s:256:"机芯（途安1.4T、LY09.5.1后）14/05/22；&nbsp;空气滤芯(途安 1.4T)14/05/22；&nbsp;汽芯（LY1.4T、TUAN1.4T TIGUAN）14/05/22；&nbsp;油底螺丝（B5、PLO）14/03/18；&nbsp;全合成机油；&nbsp;后雨刷片（途安）13/09/05；&nbsp;";s:7:"mileage";s:5:"44831";s:10:"repairDate";s:10:"2015-11-21";s:4:"type";s:25:"常规保养;一般维修";}i:23;a:5:{s:7:"content";s:369:"人寿自费；&nbsp;右后叶子板整形；&nbsp;右后叶子板喷漆；&nbsp;右后门整形；&nbsp;右后门喷漆；&nbsp;行车建议：春夏市区风沙较大,勤换空调及空气过滤网；&nbsp;我们已经免费为您检测了：胎压、灯光、底盘等12项内容；&nbsp;行车建议:风窗若起雾尽量开窗或AC键制冷使空气流通；&nbsp;";s:8:"material";s:0:"";s:7:"mileage";s:5:"44894";s:10:"repairDate";s:10:"2015-11-23";s:4:"type";s:15:"事故车维修";}i:24;a:5:{s:7:"content";s:34:"抽真空加冷媒(R134a)；&nbsp;";s:8:"material";s:98:"134A制冷剂13/06/06；&nbsp;旋钮(纯米色)11/11/15；&nbsp;锁舌(纯米色)13/07/30；&nbsp;";s:7:"mileage";s:5:"48839";s:10:"repairDate";s:10:"2016-06-04";s:4:"type";s:12:"一般维修";}i:25;a:5:{s:7:"content";s:60:"2004~2012款途安更换灯光保险丝召回行动；&nbsp;";s:8:"material";s:30:"扁平保险丝19/2X5；&nbsp;";s:7:"mileage";s:5:"51575";s:10:"repairDate";s:10:"2016-11-16";s:4:"type";s:12:"召回行动";}}}';
$output = unserialize($input);
echo json_encode($output);
*/


	
