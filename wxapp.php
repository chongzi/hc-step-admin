<?php

defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT."/addons/hc_step/inc/model.class.php";
require_once IA_ROOT."/addons/hc_step/wxBizDataCrypt.php";
class Hc_stepModuleWxapp extends WeModuleWxapp {
    
    public function doPageTest(){
        ob_end_clean();
        global $_GPC, $_W;
        $post_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->wx_get_token();
        $post_data = '';

        $json = $this->api_notice_increment($post_url,$post_data);
        $json=json_decode($json,true);
        return $this->result(0, '获取成功',$json);
    }
    
    public function get_url_content($url)
    {
        $user_agent = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)";
        //$data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            //'Content-Length: ' . strlen($data_string)
        ));

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function doPageGetopenid()
    {
        ob_end_clean();
        global $_GPC, $_W;
        $code = $_GPC['code'];
        $account = pdo_get('account_wxapp', array('uniacid' => $_GPC['i']));
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $account['key'] . '&secret=' . $account['secret'] . '&js_code=' . $code . '&grant_type=authorization_code';
        $result = $this->get_url_content($url);
        $result = json_decode($result, true);
        return $this->result(0, '获取成功', $result);
    }
    

    public function doPageZhuce()
    {
        ob_end_clean();
        global $_GPC, $_W;
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        $data['uniacid'] = $_GPC['i'];
        $data['city'] = $_POST['city'];
        $data['country'] = $_POST['country'];
        $data['gender'] = $_POST['gender'];
        $data['open_id'] = $_POST['open_id'];
        $data['nick_name'] = $_POST['nickName'];
        $data['head_pic'] = $_POST['avatarUrl'];
        $data['province'] = $_POST['province'];
        $stact = 1;
        if(empty($data['open_id'])){
            $this->result(1, $stact, '');
        }
        $uid = pdo_getcolumn('hcstep_users', array('open_id' => $data['open_id'], 'uniacid' => $data['uniacid']), 'user_id', 1);
        if (empty($uid)){
            if($set['invitetype'] == 1){
                $stact = 0;
                //记录父id
                if($_POST['goods_id'] > 0){                 
                    $result = pdo_insert('hcstep_users', $data, $replace = true);
                    $uid = pdo_insertid();
                    //添加一条邀请记录
                    $log['uniacid'] = $_GPC['i'];
                    $log['user_id'] = $_POST['invite'];
                    $log['son_id'] = $uid;
                    $log['goods_id'] = $_POST['goods_id'];
                    $log['time'] = time();
                    if($log['user_id'] > 0){
                        $invitelog = pdo_insert('hcstep_peoplelog', $log, $replace = true);
                    }
                }else{

                    $data['fatherid'] = $_POST['invite'];                  
                    $result = pdo_insert('hcstep_users', $data, $replace = true);
                    $uid = pdo_insertid();
                    //发红包
                    if(!empty($_GPC['sethong'])){
                        $daytime = date('Y-m-d',time());
                        $today = pdo_getall('hcstep_fourhblog',array('user_id'=>$_POST['invite'],'uniacid'=>$_GPC['i'],'type'=>2,'daytime'=>$daytime));
                        if(count($today) < 4){
                           $setset = pdo_get('hcstep_message',array('uniacid'=>$_GPC['i']));
                            $min = $setset['min_smallhbmoney']*100;
                            $max = $setset['max_smallhbmoney']*100;
                            $aaa = mt_rand($min,$max);
                            $res['uniacid'] = $_GPC['i'];
                            $res['user_id'] = $_POST['invite'];
                            $res['son_id'] = $uid;
                            $res['hbmoney'] = $aaa/100;
                            $res['time'] = time();
                            $res['daytime'] = date('Y-m-d',time());
                            $res['type'] = 2;
                            $res['status'] = 1; 
                            pdo_insert('hcstep_fourhblog', $res, $replace = true);
                        }                       
                    }                   
                   //添加一条邀请记录
                    $log['uniacid'] = $_GPC['i'];
                    $log['user_id'] = $data['fatherid'];
                    $log['sonid'] = $uid;
                    $log['step'] = $set['sharestep'];
                    $log['invite_time'] = time();
                    $log['time'] = date('Y-m-d',time());
                    $log['status'] = 0;
                    if($log['user_id'] > 0){
                        $invitelog = pdo_insert('hcstep_invitelog', $log, $replace = true);
                    }
                }               
            }
            if($set['invitetype'] == 2){
                $stact = 0;
                //记录父id
                if($_POST['goods_id'] > 0){                 
                    $result = pdo_insert('hcstep_users', $data, $replace = true);
                    $uid = pdo_insertid();
                    //添加一条邀请记录
                    $log['uniacid'] = $_GPC['i'];
                    $log['user_id'] = $_POST['invite'];
                    $log['son_id'] = $uid;
                    $log['goods_id'] = $_POST['goods_id'];
                    $log['time'] = time();
                    if($log['user_id'] > 0){
                        $invitelog = pdo_insert('hcstep_peoplelog', $log, $replace = true);
                    }
                }else{
                    $data['hongbaofid'] = $_POST['invite'];                  
                    $result = pdo_insert('hcstep_users', $data, $replace = true);
                    $uid = pdo_insertid();
                    //添加一条邀请记录
                    $log['uniacid'] = $_GPC['i'];
                    $log['user_id'] = $data['hongbaofid'];
                    $log['sonid'] = $uid;
                    $log['invite_time'] = time();
                    $log['status'] = 0;
                    if($log['user_id'] > 0){
                        $invitelog = pdo_insert('hcstep_hongbaolog', $log, $replace = true);
                        $hongbao = pdo_getall('hcstep_hongbaolog',array('user_id'=>$log['user_id'],'uniacid'=>$_GPC['i']));
                        $count = count($hongbao);
                        $hbmoney = pdo_getcolumn('hcstep_hongbao',array('displayorder'=>$count,'uniacid'=>$_GPC['i']),'hongbaomoney',1);
                        if($hbmoney){
                           $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$log['user_id']));
                           $nowmoney = $user['money'] + $hbmoney;
                           pdo_update('hcstep_users',array('money'=>$nowmoney),array('user_id'=>$log['user_id']));
                        }
                    }
                }                
            }            
        }else{
            $result = pdo_update('hcstep_users', $data, array('user_id' => $uid));
        }

        $mc_have = pdo_get('mc_mapping_fans',array('openid'=>$data['open_id']));
        if(empty($mc_have)){
            $mc_data = array(
                'acid' =>$data['uniacid'],
                'uniacid' =>$data['uniacid'],
                'uid' =>$uid,
                'openid' =>$data['open_id'],
                'nickname' =>$data['nick_name'],
            );
            pdo_insert('mc_mapping_fans',$mc_data);
        }
        
        $this->result(0, $stact, $uid);
    }

    //是否拉黑
    public function doPageBlack(){
        global $_GPC, $_W;       
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));              
        return $this->result(0, '是否拉黑',$user['black']);
    }

    //签到
    public function doPageSignin(){
        
        global $_GPC, $_W;        
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        /**如果有就进行判断时间差，然后处理签到次数*/
        /**昨天的时间戳时间范围*/
        $t = time();
        $last_start_time = mktime(0,0,0,date("m",$t),date("d",$t)-1,date("Y",$t));
        $last_end_time = mktime(23,59,59,date("m",$t),date("d",$t)-1,date("Y",$t));
        /**今天的时间戳时间范围*/
        $now_start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $now_end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        /**判断最后一次签到时间是否在昨天的时间范围内*/
        if($last_start_time<$user['lasttime']&&$user['lasttime']<$last_end_time){
            $user['lasttime'] = time();
            $user['signtime'] = $user['signtime'] + 1;
            //$user['money'] = $user['money'] + 2;
            /**这里还可以加一些判断连续签到几天然后加积分等等的操作*/
            $result = pdo_update('hcstep_users',$user,array('user_id' => $_GPC['user_id']));
            return $this->result(0, '获取成功',$result);
        }elseif($now_start_time<$user['lasttime']&&$user['lasttime']<$now_end_time){
            /**返回已经签到的操作*/
            $user['lasttime'] = time();
            //$user['signtime'] = 1;
            $result = pdo_update('hcstep_users',$user,array('user_id' => $_GPC['user_id']));
            return $this->result(0, '获取成功',$result);
        }else{
            $user['lasttime'] = time();
            $user['signtime'] = 1;
            $result = pdo_update('hcstep_users',$user,array('user_id' => $_GPC['user_id']));
            return $this->result(0, '获取成功',$result);
        }
        
    }

    //签到分享
    public function doPageSignshare(){
        global $_GPC, $_W;        
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        /**如果有就进行判断时间差，然后处理签到次数*/
        /**昨天的时间戳时间范围*/
        $t = time();
        //$last_start_time = mktime(0,0,0,date("m",$t),date("d",$t)-1,date("Y",$t));
        //$last_end_time = mktime(23,59,59,date("m",$t),date("d",$t)-1,date("Y",$t));
        /**今天的时间戳时间范围*/
         $now_start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
         $now_end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        /**判断最后一次签到时间是否在昨天的时间范围内*/
        if($now_start_time<$user['sharetime']&&$user['sharetime']<$now_end_time){
            /**这里还可以加一些判断连续签到几天然后加积分等等的操作*/
            return $this->result(0, '今天已经分享过',$user);
        }else{
            /**返回已经签到的操作*/
            $user['sharetime'] = time();
            $user['money'] = $user['money'] + $set['signsharemoney'];
            $result = pdo_update('hcstep_users',$user,array('user_id' => $_GPC['user_id']));
            return $this->result(0, '分享成功',$result);
        }
    }

    //审核状态
    public function doPageStatus(){
        global $_GPC, $_W;
        $config = pdo_get('hczhongzhuan_set',array('uniacid'=>$_GPC['i']),array('status'));       
         
        return $this->result(0, '审核状态',$config);
    }
    //页面
    /*public function doPageShenhe(){
        global $_GPC, $_W;
        $shenhe = pdo_getall('hczhongzhuan_shenhe', array('stact'=>1,'uniacid' => $_GPC['i']), array(),'','sort asc');
        foreach ($shenhe as $key => $val) {
            $shenhe[$key]['icon'] = $_W['attachurl'].$val['img'];
        }      
         
        ob_end_clean();
        global $_GPC, $_W;
        $shenhe=pdo_get('hczhongzhuan_shenhe', array('id' => $_GPC['id']));
        $this->result(0, '获取成功', $shenhe);
        //return $this->result(0, '页面',$shenhe);
    }*/
    
    function api_notice_increment($url, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function doPagebushu(){
        global $_GPC, $_W;
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));

        $code = $_GPC['code'];
        $account = pdo_get('account_wxapp', array('uniacid' => $_GPC['i']));
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $account['key'] . '&secret=' . $account['secret'] . '&js_code=' . $code . '&grant_type=authorization_code';
        $result = $this->get_url_content($url);
        $result = json_decode($result, true);

        $uid           = $_GPC['user_id'];
        $encryptedData = $_GPC['wRunEncryptedData'];
        $iv            = $_GPC['iv'];
        $sessionKey    = $result['session_key'];
        $appid = $_W['account']['key'];
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            $bushu = json_decode($data,'true');
            $bushu30 = $bushu['stepInfoList'];
            $bushu7 = array();
            //前七日数据
            $bushu7[0] = $bushu['stepInfoList'][23]['step']; 
            $bushu7[1] = $bushu['stepInfoList'][24]['step']; 
            $bushu7[2] = $bushu['stepInfoList'][25]['step']; 
            $bushu7[3] = $bushu['stepInfoList'][26]['step']; 
            $bushu7[4] = $bushu['stepInfoList'][27]['step']; 
            $bushu7[5] = $bushu['stepInfoList'][28]['step']; 
            $bushu7[6] = $bushu['stepInfoList'][29]['step']; 

            $bushu = $bushu['stepInfoList'][30]['step'];

            $weixinbushu = $bushu;
            
            $time=date('Y-m-d',time());
            //兑换记录
            $log = pdo_getall('hcstep_bushulog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'time'=>$time));
            $zong = array_sum(array_column($log,'bushu'));
            //邀请记录
            $invitelog = pdo_getall('hcstep_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'status'=>1,'time'=>$time));
            $invitezong = array_sum(array_column($invitelog,'step'));
            //步数加成比例
            $invitelogall = pdo_getall('hcstep_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']));
            $invitelogtoday = pdo_getall('hcstep_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'time'=>$time));
            $count = count($invitelogall) - count($invitelogtoday);
            //步数加成
            $uplog = pdo_getall('hcstep_uplog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'day'=>$time));
            $upzong = array_sum(array_column($uplog,'step'));
            $upbushu = $bushu * $count*$set['up']/100 - $upzong;
            $upbushu = floor($upbushu);
            //关注送步数
            $guanzhu = pdo_get('hcstep_guanzhulog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'status'=>1));
            //看视频送步数
            $shipin = pdo_get('hcstep_shipinlog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'status'=>1,'daytime'=>$time));
            //任务记录
            $missionlog = pdo_getall('hcstep_missionlog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'status'=>1,'daytime'=>$time));
            $missionzong = array_sum(array_column($missionlog,'step'));

            $bushu = $bushu + $invitezong + $upzong + $guanzhu['step'] + $shipin['step'] + $missionzong - $zong ;
             
            return $this->result(0, '操作成功',array('bushu'=>$bushu,'user'=>$user,'upbushu'=>$upbushu,'weixinbushu'=>$weixinbushu,'bushu30'=>$bushu7));
        } else {
            return $this->result(1, array('errcode'=>$errCode,'appid'=>$appid));
        }

    }

    public function doPagebushu2money(){
        
        global $_GPC, $_W;
        
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        $time=date('Y-m-d',time());
        $log = pdo_getall('hcstep_bushulog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'time'=>$time));
        $zong = array_sum(array_column($log,'bushu'));
       
        $data['bushu'] = $_GPC['step'];
        $data['user_id'] = $_GPC['user_id'];
        $money = $data['bushu']*$set['rate'];

        if($data['bushu'] == 0){
           return $this->result(1,$set['zerotip']);
        }elseif($zong >= $set['maxstep'] and !empty($zong)){
           return $this->result(1,'今日已达到步数兑换上限，请明日再来吧');
        }else{
           if(($data['bushu'] +$zong) > $set['maxstep']){
             $money = ($set['maxstep'] - $zong)*$set['rate'];
             return $this->result(0,'',$money);
           }else{
             return $this->result(0,'',$money);
           }         
        }      
    }

    public function doPagebushulog(){
        global $_GPC, $_W;
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        
        $time=date('Y-m-d',time());
        $log = pdo_getall('hcstep_bushulog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'time'=>$time));
        $zong = array_sum(array_column($log,'bushu'));
        
        $data['bushu'] = $_GPC['step'];
        $data['user_id'] = $_GPC['user_id'];
        $data['uniacid'] = $_GPC['i'];
        $data['timestamp'] = time();
        $data['time'] = date('Y-m-d',time());
        $data['money'] = $data['bushu']*$set['rate'];

        if($data['bushu'] == 0){
           return $this->result(1,$set['zerotip']);
        }elseif(($data['bushu'] + $zong) >= $set['maxstep']){
            $data['bushu'] = $set['maxstep'] - $zong;
            $data['user_id'] = $_GPC['user_id'];
            $data['uniacid'] = $_GPC['i'];
            $data['timestamp'] = time();
            $data['time'] = date('Y-m-d',time());
            $data['money'] = $data['bushu']*$set['rate'];

            $result = pdo_insert('hcstep_bushulog',$data);

            $nowmoney = $user['money']+$data['money'];

            pdo_update('hcstep_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));  

            return $this->result(0, '操作成功',$result);         
        }else{
           $result = pdo_insert('hcstep_bushulog',$data);

           $nowmoney = $user['money']+$data['money'];

           pdo_update('hcstep_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));  

           return $this->result(0, '操作成功',$result);
        }

    }

    public function doPageTan(){
        global $_GPC, $_W;
        $time=date('Y-m-d',time());
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        if($user['tantime'] == $time){
            $set = pdo_get('hcstep_message',array('uniacid'=>$_GPC['i']));
            $goods = pdo_get('hcstep_goods',array('uniacid'=>$_GPC['i'],'id'=>$set['tan_goodsid']));
            $data['is_tan'] = $set['is_tan'];
            $data['tan_type'] = $set['tan_type'];
            $data['tan_goodsid'] = $set['tan_goodsid'];
            $data['tan_pic'] = tomedia($set['tan_pic']);
            $data['tanguo'] = 1;//弹过
            $data['paytype'] = $goods['paytype'];
            $data['selltype'] = $goods['selltype'];
        }else{
            $set = pdo_get('hcstep_message',array('uniacid'=>$_GPC['i']));
            $goods = pdo_get('hcstep_goods',array('uniacid'=>$_GPC['i'],'id'=>$set['tan_goodsid']));
            $data['is_tan'] = $set['is_tan'];
            $data['tan_type'] = $set['tan_type'];
            $data['tan_goodsid'] = $set['tan_goodsid'];
            $data['tan_pic'] = tomedia($set['tan_pic']);
            $data['tanguo'] = 0;//没弹过
            $data['paytype'] = $goods['paytype'];
            $data['selltype'] = $goods['selltype'];
            pdo_update('hcstep_users',array('tantime'=>$time), array('user_id' =>$_GPC['user_id'])); 
        }
        return $this->result(0, '操作成功',$data);
    }

    public function doPageHeadcolor(){
        global $_GPC, $_W;
        
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        $res = pdo_get('hcstep_message',array('uniacid'=>$_GPC['i']));
        $user['stepid'] = $user['user_id'] + 100000;

        $set['sharepic'] = $_W['attachurl'].$set['sharepic'];
        $set['rulepic'] = $_W['attachurl'].$set['rulepic'];
        $set['voice'] = $_W['siteroot']."addons/hc_step/coin.mp3";
        $set['no'] = $_W['siteroot']."addons/hc_step/no.png";
        $set['no2'] = $_W['siteroot']."addons/hc_step/no2.png";
        
        if(!empty($set['loginpic'])){
            $set['loginpic'] = $_W['attachurl'].$set['loginpic']; 
        }else{
            $set['loginpic'] = '';
        }
        if(!empty($set['indexbg'])){
            $set['indexbg'] = $_W['attachurl'].$set['indexbg']; 
        }else{
            $set['indexbg'] = '';
        }
        if(!empty($set['indexbutton'])){
            $set['indexbutton'] = $_W['attachurl'].$set['indexbutton']; 
        }else{
            $set['indexbutton'] = '';
        }
        if(!empty($set['inviteball'])){
            $set['inviteball'] = $_W['attachurl'].$set['inviteball']; 
        }else{
            $set['inviteball'] = '';
        }
        if(!empty($set['upball'])){
            $set['upball'] = $_W['attachurl'].$set['upball']; 
        }else{
            $set['upball'] = '';
        }
        if(!empty($set['questionpic'])){
            $set['questionpic'] = $_W['attachurl'].$set['questionpic']; 
        }else{
            $set['questionpic'] = '';
        }
        if(!empty($set['followpic'])){
            $set['followpic'] = $_W['attachurl'].$set['followpic']; 
        }else{
            $set['followpic'] = '';
        }
        if(!empty($set['kefupic'])){
            $set['kefupic'] = $_W['attachurl'].$set['kefupic']; 
        }else{
            $set['kefupic'] = '';
        }
        if(!empty($set['followlogo'])){
            $set['followlogo'] = $_W['attachurl'].$set['followlogo']; 
        }else{
            $set['followlogo'] = '';
        }
        if(!empty($set['boxpic'])){
            $set['boxpic'] = $_W['attachurl'].$set['boxpic']; 
        }else{
            $set['boxpic'] = '';
        }
        if(!empty($set['activitypic'])){
            $set['activitypic'] = $_W['attachurl'].$set['activitypic']; 
        }else{
            $set['activitypic'] = '';
        }
        if(!empty($set['applypic'])){
            $set['applypic'] = $_W['attachurl'].$set['applypic']; 
        }else{
            $set['applypic'] = '';
        }
        if(!empty($set['icon'])){
            $set['icon'] = $_W['attachurl'].$set['icon']; 
        }else{
            $set['icon'] = '';
        }
        if(!empty($set['signpic'])){
            $set['signpic'] = $_W['attachurl'].$set['signpic']; 
        }else{
            $set['signpic'] = '';
        }
        if(!empty($set['frame'])){
            $set['frame'] = $_W['attachurl'].$set['frame']; 
        }else{
            $set['frame'] = '';
        }
        if(!empty($set['signicon'])){
            $set['signicon'] = $_W['attachurl'].$set['signicon']; 
        }else{
            $set['signicon'] = '';
        }
        if(!empty($set['buttonbg'])){
            $set['buttonbg'] = $_W['attachurl'].$set['buttonbg']; 
        }else{
            $set['buttonbg'] = '';
        }
        if(!empty($set['coinpic'])){
            $set['coinpic'] = $_W['attachurl'].$set['coinpic']; 
        }else{
            $set['coinpic'] = '';
        }
        if(!empty($set['hongbaobg'])){
            $set['hongbaobg'] = $_W['attachurl'].$set['hongbaobg']; 
        }else{
            $set['hongbaobg'] = '';
        }
        if(!empty($set['longbg'])){
            $set['longbg'] = $_W['attachurl'].$set['longbg']; 
        }else{
            $set['longbg'] = '';
        }
        if(!empty($set['updatepic'])){
            $set['updatepic'] = $_W['attachurl'].$set['updatepic']; 
        }else{
            $set['updatepic'] = '';
        }
        if(!empty($res['fourhb_mainpic'])){
            $set['fourhb_mainpic'] = tomedia($res['fourhb_mainpic']); 
        }else{
            $set['fourhb_mainpic'] = $_W['siteroot']."addons/hc_step/openhb.png";
        }
        if(!empty($res['fourhb_sharepic'])){
            $set['fourhb_sharepic'] = $_W['attachurl'].$res['fourhb_sharepic']; 
        }else{
            $set['fourhb_sharepic'] = '';
        }
        if(!empty($res['daijiesuo'])){
            $set['daijiesuo'] = $_W['attachurl'].$res['daijiesuo']; 
        }else{
            $set['daijiesuo'] = '';
        }
        if(!empty($res['daikaiqi'])){
            $set['daikaiqi'] = $_W['attachurl'].$res['daikaiqi']; 
        }else{
            $set['daikaiqi'] = '';
        }
        if(!empty($res['yikaiqi'])){
            $set['yikaiqi'] = $_W['attachurl'].$res['yikaiqi']; 
        }else{
            $set['yikaiqi'] = '';
        }
        if(!empty($res['hb_icon'])){
            $set['hb_icon'] = $_W['attachurl'].$res['hb_icon']; 
        }else{
            $set['hb_icon'] = '';
        }
        if(!empty($res['openhbpic'])){
            $set['openhbpic'] = $_W['attachurl'].$res['openhbpic']; 
        }else{
            $set['openhbpic'] = '';
        }
        //个人中心图
        if(!empty($res['centerhead'])){
            $set['centerhead'] = $_W['attachurl'].$res['centerhead']; 
        }else{
            $set['centerhead'] = '';
        }
        if(!empty($res['invite_icon'])){
            $set['invite_icon'] = $_W['attachurl'].$res['invite_icon']; 
        }else{
            $set['invite_icon'] = '';
        }
        if(!empty($res['rule_icon'])){
            $set['rule_icon'] = $_W['attachurl'].$res['rule_icon']; 
        }else{
            $set['rule_icon'] = '';
        }
        if(!empty($res['qs_icon'])){
            $set['qs_icon'] = $_W['attachurl'].$res['qs_icon']; 
        }else{
            $set['qs_icon'] = '';
        }
        if(!empty($res['news_icon'])){
            $set['news_icon'] = $_W['attachurl'].$res['news_icon']; 
        }else{
            $set['news_icon'] = '';
        }
        if(!empty($res['contact_icon'])){
            $set['contact_icon'] = $_W['attachurl'].$res['contact_icon']; 
        }else{
            $set['contact_icon'] = '';
        }
        if(!empty($res['ka_icon'])){
            $set['ka_icon'] = $_W['attachurl'].$res['ka_icon']; 
        }else{
            $set['ka_icon'] = '';
        }
        if(!empty($res['set_icon'])){
            $set['set_icon'] = $_W['attachurl'].$res['set_icon']; 
        }else{
            $set['set_icon'] = '';
        }
        //漂浮按钮图
        if(!empty($res['call_icon'])){
            $set['call_icon'] = tomedia($res['call_icon']); 
        }else{
            $set['call_icon'] = $_W['siteroot']."addons/hc_step/call.png";
        }
        if(!empty($res['copy_icon'])){
            $set['copy_icon'] = tomedia($res['copy_icon']); 
        }else{
            $set['copy_icon'] = $_W['siteroot']."addons/hc_step/copy.png";
        }
        $set['vball'] = $_W['siteroot']."addons/hc_step/vball.png";
        $set['dhcolor'] = $res['dhcolor'];
        $set['name_color'] = $res['name_color'];
        $set['id_color'] = $res['id_color'];
        $set['dhjl_color'] = $res['dhjl_color'];
        $set['idbg_color'] = $res['idbg_color'];
        $set['txcolor'] = $res['txcolor'];
        $set['txjl_color'] = $res['txjl_color'];
        $set['fourhb_sharetitle'] = $res['fourhb_sharetitle'];
        $set['order_icon'] = tomedia($res['order_icon']);

        $set['is_float'] = $res['is_float'];
        $set['phoneno'] = $res['phoneno'];
        $set['copytext'] = $res['copytext'];
        $set['fourhb_coin'] = $res['fourhb_coin'];

        if($_GPC['user_id'] and $_GPC['user_id'] <> 0){

            $inviteball = pdo_getall('hcstep_invitelog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'status'=>0));
            $sonlist = pdo_getall('hcstep_users',array('uniacid'=>$_GPC['i'],'fatherid'=>$_GPC['user_id'],'fatherid !='=>0));
            //步数加成
            $time=date('Y-m-d',time());
            $invitelogall = pdo_getall('hcstep_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']));
            $invitelogtoday = pdo_getall('hcstep_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'time'=>$time));
            $count = count($invitelogall) - count($invitelogtoday);
            $tcount = count($invitelogall);
            //是否显示关注气泡
            $guanzhu = pdo_get('hcstep_guanzhulog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
            if($guanzhu['status'] == 0 && !empty($guanzhu)){
                $set['is_guanzhujiang'] = 1; //显示气泡
            }else{
                $set['is_guanzhujiang'] = 0; //不显示气泡
            }
            //是否显示视频奖励气泡
            $daytime = date('Y-m-d',time());
            $shipinlog = pdo_get('hcstep_shipinlog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'daytime'=>$daytime));
            if($shipinlog['status'] == 0 && !empty($shipinlog)){
                $set['is_shipinjiang'] = 1; //显示气泡
            }else{
                $set['is_shipinjiang'] = 0; //不显示气泡
            }
        }
        
        $set['todayup'] = $set['up']*$count;
        $set['tomorrow'] = $set['up']*$tcount;
        $set['adunit'] = $set['adunit'];
        $set['adunit2'] = $set['adunit2'];
        $set['adunit3'] = $set['adunit3'];

        //骗审数据
        //$fake = pdo_getll('hcstep_users',array('uniacid'=>$_GPC['i']));
        $fake = pdo_getslice('hcstep_users', array('uniacid'=>$_GPC['i']), array(0, 10), $total);
        $fake[0]['step'] = 23845;
        $fake[1]['step'] = 20667;
        $fake[2]['step'] = 18652;
        $fake[3]['step'] = 16632;
        $fake[4]['step'] = 12420;
        $fake[5]['step'] = 10248;
        $fake[6]['step'] = 8649;
        $fake[7]['step'] = 7456;
        $fake[8]['step'] = 6324;
        $fake[9]['step'] = 4396;

        //$shop = pdo_get('hcstep_shop',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $shop = pdo_getall('hcstep_shop',array('uniacid'=>$_GPC['i']));
        $set['is_sao'] = 0; //不显示
        foreach ($shop as $k => $v) {
            $str = explode(",",$v['user_id']);
            if(in_array($_GPC['user_id'],$str)){
               $set['is_sao'] = 1; //显示扫一扫 
            }
        }
        $set['hbtext1'] = $res['hbtext1'];
        $set['hbtext2'] = $res['hbtext2'];
        $set['is_fourhb'] = $res['is_fourhb'];

        return $this->result(0,'操作成功',array('inviteball'=>$inviteball,'sonlist'=>$sonlist,'user'=>$user,'set'=>$set,'fake'=>$fake));

    }

    public function doPageImgurl(){
        global $_GPC, $_W;
        
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        $res = pdo_get('hcstep_message',array('uniacid'=>$_GPC['i']));

        if(!empty($set['indexbg'])){
            $setdata['indexbg'] = $_W['attachurl'].$set['indexbg']; 
        }else{
            $setdata['indexbg'] = '';
        }
        if(!empty($set['frame'])){
            $setdata['frame'] = $_W['attachurl'].$set['frame']; 
        }else{
            $setdata['frame'] = '';
        }
        if(!empty($set['indexbutton'])){
            $setdata['indexbutton'] = $_W['attachurl'].$set['indexbutton']; 
        }else{
            $setdata['indexbutton'] = '';
        }     
        if(!empty($set['icon'])){
            $setdata['icon'] = $_W['attachurl'].$set['icon']; 
        }else{
            $setdata['icon'] = '';
        }
        if(!empty($set['coinpic'])){
            $setdata['coinpic'] = $_W['attachurl'].$set['coinpic']; 
        }else{
            $setdata['coinpic'] = '';
        }
        if(!empty($set['longbg'])){
            $setdata['longbg'] = $_W['attachurl'].$set['longbg']; 
        }else{
            $setdata['longbg'] = '';
        }
        if(!empty($res['fourhb_mainpic'])){
            $setdata['fourhb_mainpic'] = tomedia($res['fourhb_mainpic']); 
        }else{
            $setdata['fourhb_mainpic'] = $_W['siteroot']."addons/hc_step/openhb.png";
        }
        if(!empty($res['fabu_icon'])){
            $setdata['fabu_icon'] = tomedia($res['fabu_icon']); 
        }else{
            $setdata['fabu_icon'] = $_W['siteroot']."addons/hc_step/template/img/fabu.png";
        }

        $setdata['sweatcolor'] = $set['sweatcolor'];
        $setdata['centercolor'] = $set['centercolor'];
        $setdata['smalltipcolor'] = $set['smalltipcolor'];
        $setdata['sharetextcolor'] = $set['sharetextcolor'];
        $setdata['shareinfocolor'] = $set['shareinfocolor'];
        $setdata['headcolor'] = $set['headcolor']; 
        $setdata['invitetype'] = $set['invitetype']; 
        $setdata['upinfo'] = $set['upinfo']; 
        $setdata['hongbaotext'] = $set['hongbaotext']; 
        $setdata['cointextcolor'] = $set['cointextcolor'];
        $setdata['is_kuang'] = $set['is_kuang'];//1开启0关闭 头像框

        $setdata['is_fourhb'] = $res['is_fourhb'];      

        if($_GPC['user_id'] and $_GPC['user_id'] <> 0){

            $inviteball = pdo_getall('hcstep_invitelog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'status'=>0));
            $sonlist = pdo_getall('hcstep_users',array('uniacid'=>$_GPC['i'],'fatherid'=>$_GPC['user_id'],'fatherid !='=>0));
            //步数加成
            $time=date('Y-m-d',time());
            $invitelogall = pdo_getall('hcstep_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']));
            $invitelogtoday = pdo_getall('hcstep_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'time'=>$time));
            $count = count($invitelogall) - count($invitelogtoday);
            $tcount = count($invitelogall);
            //是否显示关注气泡
            $guanzhu = pdo_get('hcstep_guanzhulog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
            if($guanzhu['status'] == 0 && !empty($guanzhu)){
                $setdata['is_guanzhujiang'] = 1; //显示气泡
            }else{
                $setdata['is_guanzhujiang'] = 0; //不显示气泡
            }
        }

        $left1['img'] = tomedia($res['left1']);
        $left1['jump'] = $res['left1_jump'];
        $left1['appid'] = $res['left1_appid'];
        $left1['path'] = $res['left1_path'];

        $left2['img'] = tomedia($res['left2']);
        $left2['jump'] = $res['left2_jump'];
        $left2['appid'] = $res['left2_appid'];
        $left2['path'] = $res['left2_path'];

        $right1['img'] = tomedia($res['right1']);
        $right1['jump'] = $res['right1_jump'];
        $right1['appid'] = $res['right1_appid'];
        $right1['path'] = $res['right1_path'];

        $right2['img'] = tomedia($res['right2']);
        $right2['jump'] = $res['right2_jump'];
        $right2['appid'] = $res['right2_appid'];
        $right2['path'] = $res['right2_path'];

        $right3['img'] = tomedia($res['right3']);
        $right3['jump'] = $res['right3_jump'];
        $right3['appid'] = $res['right3_appid'];
        $right3['path'] = $res['right3_path'];

        $setdata['is_five'] = $res['is_five'];//1开启0关闭
        $setdata['icon_position'] = $res['icon_position'];//0竖向1横向


        return $this->result(0,'操作成功',array('inviteball'=>$inviteball,'sonlist'=>$sonlist,'user'=>$user,'setdata'=>$setdata,'left1'=>$left1,'left2'=>$left2,'right1'=>$right1,'right2'=>$right2,'right3'=>$right3));

    }

    //公告滚动
    public function doPageMenu(){
        
        global $_GPC, $_W;
        $list = array();
        $set = pdo_get('hcstep_message',array('uniacid'=>$_GPC['i']));
        $list[0]['pagePath'] = '/hc_step/pages/index/index';
        $list[0]['iconPath'] = tomedia($set['shouye']);
        $list[0]['selectedIconPath'] = tomedia($set['shouye_xuan']);
        $list[0]['text'] = '首页';

        $list[1]['pagePath'] = '/hc_step/pages/activityvip/activityvip';
        $list[1]['iconPath'] = tomedia($set['huodong']);
        $list[1]['selectedIconPath'] = tomedia($set['huodong_xuan']);
        $list[1]['text'] = '活动';

        $list[2]['pagePath'] = '/hc_step/pages/sweat/sweat';
        $list[2]['iconPath'] = tomedia($set['hanshuiriji']);
        $list[2]['selectedIconPath'] = tomedia($set['hanshuiriji_xuan']);
        $list[2]['text'] = '汗水日记';

        $list[3]['pagePath'] = '/hc_step/pages/record/record';
        $list[3]['iconPath'] = tomedia($set['wode']);
        $list[3]['selectedIconPath'] = tomedia($set['wode_xuan']);
        $list[3]['text'] = '我的';

        $data['list'] = $list;
        $data['color'] = '#7A7E83';
        $data['selectedColor'] = '#9f64f8';

        return $this->result(0,'获取成功',$data);
    }

    //公告滚动
    public function doPageNotice(){
        
        global $_GPC, $_W;
        $set = pdo_get('hcstep_message',array('uniacid'=>$_GPC['i']));
        
        $data['notice'] = $set['notice'];
        $data['rolltime'] = $set['rolltime'];
        $data['status'] = $set['status']; //1显示0不显示


        return $this->result(0,'获取成功',$data);
    }

    //签到分享状态
    public function doPageIssignshare(){
        
        global $_GPC, $_W;
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        //判断今日签到是否分享过
        $t = time();
        /**今天的时间戳时间范围*/
        $now_start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $now_end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        /**判断最后一次签到时间是否在昨天的时间范围内*/
        if($now_start_time<$user['sharetime']&&$user['sharetime']<$now_end_time){
            /**这里还可以加一些判断连续签到几天然后加积分等等的操作*/
            $set['is_signshare'] = 1;//今日已分享
        }else{
            $set['is_signshare'] = 0;//今日未分享
        }

        return $this->result(0,'操作成功',array('set'=>$set,'user'=>$user));
    }

    //商品列表
    public function doPageGoodslist(){

        global $_GPC, $_W;
        $indexsort = pdo_getall('hcstep_sort', array('type'=>1,'enabled'=>1,'uniacid'=>$_GPC['i']), array(),'','displayorder asc');
        foreach($indexsort as $k => $v){
            $list[$k]['sort_id'] = $v['id'];
            $list[$k]['title'] = $v['advname'];
            $list[$k]['is_distance'] = $v['is_distance'];
            $list[$k]['goods'] = pdo_getall('hcstep_goods', array('indexsort_id'=>$v['id'],'uniacid'=>$_GPC['i'],'status'=>1), array(),'','displayorder asc');
            foreach($list[$k]['goods'] as $key=>$value){
            $list[$k]['goods'][$key]['main_img'] = $_W['attachurl'].$value['main_img'];
        }
        }
        /*$list = pdo_getall('hcstep_goods', array('status' => 1,'uniacid'=>$_GPC['i']), array(),'','displayorder asc');
        foreach ($list as $k => $v) {
            $list[$k]['main_img'] = $_W['attachurl'].$v['main_img'];
        }*/
        return $this->result(0, '商品列表',$list);
        
    }

    //商品列表按距离排序
    public function doPageGoodslist_dsort(){

        global $_GPC, $_W;
        /*$indexsort = pdo_getall('hcstep_sort', array('type'=>1,'enabled'=>1,'uniacid'=>$_GPC['i']), array(),'','displayorder asc');
        foreach($indexsort as $k => $v){*/
            /*$list[$k]['title'] = $v['advname'];
            $list[$k]['is_distance'] = $v['is_distance'];*/
            $list= pdo_getall('hcstep_goods', array('indexsort_id'=>$_GPC['sort_id'],'uniacid'=>$_GPC['i'],'status'=>1), array(),'','displayorder asc');
            foreach($list as $key=>$value){
            $list[$key]['main_img'] = $_W['attachurl'].$value['main_img'];
            $shop = pdo_get('hcstep_shop',array('id'=>$value['shop_id']));
            $list[$key]['distance'] = $this->getDistance($shop['lat'],$shop['lng'],$_GPC['latitude'],$_GPC['longitude'],2,2);
            }
            array_multisort(array_column($list,'distance'),SORT_ASC,$list);
        //}

        return $this->result(0, '商品列表',$list);
        
    }

    function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit, $decimal){

          $EARTH_RADIUS = 6370.996; // 地球半径系数
          $PI = 3.1415926;

          $radLat1 = $latitude1 * $PI / 180.0;
          $radLat2 = $latitude2 * $PI / 180.0;

          $radLng1 = $longitude1 * $PI / 180.0;
          $radLng2 = $longitude2 * $PI /180.0;

          $a = $radLat1 - $radLat2;
          $b = $radLng1 - $radLng2;

          $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
          $distance = $distance * $EARTH_RADIUS * 1000;

          if($unit==2){
            $distance = $distance / 1000;
          }

          return round($distance, $decimal);

}

    //商品列表
    public function doPageMore(){

        global $_GPC, $_W;
        $sort = pdo_getall('hcstep_sort', array('type'=>2,'uniacid'=>$_GPC['i'],'enabled'=>1), array(),'','displayorder asc');

        if(empty($_GPC['id'])){
            $_GPC['id'] = $sort[0]['id'];
        }else{
            $_GPC['id'] = $sort[$_GPC['id']]['id'];
        }
        
        $list = pdo_getall('hcstep_goods', array('status' => 1,'uniacid'=>$_GPC['i'],'sort_id' => $_GPC['id']), array(),'','displayorder asc');
        foreach ($list as $k => $v) {
            $list[$k]['main_img'] = $_W['attachurl'].$v['main_img'];
        }

        return $this->result(0, '商品列表',array('sort'=>$sort,'list'=>$list));

    }

    //商品列表
    public function doPageGoodsdetail(){

        global $_GPC, $_W;    
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        if($_GPC['index'] == 1){
            $goods = pdo_get('hcstep_goods',array('id'=>$_GPC['goods_id'],'uniacid'=>$_GPC['i']));
            if($goods['selltype'] == 1 or $goods['selltype'] == 3){
               $shop = pdo_get('hcstep_shop',array('id'=>$goods['shop_id']));
               $goods['sheng'] = $shop['sheng'];
               $goods['shi'] = $shop['shi'];
               $goods['qu'] = $shop['qu'];
               $goods['address'] = $shop['address'];
               $goods['tel'] = $shop['tel'];
               $goods['starttime'] = $shop['starttime'];
               $goods['endtime'] = $shop['endtime'];
               $goods['lat'] = $shop['lat'];
               $goods['lng'] = $shop['lng'];
            }

            //红包
            if($goods['maxhongbao'] >= $user['rmb']){
               $user['keyong'] = $user['rmb']; 
            }else{
               $user['keyong'] = $goods['maxhongbao']; 
            }
            $goods['quanhou'] = $goods['maxrmb'] - $user['keyong'];

            //兑换记录
            $goodslog = pdo_getall('hcstep_orders', array('uniacid'=>$_GPC['i'],'goods_id'=>$goods['id'],'status !=' => 2));
            $xuni = pdo_getall('hcstep_xuni', array('uniacid'=>$_GPC['i'],'goods_id'=>$goods['id']));
            foreach ($goodslog as $k => $v){
                $userinfo = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$v['user_id']));
                $list1[$k]['nick_name'] = $userinfo['nick_name'];
                $list1[$k]['head_pic'] = $userinfo['head_pic'];
                $list1[$k]['time'] = date('Y-m-d H:i',$v['time']);
            }
            foreach ($xuni as $k => $v){
                $list2[$k]['nick_name'] = $v['nick_name'];
                $list2[$k]['head_pic'] = $_W['attachurl'].$v['head_pic'];
                $list2[$k]['time'] = $v['time'];
            }
            
            if(!empty($list1) and !empty($list2)){
                $list = array_merge($list1,$list2);
                $temp = array_column($list,'time');
                array_multisort($temp, SORT_DESC, $list);
            }elseif(!empty($list1) and empty($list2)){
                $list = $list1;
                $temp = array_column($list,'time');
                array_multisort($temp, SORT_DESC, $list); 
            }elseif(empty($list1) and !empty($list2)){
                $list = $list2; 
                $temp = array_column($list,'time');
                array_multisort($temp, SORT_DESC, $list);
            }else{
                $list = $list; 
            }
            //邀请记录
            $peoplelog = pdo_getall('hcstep_peoplelog', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id']));
            
            for ($i=0; $i<$goods['minpeople']; $i++)
            {
                if(empty($peoplelog[$i])){
                   $people[$i]['head_pic'] = '';
                   $people[$i]['nick_name'] = '';
                   $people[$i]['is_yao'] = '';
                }else{
                   $user = pdo_get('hcstep_users',array('user_id'=>$peoplelog[$i]['son_id'],'uniacid'=>$_GPC['i']));
                   $people[$i]['head_pic'] = $user['head_pic'];
                   $people[$i]['nick_name'] = $user['nick_name'];
                }
            }

            $peoplesum = count($peoplelog);
            if($peoplesum >= $goods['minpeople']){
                $goods['is_duihuan'] = 1;  //满足邀请好友条件
            }else{
                $goods['is_duihuan'] = 0;  //未满足
            }

            $goods['goods_img'] = json_decode($goods['goods_img']);

            $goods['is_you'] = 1;
            if($goods['inventory'] <= 0){
                $goods['is_you'] = 0;
                //return $this->result(1, '已经抢光了',$goods);
            }
            if($goods['paytype'] == 1 and $goods['price2'] >$user['money']){
                $goods['is_rich'] = 0;//钱不够
            }elseif($goods['paytype'] == 0 and $goods['price'] >$user['money']){
                $goods['is_rich'] = 0;//钱不够
            }else{
                $goods['is_rich'] = 1;//钱够
            }

            if(!empty($goods['goods_img'])){
                foreach ($goods['goods_img'] as $k => $v) {
                   $goods['goods_img'][$k] = $_W['attachurl'].$v;
                }
            }
            if(!empty($goods['main_img'])){
                $goods['main_img'] = $_W['attachurl'].$goods['main_img']; 
            }else{
                $goods['main_img'] = '';
            }
            
            $goods['usermoney'] = $user['money'];

            return $this->result(0, '商品详情',array('goods'=>$goods,'list'=>$list,'people'=>$people,'user'=>$user));
        }else{
            $goods = pdo_get('hcstep_awards',array('id'=>$_GPC['goods_id'],'uniacid'=>$_GPC['i']));
            $goods['goods_img'] = json_decode($goods['goods_img']);

            if($goods['inventory'] <= 0){
                return $this->result(1, '已经抢光了',$goods);
            }
            if(!empty($goods['goods_img'])){
                foreach ($goods['goods_img'] as $k => $v) {
                   $goods['goods_img'][$k] = $_W['attachurl'].$v;
                }
            }        
            $goods['usermoney'] = $user['money'];
            return $this->result(0, '商品详情',$goods);
        }
    }

    //奖品列表
    public function doPageAwardslist(){

        global $_GPC, $_W;          
        $list = pdo_getall('hcstep_awards', array('uniacid'=>$_GPC['i']), array(), '', 'sort asc'); 
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        foreach ($list as $k => $v) {
            if($v['awards_type'] == 1){
                $list[$k]['goods_name'] = $v['goods_name'];
                $list[$k]['main_img'] = $_W['attachurl'].$v['main_img'];
            }
            if($v['awards_type'] == 2){
                $list[$k]['goods_name'] = $v['awards_coin'].$set['coinname'];
                $list[$k]['main_img'] = $_W['siteroot']."addons/hc_step/coin.png";
            }
            if($v['awards_type'] == 3){
                $list[$k]['goods_name'] = $v['awards_money'].'元红包';
                $list[$k]['main_img'] = $_W['siteroot']."addons/hc_step/template/img/hb1.png";
            }
            
        }
        return $this->result(0, '商品列表',$list);

    }

    //商品列表
    public function doPageCreateorder(){

        global $_GPC, $_W; 

        $goods = pdo_get('hcstep_goods',array('id'=>$_GPC['goods_id'],'uniacid'=>$_GPC['i']));
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));   
        
        $data['uniacid'] = $_GPC['i'];
        $data['userName'] = $_GPC['userName'];
        $data['postalCode'] = $_GPC['postalCode'];
        $data['provinceName'] = $_GPC['provinceName'];
        $data['cityName'] = $_GPC['cityName'];
        $data['countyName'] = $_GPC['countyName'];
        $data['detailInfo'] = $_GPC['detailInfo'];
        $data['nationalCode'] = $_GPC['nationalCode'];
        $data['telNumber'] = $_GPC['telNumber'];
        $data['user_id'] = $_GPC['user_id'];
        $data['goods_id'] = $_GPC['goods_id'];
        $data['type'] = 2;
        $data['status'] = 0;
        $data['time'] = time();

        $orders = pdo_getall('hcstep_orders', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id'],'status !='=>2));
        $ordersum = count($orders);
        if($goods['maxbuy'] > 0){
            if($ordersum >= $goods['maxbuy']){
                return $this->result(1, '已达兑换上限',$user);
            }
        }

        if($user['money'] < $goods['price']){
            return $this->result(1, '余额不足',$user);
        }elseif($goods['inventory'] <=0 ){
            return $this->result(1, '库存不足',$goods);
        }else{
            $order = pdo_insert('hcstep_orders', $data, $replace = true);
            $nowmoney = $user['money'] - $goods['price'];
            pdo_update('hcstep_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));
            $nowinventory = $goods['inventory'] - 1;
            pdo_update('hcstep_goods',array('inventory'=>$nowinventory), array('id' => $_GPC['goods_id']));
            if($order){
               return $this->result(0, '兑换成功',$order); 
            }else{
               return $this->result(1, '兑换失败',$order);
            }
        } 
        
    }

    //商品列表
    public function doPageCreatepeoplegoods(){

        global $_GPC, $_W; 

        $goods = pdo_get('hcstep_goods',array('id'=>$_GPC['goods_id'],'uniacid'=>$_GPC['i']));
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'])); 
        $log =  pdo_get('hcstep_orders',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id'],'type'=>3)); 

        if(!empty($log)){
           return $this->result(1, '您已经兑换过此商品',$log);
        }else{
           $data['uniacid'] = $_GPC['i'];
            $data['userName'] = $_GPC['userName'];
            $data['postalCode'] = $_GPC['postalCode'];
            $data['provinceName'] = $_GPC['provinceName'];
            $data['cityName'] = $_GPC['cityName'];
            $data['countyName'] = $_GPC['countyName'];
            $data['detailInfo'] = $_GPC['detailInfo'];
            $data['nationalCode'] = $_GPC['nationalCode'];
            $data['telNumber'] = $_GPC['telNumber'];
            $data['user_id'] = $_GPC['user_id'];
            $data['goods_id'] = $_GPC['goods_id'];
            $data['type'] = 3;
            $data['status'] = 0;
            $data['time'] = time();
           

                $order = pdo_insert('hcstep_orders', $data, $replace = true);
                $oid = pdo_insertid();
                $nowmoney = $user['money'] - $goods['price'];
                pdo_update('hcstep_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));
                $nowinventory = $goods['inventory'] - 1;
                pdo_update('hcstep_goods',array('inventory'=>$nowinventory), array('id' => $_GPC['goods_id']));
                if($order){
                   return $this->result(0, '兑换成功',$oid); 
                }else{
                   return $this->result(1, '兑换失败',$oid);
                }
 
        }     
    }

    //商品列表
    public function doPageCreatehexiao(){

        global $_GPC, $_W; 

        $goods = pdo_get('hcstep_goods',array('id'=>$_GPC['goods_id'],'uniacid'=>$_GPC['i']));
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));   
        
        $data['uniacid'] = $_GPC['i'];
        /*$data['userName'] = $_GPC['userName'];
        $data['postalCode'] = $_GPC['postalCode'];
        $data['provinceName'] = $_GPC['provinceName'];
        $data['cityName'] = $_GPC['cityName'];
        $data['countyName'] = $_GPC['countyName'];
        $data['detailInfo'] = $_GPC['detailInfo'];
        $data['nationalCode'] = $_GPC['nationalCode'];
        $data['telNumber'] = $_GPC['telNumber'];*/
        $data['user_id'] = $_GPC['user_id'];
        $data['goods_id'] = $_GPC['goods_id'];
        $data['type'] = 12;//纯币核销
        if($goods['selltype'] == 3){
           $data['type'] = 13;//邀请好友核销 
        }
        $data['status'] = 0;
        $data['time'] = time();
        if($goods['validity'] > 0){
            $data['endtime'] = time()+24*3600*$goods['validity'];
        }else{
            $data['endtime'] = 0;
        }
        
        $orders = pdo_getall('hcstep_orders', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id']));
        $ordersum = count($orders);
        if($goods['maxbuy'] > 0){
            if($ordersum >= $goods['maxbuy']){
                return $this->result(1, '已达兑换上限',$user);
            }
        }
       
        if($user['money'] < $goods['price'] and $goods['selltype'] == 1){
            return $this->result(1, '余额不足',$user);
        }elseif($goods['inventory'] <= 0){
            return $this->result(1, '库存不足',$goods);
        }else{
            $order = pdo_insert('hcstep_orders', $data, $replace = true);
            $oid = pdo_insertid();
            if($goods['selltype'] == 1){
               $nowmoney = $user['money'] - $goods['price'];
               pdo_update('hcstep_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id'])); 
            }        
            $nowinventory = $goods['inventory'] - 1;
            pdo_update('hcstep_goods',array('inventory'=>$nowinventory), array('id' => $_GPC['goods_id']));
            if($order){
               return $this->result(0, '兑换成功',$oid); 
            }else{
               return $this->result(1, '兑换失败',$oid);
            }
        }        
    }
    //点击邀请气泡换步数
    public function doPageBall2bushu(){
        global $_GPC, $_W;
        $ballid = $_GPC['id'];
        $result = pdo_update('hcstep_invitelog',array('status'=>1), array('id' => $ballid));
        if($result){
           return $this->result(0, '获取成功',$result); 
        }else{
           return $this->result(1, '获取失败',$result);
        }
    }

    //点击加成气泡换步数
    public function doPageUpball2bushu(){
        global $_GPC, $_W;

        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $data['step'] = $_GPC['upbushu'];
        $data['time'] = time();
        $data['day'] = date('Y-m-d',time());
   
        $result = pdo_insert('hcstep_uplog', $data, $replace = true);
        if($result){
           return $this->result(0, '获取成功',$result); 
        }else{
           return $this->result(1, '获取失败',$result);
        }
    }

    //点击关注气泡换步数
    public function doPageGuanzhuball2bushu(){
        global $_GPC, $_W;

        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $result = pdo_update('hcstep_guanzhulog',array('status'=>1), array('user_id' => $_GPC['user_id'],'uniacid'=>$_GPC['i']));

        if($result){
           return $this->result(0, '获取成功',$result); 
        }else{
           return $this->result(1, '获取失败',$result);
        }
    }

    //点击视频气泡换步数
    public function doPageShipinball2bushu(){
        global $_GPC, $_W;

        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $daytime = date('Y-m-d',time());
        $result = pdo_update('hcstep_shipinlog',array('status'=>1), array('user_id' => $_GPC['user_id'],'uniacid'=>$_GPC['i'],'daytime'=>$daytime));

        if($result){
           return $this->result(0, '获取成功',$result); 
        }else{
           return $this->result(1, '获取失败',$result);
        }
    }

    //点击邀请气泡换步数
    public function doPageYy(){
        global $_GPC, $_W;
        $result = pdo_update('hcstep_users',array('is_yy'=>1), array('user_id' =>$_GPC['user_id']));
        if($result){
           return $this->result(0, '获取成功',$result); 
        }else{
           return $this->result(1, '获取失败',$result);
        }
    }

    //点击任务列表换步数
    public function doPageMission2bushu(){
        global $_GPC, $_W;
        $daytime = date("Y-m-d",time());
        $mission = pdo_get('hcstep_mission',array('id'=>$_GPC['mission_id'],'uniacid'=>$_GPC['i']));
        if($mission['mission_type'] == 4){
            $log = pdo_get('hcstep_missionlog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'mission_id'=>$_GPC['mission_id'],'daytime'=>$daytime));
            $result = pdo_update('hcstep_missionlog',array('status'=>1), array('id' => $log['id']));
        }else{
            $log = pdo_get('hcstep_missionlog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'mission_id'=>$_GPC['mission_id']));
            $result = pdo_update('hcstep_missionlog',array('status'=>1), array('id' => $log['id']));
        }
        
        if($result){
           return $this->result(0, '获取成功',$result); 
        }else{
           return $this->result(1, '获取失败',$result);
        }
    }

    //抽奖
    public function doPageLotto(){
        
        global $_GPC, $_W;
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        if($user['money'] < $set['boxprice']){
            return $this->result(1, $set['coinname'].'不足',$user);
        }else{
            $list = pdo_getall('hcstep_awards', array('uniacid'=>$_GPC['i'],'status'=>1));
            $nolist = pdo_getall('hcstep_awards', array('uniacid'=>$_GPC['i'],'status'=>0));
            if($list){

               foreach ($list as $k => $v) {
               $arr[$k]['id'] = $v['id'];
               $arr[$k]['name'] = $v['goods_name'];
               $arr[$k]['v'] = $v['rate'];
               }
               $result = $this->get_rand($arr);

               if(empty($result)){
                    $nowmoney = $user['money'] - $set['boxprice'];
                    pdo_update('hcstep_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));
                    $aaa['sort'] = 8;
                    $aaa['status'] = 2;//未中奖
                    return $this->result(1,'未中奖',$aaa);
               }else{
                    
                    
                    $data['uniacid'] = $_GPC['i'];
                    $data['user_id'] = $_GPC['user_id'];
                    $data['goods_id'] = $result['id'];
                    $data['time'] =time();
                    $goods = pdo_get('hcstep_awards',array('id'=>$data['goods_id'],'uniacid'=>$_GPC['i']));               
                    if($goods['awards_type'] != 1){
                       $data['status'] = 1;
                    }
                    $result = pdo_insert('hcstep_winlog', $data, $replace = true);
                   
                    $nowmoney = $user['money'] - $set['boxprice'];
                    pdo_update('hcstep_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));

                    if($goods['awards_type'] == 1){
                        $aaa['is_jump'] = 1;//跳
                        $aaa['goods_name'] = $goods['goods_name'];
                    }
                    if($goods['awards_type'] == 2){
                        $aaa['is_jump'] = 0;//不跳
                        $aaa['goods_name'] = $goods['awards_coin'].$set['coinname'];
                        $aaa['goods_id'] = $goods['id'];
                        /*$user1 = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
                        $nowmoney1 = $user1['money'] + $goods['awards_coin'];
                        pdo_update('hcstep_users',array('money'=>$nowmoney1), array('user_id' => $_GPC['user_id']));*/
                    }
                    if($goods['awards_type'] == 3){
                        $aaa['is_jump'] = 0;//不跳
                        $aaa['goods_name'] = $goods['awards_money'].'元红包';
                        $aaa['goods_id'] = $goods['id'];
                        /*$nowrmb = $user['rmb'] + $goods['awards_money'];
                        pdo_update('hcstep_users',array('rmb'=>$nowrmb), array('user_id' => $_GPC['user_id']));*/
                    }
                    $aaa['sort'] = $goods['sort'];
                    $aaa['status'] = 1;//中奖
                    return $this->result(0,'获取成功',$aaa);
               }
            }else{
               $nowmoney = $user['money'] - $set['boxprice'];
               pdo_update('hcstep_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));
               $aaa['sort'] = 8;
               $aaa['status'] = 2;//未中奖
               return $this->result(1,'未中奖',$aaa);
            }
        }       
    }

    public function doPageLotto_result(){

        global $_GPC, $_W;
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $goods = pdo_get('hcstep_awards',array('id'=>$_GPC['goods_id'],'uniacid'=>$_GPC['i']));
        if($goods['awards_type'] == 2){
            $nowmoney = $user['money'] + $goods['awards_coin'];
            pdo_update('hcstep_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));
        }
        if($goods['awards_type'] == 3){
            $nowrmb = $user['rmb'] + $goods['awards_money'];
            pdo_update('hcstep_users',array('rmb'=>$nowrmb), array('user_id' => $_GPC['user_id']));
        }

        return $this->result(0,'获取成功',$user);
    }

    public function get_rand($proArr){
       $result = array();
        foreach ($proArr as $key => $val) {
            $arr[$key] = $val['v'];
        }
        // 概率数组的总概率
        //$proSum = array_sum($arr);
        $proSum = 100;
        asort($arr);
        // 概率数组循环
        foreach ($arr as $k => $v) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $v) {
                $result = $proArr[$k];
                break;
            } else {
                $proSum -= $v;
            }
        }
        return $result;
    }

    //完善中奖订单信息
    public function doPageEditwinlog(){

        global $_GPC, $_W;    
        
        $data['userName'] = $_GPC['userName'];
        $data['postalCode'] = $_GPC['postalCode'];
        $data['provinceName'] = $_GPC['provinceName'];
        $data['cityName'] = $_GPC['cityName'];
        $data['countyName'] = $_GPC['countyName'];
        $data['detailInfo'] = $_GPC['detailInfo'];
        $data['nationalCode'] = $_GPC['nationalCode'];
        $data['telNumber'] = $_GPC['telNumber'];

        $order = pdo_update('hcstep_winlog', $data, array('id' =>$_GPC['id']));


        if($order){
           return $this->result(0,'修改成功',$order); 
        }else{
           return $this->result(1,'修改失败',$order);
        }

    }

    //兑换记录
    public function doPageLog(){
       
        global $_GPC, $_W;
        $set = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        $qie = $_GPC['qie'];
        //燃力币兑换记录
        if($qie == 0){
           $coinlog = pdo_getall('hcstep_bushulog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']),array(),'','timestamp desc');
            foreach ($coinlog as $k => $v) {
                $coinlog[$k]['timestamp'] = date('Y-m-d H:i:s',$v['timestamp']);
            } 
        }
        //商品兑换记录
        if($qie == 1){
            $goodslog = pdo_getall('hcstep_orders',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'status !='=> 2,'type <'=>10),array(),'','time desc');
            foreach ($goodslog as $k => $v) {
                $goods = pdo_get('hcstep_goods',array('id'=>$v['goods_id'],'uniacid'=>$_GPC['i']));
                $goodslog[$k]['goods_name'] = $goods['goods_name'];
                $goodslog[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
                $goodslog[$k]['fahuotime'] = date('Y-m-d H:i:s',$v['fahuotime']);
                if($v['status'] == 0){
                    $goodslog[$k]['fahuo'] = '待发货';
                }else{
                    $goodslog[$k]['fahuo'] = '已发货';
                }           
            } 
        }

        //奖品兑换记录
        if($qie == 2){
            $awardslog = pdo_getall('hcstep_winlog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']),array(),'','time desc');
            foreach ($awardslog as $k => $v) {
                $goods = pdo_get('hcstep_awards',array('id'=>$v['goods_id'],'uniacid'=>$_GPC['i']));
                /*$awardslog[$k]['main_img'] = $_W['attachurl'].$goods['main_img'];
                $awardslog[$k]['goods_name'] = $goods['goods_name'];*/
                $awardslog[$k]['time'] = date('Y-m-d H:i:s',$v['time']);

                if($goods['awards_type'] == 1){
                    $awardslog[$k]['goods_name'] = $goods['goods_name'];
                    $awardslog[$k]['main_img'] = $_W['attachurl'].$goods['main_img'];
                }
                if($goods['awards_type'] == 2){
                    $awardslog[$k]['goods_name'] = $goods['awards_coin'].$set['coinname'];
                    $awardslog[$k]['main_img'] = $_W['siteroot']."addons/hc_step/coin.png";
                }
                if($goods['awards_type'] == 3){
                    $awardslog[$k]['goods_name'] = $goods['awards_money'].'元红包';
                    $awardslog[$k]['main_img'] = $_W['siteroot']."addons/hc_step/template/img/hb1.png";
                }
                
                if($awardslog[$k]['status'] == 0){
                    $awardslog[$k]['zhuangtai'] = "待发货";
                }else{
                    $awardslog[$k]['zhuangtai'] = "已发货";
                }
                if($awardslog[$k]['userName']){
                    $awardslog[$k]['is_dizhi'] = 1;
                }else{
                    $awardslog[$k]['is_dizhi'] = 0;
                }
            } 
        }

        return $this->result(0, '获取成功',array('coinlog'=>$coinlog,'goodslog'=>$goodslog,'awardslog'=>$awardslog));
    }

    //邀请记录
    public function doPageInvitelog(){
       
        global $_GPC, $_W;

        $set = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));

        if($set['invitetype'] == 1){
           $invitelog = pdo_getall('hcstep_invitelog', array('user_id'=>$_GPC['user_id'],'uniacid' => $_GPC['i']), array(), '', 'invite_time desc');  
        }else{
            $invitelog = pdo_getall('hcstep_hongbaolog', array('user_id'=>$_GPC['user_id'],'uniacid' => $_GPC['i']), array(), '', 'invite_time desc');
        }

        
        foreach ($invitelog as $k => $v) {
            $user = pdo_get('hcstep_users',array('user_id'=>$v['sonid'],'uniacid'=>$_GPC['i']));
            $invitelog[$k]['nick_name'] = $user['nick_name'];
            $invitelog[$k]['head_pic'] = $user['head_pic'];  
            $invitelog[$k]['invite_time'] = date('Y-m-d H:i',$v['invite_time']);
        }

        $invitetype = $set['invitetype'];

        return $this->result(0, '获取成功',array('invitelog'=>$invitelog,'invitetype'=>$invitetype));
    }

    //活动列表
    public function doPageHuodonglist(){
       
        global $_GPC, $_W;
        $set = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        $huodonglist = pdo_getall('hcstep_huodong', array('uniacid' => $_GPC['i']), array(), '', 'displayorder asc');    
        foreach ($huodonglist as $k => $v){
            $huodonglist[$k]['entrypic'] = tomedia($v['entrypic']);
            $huodonglist[$k]['diypic'] = tomedia($v['diypic']);
        }
        return $this->result(0, '获取成功',array('huodonglist'=>$huodonglist));

    }

    //问题列表
    public function doPageQuestion(){

        global $_GPC, $_W;
        $question = pdo_getall('hcstep_question', array('enabled' => 1,'uniacid'=>$_GPC['i']));
        
        foreach ($question as $k => $v) {
            $question[$k]['createtime'] = date('Y-m-d',$v['createtime']);
        }
        
        return $this->result(0,'问题',$question);

    }
    public function doPagePosterlist(){
        global $_GPC, $_W;
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i'])); 

        if(!empty($set['posterpic'])){
            $data['url'][0] = tomedia($set['posterpic']); 
        }else{
            $data['url'][0] = $_W['siteroot']."addons/hc_step/template/img/tu1.jpg";
        }  
        if(!empty($set['posterpic2'])){
            $data['url'][1] = tomedia($set['posterpic2']); 
        }else{
            $data['url'][1] = $_W['siteroot']."addons/hc_step/template/img/tu2.jpg";
        }
        if(!empty($set['posterpic3'])){
            $data['url'][2] = tomedia($set['posterpic3']); 
        }else{
            $data['url'][2] = $_W['siteroot']."addons/hc_step/template/img/tu3.jpg";
        } 
        
        $data['text'] = $set['comeon']; 
        $data['nick_name'] = $user['nick_name'];
        $data['head_pic'] = $user['head_pic'];
        $data['time'] = date('Y年m月d日',time());

        /*$accessTokenObject = json_decode(file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$_W['account']['key'].'&secret='.$_W['account']['secret']));
        //二维码接口
        //$url="https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=".$accessTokenObject->access_token;
        //菊花码
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$accessTokenObject->access_token;
        $width = '430';
        $path = '/hc_step/pages/index/index?invite='.$user_id;
        $json='{"path":"'.$path.'","width":'.$width.'}';
        //$json = '{"scene": "/user_id/'.$user_id.'", "width": 50, "page": ""}';
        //$data=$this->api_notice_increment($url,$json);
        $aaa=$this->request_post($url,$json);*/

        $data['qrcode'] = $this->GetQrcode2($_GPC['user_id']);
        $data['xcx'] = $set['xcx'];
        /*$data['1'] =  $accessTokenObject;
        $data['2'] =  $url;
        $data['3'] =  $aaa;*/   
        
        return $this->result(0,'海报',$data);
    }

    public function doPagePosterurl(){
        global $_GPC, $_W;    
        $url = $_W['siteroot']."addons/hc_step/poster.jpg";       
        
        return $this->result(0,'海报',$url);
    }

    public function doPageCreate(){
        ob_end_clean();
        ob_clean();
        global $_GPC, $_W;   
        $res = $this->createPoster($_GPC['bushu'],$_GPC['user_id'],$_GPC['listimg']);
    }

    // 生成海报
    public function CreatePoster($bushu,$user_id,$bg_path){
        ob_end_clean();
        ob_clean();
        global $_W,$_GPC;
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i'])); 
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$user_id));
        /*if(!empty($set['posterpic'])){
            $bg_path = $_W['attachurl'].$set['posterpic']; 
        }else{
            $bg_path = $_W['siteroot']."addons/hc_step/tu1.jpg";//自定义背景
        } */              
        $dst_path = $_W['siteroot']."addons/hc_step/white.png";//背景图
        $hp_path = $this->download_remote_pic($user['head_pic']);
        $qr_path = $this->GetQrcode2($user_id);//小程序码
        //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
        $qr = imagecreatefromstring(file_get_contents($qr_path));
        $hp = imagecreatefromstring(file_get_contents($hp_path));
        $bg = imagecreatefromstring(file_get_contents($bg_path)); 
        //打上文字
        $font = '../addons/hc_step/PingFang.ttf';//字体路径
        $boldfont = '../addons/hc_step/Bold.ttf';//粗字体路径
        //echo $font;
        $black = imagecolorallocate($dst, 0x00, 0x00, 0x00);//字体颜色
        $red = imagecolorallocate($dst,224,47,37);//券后价字体颜色
        $white = imagecolorallocate($dst,255,255,255);//优惠券颜色
        $hui = imagecolorallocate($dst,126,126,126);//原价颜色      
        
        //印上背景图
        list($bg_w, $bg_h) = getimagesize($bg_path);
        $bgnew_x = 689;
        $bgnew_y = 772;
        $image_bg = imagecreatetruecolor($bgnew_x,$bgnew_y); //设置缩略图
        imagecopyresampled($image_bg, $bg, 0, 0, 0, 0, $bgnew_x, $bgnew_y, $bg_w, $bg_h);
        imagecopymerge($dst,$image_bg,0,0,0,0,$bgnew_x,$bgnew_y,100);
        //印上二维码
        list($qr_w, $qr_h) = getimagesize($qr_path);
        $new_x = 115;
        $new_y = 115;
        $image_p = imagecreatetruecolor($new_x, $new_y); //设置缩略图
        imagecopyresampled($image_p, $qr, 0, 0, 0, 0, $new_x, $new_y, $qr_w, $qr_h);
        imagecopymerge($dst,$image_p,570,773,0,0,$new_x,$new_y,100);
        //印上头像
        list($hp_w, $hp_h) = getimagesize($hp_path);
        $hnew_x = 110;
        $hnew_y = 110;
        $image_hp = imagecreatetruecolor($hnew_x,$hnew_y); //设置缩略图
        imagecopyresampled($image_hp, $hp, 0, 0, 0, 0, $hnew_x, $hnew_y, $hp_w, $hp_h);
        imagecopymerge($dst,$image_hp,17,637,0,0,$hnew_x,$hnew_y,100);
        //打上文字
        $nickname =$user['nick_name'];
        $text =$set['comeon'];
        $date = date('Y年m月d日',time());
        $wname = $set['xcx'];
        $long = strlen($bushu);
        $long2 = strlen($wname);
        $right = 25 + 43*$long;
        $rightright = 550 - 10*$long2;
        imagefttext($dst,18,0,137,670,$white,$boldfont,$nickname);//昵称
        imagefttext($dst,18,0,137,700,$white,$font,'——————————————————————————————————————————');//昵称
        imagefttext($dst,30,0,137,730,$white,$boldfont,$text);//格言
        imagefttext($dst,15,0,480,25,$white,$boldfont,$date);//日期
        imagefttext($dst,50,0,25,850,$black,$boldfont,$bushu);//步数
        imagefttext($dst,20,0,$right,850,$black,$boldfont,"步");//步数
        imagefttext($dst,23,0,$rightright,830,$black,$boldfont,$wname);//步数
        imagefttext($dst,15,0,415,860,$hui,$boldfont,'长按识别小程序');//步数
        //输出图片
       /* list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
        switch ($dst_type) {
        case 1://GIF
           //header('Content-Type: image/gif');
           imagegif($dst);
           break;
        case 2://JPG
           //header('Content-Type: image/jpeg');
           imagejpeg($dst);
           break;
        case 3://PNG
           //header('Content-Type: image/png');          
           imagepng($dst);
           break;
        default:
           break;
        }*/

        $filename = dirname(__FILE__)."/poster.jpg";
        imagepng($dst,$filename);
        imagedestroy($dst);

    }
    public function doPageGoodsposterurl(){
        global $_GPC, $_W;    
        $url = $_W['siteroot']."addons/hc_step/goodsposter.jpg";       
        
        return $this->result(0,'海报',$url);
    }
    public function doPageGoodsposter()
    {
        ob_end_clean();
        ob_clean();
        global $_W,$_GPC;
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        $goods = pdo_get('hcstep_goods',array('uniacid'=>$_GPC['i'],'id'=>$_GPC['goods_id']));

        $user_id = $_GPC['user_id'];
        $selltype = $_GPC['selltype'];
        $goods_id = $_GPC['goods_id'];            
      
        $dst_path = $_W['siteroot']."addons/hc_step/bg2.png";//背景图
        $qr_path = $this->GetQrcode($user_id,$goods['id'],$goods['selltype'],$goods['paytype']);//小程序码
        //$qr_path = $_W['siteroot']."addons/hc_step/erweima.png";//小程序码
        $src_path = $_W['attachurl'].$goods['main_img'];
        //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
        $src = imagecreatefromstring(file_get_contents($src_path));
        $qr = imagecreatefromstring(file_get_contents($qr_path));
        //打上文字
        $font = '../addons/hc_step/PingFang.ttf';//字体路径
        $boldfont = '../addons/hc_step/Bold.ttf';//粗字体路径
        //echo $font;
        $black = imagecolorallocate($dst, 0x00, 0x00, 0x00);//字体颜色
        $red = imagecolorallocate($dst,224,47,37);//券后价字体颜色
        $white = imagecolorallocate($dst,255,255,255);//优惠券颜色
        $hui = imagecolorallocate($dst,126,126,126);//原价颜色

        //获取水印图片的宽高
        list($src_w, $src_h) = getimagesize($src_path);
        //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
        //小图居中
        $center = (800 - $src_w)/2;
        if($center < 0){
            $center = 0;
        }
        if($src_w > 800){
            $img_p = imagecreatetruecolor(800, 800); //设置缩略图
            imagecopyresampled($img_p, $src, 0, 0, 0, 0, 800, 800, $src_w, $src_h);
            imagecopymerge($dst, $img_p,0, 320, 0, 0, 800, 800, 100);
        }else{
            imagecopymerge($dst, $src,$center, 320, 0, 0, $src_w, $src_h, 100);
        }
        //获取水印图片的宽高
        list($qr_w, $qr_h) = getimagesize($qr_path);
        $new_x = 300;
        $new_y = 300;
        $image_p = imagecreatetruecolor($new_x, $new_y); //设置缩略图
        imagecopyresampled($image_p, $qr, 0, 0, 0, 0, $new_x, $new_y, $qr_w, $qr_h);
        imagecopymerge($dst,$image_p,50,1150,0,0,$new_x,$new_y,100);
        $goodname = $goods['goods_name'];
        $res = mb_strlen($goodname);
        
        if($res >= 18){
            $goodname1 = mb_substr($goodname,0,18,'utf-8');
            $goodname2 = mb_substr($goodname,18,$res,'utf-8');
            $goodname = $goodname1."\n".$goodname2;
            $left = 80;
        }else{
            $left = (800 - 36*$res)/2;
        }
        
        if($goods['selltype']==0 and $goods['paytype']==0){
            $tquanhoujia = $set['coinname'].$goods['price'];
            //$tyuanjia = "原价￥19.9";
            $b = imagettfbbox(28,0,$boldfont,$tquanhoujia);
            $w = abs($b[2] - $b[0]);
            $left1 = (800 - $w)/2;

            $xcxname = $set['xcx'];
            imagefttext($dst, 28, 0,$left, 120, $black, $boldfont,$goodname);
            imagefttext($dst,28,0,$left1,200,$red, $boldfont,$tquanhoujia);//券后价
            //imagefttext($dst,20,0,350,275,$hui, $boldfont,$tyuanjia);//原价
            imagefttext($dst,40,0,420,1300,$red, $boldfont,$xcxname);//领优惠券
        }
        if($goods['selltype']==0 and $goods['paytype']==1){
            $tquanhoujia = $set['coinname'].$goods['price2']."+".$goods['rmb']."元";
            $tyuanjia = "原价".$goods['maxrmb']."元";
            $b = imagettfbbox(28,0,$boldfont,$tquanhoujia);
            $w = abs($b[2] - $b[0]);
            $left1 = (800 - $w)/2;
            $bb = imagettfbbox(20,0,$boldfont,$tyuanjia);
            $ww = abs($bb[2] - $bb[0]);
            $left2 = (800 - $ww)/2;
            $xcxname = $set['xcx'];
            imagefttext($dst, 28, 0, $left, 120, $black, $boldfont,$goodname);
            imagefttext($dst,28,0,$left1,200,$red, $boldfont,$tquanhoujia);//券后价
            imagefttext($dst,20,0,$left2,275,$hui, $boldfont,$tyuanjia);//原价
            imagefttext($dst,40,0,420,1300,$red, $boldfont,$xcxname);//领优惠券
        }
        if($goods['selltype']==1 and $goods['paytype']==0){
            $tquanhoujia = $set['coinname'].$goods['price'];
            //$tyuanjia = "原价￥19.9";
            $b = imagettfbbox(28,0,$boldfont,$tquanhoujia);
            $w = abs($b[2] - $b[0]);
            $left1 = (800 - $w)/2;
            $xcxname = $set['xcx'];
            imagefttext($dst, 28, 0, $left, 120, $black, $boldfont,$goodname);
            imagefttext($dst,28,0,$left1,200,$red, $boldfont,$tquanhoujia);//券后价
            //imagefttext($dst,20,0,350,275,$hui, $boldfont,$tyuanjia);//原价
            imagefttext($dst,40,0,420,1300,$red, $boldfont,$xcxname);//领优惠券
        }
        if($goods['selltype']==1 and $goods['paytype']==1){
            $tquanhoujia = $set['coinname'].$goods['price2']."+".$goods['rmb']."元";
            $tyuanjia = "原价".$goods['maxrmb']."元";
            $b = imagettfbbox(28,0,$boldfont,$tquanhoujia);
            $w = abs($b[2] - $b[0]);
            $left1 = (800 - $w)/2;
            $bb = imagettfbbox(20,0,$boldfont,$tyuanjia);
            $ww = abs($bb[2] - $bb[0]);
            $left2 = (800 - $ww)/2;
            $xcxname = $set['xcx'];
            imagefttext($dst, 28, 0, $left, 120, $black, $boldfont,$goodname);
            imagefttext($dst,28,0,$left1,200,$red, $boldfont,$tquanhoujia);//券后价
            imagefttext($dst,20,0,$left2,275,$hui, $boldfont,$tyuanjia);//原价
            imagefttext($dst,40,0,420,1300,$red, $boldfont,$xcxname);//领优惠券
        }
        if($goods['selltype']==2){
            $tquanhoujia = "邀请".$goods['minpeople']."位好友免费送";
            //$tyuanjia = "原价￥19.9";
            $b = imagettfbbox(28,0,$boldfont,$tquanhoujia);
            $w = abs($b[2] - $b[0]);
            $left1 = (800 - $w)/2;
            $xcxname = $set['xcx'];
            imagefttext($dst, 28, 0, $left, 120, $black, $boldfont,$goodname);
            imagefttext($dst,28,0,$left1,200,$red, $boldfont,$tquanhoujia);//券后价
            //imagefttext($dst,20,0,350,275,$hui, $boldfont,$tyuanjia);//原价
            imagefttext($dst,40,0,420,1300,$red, $boldfont,$xcxname);//领优惠券
        }
        
        //输出图片
        list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
        switch ($dst_type) {
        case 1://GIF
           //header('Content-Type: image/gif');
           imagegif($dst);
           break;
        case 2://JPG
           //header('Content-Type: image/jpeg');
           imagejpeg($dst);
           break;
        case 3://PNG
           //header('Content-Type: image/png');          
           imagepng($dst);
           break;
        default:
           break;
        }
        
        $filename = dirname(__FILE__)."/goodsposter.jpg";
        imagepng($dst,$filename);
        imagedestroy($dst);
 

    }
    //生成小程序码
    public function GetQrcode($user_id,$goods_id,$selltype,$paytype){
        global $_GPC, $_W;
        // 获取access_token
        //$accessTokenObject = json_decode(file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$_W['account']['key'].'&secret='.$_W['account']['secret']));
        $account_api = WeAccount::create();
        $token = $account_api->getAccessToken();
        //菊花码
        //$url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$accessTokenObject->access_token;
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$token;
        $width = '430';
        
        if(!empty($goods_id) or !empty($selltype) or !empty($paytype)){
            $path = '/hc_step/pages/detail/detail?invite='.$user_id.'&index=1&id='.$goods_id.'&selltype='.$selltype.'&paytype='.$paytype;
        }else{
            $path = '/hc_step/pages/index/index?invite='.$user_id;
        }
        $json='{"path":"'.$path.'","width":'.$width.'}';
        //$json = '{"scene": "/user_id/'.$user_id.'", "width": 50, "page": ""}';
        //$data=$this->api_notice_increment($url,$json);
        $data=$this->request_post($url,$json);
        $filename = dirname(__FILE__)."/erweima.png";
        $local_file = fopen($filename, 'w');
        if (false !== $local_file) {
            if (false !== fwrite($local_file, $data)) {
                fclose($local_file);
            }
        }
        //file_put_contents($filename,$data);
        return $_W['siteroot']."addons/hc_step/erweima.png";
        //return $data;
    }

    //生成小程序码
    public function GetQrcode2($user_id){
        global $_GPC, $_W;
        // 获取access_token
        //$accessTokenObject = json_decode(file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$_W['account']['key'].'&secret='.$_W['account']['secret']));
        $account_api = WeAccount::create();
        $token = $account_api->getAccessToken();
        //菊花码
        //$url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$accessTokenObject->access_token;
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$token;
        $width = '430';
        
        /*if(!empty($goods_id) or !empty($selltype) or !empty($paytype)){
            $path = '/hc_step/pages/detail/detail?invite='.$user_id.'&index=1&id='.$goods_id.'&selltype='.$selltype.'&paytype='.$paytype;
        }else{*/
            $path = '/hc_step/pages/index/index?invite='.$user_id;
        //}
        $json='{"path":"'.$path.'","width":'.$width.'}';
        //$json = '{"scene": "/user_id/'.$user_id.'", "width": 50, "page": ""}';
        //$data=$this->api_notice_increment($url,$json);
        $data=$this->request_post($url,$json);
        $filename = dirname(__FILE__)."/erweima.png";
        $local_file = fopen($filename, 'w');
        if (false !== $local_file) {
            if (false !== fwrite($local_file, $data)) {
                fclose($local_file);
            }
        }
        //file_put_contents($filename,$data);
        return $_W['siteroot']."addons/hc_step/erweima.png";
        //return $data;
    }

    //生成小程序码
    public function doPageGetgoodscode(){
        global $_GPC, $_W;
        $user_id = $_GPC['user_id'];
        $goods_id = $_GPC['goods_id'];
        $selltype = $_GPC['selltype'];
        $paytype = $_GPC['paytype'];
        // 获取access_token
        //$accessTokenObject = json_decode(file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$_W['account']['key'].'&secret='.$_W['account']['secret']));
        $account_api = WeAccount::create();
        $token = $account_api->getAccessToken();
        //菊花码
        //$url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$accessTokenObject->access_token;
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$token;
        $width = '180';
        $path = '/hc_step/pages/detail/detail?invite='.$user_id.'&index=1&id='.$goods_id.'&selltype='.$selltype.'&paytype='.$paytype;
        $json='{"path":"'.$path.'","width":'.$width.'}';
        //$json = '{"scene": "/user_id/'.$user_id.'", "width": 50, "page": ""}';
        //$data=$this->api_notice_increment($url,$json);
        $data = $this->request_post($url,$json);
        $png = "/headpic/goods".$_GPC['i'].time().".png";
        $filename = dirname(__FILE__).$png;
        $local_file = fopen($filename, 'w');
        if (false !== $local_file) {
            if (false !== fwrite($local_file, $data)) {
                fclose($local_file);
            }
        }
        $url = $_W['siteroot']."addons/hc_step".$png;
        $aaa['url'] = $url;
        return $this->result(0,'获取成功',$aaa);
    }

    //生成小程序码
    public function doPageGethxcode(){
        global $_GPC, $_W;
        //获取access_token
        //$accessTokenObject = json_decode(file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$_W['account']['key'].'&secret='.$_W['account']['secret']));
        $order = pdo_get('hcstep_orders',array('id'=>$_GPC['order_id']));
        $goods = pdo_get('hcstep_goods',array('id'=>$order['goods_id'])); 
        $shop  = pdo_get('hcstep_shop',array('id'=>$goods['shop_id']));
        $aaa['logo'] = $_W['attachurl'].$shop['logo'];
        $aaa['shopname'] = $shop['shopname']; 
        $account_api = WeAccount::create();
        $token = $account_api->getAccessToken();
        //菊花码
        //$url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$accessTokenObject->access_token;
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$token;
        $width = '180';
        $path = '/hc_step/pages/room/room?order_id='.$_GPC['order_id'];
        $json='{"path":"'.$path.'","width":'.$width.'}';
        //$json = '{"scene": "/user_id/'.$user_id.'", "width": 50, "page": ""}';
        //$data=$this->api_notice_increment($url,$json);
        $data = $this->request_post($url,$json);
        $png = "/headpic/hexiao".$_GPC['i'].time().".png";
        $filename = dirname(__FILE__).$png;
        $local_file = fopen($filename, 'w');
        if (false !== $local_file) {
            if (false !== fwrite($local_file, $data)) {
                fclose($local_file);
            }
        }
        $url = $_W['siteroot']."addons/hc_step".$png;
        $aaa['url'] = $url;
        return $this->result(0,'获取成功',$aaa);
        //return $_W['siteroot']."addons/hc_step/hexiao".$_GPC['i'].".png";
    }

    //消息客服
    public function doPageKefu()
    {
        ob_end_clean();
        global $_GPC, $_W;

        $user_id = $_GPC['user_id'];
        $users=pdo_get('hcstep_users', array('user_id' => $user_id));
        $setup=pdo_get('hcstep_set', array('uniacid' => $_GPC['i']));
        $post_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->wx_get_token();
        $post_data = json_encode(array(
            'touser' =>$users['open_id'],
            'msgtype' => 'link',
            'link' => array(
                'title'=>$setup['kefu_title'],
                'description'=>$setup['kefu_gaishu'],
                'url'=>$setup['kefu_url'],
                'thumb_url'=>$_W['attachurl'].$setup['kefu_img'],
            ),
        ), JSON_UNESCAPED_UNICODE);
        $json = $this->api_notice_increment($post_url,$post_data);
        $arr = json_decode($json,true);
        if($arr['errcode']!=0){
            $this->kefu($user_id);
        }
        $myfile = fopen(IA_ROOT."/addons/hc_step/kefu.txt", 'w');
        fwrite($myfile, $json);
        fclose($myfile);
        $json=json_decode($json,true);
        return $this->result(0, '获取成功',$json);
    }


    public function kefu($user_id)
    {
        ob_end_clean();
        global $_GPC, $_W;
        $users=pdo_get('hcstep_users', array('user_id' => $user_id));
        $setup=pdo_get('hcstep_set', array('uniacid' => $users['uniacid']));
        $post_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->wx_get_token();
        $post_data = json_encode(array(
            'touser' =>$users['open_id'],
            'msgtype' => 'link',
            'link' => array(
                'title'=>$setup['kefu_title'],
                'description'=>$setup['kefu_gaishu'],
                'url'=>$setup['kefu_url'],
                'thumb_url'=>$_W['attachurl'].$setup['kefu_img'],
            ),
        ), JSON_UNESCAPED_UNICODE);

        $json = $this->api_notice_increment($post_url,$post_data);
        $json=json_decode($json,true);
        if($json['errcode']!=0){
            $this->kefu($user_id);
        }
        return $this->result(0, '获取成功',$json);
    }

    //关注记录
    public function doPageGuanzhuaddstep()
    {
        ob_end_clean();
        global $_GPC, $_W;
        $set = pdo_get('hcstep_set', array('uniacid' => $_GPC['i']));
        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $data['step'] = $set['guanzhu_step'];
        $data['time'] = time();
        $data['status'] = 0;//关注未赠送

        $ishave = pdo_get('hcstep_guanzhulog', array('user_id' => $_GPC['user_id'],'uniacid'=>$_GPC['i']));
            if($ishave){
                $aaa = 1;
            }else{
                $result = pdo_insert('hcstep_guanzhulog', $data, $replace = true);
            }
            if(!empty($result)) {
               return $this->result(0, '获取成功',$result);
            }else{
               return $this->result(1,'',$result);
            }
    }

    //根据版本号骗审
    public function doPageShenhe(){

        global $_GPC, $_W;
    
        $shenhe = pdo_get('hcstep_set', array('uniacid'=>$_GPC['i']));

        if($shenhe['version'] == $_GPC['v']){
            $data['shenhe'] = 1;
        }else{
            $data['shenhe'] = 0;
        }
        
        $this->result(0, '是否骗审',$data);      
          
    }
    //骗审功能接口
    public function doPageSanshibushu(){
        global $_GPC, $_W;
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));

        $code = $_GPC['code'];
        $account = pdo_get('account_wxapp', array('uniacid' => $_GPC['i']));
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $account['key'] . '&secret=' . $account['secret'] . '&js_code=' . $code . '&grant_type=authorization_code';
        $result = $this->get_url_content($url);
        $result = json_decode($result, true);

        $uid           = $_GPC['user_id'];
        $encryptedData = $_GPC['wRunEncryptedData'];
        $iv            = $_GPC['iv'];
        $sessionKey    = $result['session_key'];
        $appid = $_W['account']['key'];
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            $bushu = json_decode($data,'true');
            //$bushu = $bushu['stepInfoList'][30]['step'];
            $bushu = $bushu['stepInfoList'];
            foreach ($bushu as $k => $v) {
                $bushu[$k]['timestamp'] = date('Y-m-d',$v['timestamp']);
                $arr[] = $v['step'];
            }
            
            $time=date('Y-m-d',time());

            $aaa['today'] = $bushu[30]['step'];
            $aaa['max'] = max($arr);
            $aaa['count'] = array_sum($arr);
                        
            return $this->result(0, '操作成功',array('bushu'=>$bushu,'data'=>$aaa));
        }else{
            return $this->result(1, array('errcode'=>$errCode,'appid'=>$appid));
        }

    }
    public function doPageAdv(){

        global $_GPC, $_W;
        //首页轮播图
        $banner = pdo_getall('hcstep_adv', array('enabled'=>1,'uniacid' => $_GPC['i']), array(), '', 'displayorder asc');
        foreach ($banner as $key => $val) {
            if(strpos($val['thumb'],'https') !== false){
               $banner[$key]['thumb'] = $val['thumb'];
            }else{
               $banner[$key]['thumb'] = $_W['attachurl'].$val['thumb'];
            } 

            if(strpos($val['tippic'],'https') !== false){
               $banner[$key]['tippic'] = $val['tippic'];
            }else{
               $banner[$key]['tippic'] = $_W['attachurl'].$val['tippic'];
            }       
        }

        $icon = pdo_getall('hcstep_icon', array('enabled'=>1,'uniacid' => $_GPC['i']), array(), '', 'displayorder asc');
        foreach ($icon as $key => $val) {
            if(strpos($val['thumb'],'https') !== false){
               $icon[$key]['thumb'] = $val['thumb'];
            }else{
               $icon[$key]['thumb'] = $_W['attachurl'].$val['thumb'];
            }
            if(strpos($val['tippic'],'https') !== false){
               $icon[$key]['tippic'] = $val['tippic'];
            }else{
               $icon[$key]['tippic'] = $_W['attachurl'].$val['tippic'];
            }
            if($icon[$key]['jump'] == 0 ){
                if(empty($val['runpic'])){
                   $runpic = $val['runpic'];
                }else{
                   $runpic = $_W['attachurl'].$val['runpic'];
                }
            }        
        }

        $hongbao = pdo_getall('hcstep_hongbao', array('enabled'=>1,'uniacid' => $_GPC['i']), array(), '', 'displayorder asc');
        $hongbaolog = pdo_getall('hcstep_hongbaolog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']));
        $count = count($hongbaolog);
        foreach ($hongbao as $key => $val) {
            if(strpos($val['hongbaopic'],'https') !== false){
               $hongbao[$key]['hongbaopic'] = $val['hongbaopic'];
            }else{
               $hongbao[$key]['hongbaopic'] = $_W['attachurl'].$val['hongbaopic'];
            }        
        }
        foreach ($hongbao as $k => $v) {
            if($v['displayorder'] <= $count){
                $hongbao[$k]['status'] = 1;//已分享
            }else{
                $hongbao[$k]['status'] = 0;//未分享
            }        
        }

        $hongbao['finishpic'] = $_W['siteroot']."addons/hc_step/hongbao.png";

        return $this->result(0,'操作成功',array('adv'=>$banner,'icon'=>$icon,'runpic'=>$runpic,'hongbao'=>$hongbao));
    }

    //3000,5000,10000
    public function doPageActivity(){
        global $_GPC, $_W;
        //首页轮播图
        $activity = pdo_getall('hcstep_activity', array('uniacid' => $_GPC['i']), array(), '', 'displayorder asc');
        foreach ($activity as $k => $v) {
            $activity[$k]['step'] = $v['step'].'步';
            $activity[$k]['tomorrow'] = date("Y-m-d",strtotime("+1 day"));
            //$weekarray=array("日","一","二","三","四","五","六");
            //$activity[$k]['libai'] = $weekarray[date("w",strtotime(date("Y-m-d",strtotime("+1 day"))))];
        }

        return $this->result(0, '操作成功',array('activity'=>$activity));
    }

    //3000步活动昨天今天明天
    public function doPageActivitylist(){
        
        global $_GPC, $_W;       
        $activity = pdo_getall('hcstep_activity', array('uniacid' => $_GPC['i']), array(), '', 'displayorder asc');
        $aid = $_GPC['aid'];      
        if($aid){
            $set = pdo_get('hcstep_activity',array('uniacid'=>$_GPC['i'],'id'=>$aid));
        }else{
            $set = $activity[0];
        }                 
        
        $nextday = date('Y-m-d',strtotime("+1 day"));
        $mingtian = date('Y年m月d日',strtotime("+1 day"));
        
        $time=date('Y-m-d',time());
        $jintian = date('Y年m月d日',time());
        
        $lastday = date('Y-m-d',strtotime("-1 day"));
        $zuotian = date('Y年m月d日',strtotime("-1 day"));
        $weekarray=array("日","一","二","三","四","五","六");

        if($aid){
            $ishave = pdo_get('hcstep_activitylog',array('user_id'=>$_GPC['user_id'],'aid'=>$_GPC['aid'],'time'=>$nextday));
        }else{
            $set = $activity[0];
            $ishave = pdo_get('hcstep_activitylog',array('user_id'=>$_GPC['user_id'],'aid'=>$set['id'],'time'=>$nextday));
        }
        if($ishave){
            $data['tomorrow']['status'] = 1; //已报名
        }else{
            $data['tomorrow']['status'] = 0; //未报名
        }

        $data['tomorrow']['aid'] = $set['id'];
        $data['tomorrow']['renshu'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$nextday,'aid'=>$_GPC['aid']));
        $data['tomorrow']['renshu'] = count($data['tomorrow']['renshu']);
        $data['tomorrow']['jiangjin'] = $data['tomorrow']['renshu'] * $set['entryfee']; 
        $data['tomorrow']['entryfee'] = $set['entryfee'];
        $data['tomorrow']['step'] = $set['step'];
        $data['tomorrow']['kaishi'] = $set['starttime'];
        $data['tomorrow']['jieshu'] = $set['endtime'];
        $data['tomorrow']['day'] = $mingtian;
        $data['tomorrow']['week'] = $weekarray[date("w",strtotime($nextday))];
        $data['tomorrow']['nowtime'] = time();
        $data['tomorrow']['endtime'] = strtotime(date("Y-m-d",strtotime("+1 day")));

        $data['today']['success'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$time,'status !='=>0,'aid'=>$_GPC['aid']));
        $data['today']['success'] = count($data['today']['success']);
        $data['today']['fail'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$time,'status'=>0,'aid'=>$_GPC['aid']));
        $data['today']['fail'] = count($data['today']['fail']);
        $data['today']['zong'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$time,'aid'=>$_GPC['aid']));
        $data['today']['zong'] = count($data['today']['zong']);
        if($data['today']['success'] == 0){
           $data['today']['returnrate'] = -100;
        }else{
           $data['today']['returnrate'] = ($data['today']['zong']/$data['today']['success'] - 1)*100;
        }
        $data['today']['returnrate'] = round($data['today']['returnrate'],2);
        $data['today']['jiangjin'] = $data['today']['zong'] * $set['entryfee'];
        $data['today']['day'] = $jintian;
        $data['today']['week'] = $weekarray[date("w",strtotime($time))];
        $data['today']['canjia'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$time,'user_id'=>$_GPC['user_id'],'aid'=>$_GPC['aid']));
        if($data['today']['canjia']){
            $data['today']['status'] = 1; //已参加
        }else{
            $data['today']['status'] = 0; //未参加
        }

        $data['yesterday']['success'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$lastday,'status !='=>0,'aid'=>$_GPC['aid']));
        $data['yesterday']['success'] = count($data['yesterday']['success']);
        $data['yesterday']['zong'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$lastday,'aid'=>$_GPC['aid']));
        $data['yesterday']['zong'] = count($data['yesterday']['zong']);
        $data['yesterday']['jiangjin'] = $data['yesterday']['zong'] * $set['entryfee'];
        $data['yesterday']['day'] = $zuotian;
        $data['yesterday']['week'] = $weekarray[date("w",strtotime($lastday))];
        $data['yesterday']['canjia'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$lastday,'user_id'=>$_GPC['user_id'],'aid'=>$_GPC['aid']));
        if($data['yesterday']['canjia']){
            $data['yesterday']['status'] = 1; //已参加
        }else{
            $data['yesterday']['status'] = 0; //未参加
        }

        return $this->result(0, '操作成功',array('data'=>$data));
    }

    //参赛记录
    public function doPageActivitylog(){
        global $_GPC, $_W;
        $tomorrow = date('Y-m-d',strtotime("+1 day"));      
        $today=date('Y-m-d',time());      
        $yesterday = date('Y-m-d',strtotime("-1 day"));

        $log = pdo_getall('hcstep_activitylog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']),array(),'','timestamp desc');
        foreach ($log as $k => $v){
              $activity = pdo_get('hcstep_activity',array('uniacid' => $_GPC['i'],'id'=>$v['aid']));
              $log[$k]['name'] = $activity['step'];
              $log[$k]['entryfee'] = $activity['entryfee'];
              if($v['time'] == $tomorrow){
                $log[$k]['status'] = '未开赛';
                $log[$k]['color'] = '00b4ff';
              }elseif($v['time'] == $today){
                $log[$k]['status'] = '进行中';
                $log[$k]['color'] = '00b4ff';
              }else{
                if($v['status'] == 0){
                    $log[$k]['status'] = '挑战失败';
                    $log[$k]['color'] = 'ff0000';
                }else{
                    $log[$k]['status'] = '挑战成功';
                    $log[$k]['color'] = '309e4d';
                }
              }           
          }

        return $this->result(0, '操作成功',array('log'=>$log));      
    }

    //参赛记录
    public function doPageHexiaolog(){
        
        global $_GPC, $_W;
        $state = $_GPC['state'];
        if($state < 2){
            $log = pdo_getall('hcstep_orders',array('uniacid'=>$_GPC['i'],'type >'=>9,'user_id'=>$_GPC['user_id'],'hexiaostatus'=>$state,'status <'=>2),array(),'','time desc');
            $time = time();
            foreach ($log as $k => $v){
               $goods = pdo_get('hcstep_goods',array('uniacid'=>$_GPC['i'],'id'=>$v['goods_id']));
               $shop = pdo_get('hcstep_shop',array('uniacid'=>$_GPC['i'],'id'=>$goods['shop_id']));
               $log[$k]['shopname'] = $shop['shopname'];
               $log[$k]['logo'] = $_W['attachurl'].$shop['logo'];
               $log[$k]['sheng'] = $shop['sheng'];
               $log[$k]['shi'] = $shop['shi'];
               $log[$k]['qu'] = $shop['qu'];
               $log[$k]['address'] = $shop['address'];
               $log[$k]['goods_name'] = $goods['goods_name'];
               $log[$k]['paytime'] = date("Y-m-d H:i",$v['time']);
               $log[$k]['hexiaotime'] = date("Y-m-d:H:i",$v['hexiaotime']);
               if($state == 0){ 
                  if($v['endtime'] <= $time and $v['endtime'] > 0){
                     $log[$k]['guoqi'] = 1;                
                  }
                  if($v['endtime'] != 0){
                    $log[$k]['endtime'] = date("Y-m-d H:i",$v['endtime']);          
                  }else{
                    $log[$k]['endtime'] = '永久';
                  }
                  
               }
            } 
        }
        if($state == 2){
            $time = time();
            $log = pdo_getall('hcstep_orders',array('uniacid'=>$_GPC['i'],'type >'=>9,'user_id'=>$_GPC['user_id'],'hexiaostatus'=>0,'status <'=>2,'endtime <='=>$time,'endtime >'=>0),array(),'','time desc');
            
            foreach ($log as $k => $v){
               $goods = pdo_get('hcstep_goods',array('uniacid'=>$_GPC['i'],'id'=>$v['goods_id']));
               $shop = pdo_get('hcstep_shop',array('uniacid'=>$_GPC['i'],'id'=>$goods['shop_id']));
               $log[$k]['shopname'] = $shop['shopname'];
               $log[$k]['logo'] = $_W['attachurl'].$shop['logo'];
               $log[$k]['sheng'] = $shop['sheng'];
               $log[$k]['shi'] = $shop['shi'];
               $log[$k]['qu'] = $shop['qu'];
               $log[$k]['address'] = $shop['address'];
               $log[$k]['goods_name'] = $goods['goods_name'];
               $log[$k]['paytime'] = date("Y-m-d H:i",$v['time']);
               $log[$k]['hexiaotime'] = date("Y-m-d:H:i",$v['hexiaotime']);
               //if($state == 0){ 
                  //if($v['endtime'] <= $time and $v['endtime'] > 0){
                     $log[$k]['guoqi'] = 1;                
                  //}
                  //if($v['endtime'] != 0){
                    $log[$k]['endtime'] = date("Y-m-d H:i",$v['endtime']);          
                  //}else{
                  //  $log[$k]['endtime'] = '永久';
                  //}
                  
               //}
            } 
        }
        
        return $this->result(0, '操作成功',array('log'=>$log));      
    
    }

    public function doPageHexiao(){
        
        global $_GPC, $_W;
        $order = pdo_get('hcstep_orders',array('uniacid'=>$_GPC['i'],'id'=>$_GPC['order_id']));
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$order['user_id']));
        $goods = pdo_get('hcstep_goods',array('uniacid'=>$_GPC['i'],'id'=>$order['goods_id']));
        $shop =  pdo_get('hcstep_shop',array('uniacid'=>$_GPC['i'],'id'=>$goods['shop_id']));
        $time = time();

        $data['username'] = $user['nick_name'];
        $data['goodsname'] = $goods['goods_name'];
        $data['shopname'] = $shop['shopname'];
        $data['topbg'] = $_W['attachurl'].$shop['topbg'];
        $data['paytime'] = date("Y-m-d:H:i:s",$order['time']);
        if($order['hexiaostatus'] == 1){
           $data['hexiaostatus'] = '已核销';
        }else{
           if($order['endtime']<$time and $order['endtime']>0){
              $data['hexiaostatus'] = '已过期'; 
           }else{
              $data['hexiaostatus'] = '未核销'; 
           }
           
        }
        return $this->result(0, '操作成功',array('data'=>$data));          
    }

    public function doPageHexiaoshenhe(){
        
        global $_GPC, $_W;
        $order = pdo_get('hcstep_orders',array('uniacid'=>$_GPC['i'],'id'=>$_GPC['order_id']));
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$order['user_id']));
        $goods = pdo_get('hcstep_goods',array('uniacid'=>$_GPC['i'],'id'=>$order['goods_id']));
        $shop =  pdo_get('hcstep_shop',array('uniacid'=>$_GPC['i'],'id'=>$goods['shop_id']));
        $time = time();
        $str = explode(",",$shop['user_id']);

        if($order['hexiaostatus'] == 1){
            return $this->result(1, '已核销',$order);
        }elseif(in_array($_GPC['shenheyuan_id'],$str)){
            if($order['endtime']<$time and $order['endtime']>0){
               return $this->result(1, '核销码已过期',array('data'=>$data)); 
            }else{
                $data = pdo_update('hcstep_orders',array('hexiaostatus'=>1,'hexiaotime'=>time(),'hexiaoyuan'=>$_GPC['shenheyuan_id']), array('id' => $_GPC['order_id']));
                return $this->result(0, '核销成功',array('data'=>$data));
            }        
        }else{
            return $this->result(1, '没有核销权限',$order);
        }      
    }

    //报名
    public function doPageApply(){
        global $_GPC, $_W;
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        //记录报名
        $set = pdo_get('hcstep_activity', array('uniacid' => $_GPC['i'],'id'=>$_GPC['aid']));
        $data['time'] = date('Y-m-d',strtotime("+1 day"));
        $log = pdo_get('hcstep_activitylog', array('uniacid' => $_GPC['i'],'aid'=>$_GPC['aid'],'user_id'=>$_GPC['user_id'],'time'=>$data['time']));

        if($user['money'] < $set['entryfee']){           
           return $this->result(1, '报名费不足');       
        }elseif(!empty($log)){
           return $this->result(1, '请不要重复报名');  
        }else{           
            $data['uniacid'] = $_GPC['i'];
            $data['user_id'] = $_GPC['user_id'];
            $data['aid'] = $_GPC['aid'];
            $data['step'] = 0;
            $data['entryfee'] = $set['entryfee'];
            $data['timestamp'] = time();
            $data['time'] = date('Y-m-d',strtotime("+1 day"));
            $data['status'] = 0;
            $result = pdo_insert('hcstep_activitylog', $data, $replace = true);
            if($result){
                $nowmoney = $user['money'] - $set['entryfee'];
                pdo_update('hcstep_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));
            }
            

            return $this->result(0, '报名成功',array('result'=>$result));
        }       
    }
    
    //报名
    public function doPageUpdatestep(){
        
        global $_GPC, $_W;
        $set = pdo_getall('hcstep_activity', array('uniacid' => $_GPC['i']));
        $time=date('Y-m-d',time());
        
        $code = $_GPC['code'];
        $account = pdo_get('account_wxapp', array('uniacid' => $_GPC['i']));
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $account['key'] . '&secret=' . $account['secret'] . '&js_code=' . $code . '&grant_type=authorization_code';
        $result = $this->get_url_content($url);
        $result = json_decode($result, true);

        $uid           = $_GPC['user_id'];
        $encryptedData = $_GPC['wRunEncryptedData'];
        $iv            = $_GPC['iv'];
        $sessionKey    = $result['session_key'];
        $appid = $_W['account']['key'];
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if($errCode == 0){
            $bushu = json_decode($data,'true');
            $bushu = $bushu['stepInfoList'][30]['step'];
            foreach ($set as $k => $v) {
                if($bushu >= $v['step']){
                    pdo_update('hcstep_activitylog',array('step'=>$bushu,'status'=>1), array('user_id' => $_GPC['user_id'],'aid'=>$v['id'],'time'=>$time,'uniacid'=>$_GPC['i']));
                }else{
                   pdo_update('hcstep_activitylog',array('step'=>$bushu), array('user_id' => $_GPC['user_id'],'aid'=>$v['id'],'time'=>$time,'uniacid'=>$_GPC['i'])); 
                }
            }         
            return $this->result(0,'同步成功',$bushu);
        }else{
            return $this->result(1, array('errcode'=>$errCode));
        }
        
    }
    public function doPageFormid(){
        ob_end_clean();
        global $_GPC, $_W;
        $data['user_id']=$_GPC['user_id'];
        $data['formid']=$_GPC['formid'];
        //$aa=$this->getMessagemoney($user_id,$money);
        if(strpos($data['formid']," ")){
            $this->result(0, 'formid不正确');
        }elseif(empty($_GPC['formid']))
        {
            $this->result(0, 'formid为空');
        }else{
            pdo_insert('hcstep_formid', $data);
            $cid = pdo_insertid();
            $this->result(0, '获取成功',$_GPC);
        }

    }
    //开启较大红包
    public function doPageBighb(){
        global $_GPC,$_W;
        $set = pdo_get('hcstep_message',array('uniacid'=>$_GPC['i']));
        $set2 = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $min = $set['min_bighbmoney']*100;
        $max = $set['max_bighbmoney']*100;
        $aaa = mt_rand($min,$max);
        $res['uniacid'] = $_GPC['i'];
        $res['user_id'] = $_GPC['user_id'];
        $res['son_id'] = $_GPC['son_id'];
        $res['hbmoney'] = $aaa/100;
        $res['time'] = time();
        $res['daytime'] = date('Y-m-d',time());
        $res['type'] = 1;
        $res['status'] = 2;
        $ishave = pdo_get('hcstep_fourhblog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'daytime'=>$res['daytime']));

        if($user['money'] < $set['fourhb_coin']){
            if(!empty($ishave)){
                $res['isopen'] = 1;//已开启
                $res['is_money'] = 666;
                $this->result(0, '获取成功',$res);
            }else{
                //$user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
                /*$nowrmb = $user['rmb'] + $res['hbmoney'];
                $result = pdo_update('hcstep_users', array('rmb'=>$nowrmb), array('user_id'=>$_GPC['user_id']));
                $nowmoney = $user['money'] - $set['fourhb_coin'];
                $result = pdo_update('hcstep_users', array('money'=>$nowmoney), array('user_id'=>$_GPC['user_id']));
                $aaa = pdo_insert('hcstep_fourhblog',$res);*/
                $res['isopen'] = 0;//未开启
                $res['is_money'] = 666;
                //$res['fourhb_coin'] = $set['fourhb_coin'].$set2['coinname'];
                $this->result(0, '获取成功',$res);
            }
        }else{
            if(!empty($ishave)){
                $res['isopen'] = 1;//已开启
                $res['is_money'] = 111;
                $this->result(0, '获取成功',$res);
            }else{
                //$user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
                $nowrmb = $user['rmb'] + $res['hbmoney'];
                $result = pdo_update('hcstep_users', array('rmb'=>$nowrmb), array('user_id'=>$_GPC['user_id']));
                $nowmoney = $user['money'] - $set['fourhb_coin'];
                $result = pdo_update('hcstep_users', array('money'=>$nowmoney), array('user_id'=>$_GPC['user_id']));
                $aaa = pdo_insert('hcstep_fourhblog',$res);
                $res['isopen'] = 0;//未开启
                $res['is_money'] = 111;
                $res['fourhb_coin'] = $set['fourhb_coin'].$set2['coinname'];
                $this->result(0, '获取成功',$res);
            }
        }        
    }
    //开启较小红包
    public function doPageOpensmallhb(){
        global $_GPC,$_W;
        $daytime = date('Y-m-d',time());
        $today1 = pdo_getall('hcstep_fourhblog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'type'=>2,'status'=>1,'daytime'=>$daytime),array(),'','time asc');//待开启
        $result = pdo_update('hcstep_fourhblog', array('status'=>2), array('id'=>$today1[0]['id']));
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $nowmoney = $user['rmb'] + $today1[0]['hbmoney'];
        $aaa = pdo_update('hcstep_users', array('rmb'=>$nowmoney), array('user_id'=>$_GPC['user_id']));
        $this->result(0, '获取成功',$result);  
    }
    //小红包状态
    public function doPageSmallhb(){
        global $_GPC, $_W; 
        $user = pdo_get('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'])); 
        $daytime = date('Y-m-d',time());
        $today1 = pdo_getall('hcstep_fourhblog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'type'=>2,'status'=>1,'daytime'=>$daytime),array(),'','time asc');//待开启
        $today2 = pdo_getall('hcstep_fourhblog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'type'=>2,'status'=>2,'daytime'=>$daytime),array(),'','time asc');//已开启 
        $aaa = count($today1);
        $bbb = count($today2);
        if($aaa == 0 and $bbb == 0){
            $data[0]['status'] = 0;//
            $data[0]['hbmoney'] = 0.00;//
            $data[1]['status'] = 0;//
            $data[1]['hbmoney'] = 0.00;//
            $data[2]['status'] = 0;//
            $data[2]['hbmoney'] = 0.00;//
            $data[3]['status'] = 0;//
            $data[3]['hbmoney'] = 0.00;//
        }
        if($aaa == 0 and $bbb == 1){
            $data[0]['status'] = 2;//
            $data[0]['hbmoney'] = $today2[0]['hbmoney'];//
            $data[1]['status'] = 0;//
            $data[1]['hbmoney'] = 0.00;//
            $data[2]['status'] = 0;//
            $data[2]['hbmoney'] = 0.00;//
            $data[3]['status'] = 0;//
            $data[3]['hbmoney'] = 0.00;//
        }
        if($aaa == 0 and $bbb == 2){
            $data[0]['status'] = 2;//
            $data[0]['hbmoney'] = $today2[0]['hbmoney'];//
            $data[1]['status'] = 2;//
            $data[1]['hbmoney'] = $today2[1]['hbmoney'];//
            $data[2]['status'] = 0;//
            $data[2]['hbmoney'] = 0.00;//
            $data[3]['status'] = 0;//
            $data[3]['hbmoney'] = 0.00;//
        }
        if($aaa == 0 and $bbb == 3){
            $data[0]['status'] = 2;//
            $data[0]['hbmoney'] = $today2[0]['hbmoney'];//
            $data[1]['status'] = 2;//
            $data[1]['hbmoney'] = $today2[1]['hbmoney'];//
            $data[2]['status'] = 2;//
            $data[2]['hbmoney'] = $today2[2]['hbmoney'];//
            $data[3]['status'] = 0;//
            $data[3]['hbmoney'] = 0.00;//
        }
        if($aaa == 0 and $bbb == 4){
            $data[0]['status'] = 2;//
            $data[0]['hbmoney'] = $today2[0]['hbmoney'];//
            $data[1]['status'] = 2;//
            $data[1]['hbmoney'] = $today2[1]['hbmoney'];//
            $data[2]['status'] = 2;//
            $data[2]['hbmoney'] = $today2[2]['hbmoney'];//
            $data[3]['status'] = 2;//
            $data[3]['hbmoney'] = $today2[3]['hbmoney'];//
        }
        if($aaa == 1 and $bbb == 0){
            $data[0]['status'] = 1;//
            $data[0]['hbmoney'] = 0;//
            $data[1]['status'] = 0;//
            $data[1]['hbmoney'] = 0;//
            $data[2]['status'] = 0;//
            $data[2]['hbmoney'] = 0;//
            $data[3]['status'] = 0;//
            $data[3]['hbmoney'] = 0;//
        }
        if($aaa == 1 and $bbb == 1){
            $data[0]['status'] = 2;//
            $data[0]['hbmoney'] = $today2[0]['hbmoney'];//
            $data[1]['status'] = 1;//
            $data[1]['hbmoney'] = 0;//
            $data[2]['status'] = 0;//
            $data[2]['hbmoney'] = 0;//
            $data[3]['status'] = 0;//
            $data[3]['hbmoney'] = 0;//
        }
        if($aaa == 1 and $bbb == 2){
            $data[0]['status'] = 2;//
            $data[0]['hbmoney'] = $today2[0]['hbmoney'];//
            $data[1]['status'] = 2;//
            $data[1]['hbmoney'] = $today2[1]['hbmoney'];//
            $data[2]['status'] = 1;//
            $data[2]['hbmoney'] = 0;//
            $data[3]['status'] = 0;//
            $data[3]['hbmoney'] = 0;//
        }
        if($aaa == 1 and $bbb == 3){
            $data[0]['status'] = 2;//
            $data[0]['hbmoney'] = $today2[0]['hbmoney'];//
            $data[1]['status'] = 2;//
            $data[1]['hbmoney'] = $today2[1]['hbmoney'];//
            $data[2]['status'] = 2;//
            $data[2]['hbmoney'] = $today2[2]['hbmoney'];//
            $data[3]['status'] = 1;//
            $data[3]['hbmoney'] = 0;//
        }
        if($aaa == 2 and $bbb == 0){
            $data[0]['status'] = 1;//
            $data[0]['hbmoney'] = 0;//
            $data[1]['status'] = 1;//
            $data[1]['hbmoney'] = 0;//
            $data[2]['status'] = 0;//
            $data[2]['hbmoney'] = 0;//
            $data[3]['status'] = 1;//
            $data[3]['hbmoney'] = 0;//
        }
        if($aaa == 2 and $bbb == 1){
            $data[0]['status'] = 2;//
            $data[0]['hbmoney'] = $today2[0]['hbmoney'];//
            $data[1]['status'] = 1;//
            $data[1]['hbmoney'] = 0;//
            $data[2]['status'] = 1;//
            $data[2]['hbmoney'] = 0;//
            $data[3]['status'] = 0;//
            $data[3]['hbmoney'] = 0;//
        }
        if($aaa == 2 and $bbb == 2){
            $data[0]['status'] = 2;//
            $data[0]['hbmoney'] = $today2[0]['hbmoney'];//
            $data[1]['status'] = 2;//
            $data[1]['hbmoney'] = $today2[1]['hbmoney'];//
            $data[2]['status'] = 1;//
            $data[2]['hbmoney'] = 0;//
            $data[3]['status'] = 1;//
            $data[3]['hbmoney'] = 0;//
        }
        if($aaa == 3 and $bbb == 0){
            $data[0]['status'] = 1;//
            $data[0]['hbmoney'] = 0;//
            $data[1]['status'] = 1;//
            $data[1]['hbmoney'] = 0;//
            $data[2]['status'] = 1;//
            $data[2]['hbmoney'] = 0;//
            $data[3]['status'] = 0;//
            $data[3]['hbmoney'] = 0;//
        }
        if($aaa == 3 and $bbb == 1){
            $data[0]['status'] = 2;//
            $data[0]['hbmoney'] = $today2[0]['hbmoney'];//
            $data[1]['status'] = 1;//
            $data[1]['hbmoney'] = 0;//
            $data[2]['status'] = 1;//
            $data[2]['hbmoney'] = 0;//
            $data[3]['status'] = 1;//
            $data[3]['hbmoney'] = 0;//
        }
        if($aaa == 4 and $bbb == 0){
            $data[0]['status'] = 1;//
            $data[0]['hbmoney'] = 0;//
            $data[1]['status'] = 1;//
            $data[1]['hbmoney'] = 0;//
            $data[2]['status'] = 1;//
            $data[2]['hbmoney'] = 0;//
            $data[3]['status'] = 1;//
            $data[3]['hbmoney'] = 0;//
        }
        
        $this->result(0, '获取成功',array('data'=>$data,'user'=>$user));
    }
    //开启小红包
    public function doPageOpensamllhb(){
        global $_GPC, $_W;        
        $data['user_id'] = $_GPC['user_id'];
        $this->result(0, '获取成功',$data);
    }
    //红包树账户
    public function doPageHbwith(){

        global $_GPC, $_W;
        $users = pdo_get('hcstep_users', array('user_id' => $_GPC['user_id'],'uniacid'=>$_GPC['i']));
        $set = pdo_get('hcstep_message', array('uniacid'=>$_GPC['i']));   
        if(!empty($set['fourhb_sharepic'])){
            $set['fourhb_sharepic'] = $_W['attachurl'].$set['fourhb_sharepic']; 
        }else{
            $set['fourhb_sharepic'] = '';
        }   
        return $this->result(0, '提现页面',array('user'=>$users,'set'=>$set));

    }
    //收益记录
    public function doPageHblog(){
        global $_GPC, $_W;
        $log = pdo_getall('hcstep_fourhblog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']),array(),'','time desc');
        foreach ($log as $k => $v){
            if($v['type'] == 1){
               $log[$k]['title'] = '领取每日红包';   
            }else{
               $log[$k]['title'] = '邀请好友奖励';   
            }
        }
     
        return $this->result(0, '提现页面',array('log'=>$log));

    }
    //提现记录
    public function doPageTixianlog(){
        global $_GPC, $_W;
        $log = pdo_getall('hcstep_hbwith',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']),array(),'','add_time desc');
        foreach ($log as $k => $v){
            $log[$k]['add_time'] = date("Y-m-d H:i",$v['add_time']); 
            $week = date("w",$v['add_time']);
            $array = ["周日","周一","周二","周三","周四","周五","周六"];
            $log[$k]['day'] = $array[$week];
        }
     
        return $this->result(0, '提现记录',array('log'=>$log));
    }
    //红包树提现
    public function doPageTixian()
    {
        ob_end_clean();
        global $_GPC, $_W;
        $user_id=$_GPC['user_id'];
        $money=$_GPC['tmoney'];
        $user = pdo_get('hcstep_users', array('user_id' => $user_id));
        $set = pdo_get('hcstep_message', array('uniacid'=>$_GPC['i']));

        $data['uniacid']=$_GPC['i'];
        $data['user_id']=$_GPC['user_id'];
        $data['money']=$_GPC['tmoney'];
        $data['add_time']=time();
        $data['partner_trade_no']='STEP'.time();
        $data['nick_name']=$user['nick_name'];

        if($user['rmb'] < $data['money']){
            return $this->result(1, '可提现金额不足',$data);
        }
        elseif($data['money'] < $set['min_hbtxmoney']){
            return $this->result(1, '至少提现'.$set['min_hbtxmoney'].'元',$data);
        }else{
            $data['status'] = 0;
            $result = pdo_insert('hcstep_hbwith', $data);
            $nowmoney     = $user['rmb'] - $data['money'];
            $cao = pdo_update('hcstep_users',array('rmb'=>$nowmoney), array('user_id' => $user_id ));
            if($cao){
                    return $this->result(0, '申请成功',$data);
            }else{
                    return $this->result(1, '系统错误',array('n'=>$nowmoney));
            }              
        }
    }

    //步友圈话题列表
    public function doPageTopiclist()
    {
        global $_GPC, $_W;
        $list = pdo_getall('hcstep_topic',array('uniacid'=>$_GPC['i'],'status'=>1),array(),'','displayorder asc');
        foreach ($list as $k => $v) {
            $list[$k]['title'] = '#'.$v['title'].'#';
        }
        return $this->result(0, '获取成功',$list);

    }

    //步友圈上传图
    public function doPageUploadimg()
    {
        global $_GPC, $_W;
        if($_FILES){
            $file = $_FILES['file'];
            $file_name = "dt".rand(100,999).time().".png";
            $tmp_file = $file['tmp_name'];
            $error = $file['error'];
            if($error == 0){
                $file_name= mb_convert_encoding($file_name,'GBK','utf-8');
                move_uploaded_file($tmp_file, dirname(__FILE__).'/dt/'.$file_name);
            }
        }
        $url = $_W['siteroot'].'addons/hc_step/dt/'.$file_name;
        return $this->result(0, '获取成功',$url);

    }

    //步友圈上传动态
    public function doPageAdddt()
    {
        global $_GPC, $_W;
        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $data['content'] = $_GPC['description'];
        $_GPC['pics'] = explode(",",$_GPC['pics']);
        $data['content_img'] = json_encode($_GPC['pics']);
        $data['position'] = $_GPC['addressname'];
        $data['topic_id'] = $_GPC['topic_id'];
        $data['time'] = time();
        $data['status'] = 1;
        
        $data['latitude'] = $_GPC['latitude'];
        $data['longitude'] = $_GPC['longitude'];

        $res = pdo_insert('hcstep_dt',$data);
        if($res){
           return $this->result(0, '获取成功',$res);
        }else{
           return $this->result(0, '系统错误',$res); 
        }

    }

    //步友圈删除动态
    public function doPageDeletedt()
    {
        global $_GPC, $_W;
        $log = pdo_get('hcstep_dt',array('id'=>$_GPC['dt_id']));
        if($log['user_id'] == $_GPC['user_id']){
            $result = pdo_delete('hcstep_dt',array('id'=>$_GPC['dt_id']));
            return $this->result(0,'获取成功',$log);
        }else{
            return $this->result(1,'无权操作',$log); 
        }
    }

    //步友圈评论
    public function doPageAddpl()
    {
        global $_GPC, $_W;
        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $data['dt_id'] = $_GPC['dt_id'];
        $data['content'] = $_GPC['content'];   
        $data['time'] = time();
        $dt = pdo_get('hcstep_dt',array('uniacid'=>$_GPC['i'],'id'=>$_GPC['dt_id']));
        $res['uniacid'] = $_GPC['i'];
        $res['user_id'] = $dt['user_id'];
        $res['makenews_uid'] = $_GPC['user_id'];
        $res['dt_id'] = $_GPC['dt_id'];
        $res['type'] = 3;
        $res['time'] = time();
        $res['status'] = 0;
        $aaa = pdo_insert('hcstep_news',$res,$replace = true);

        $res = pdo_insert('hcstep_comment',$data);
        if($res){
           return $this->result(0, '获取成功',$res);
        }else{
           return $this->result(0, '系统错误',$res); 
        }

    }

    //步友圈评论
    public function doPageDt_news()
    {
        global $_GPC, $_W;
        $list = pdo_getall('hcstep_news',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'status'=>0),array(),'','time desc');
        if(!empty($list)){
            foreach ($list as $k => $v) {
                $user = pdo_get('hcstep_users',array('user_id'=>$v['makenews_uid']));
                $list[$k]['nick_name'] = $user['nick_name'];
                $list[$k]['head_pic'] = $user['head_pic'];
                if($v['type'] == 1){
                   $list[$k]['text'] = "赞了您的帖子"; 
                }
                if($v['type'] == 2){             
                   $list[$k]['text'] = "关注了您";           
                }
                if($v['type'] == 3){
                   $dt = pdo_get('hcstep_dt',array('id'=>$v['dt_id']));
                   $img = json_decode($dt['content_img']);
                   $url = $img['0'];
                   $list[$k]['text'] = "评论了您的帖子";
                   $list[$k]['img'] = $url;  
                }
                $list[$k]['time'] = $this->formatDate($v['time']);
            }
            return $this->result(0, '获取成功',array('status'=>1,'list'=>$list));//有新消息
        }else{
            return $this->result(0, '获取成功',array('status'=>0,'list'=>''));//无新消息
        }   

    }

    //步友圈评论
    public function doPageAllDt_news()
    {
        global $_GPC, $_W;
        if($_GPC['AllDt_newid'] == 0 or empty($_GPC['AllDt_newid'])){
            $list = pdo_getall('hcstep_news',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'type'=>1),array(),'','time desc');
        }
        if($_GPC['AllDt_newid'] == 1){
            $list = pdo_getall('hcstep_news',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'type'=>2),array(),'','time desc');
        }
         if($_GPC['AllDt_newid'] == 2){
            $list = pdo_getall('hcstep_news',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'type'=>3),array(),'','time desc');
        }
        
        if(!empty($list)){
            foreach ($list as $k => $v) {
                $user = pdo_get('hcstep_users',array('user_id'=>$v['makenews_uid']));
                $list[$k]['nick_name'] = $user['nick_name'];
                $list[$k]['head_pic'] = $user['head_pic'];
                if($v['type'] == 1){
                   $list[$k]['text'] = "赞了您的帖子"; 
                }
                if($v['type'] == 2){             
                   $list[$k]['text'] = "关注了您";           
                }
                if($v['type'] == 3){
                   $dt = pdo_get('hcstep_dt',array('id'=>$v['dt_id']));
                   $img = json_decode($dt['content_img']);
                   $url = $img['0'];
                   $list[$k]['text'] = "评论了您的帖子";
                   $list[$k]['img'] = $url;  
                }
                $list[$k]['time'] = $this->formatDate($v['time']);
            }
            return $this->result(0, '获取成功',array('status'=>1,'list'=>$list));//有新消息
        }else{
            return $this->result(0, '获取成功',array('status'=>0,'list'=>''));//无新消息
        }   

    }

    //步友圈评论
    public function doPageRead_news()
    {
        global $_GPC, $_W;
        $result = pdo_update('hcstep_news',array('status'=>1),array('user_id'=>$_GPC['user_id'],'status'=>0,'uniacid'=>$_GPC['i']));  

        return $this->result(0, '获取成功',$result);//无新消息
    }

    //步友圈动态列表
    public function doPageDtlist()
    {
        global $_GPC, $_W;
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 5;
        //$list = pdo_getslice('hcwish_with',array('status'=>1,'user_id'=>$uid),array($pageindex, $pagesize),$total,array(),'','add_time desc');
        if(!empty($_GPC['detailsid'])){
           $list = pdo_getall('hcstep_dt',array('uniacid'=>$_GPC['i'],'status'=>1,'id'=>$_GPC['detailsid'])); 
        }else{
           //$list = pdo_getall('hcstep_dt',array('uniacid'=>$_GPC['i'],'status'=>1),array(),'','time desc');
           $list = pdo_getslice('hcstep_dt',array('uniacid'=>$_GPC['i'],'status'=>1),array($pageindex, $pagesize),$total,array(),'','time desc');
        }    
        foreach ($list as $k => $v) {
            $log = pdo_get('hcstep_follow',array('user_id'=>$_GPC['user_id'],'follow_id'=>$v['user_id']));
            //评论
            $comment = pdo_getall('hcstep_comment',array('uniacid'=>$_GPC['i'],'dt_id'=>$v['id']),array(),'','time desc');
            if($comment){
                foreach ($comment as $key => $value) {
                    $u = pdo_get('hcstep_users',array('user_id'=>$value['user_id']));
                    $comment[$key]['nick_name'] = $u['nick_name']; 
                    $comment[$key]['head_pic'] = $u['head_pic']; 
                    $comment[$key]['time'] = $this->formatDate($value['time']);
                }
                $pl_num = count($comment);
            }else{
                $pl_num = 0;
            }
            if(!empty($log)){
                $list[$k]['status'] = 1;//已关注
            }else{
                $list[$k]['status'] = 0;//未关注
            }

            $log2 = pdo_get('hcstep_zan',array('user_id'=>$_GPC['user_id'],'dt_id'=>$v['id']));
            $zan = pdo_getall('hcstep_zan',array('uniacid'=>$_GPC['i'],'dt_id'=>$v['id']));
            if($zan){
                $zan_num = count($zan);
            }else{
                $zan_num = 0;
            }
            if(!empty($log2)){
                $list[$k]['is_zan'] = 1;//已赞
            }else{
                $list[$k]['is_zan'] = 0;//未赞
            }
            $user = pdo_get('hcstep_users',array('user_id'=>$v['user_id']));
            $topic = pdo_get('hcstep_topic',array('id'=>$v['topic_id']));
            $list[$k]['head_pic'] = $user['head_pic'];
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['content_img'] = json_decode($v['content_img']);
            $list[$k]['zishu'] = mb_strlen($v['content'], 'UTF-8');
            $list[$k]['time'] = $this->formatDate($v['time']);
            if($topic){
                $list[$k]['topic'] = '#'.$topic['title'].'#';
                $list[$k]['topic_id'] = $topic['id'];
            }else{
                $list[$k]['topic'] = '';
            }
            $list[$k]['comment'] = $comment;
            $list[$k]['pl_num'] = $pl_num; 
            $list[$k]['zan'] = $zan_num;           
        }
        return $this->result(0, '获取成功',$list);
    }

    //步友圈动态列表
    public function doPageGuanzhulist()
    {
        global $_GPC, $_W;
        $listlist = pdo_getall('hcstep_dt',array('uniacid'=>$_GPC['i'],'status'=>1),array(),'','time desc');
        $guanzhu = pdo_getall('hcstep_follow',array('user_id'=>$_GPC['user_id']),array(),'','time desc');
        foreach ($guanzhu as $k => $v) {
            $gz[] = $v['follow_id'];
        }
        foreach ($listlist as $key => $value) {
            if(in_array($value['user_id'],$gz)){
                $list[] = $value;
            }
        }

        foreach ($list as $k => $v){
            $log = pdo_get('hcstep_follow',array('user_id'=>$_GPC['user_id'],'follow_id'=>$v['user_id']));
            if(!empty($log)){
                $list[$k]['status'] = 1;//已关注
            }else{
                $list[$k]['status'] = 0;//未关注
            }
            $user = pdo_get('hcstep_users',array('user_id'=>$v['user_id']));
            $topic = pdo_get('hcstep_topic',array('id'=>$v['topic_id']));
            $list[$k]['head_pic'] = $user['head_pic'];
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['content_img'] = json_decode($v['content_img']);
            $list[$k]['zishu'] = mb_strlen($v['content'], 'UTF-8');
            $list[$k]['time'] = $this->formatDate($v['time']);
            if($topic){
                $list[$k]['topic'] = '#'.$topic['title'].'#';
                $list[$k]['topic_id'] = $topic['id'];
            }else{
                $list[$k]['topic'] = '';
            }
            if(empty($v['zan'])){
                $list[$k]['zan'] = 0;
            } 
            $comment = pdo_getall('hcstep_comment',array('uniacid'=>$_GPC['i'],'dt_id'=>$v['id']),array(),'','time desc');
            if($comment){
                $pl_num = count($comment);
            }else{
                $pl_num = 0;
            } 

            $log2 = pdo_get('hcstep_zan',array('user_id'=>$_GPC['user_id'],'dt_id'=>$v['id']));
            $zan = pdo_getall('hcstep_zan',array('uniacid'=>$_GPC['i'],'dt_id'=>$v['id']));
            if($zan){
                $zan_num = count($zan);
            }else{
                $zan_num = 0;
            }
            if(!empty($log2)){
                $list[$k]['is_zan'] = 1;//已赞
            }else{
                $list[$k]['is_zan'] = 0;//未赞
            }
            $list[$k]['zan'] = $zan_num;
            $list[$k]['pl_num'] = $pl_num;          
        }
        if(empty($list)){
            $list = "";
        }
        return $this->result(0, '获取成功',$list);
    }

    //步友圈话题动态列表
    public function doPageTopicdtlist()
    {
        global $_GPC, $_W;
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 5;
        $list = pdo_getslice('hcstep_dt',array('uniacid'=>$_GPC['i'],'status'=>1,'topic_id'=>$_GPC['topic_id']),array($pageindex, $pagesize),$total,array(),'','time desc');
        //$list = pdo_getall('hcstep_dt',array('uniacid'=>$_GPC['i'],'status'=>1,'topic_id'=>$_GPC['topic_id']),array(),'','time desc');
        $topic1 = pdo_get('hcstep_topic',array('id'=>$_GPC['topic_id']));
        $img = tomedia($topic1['toppic']);

        foreach ($list as $k => $v){
            $log = pdo_get('hcstep_follow',array('user_id'=>$_GPC['user_id'],'follow_id'=>$v['user_id']));
            if(!empty($log)){
                $list[$k]['status'] = 1;//已关注
            }else{
                $list[$k]['status'] = 0;//未关注
            }
            $user = pdo_get('hcstep_users',array('user_id'=>$v['user_id']));
            $topic = pdo_get('hcstep_topic',array('id'=>$v['topic_id']));
            $list[$k]['head_pic'] = $user['head_pic'];
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['content_img'] = json_decode($v['content_img']);
            $list[$k]['zishu'] = mb_strlen($v['content'], 'UTF-8');
            $list[$k]['time'] = $this->formatDate($v['time']);
            if($topic){
                $list[$k]['topic'] = '#'.$topic['title'].'#';
                $list[$k]['topic_id'] = $topic['id'];
            }else{
                $list[$k]['topic'] = '';
            }
            if(empty($v['zan'])){
                $list[$k]['zan'] = 0;
            } 
            $comment = pdo_getall('hcstep_comment',array('uniacid'=>$_GPC['i'],'dt_id'=>$v['id']),array(),'','time desc');
            if($comment){
                $pl_num = count($comment);
            }else{
                $pl_num = 0;
            } 

            $log2 = pdo_get('hcstep_zan',array('user_id'=>$_GPC['user_id'],'dt_id'=>$v['id']));
            $zan = pdo_getall('hcstep_zan',array('uniacid'=>$_GPC['i'],'dt_id'=>$v['id']));
            if($zan){
                $zan_num = count($zan);
            }else{
                $zan_num = 0;
            }
            if(!empty($log2)){
                $list[$k]['is_zan'] = 1;//已赞
            }else{
                $list[$k]['is_zan'] = 0;//未赞
            }
            $list[$k]['zan'] = $zan_num;
            $list[$k]['pl_num'] = $pl_num;         
        }
        return $this->result(0, '获取成功',array('list'=>$list,'img'=>$img));
    }

    //步友圈动态列表
    public function doPageMyhome()
    {
        global $_GPC, $_W;
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 5;
        $list = pdo_getslice('hcstep_dt',array('uniacid'=>$_GPC['i'],'status'=>1,'user_id'=>$_GPC['target_id']),array($pageindex, $pagesize),$total,array(),'','time desc');
        //$list = pdo_getall('hcstep_dt',array('uniacid'=>$_GPC['i'],'status'=>1,'user_id'=>$_GPC['user_id']),array(),'','time desc');
        $user = pdo_get('hcstep_users',array('user_id'=>$_GPC['target_id']));
        
        $info['id'] = 100000 + $user['user_id'];
        $info['head_pic'] = $user['head_pic'];
        $info['nick_name'] = $user['nick_name'];
        $guanzhu = pdo_getall('hcstep_follow',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['target_id']));
        if(!empty($guanzhu)){
            $info['guanzhu'] = count($guanzhu);
        }else{
            $info['guanzhu'] = 0;
        }
        $fans = pdo_getall('hcstep_follow',array('uniacid'=>$_GPC['i'],'follow_id'=>$_GPC['target_id']));
        if(!empty($fans)){
            $info['fans'] = count($fans);
        }else{
            $info['fans'] = 0;
        }
        $tie = pdo_getall('hcstep_dt',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['target_id']));
        if(!empty($tie)){
            $info['tie'] = count($tie);
        }else{
            $info['tie'] = 0;
        }
        $zan = pdo_getall('hcstep_zan',array('uniacid'=>$_GPC['i'],'target_id'=>$_GPC['target_id']));
        if(!empty($zan)){
            $info['zan'] = count($zan);
        }else{
            $info['zan'] = 0;
        }

        $log = pdo_get('hcstep_follow',array('user_id'=>$_GPC['my_id'],'follow_id'=>$_GPC['target_id']));
            if(!empty($log)){
                $info['status'] = 1;//已关注
            }else{
                $info['status'] = 0;//未关注
            }

        foreach ($list as $k => $v){        
            $topic = pdo_get('hcstep_topic',array('id'=>$v['topic_id']));
            $list[$k]['head_pic'] = $user['head_pic'];
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['content_img'] = json_decode($v['content_img']);
            $list[$k]['zishu'] = mb_strlen($v['content'], 'UTF-8');
            $list[$k]['time'] = $this->formatDate($v['time']);
            if($topic){
                $list[$k]['topic'] = '#'.$topic['title'].'#';
                $list[$k]['topic_id'] = $topic['id'];
            }else{
                $list[$k]['topic'] = '';
            }
            if(empty($v['zan'])){
                $list[$k]['zan'] = 0;
            }
            $comment = pdo_getall('hcstep_comment',array('uniacid'=>$_GPC['i'],'dt_id'=>$v['id']),array(),'','time desc');
            if($comment){
                $pl_num = count($comment);
            }else{
                $pl_num = 0;
            } 

            $log2 = pdo_get('hcstep_zan',array('user_id'=>$_GPC['my_id'],'dt_id'=>$v['id']));
            $zan = pdo_getall('hcstep_zan',array('uniacid'=>$_GPC['i'],'dt_id'=>$v['id']));
            if($zan){
                $zan_num = count($zan);
            }else{
                $zan_num = 0;
            }
            if(!empty($log2)){
                $list[$k]['is_zan'] = 1;//已赞
            }else{
                $list[$k]['is_zan'] = 0;//未赞
            }
            $list[$k]['zan'] = $zan_num;
            $list[$k]['status'] = $info['status'];
            $list[$k]['pl_num'] = $pl_num;          
        }
        return $this->result(0, '获取成功',array('list'=>$list,'info'=>$info));
    }

    //步友圈关注
    public function doPageGuanzhu()
    {
        global $_GPC, $_W;
        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $data['follow_id'] = $_GPC['follow_id'];
        $data['time'] = time();

        $log = pdo_get('hcstep_follow',array('user_id'=>$_GPC['user_id'],'follow_id'=>$_GPC['follow_id']));
        if(empty($log)){
            $result = pdo_insert('hcstep_follow',$data,$replace = true);
            $res['uniacid'] = $_GPC['i'];
            $res['user_id'] = $_GPC['follow_id'];
            $res['makenews_uid'] = $_GPC['user_id'];
            $res['type'] = 2;
            $res['time'] = time();
            $res['status'] = 0;
            $aaa = pdo_insert('hcstep_news',$res,$replace = true);
        }else{
            $result = pdo_delete('hcstep_follow',array('user_id'=>$_GPC['user_id'],'follow_id'=>$_GPC['follow_id']));
            $aaa = pdo_delete('hcstep_news',array('makenews_uid'=>$_GPC['user_id'],'user_id'=>$_GPC['follow_id'],'type'=>2));
        }
        return $this->result(0, '获取成功',$result);
    }

    //步友圈分享
    public function doPagebyqshare()
    {
        global $_GPC, $_W;
        $data['share_title1'] = "我发现了一个有趣的社区";
        $data['share_title2'] = "我发现了一个有趣的人";
        $data['share_title3'] = "我发现了一条有趣的动态";
        $data['share_title4'] = "我发现了一个有趣的话题";
        return $this->result(0, '获取成功',$data);
    }

    //步友圈话题列表
    public function doPageMissionlist()
    {
        global $_GPC, $_W;
        $list = pdo_getall('hcstep_mission',array('uniacid'=>$_GPC['i']),array(),'','displayorder asc');
        foreach ($list as $k => $v) {
            $list[$k]['mission_icon'] = tomedia($v['mission_icon']);
            if($v['mission_type'] == 4){
                $data['daytime'] = date('Y-m-d',time());
                $log = pdo_get('hcstep_missionlog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'mission_id'=>$v['id'],'daytime'=>$data['daytime']));
            }else{
                $log = pdo_get('hcstep_missionlog',array('user_id'=>$_GPC['user_id'],'mission_id'=>$v['id']));
            }
            
            if(empty($log)){
                $list[$k]['flag'] = 0;//0任务未开始1任务已完成可领取步数2任务已完成已领取步数
            }else{
                if($log['status'] == 0){
                    $list[$k]['flag'] = 1;//0任务未开始1任务已完成可领取步数2任务已完成已领取步数
                }else{
                    $list[$k]['flag'] = 2;//0任务未开始1任务已完成可领取步数2任务已完成已领取步数
                }
            }  
                   
        }
        $img = $_W['siteroot'].'addons/hc_step/template/img/mission_top.png';
        return $this->result(0, '获取成功',array('list'=>$list,'img'=>$img));
    }
    //
    public function doPageMissiondo()
    {
        global $_GPC, $_W;
        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $data['mission_id'] = $_GPC['mission_id'];
        $mission = pdo_get('hcstep_mission',array('id'=>$_GPC['mission_id'],'uniacid'=>$_GPC['i']));
        $data['step'] = $mission['step'];
        $data['time'] = time();
        $data['daytime'] = date('Y-m-d',time());
        $data['status'] = 0;

        $log = pdo_get('hcstep_missionlog',array('user_id'=>$_GPC['user_id'],'mission_id'=>$_GPC['mission_id']));
        if(empty($log)){
            if($mission['mission_type'] == 1){
                $result = pdo_insert('hcstep_missionlog',$data,$replace = true);
            }
            if($mission['mission_type'] == 2){
                $dt = pdo_get('hcstep_dt',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']));
                if(!empty($dt)){
                    $result = pdo_insert('hcstep_missionlog',$data,$replace = true);
                }            
            }
            if($mission['mission_type'] == 3){
                $phone = $this->Usermobile($_GPC['user_id'],$_GPC['encryptedData'],$_GPC['iv'],$_GPC['session_key']);
                if($phone['errCode'] == 0){
                    pdo_update('hcstep_users',array('mobile'=>$phone['phone']),array('user_id'=>$_GPC['user_id']));
                    $result = pdo_insert('hcstep_missionlog',$data,$replace = true);
                }
            }    
        }
        if($mission['mission_type'] == 4){
                $ad = pdo_get('hcstep_missionlog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'mission_id'=>$_GPC['mission_id'],'daytime'=>$data['daytime']));
                if(empty($ad)){
                    $result = pdo_insert('hcstep_missionlog',$data,$replace = true);
                }            
        } 
        return $this->result(0, '获取成功',$data);
    }

    public function doPageHuodongshipin()
    {
        global $_GPC, $_W;
        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $huodong = pdo_get('hcstep_huodong',array('id'=>$_GPC['huodong_id'],'uniacid'=>$_GPC['i']));
        $data['step'] = $huodong['step'];
        $data['time'] = time();
        $data['daytime'] = date('Y-m-d',time());
        $data['status'] = 0;

        $log = pdo_get('hcstep_shipinlog',array('user_id'=>$_GPC['user_id'],'daytime'=>$data['daytime'],'uniacid'=>$_GPC['i']));
        if(empty($log)){
           $result = pdo_insert('hcstep_shipinlog',$data,$replace = true);      
        }
        return $this->result(0, '获取成功',$data);
    }

    public function Usermobile($uid,$encryptedData,$iv,$sessionKey){
        global $_GPC, $_W;
        /*$uid           = $_GPC['user_id'];
        $encryptedData = $_GPC['encryptedData'];
        $iv            = $_GPC['iv'];
        $sessionKey    = $_GPC['session_key'];*/
        $appid = $_W['account']['key'];
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if($errCode == 0){
            $phone = json_decode($data,'true');
            //pdo_update('hcstep_users',array('mobile'=>$phone['phoneNumber']),array('user_id'=>$uid));
            $res['errCode'] = $errCode;
            $res['phone'] = $phone['phoneNumber'];
            return $res;
        }else{
            $res['errCode'] = $errCode;
            return $res;
        }

    }

    //获取用户手机号
    public function doPageGetsessionkey(){
        global $_GPC, $_W;
        $code = $_GPC['code'];
        $account = pdo_get('account_wxapp', array('uniacid' => $_GPC['i']));
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $account['key'] . '&secret=' . $account['secret'] . '&js_code=' . $code . '&grant_type=authorization_code';
        $result = $this->get_url_content($url);
        $result = json_decode($result, true);
        
        return $this->result(0, '操作成功',$result);

    }

    //步友圈关注
    public function doPageZan()
    {
        global $_GPC, $_W;
        $data['uniacid'] = $_GPC['i'];
        $data['dt_id'] = $_GPC['dt_id'];
        $data['user_id'] = $_GPC['user_id'];

        $log = pdo_get('hcstep_zan',array('user_id'=>$_GPC['user_id'],'dt_id'=>$_GPC['dt_id']));
        $dt = pdo_get('hcstep_dt',array('uniacid'=>$_GPC['i'],'id'=>$_GPC['dt_id']));
        $data['target_id'] = $dt['user_id'];
        if(empty($log)){
            $result = pdo_insert('hcstep_zan',$data,$replace = true);
            $res['uniacid'] = $_GPC['i'];
            $res['user_id'] = $dt['user_id'];
            $res['makenews_uid'] = $_GPC['user_id'];
            $res['dt_id'] = $_GPC['dt_id'];
            $res['type'] = 1;
            $res['time'] = time();
            $res['status'] = 0;
            $aaa = pdo_insert('hcstep_news',$res,$replace = true);

        }else{
            $result = pdo_delete('hcstep_zan',array('user_id'=>$_GPC['user_id'],'dt_id'=>$_GPC['dt_id']));
            $aaa = pdo_delete('hcstep_news',array('makenews_uid'=>$_GPC['user_id'],'dt_id'=>$_GPC['dt_id'],'type'=>1));
        }

        return $this->result(0, '获取成功',$result);
    }

    function formatDate($sTime){
         //sTime=源时间，cTime=当前时间，dTime=时间差
         $cTime  = time();
         $dTime  = $cTime - $sTime;
         $dDay  = intval(date("Ymd",$cTime)) - intval(date("Ymd",$sTime));
         $dYear  = intval(date("Y",$cTime)) - intval(date("Y",$sTime));
         if( $dTime < 60 ){
          $dTime =  $dTime."秒前";
         }elseif( $dTime < 3600 ){
          $dTime =  intval($dTime/60)."分钟前";
         }elseif( $dTime >= 3600 && $dDay == 0  ){
          $dTime =  "今天".date("H:i",$sTime);
         }elseif($dYear==0){
          $dTime =  date("m-d H:i",$sTime);
         }else{
          $dTime =  date("Y-m-d H:i",$sTime);
         }
         return $dTime;
    }

    //口红奖品展示
    public function doPageKouhonglist(){

        global $_GPC, $_W;
        $set = pdo_get('hcstep_set',array('uniacid'=>$_GPC['i']));    
        $info = pdo_get('hcstep_message',array('uniacid'=>$_GPC['i']));
        $info['kouhong_sharepic'] = tomedia($info['kouhong_sharepic']);  
        $kouhong = $info['kouhong_ids'];
        if(!empty($kouhong)){
            $kouhong = explode(",",$kouhong);
            foreach ($kouhong as $k => $v){
                $list[$k] = pdo_get('hcstep_goods',array('id'=>$v)); 
                $list[$k]['main_img'] = tomedia($list[$k]['main_img']);
            }
        }

        $time=date('Y-m-d',time()); 
        $log = pdo_get('hcstep_kouhonglog', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'createday'=>$time));
        $invite = $log['invite_id'];
        $res = explode(",",$invite);

        if(!empty($res[0])){
            $peoplelist[0] = pdo_getcolumn('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$res[0]),array('head_pic'));
        }else{
            $peoplelist[0] = "";
        }
        if(!empty($res[1])){
            $peoplelist[1] = pdo_getcolumn('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$res[1]),array('head_pic'));
        }else{
            $peoplelist[1] = "";
        }
        if(!empty($res[2])){
            $peoplelist[2] = pdo_getcolumn('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$res[2]),array('head_pic'));
        }else{
            $peoplelist[2] = "";
        }
        if(!empty($res[3])){
            $peoplelist[3] = pdo_getcolumn('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$res[3]),array('head_pic'));
        }else{
            $peoplelist[3] = "";
        }
        if(!empty($res[4])){
            $peoplelist[4] = pdo_getcolumn('hcstep_users',array('uniacid'=>$_GPC['i'],'user_id'=>$res[4]),array('head_pic'));
        }else{
            $peoplelist[4] = "";
        }    
        
        $this->result(0, '首页变色盒',array('list'=>$list,'peoplelist'=>$peoplelist,'title'=>$info['kouhong_sharetitle'],'pic'=>$info['kouhong_sharepic']));         
    }

    //口红挑战开始
    public function doPageGame(){

        global $_GPC, $_W;    
        $time=date('Y-m-d',time()); 
        $log = pdo_get('hcstep_kouhonglog', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'createday'=>$time));
        $url = $_W['siteroot'].'addons/hc_step/kouhong/play.html?id='.$_GPC['id'].'&index=1&selltype='.$_GPC['selltype'].'&paytype='.$_GPC['paytype'];
        if(empty($log)){
            $data['uniacid'] = $_GPC['i'];
            $data['user_id'] = $_GPC['user_id'];
            $data['createtime'] = time();
            $data['createday'] = $time;
            $data['goods_id'] = $_GPC['id'];
            $data['status'] = 0;
            $data['cishu'] = 1;
            $result = pdo_insert('hcstep_kouhonglog', $data);
            $this->result(0, '获取成功',$url);      
        }elseif($log['cishu'] == 0){
            pdo_update('hcstep_kouhonglog',array('cishu'=>1,'goods_id'=>$_GPC['id']),array('id'=>$log['id']));
            $this->result(0, '获取成功',$url);   
        }elseif($log['cishu'] == 1){
            $invite = $log['invite_id'];
            $kouhong = explode(",",$invite);
            $count = count($kouhong);
            if($count < 5){
               $this->result(0, '邀请好友获得一次游戏机会',array('status'=>1));
            }else{
               pdo_update('hcstep_kouhonglog',array('cishu'=>2,'goods_id'=>$_GPC['id']),array('id'=>$log['id']));
               $this->result(0, '获取成功',$url);
            }
        }elseif($log['cishu'] == 2){
            $this->result(0, '今日挑战次数已达上限，请明天再来吧',array('status'=>2));
        }     
        $this->result(0, '获取成功',$url);           
    }

    //口红邀请好友
    public function doPageGamezhuce()
    {
        ob_end_clean();
        global $_GPC, $_W;
        $data['uniacid'] = $_GPC['i'];
        $data['city'] = $_POST['city'];
        $data['country'] = $_POST['country'];
        $data['gender'] = $_POST['gender'];
        $data['open_id'] = $_POST['openid'];
        $data['nick_name'] = $_POST['nickname'];
        $data['head_pic'] = $_POST['avatar'];
        $data['province'] = $_POST['province'];
        $stact = 1;
        if(empty($data['open_id'])){
            $this->result(0, $stact, '');
        }
        $uid = pdo_getcolumn('hcstep_users', array('open_id' => $data['open_id'], 'uniacid' => $data['uniacid']), 'user_id', 1);
        if (empty($uid)){
            $stact = 0;
            $result = pdo_insert('hcstep_users', $data, $replace = true);
            $uid = pdo_insertid();
            if(!empty($_GPC['invite_id'])){
                $time = date('Y-m-d',time()); 
                $log = pdo_get('hcstep_kouhonglog', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['invite_id'],'createday'=>$time));
                if(empty($log)){
                    $res['uniacid'] = $_GPC['i'];
                    $res['user_id'] = $_GPC['invite_id'];
                    $res['createtime'] = time();
                    $res['createday'] = $time;
                    $res['invite_id'] = $uid;
                    $res['status'] = 0;
                    $res['cishu'] = 0;
                    $result = pdo_insert('hcstep_kouhonglog', $res);  
                }else{
                    if(empty($log['invite_id'])){
                       pdo_update('hcstep_kouhonglog',array('invite_id'=>$uid),array('id'=>$log['id'])); 
                    }else{
                       $invite_id = $log['invite_id'].','.$uid;
                       pdo_update('hcstep_kouhonglog',array('invite_id'=>$invite_id),array('id'=>$log['id'])); 
                    }
                }
            }
            $this->result(0, $stact,$uid);
        }else{
            $this->result(0, $stact,$uid);
        }        
    }

    //口红邀请好友
    public function doPageTuijianlist()
    {
        global $_GPC, $_W;
        //$list = pdo_getall('hcstep_goods', array('indexsort_id'=>$v['id'],'uniacid'=>$_GPC['i'],'status'=>1), array(),'','displayorder asc');
        $list = pdo_getslice('hcstep_goods',array('uniacid'=>$_GPC['i'],'status'=>1),array(1,20),$total,array(),'','displayorder asc');
        foreach ($list as $k => $v) {
            $list[$k]['main_img'] = tomedia($v['main_img']);
        }

        return $this->result(0, '获取成功',$list);
    }

    public function doPagePay(){
        global $_GPC, $_W;
        $uid = $_GPC['user_id'];
        $goods_id = $_GPC['goods_id'];
        $paytype = $_GPC['state'];
        $weid= $_GPC['i'];
        $goods = pdo_get('hcstep_goods',array('id'=>$goods_id));
        $user = pdo_get('hcstep_users',array('user_id'=>$uid));
        if($paytype == 0){
            $fee = $goods['maxrmb'];
            $orders = pdo_getall('hcstep_orders', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id'],'status !='=>2));
            $ordersum = count($orders);
            if($goods['inventory'] <=0 ){
            return $this->result(1, '库存不足',$goods);
            }
            if($goods['maxbuy'] > 0){
                if($ordersum >= $goods['maxbuy']){
                    return $this->result(1, '已达兑换上限',$user);
                }
            }
        }
        if($paytype == 1){
            $fee = $goods['rmb'];
            if($user['money'] < $goods['price2']){
                return $this->result(1, '金币不足');
            }
            if($goods['inventory'] <=0 ){
            return $this->result(1, '库存不足',$goods);
            }
            $orders = pdo_getall('hcstep_orders', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id'],'status !='=>2));
            $ordersum = count($orders);
            if($goods['maxbuy'] > 0){
                if($ordersum >= $goods['maxbuy']){
                    return $this->result(1, '已达兑换上限',$user);
                }
            }
        }
        //使用红包
        if($paytype == 2){
            //红包
            if($goods['maxhongbao'] >= $user['rmb']){
               $user['keyong'] = $user['rmb']; 
            }else{
               $user['keyong'] = $goods['maxhongbao']; 
            }
            $fee = $goods['maxrmb'] - $user['keyong'];
            /*if($user['money'] < $goods['price2']){
                return $this->result(1, '金币不足');
            }*/
            if($goods['inventory'] <=0 ){
            return $this->result(1, '库存不足',$goods);
            }
            $orders = pdo_getall('hcstep_orders', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id'],'status !='=>2));
            $ordersum = count($orders);
            if($goods['maxbuy'] > 0){
                if($ordersum >= $goods['maxbuy']){
                    return $this->result(1, '已达兑换上限',$user);
                }
            }
        }         
        $data = array(
            'weid' => $weid,
            'uid'  => $uid,
            'goods_id'  => $goods_id,
            'paytype'  => $paytype,
            'fee'      => $fee,
            'ordersn'  => date('YmdHis').time().rand(100000,999999),
            'status'   => 1,
            'createtime'=>time()
        );
        /*if($goods['selltype'] == 1){
            if($goods['validity'] > 0){
                $data['endtime'] = time()+24*3600*$goods['validity'];
            }else{
                $data['endtime'] = 0;
            }
        }*/
        $res = pdo_insert('hcstep_payment',$data);
        if(!empty($res)){
            $oid = pdo_insertid();
            //写入订单表
            if($paytype == 0){

                if($goods['selltype'] == 1){
                    $order['uniacid'] = $_GPC['i'];
                    $order['user_id'] = $_GPC['user_id'];
                    $order['goods_id'] = $_GPC['goods_id'];
                    $order['type'] = 10;
                    $order['status'] = 2;
                    $order['oid'] = $oid;
                    $order['time'] = time();
                    if($goods['validity'] > 0){
                        $order['endtime'] = time()+24*3600*$goods['validity'];
                    }else{
                        $order['endtime'] = 0;
                    }
                              
                    $aaa = pdo_insert('hcstep_orders', $order, $replace = true);
                }else{
                    $order['uniacid'] = $_GPC['i'];
                    $order['userName'] = $_GPC['userName'];
                    $order['postalCode'] = $_GPC['postalCode'];
                    $order['provinceName'] = $_GPC['provinceName'];
                    $order['cityName'] = $_GPC['cityName'];
                    $order['countyName'] = $_GPC['countyName'];
                    $order['detailInfo'] = $_GPC['detailInfo'];
                    $order['nationalCode'] = $_GPC['nationalCode'];
                    $order['telNumber'] = $_GPC['telNumber'];
                    $order['user_id'] = $_GPC['user_id'];
                    $order['goods_id'] = $_GPC['goods_id'];
                    $order['type'] = 0;
                    $order['status'] = 2;
                    $order['oid'] = $oid;
                    $order['time'] = time();
                              
                    $aaa = pdo_insert('hcstep_orders', $order, $replace = true);
                }             
            }
            if($paytype == 1){

                if($goods['selltype'] == 1){
                    $order['uniacid'] = $_GPC['i'];
                    $order['user_id'] = $_GPC['user_id'];
                    $order['goods_id'] = $_GPC['goods_id'];
                    $order['type'] = 11;
                    $order['status'] = 2;
                    $order['oid'] = $oid;
                    $order['time'] = time();
                    if($goods['validity'] > 0){
                        $order['endtime'] = time()+24*3600*$goods['validity'];
                    }else{
                        $order['endtime'] = 0;
                    }
                              
                    $aaa = pdo_insert('hcstep_orders', $order, $replace = true);
                }else{
                    $order['uniacid'] = $_GPC['i'];
                    $order['userName'] = $_GPC['userName'];
                    $order['postalCode'] = $_GPC['postalCode'];
                    $order['provinceName'] = $_GPC['provinceName'];
                    $order['cityName'] = $_GPC['cityName'];
                    $order['countyName'] = $_GPC['countyName'];
                    $order['detailInfo'] = $_GPC['detailInfo'];
                    $order['nationalCode'] = $_GPC['nationalCode'];
                    $order['telNumber'] = $_GPC['telNumber'];
                    $order['user_id'] = $_GPC['user_id'];
                    $order['goods_id'] = $_GPC['goods_id'];
                    $order['type'] = 1;
                    $order['status'] = 2;
                    $order['oid'] = $oid;
                    $order['time'] = time();
                              
                    $aaa = pdo_insert('hcstep_orders', $order, $replace = true);
                }              
            }
            if($paytype == 2){

                if($goods['selltype'] == 1){
                    $order['uniacid'] = $_GPC['i'];
                    $order['user_id'] = $_GPC['user_id'];
                    $order['goods_id'] = $_GPC['goods_id'];
                    $order['type'] = 17;//核销原价用红包
                    $order['status'] = 2;
                    $order['oid'] = $oid;
                    $order['time'] = time();
                    if($goods['validity'] > 0){
                        $order['endtime'] = time()+24*3600*$goods['validity'];
                    }else{
                        $order['endtime'] = 0;
                    }
                              
                    $aaa = pdo_insert('hcstep_orders', $order, $replace = true);
                }else{
                    $order['uniacid'] = $_GPC['i'];
                    $order['userName'] = $_GPC['userName'];
                    $order['postalCode'] = $_GPC['postalCode'];
                    $order['provinceName'] = $_GPC['provinceName'];
                    $order['cityName'] = $_GPC['cityName'];
                    $order['countyName'] = $_GPC['countyName'];
                    $order['detailInfo'] = $_GPC['detailInfo'];
                    $order['nationalCode'] = $_GPC['nationalCode'];
                    $order['telNumber'] = $_GPC['telNumber'];
                    $order['user_id'] = $_GPC['user_id'];
                    $order['goods_id'] = $_GPC['goods_id'];
                    $order['type'] = 7;//原价用红包
                    $order['status'] = 2;
                    $order['oid'] = $oid;
                    $order['time'] = time();
                              
                    $aaa = pdo_insert('hcstep_orders', $order, $replace = true);
                }              
            }
            $pay_params = $this->payment($oid);
            if (is_error($pay_params)){
                return $this->result(1, '支付失败，请重试');
            }
            return $this->result(0, '',$pay_params);
        }else{
            return $this->result(1, '操作失败');
        }
    }
    /**
     * 调起微信支付
     */
    public function payment($order_id){
        global $_GPC, $_W;
        $weid = $_GPC['i'];
        load()->model('payment');
        load()->model('account');
        $setting = uni_setting($weid, array('payment'));
        $wechat_payment = array(
            'appid'   => $_W['account']['key'],
            'signkey' => $setting['payment']['wechat']['signkey'],
            'mchid'   => $setting['payment']['wechat']['mchid'],
        );
        //echo "<pre>";print_r($wechat_payment);die;
        $order  = pdo_get('hcstep_payment',array('id'=>$order_id,'weid'=>$weid));

        $openid = pdo_getcolumn('hcstep_users',array('user_id'=>$order['uid']),array('open_id'));
        //返回小程序参数
        $notify_url = $_W['siteroot'].'addons/hc_step/wxpay.php';
        $res = $this->getPrePayOrder($wechat_payment,$notify_url,$order,$openid);
        //echo "<pre>";print_r($notify_url);die;
        if($res['return_code']=='FAIL'){
            return $this->result(1, '操作失败',$res['return_msg']);
        }
        if($res['result_code']=='FAIL'){
            return $this->result(1, '操作失败',$res['err_code'].$res['err_code_des']);
        }

        if($res['return_code']=='SUCCESS'){
            $wxdata = $this->getOrder($res['prepay_id'],$wechat_payment,$order_id);
            //echo "<pre>";print_r($wxdata);
            pdo_update('hcstep_payment', array('package'=>$res['prepay_id']), array('id'=>$order_id));

            return $this->result(0, '操作成功',$wxdata);
        }else{
            return $this->result(1, '操作失败');
        }
    }
    //微信统一支付
    public function getPrePayOrder($wechat_payment,$notify_url,$order,$openid){
        $model = new HcfkModel();
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        
        $data["appid"] = $wechat_payment['appid'];
        $data["body"] = $order['ordersn'];
        $data["mch_id"] = $wechat_payment['mchid'];
        $data["nonce_str"] = $model->getRandChar(32);
        $data["notify_url"] = $notify_url;
        $data["out_trade_no"] = $order['ordersn'];
        $data["spbill_create_ip"] = $model->get_client_ip();
        $data["total_fee"] = $order['fee']*100;
        $data["trade_type"] = "JSAPI";
        $data["openid"] = $openid;
        $data["sign"] = $model->getSign($data,$wechat_payment['signkey']);
    
        $xml = $model->arrayToXml($data);
        $response = $model->postXmlCurl($xml, $url);
        return $model->xmlstr_to_array($response);
    }
    
    // 执行第二次签名，才能返回给客户端使用
    public function getOrder($prepayId,$wechat_payment,$order_id){
        global $_GPC, $_W;
        $model = new HcfkModel();
        $data["appId"] = $wechat_payment['appid'];
        $data["nonceStr"] = $model->getRandChar(32);
        $data["package"] = "prepay_id=".$prepayId;
        $data['signType'] = "MD5";
        $data["timeStamp"] = time();
        $order = pdo_get('hcstep_orders',array('oid'=>$order_id));
        $data['order_id'] = $order['id'];
        $data["sign"] = $model->getSign1($data,$wechat_payment['signkey']);
        return $data;
    }

    function wx_get_token() {
        global $_GPC, $_W;
        /*$appid=$_W['account']['key'];
        $AppSecret=$_W['account']['secret'];
        $res = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$AppSecret);
        $res = json_decode($res, true);
        $token = $res['access_token'];*/
        $account_api = WeAccount::create();
        $token = $account_api->getAccessToken();
        return $token;
    }

    public function download_remote_pic($url){
        global $_W,$_GPC;
        $header = [     
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0',
            'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding: gzip, deflate'
        ];  
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');  
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);curl_close($curl);  
        if($code == 200){//把URL格式的图片转成base64_encode格式的！      
           $imgBase64Code = "data:image/png;base64," . base64_encode($data);  
        }  
        $img_content=$imgBase64Code;//图片内容  
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result)){   
        $type = $result[2];//得到图片类型png?jpg?gif?   
        $new_file = time().rand(1,10000).".{$type}";
        if (file_put_contents(dirname(__FILE__)."/headpic/".$new_file, base64_decode(str_replace($result[1], ‘‘, $img_content)))) {  
                return $_W['siteroot']."addons/hc_step/headpic/".$new_file; 
            } 
        } 
    }

    /*public function request_post($url, $data){
            $ch = curl_init();
            $header = "Accept-Charset: utf-8";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $tmpInfo = curl_exec($ch);
            if (curl_errno($ch)) {
                return false;
            }else{
                return $tmpInfo;
            }
        }*/

    public function request_post($url, $data){
            $ch = curl_init();
            $header[] = "Content-type: text/xml"; // 改为数组解决
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            //curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $tmpInfo = curl_exec($ch);
            if (curl_errno($ch)) {
                return false;
            }else{
                return $tmpInfo;
            }
        }

}