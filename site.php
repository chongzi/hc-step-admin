<?php

defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT."/addons/hc_step/inc/model.class.php"; 
class Hc_stepModuleSite extends WeModuleSite {

        

    public function doWebTest(){

        global $_GPC,$_W;
        $url = $_W['siteroot'].'addons/hc_step/kouhong/play.html';

        echo $url;
    }


    public function get_rand($proArr){
       $result = array();
        foreach ($proArr as $key => $val) {
            $arr[$key] = $val['v'];
        }
        // 概率数组的总概率
        $proSum = 100;//array_sum($arr);
        asort($arr);
        // 概率数组循环
        foreach($arr as $k => $v){
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

    public function download_remote_pic($url){
        global $_W,$_GPC;
        $header = [
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0',      
            'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',      
            'Accept-Encoding: gzip, deflate',
        ];      
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_ENCODING,'');  
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);  
        if ($code == 200) {//把URL格式的图片转成base64_encode格式的！      
           $imgBase64Code = "data:image/jpeg;base64," . base64_encode($data);  
        }  
        $img_content=$imgBase64Code;//图片内容  
        //echo $img_content;exit;  
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result)) {   
            $type = $result[2];//得到图片类型png?jpg?gif?   
            $new_file = time().rand(1,10000).".{$type}";   
            if (file_put_contents(dirname(__FILE__)."/headpic/".$new_file, base64_decode(str_replace($result[1], ‘‘, $img_content)))) {  
                return $_W['siteroot']."addons/hc_step/headpic/".$new_file; 
            }
        } 
    }
	

    /*public function doWebShenhe()
    {
        //这个操作被定义用来呈现 管理中心导航菜单
        global $_W, $_GPC;
        $pindex = max(intval($_GPC['page']), 1);
        $psize = 20;
        $shenhe = pdo_getslice('hczhongzhuan_shenhe', array('uniacid' => $_W['uniacid']), array($pindex, $psize), $total, array(), '', array('sort desc'));
        for ($i = 0; $i < count($shenhe); $i++) {
            if ($shenhe[$i]['stact'] == 0) {
                $shenhe[$i]['stact'] = '不显示';
            } else {
                $shenhe[$i]['stact'] = '显示';
            }
        }
        $pager = pagination($total, $pindex, $psize);
        include $this->template("shenhe");
    }*/


    public function doWebAddshenhe()
    {
        global $_W, $_GPC;
        //这个操作被定义用来呈现 管理中心导航菜单
        if (!empty($_GPC['name'])) {
            $data['name'] = $_GPC['name'];
            $data['uniacid'] = $_W['uniacid'];
            $data['sort'] = $_GPC['sort'];
            $data['stact'] = $_GPC['stact'];
            $data['img'] = $_GPC['img'];
            $data['content'] = $_GPC['content'];
            $data['time'] = date("Y-m-d");
            pdo_insert('hczhongzhuan_shenhe', $data);
        }
        include $this->template("addshenhe");
    }

    
    public function doWebEditshenhe()
    {
        global $_W, $_GPC;
        $id = $_GPC['id'];
        if (!empty($_GPC['name'])) {
            $data['name'] = $_GPC['name'];
            $data['uniacid'] = $_W['uniacid'];
            $data['sort'] = $_GPC['sort'];
            $data['stact'] = $_GPC['stact'];
            $data['img'] = $_GPC['img'];
            $data['content'] = $_GPC['content'];
            pdo_update('hczhongzhuan_shenhe', $data, array('id' => $id));
        }
        $shenhe = pdo_get('hczhongzhuan_shenhe', array('id' => $id));
        include $this->template("editshenhe");
    }

    public function doWebDeleteshenhe()
    {
        global $_W, $_GPC;
        pdo_delete('hczhongzhuan_shenhe', array('id' => $_GPC['id']));
        $pindex = max(intval($_GPC['page']), 1);
        $psize = 20;
        $shenhe = pdo_getslice('hczhongzhuan_shenhe', array('uniacid' => $_W['uniacid']), array($pindex, $psize), $total, array(), '', array('sort desc'));
        for ($i = 0; $i < count($shenhe); $i++) {
            if ($shenhe[$i]['stact'] == 0) {
                $shenhe[$i]['stact'] = '不显示';
            } else {
                $shenhe[$i]['stact'] = '显示';
            }
        }
        $pager = pagination($total, $pindex, $psize);
        include $this->template("shenhe");
    }

    //商品管理
    public function doWebGoods() {
        global $_W,$_GPC;
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword'])){
            $where['goods_name LIKE'] = '%'.$keyword.'%';
        }
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_goods',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);

        include $this->template('goods');
    }

    //模板消息
    public function doWebMessage()
    {
        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/           
            //$data['hongbao_msgid'] = $_GPC['hongbao_msgid'];
            $data['msgid'] = $_GPC['msgid'];
            $data['keyword1'] = $_GPC['keyword1'];
            $data['keyword2'] = $_GPC['keyword2'];
            $data['keyword3'] = $_GPC['keyword3'];
            $data['fahuomsgid'] = $_GPC['fahuomsgid'];
            
            $ishave = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_message', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_message', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('message'));
            }
        }
        if($_GPC['act']=='qing'){
            pdo_query("TRUNCATE TABLE ".tablename('hcstep_formid'),array());
            message('清除成功',$this->createWebUrl('message'));
        }
        $setup = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
        include $this->template('message');
    }


    //添加商品
    public function doWebAddgoods() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            $data['goods_name'] = $_GPC['goods_name'];
            $data['main_img'] = $_GPC['main_img'];
            $data['goods_img'] = json_encode($_GPC['goods_img']);
            $data['price'] = $_GPC['price'];
            $data['inventory'] = $_GPC['inventory'];
            $data['express'] = $_GPC['express'];
            $data['status'] = $_GPC['status'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['goodsinfo'] = $_GPC['goodsinfo'];
            $data['price2'] = $_GPC['price2'];
            $data['rmb'] = $_GPC['rmb'];
            $data['paytype'] = $_GPC['paytype'];
            $data['maxrmb'] = $_GPC['maxrmb'];
            $data['selltype'] = $_GPC['selltype'];
            $data['shop_id'] = $_GPC['shop_id'];
            $data['minpeople'] = $_GPC['minpeople'];
            $data['maxbuy'] = $_GPC['maxbuy'];
            $data['indexsort_id'] = $_GPC['indexsort_id'];
            $data['sort_id'] = $_GPC['sort_id'];
            $data['is_hongbao'] = $_GPC['is_hongbao'];
            $data['maxhongbao'] = $_GPC['maxhongbao'];
            $data['validity'] = $_GPC['validity'];


            $result = pdo_insert('hcstep_goods', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('goods'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['goods_name'] = $_GPC['goods_name'];
            $data['main_img'] = $_GPC['main_img'];
            $data['goods_img'] = json_encode($_GPC['goods_img']);
            $data['price'] = $_GPC['price'];
            $data['inventory'] = $_GPC['inventory'];
            $data['express'] = $_GPC['express'];
            $data['status'] = $_GPC['status'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['goodsinfo'] = $_GPC['goodsinfo'];
            $data['price2'] = $_GPC['price2'];
            $data['rmb'] = $_GPC['rmb'];
            $data['paytype'] = $_GPC['paytype'];
            $data['maxrmb'] = $_GPC['maxrmb'];
            $data['selltype'] = $_GPC['selltype'];
            $data['shop_id'] = $_GPC['shop_id'];
            $data['minpeople'] = $_GPC['minpeople'];
            $data['maxbuy'] = $_GPC['maxbuy'];
            $data['indexsort_id'] = $_GPC['indexsort_id'];
            $data['sort_id'] = $_GPC['sort_id'];
            $data['is_hongbao'] = $_GPC['is_hongbao'];
            $data['maxhongbao'] = $_GPC['maxhongbao'];
            $data['validity'] = $_GPC['validity'];

            $result = pdo_update('hcstep_goods', $data, array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('goods'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_goods', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('goods'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('hcstep_goods',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('goods'));
            }
        }
        $shop = pdo_getall('hcstep_shop',array('uniacid'=>$_W['uniacid']),array(),'','id asc');
        $indexsort = pdo_getall('hcstep_sort',array('uniacid'=>$_W['uniacid'],'type'=>1),array(),'','displayorder asc');
        $sort = pdo_getall('hcstep_sort',array('uniacid'=>$_W['uniacid'],'type'=>2),array(),'','displayorder asc');
        $info = pdo_get('hcstep_goods',array('id'=>$id));
        $info['goods_img'] = json_decode($info['goods_img']);

        include $this->template('addgoods');
    }

    //奖品管理
    public function doWebAwards() {
        global $_W,$_GPC;
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword'])){
            $where['goods_name LIKE'] = '%'.$keyword.'%';
        }
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_awards',$where,array($pageindex, $pagesize),$total,array(),'','sort asc');
        foreach ($list as $k => $v) {
            if($v['awards_type'] == 1){
                $list[$k]['goods_name'] = $v['goods_name'];
                $list[$k]['main_img'] = $_W['attachurl'].$v['main_img'];
            }
            if($v['awards_type'] == 2){
                $list[$k]['goods_name'] = $v['awards_coin'].'步数币';
                $list[$k]['main_img'] = $_W['siteroot']."addons/hc_step/coin.png";
            }
            if($v['awards_type'] == 3){
                $list[$k]['goods_name'] = $v['awards_money'].'元红包';
                $list[$k]['main_img'] = $_W['siteroot']."addons/hc_step/template/img/hb1.png";
            }
            
        }
        $page = pagination($total, $pageindex, $pagesize);

        include $this->template('awards');
    }


    //添加奖品
    public function doWebAddawards() {
        global $_W,$_GPC;
        $id = $_GPC['id'];
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            $data['goods_name'] = $_GPC['goods_name'];
            $data['main_img'] = $_GPC['main_img'];
            $data['price'] = $_GPC['price'];
            $data['awards_type'] = $_GPC['awards_type'];
            $data['awards_coin'] = $_GPC['awards_coin'];
            $data['awards_money'] = $_GPC['awards_money'];
            $data['status'] = $_GPC['status'];
            $data['rate'] = $_GPC['rate'];
            $data['sort'] = $_GPC['sort'];

            $result = pdo_insert('hcstep_awards', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('awards'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['goods_name'] = $_GPC['goods_name'];
            $data['main_img'] = $_GPC['main_img'];
            $data['price'] = $_GPC['price'];
            $data['awards_type'] = $_GPC['awards_type'];
            $data['awards_coin'] = $_GPC['awards_coin'];
            $data['awards_money'] = $_GPC['awards_money'];
            $data['status'] = $_GPC['status'];
            $data['rate'] = $_GPC['rate'];
            $data['sort'] = $_GPC['sort'];

            $result = pdo_update('hcstep_awards', $data, array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('awards'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_awards', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('awards'));
            }
        }
        $info = pdo_get('hcstep_awards',array('id'=>$id));
        $info['goods_img'] = json_decode($info['goods_img']);

        include $this->template('addawards');
    }

    //用户管理
    public function doWebUsers(){
        global $_W,$_GPC;
        $op = $_GPC['op'];
        if ($op == 'del') {
            $id = $_GPC['id'];
            $item = pdo_get('hcstep_users',array('user_id'=>$id,'uniacid'=>$_W['uniacid']));
            if(empty($item)){
                message('操作失败',$this->createWebUrl('users'),'error');
            }
            if(pdo_delete('hcstep_users',array('user_id'=>$id,'uniacid'=>$_W['uniacid'])) === false) message('操作失败',referer(),'error');
            else message('操作成功',$this->createWebUrl('users'),'success');
        }
        if ($op == 'black') {
            $id = $_GPC['id'];
            $item = pdo_get('hcstep_users',array('user_id'=>$id,'uniacid'=>$_W['uniacid']));
            if(empty($item)){
                message('操作失败',$this->createWebUrl('users'),'error');
            }
            if(pdo_update('hcstep_users',array('black'=>1), array('user_id'=>$id,'uniacid'=>$_W['uniacid'])) === false) message('操作失败',referer(),'error');
            else message('操作成功',$this->createWebUrl('users'),'success');
        }
        
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==1){
            $where['nick_name LIKE'] = '%'.$keyword.'%';
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==2){
            $where['user_id'] = $keyword;
        }
        $where['uniacid'] = $_W['uniacid'];
        $where['black'] = 0;
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        if($op == 'rank'){
            $list = pdo_getslice('hcstep_users',$where,array($pageindex, $pagesize),$total,array(),'','money desc');
        }else{
            $list = pdo_getslice('hcstep_users',$where,array($pageindex, $pagesize),$total,array(),'','user_id desc');
        }
        
        $page = pagination($total, $pageindex, $pagesize);
       
        include $this->template('users');      
    }

    //黑名单
    public function doWebBlacklist(){
        global $_W,$_GPC;
        $op = $_GPC['op'];
        if ($op == 'white') {
            $id = $_GPC['id'];
            $item = pdo_get('hcstep_users',array('user_id'=>$id,'uniacid'=>$_W['uniacid']));
            if(empty($item)){
                message('操作失败',$this->createWebUrl('blacklist'),'error');
            }
            if(pdo_update('hcstep_users',array('black'=>0), array('user_id'=>$id,'uniacid'=>$_W['uniacid'])) === false) message('操作失败',referer(),'error');
            else message('操作成功',$this->createWebUrl('blacklist'),'success');
        }
        
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword'])){
            $where['nick_name LIKE'] = '%'.$keyword.'%';
        }
        $where['uniacid'] = $_W['uniacid'];
        $where['black'] = 1;
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_users',$where,array($pageindex, $pagesize),$total,array(),'','user_id desc');
        $page = pagination($total, $pageindex, $pagesize);
       
        include $this->template('blacklist');      
    }

    //基础设置
    public function doWebSetting(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['sharetitle'] = $_GPC['sharetitle'];
            $data['sharepic'] = $_GPC['sharepic'];
            $data['coinname'] = $_GPC['coinname'];
            $data['rate'] = $_GPC['rate'];
            $data['sharestep'] = $_GPC['sharestep'];
            $data['boxprice'] = $_GPC['boxprice'];
            $data['rulepic'] = $_GPC['rulepic'];
            $data['headcolor'] = $_GPC['headcolor'];
            $data['xcx'] = $_GPC['xcx'];
            $data['up'] = $_GPC['up'];
            $data['notice'] = $_GPC['notice'];
            $data['shenhe'] = $_GPC['shenhe'];
            $data['loginpic'] = $_GPC['loginpic'];
            $data['indexbg'] = $_GPC['indexbg'];
            $data['indexbutton'] = $_GPC['indexbutton'];
            $data['inviteball'] = $_GPC['inviteball'];
            $data['upball'] = $_GPC['upball'];
            $data['zerotip'] = $_GPC['zerotip'];
            $data['poortip'] = $_GPC['poortip'];
            $data['is_follow'] = $_GPC['is_follow'];
            $data['followpic'] = $_GPC['followpic'];
            $data['kefupic'] = $_GPC['kefupic'];
            $data['maxstep'] = $_GPC['maxstep'];
            $data['followlogo'] = $_GPC['followlogo'];
            $data['sharetext'] = $_GPC['sharetext'];
            $data['shareinfo'] = $_GPC['shareinfo'];
            $data['upinfo'] = $_GPC['upinfo'];
            $data['boxpic'] = $_GPC['boxpic'];
            $data['smalltip'] = $_GPC['smalltip'];
            $data['frame'] = $_GPC['frame'];
            $data['smalltipcolor'] = $_GPC['smalltipcolor'];
            $data['sharetextcolor'] = $_GPC['sharetextcolor'];
            $data['shareinfocolor'] = $_GPC['shareinfocolor'];
            $data['buttonbg'] = $_GPC['buttonbg'];
            $data['balltextcolor'] = $_GPC['balltextcolor'];
            $data['centercolor'] = $_GPC['centercolor'];
            $data['cointextcolor'] = $_GPC['cointextcolor'];
            $data['coinpic'] = $_GPC['coinpic'];
            $data['invitetype'] = $_GPC['invitetype'];
            $data['longbg'] = $_GPC['longbg'];
            $data['is_kuang'] = $_GPC['is_kuang'];
            
            $ishave = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('setting'));
            }
        }
        $info = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        include $this->template('setting');
    }

    //问题设置
    public function doWebQuestion_set(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['questionpic'] = $_GPC['questionpic'];
            
            $ishave = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('question_set'));
            }
        }
        $info = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        include $this->template('question_set');
    }

    //关注设置
    public function doWebGuanzhu(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['kefu_title'] = $_GPC['kefu_title'];
            $data['kefu_img'] = $_GPC['kefu_img'];
            $data['kefu_gaishu'] = $_GPC['kefu_gaishu'];
            $data['kefu_url'] = $_GPC['kefu_url'];
            $data['guanzhu_step'] = $_GPC['guanzhu_step'];
                      
            $ishave = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('guanzhu'));
            }
        }
        $info = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        include $this->template('guanzhu');
    }

    //关注设置
    public function doWebShenhe(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['version'] = $_GPC['version'];
                      
            $ishave = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('shenhe'));
            }
        }
        $info = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        include $this->template('shenhe');
    }

    //其他设置
    public function doWebOthers(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){

            $data['uniacid'] = $_W['uniacid'];
            $data['is_float'] = $_GPC['is_float'];
            $data['phoneno'] = $_GPC['phoneno'];
            $data['call_icon'] = $_GPC['call_icon'];
            $data['copytext'] = $_GPC['copytext'];
            $data['copy_icon'] = $_GPC['copy_icon'];

            $data['is_tan'] = $_GPC['is_tan'];
            $data['tan_type'] = $_GPC['tan_type'];
            $data['tan_goodsid'] = $_GPC['tan_goodsid'];
            $data['tan_pic'] = $_GPC['tan_pic'];

            $data['left1'] = $_GPC['left1'];
            $data['left1_jump'] = $_GPC['left1_jump'];
            $data['left1_appid'] = $_GPC['left1_appid'];
            $data['left1_path'] = $_GPC['left1_path'];

            $data['left2'] = $_GPC['left2'];
            $data['left2_jump'] = $_GPC['left2_jump'];
            $data['left2_appid'] = $_GPC['left2_appid'];
            $data['left2_path'] = $_GPC['left2_path'];

            $data['right1'] = $_GPC['right1'];
            $data['right1_jump'] = $_GPC['right1_jump'];
            $data['right1_appid'] = $_GPC['right1_appid'];
            $data['right1_path'] = $_GPC['right1_path'];

            $data['right2'] = $_GPC['right2'];
            $data['right2_jump'] = $_GPC['right2_jump'];
            $data['right2_appid'] = $_GPC['right2_appid'];
            $data['right2_path'] = $_GPC['right2_path'];

            $data['right3'] = $_GPC['right3'];
            $data['right3_jump'] = $_GPC['right3_jump'];
            $data['right3_appid'] = $_GPC['right3_appid'];
            $data['right3_path'] = $_GPC['right3_path'];

            $data['is_five'] = $_GPC['is_five'];
                      
            $ishave = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_message', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_message', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('others'));
            }
        }
        $info = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
        $info['tan_pic1'] = $_W['siteroot']."addons/hc_step/template/img/tanshangcheng.png";
        $goodslist = pdo_getall('hcstep_goods',array('uniacid'=>$_W['uniacid']));
        if(empty($info['call_icon'])){
            $info['call_icon'] = $_W['siteroot']."addons/hc_step/call.png";
        }
        if(empty($info['copy_icon'])){
            $info['copy_icon'] = $_W['siteroot']."addons/hc_step/copy.png";
        }
        include $this->template('others');
    }

    //小图标设置
    public function doWebIcon_set(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){

            $data['uniacid'] = $_W['uniacid'];
            $data['icon_position'] = $_GPC['icon_position'];
             
            $ishave = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_message', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_message', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('icon_set'));
            }
        }
        $info = pdo_get('hcstep_message', array('uniacid'=>$_W['uniacid']));

        include $this->template('icon_set');
    }

    //小图标设置
    public function doWebByq_set(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){

            $data['uniacid'] = $_W['uniacid'];
            $data['fabu_icon'] = $_GPC['fabu_icon'];
             
            $ishave = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_message', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_message', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('byq_set'));
            }
        }
        $info = pdo_get('hcstep_message', array('uniacid'=>$_W['uniacid']));
        if(empty($info['fabu_icon'])){
            $info['fabu_icon'] = $_W['siteroot']."addons/hc_step/template/img/fabu.png";
        }

        include $this->template('byq_set');
    }

    //流量主设置
    public function doWebAd(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['adunit'] = $_GPC['adunit'];
            $data['adunit2'] = $_GPC['adunit2'];
            $data['adunit3'] = $_GPC['adunit3'];
            $data['adunit4'] = $_GPC['adunit4'];
            $data['adunit5'] = $_GPC['adunit5'];
            $data['adunit6'] = $_GPC['adunit6'];
                      
            $ishave = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('ad'));
            }
        }
        $info = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        include $this->template('ad');
    }

    //公告设置
    public function doWebNotice(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $res['uniacid'] = $_W['uniacid'];
            $res['notice'] = $_GPC['notice'];
            $res['rolltime'] = $_GPC['rolltime'];
            $res['status'] = $_GPC['status'];
                      
            $ishave2 = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave2)){
                $aaa = pdo_update('hcstep_message', $res ,array('uniacid'=>$_W['uniacid']));
            }else{
                $aaa = pdo_insert('hcstep_message', $res);
            }
            if (!empty($aaa)) {
                message('操作成功',$this->createWebUrl('notice'));
            }
        }
        $ccc = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
        include $this->template('notice');
    }

    //公告设置
    public function doWebAwards_set(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $res['uniacid'] = $_W['uniacid'];           
            $res['lotto_type'] = $_GPC['lotto_type'];
                      
            $ishave2 = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave2)){
                $aaa = pdo_update('hcstep_message', $res ,array('uniacid'=>$_W['uniacid']));
            }else{
                $aaa = pdo_insert('hcstep_message', $res);
            }
            if (!empty($aaa)) {
                message('操作成功',$this->createWebUrl('awards_set'));
            }
        }
        $ccc = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
        include $this->template('awards_set');
    }

    //公告设置
    public function doWebCenterdiy(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $res['uniacid'] = $_W['uniacid'];
            $res['centerhead'] = $_GPC['centerhead'];
            $res['invite_icon'] = $_GPC['invite_icon'];
            $res['rule_icon'] = $_GPC['rule_icon'];
            $res['qs_icon'] = $_GPC['qs_icon'];
            $res['news_icon'] = $_GPC['news_icon'];
            $res['contact_icon'] = $_GPC['contact_icon'];
            $res['ka_icon'] = $_GPC['ka_icon'];
            $res['set_icon'] = $_GPC['set_icon'];
            $res['dhcolor'] = $_GPC['dhcolor']; 
            $res['name_color'] = $_GPC['name_color'];
            $res['id_color'] = $_GPC['id_color'];
            $res['dhjl_color'] = $_GPC['dhjl_color'];
            $res['idbg_color'] = $_GPC['idbg_color'];
            $res['txcolor'] = $_GPC['txcolor'];
            $res['txjl_color'] = $_GPC['txjl_color'];
            $res['hb_icon'] = $_GPC['hb_icon'];
            $res['order_icon'] = $_GPC['order_icon'];

            $ishave2 = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave2)){
                $aaa = pdo_update('hcstep_message', $res ,array('uniacid'=>$_W['uniacid']));
            }else{
                $aaa = pdo_insert('hcstep_message', $res);
            }
            if (!empty($aaa)) {
                message('操作成功',$this->createWebUrl('centerdiy'));
            }
        }
        $info = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
        include $this->template('centerdiy');
    }

    //充值    
    public function doWebAddmoney(){

        global $_W,$_GPC;
        $user = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$_GPC['user_id']));  
        if($_GPC['act'] == 'add'){
            $data['money'] = $_GPC['finishmoney'];
            if(!empty($_GPC['finishmoney'])){
              $result = pdo_update('hcstep_users', $data ,array('user_id'=>$user['user_id']));  
              message('操作成功',$this->createWebUrl('users')); 
            }else{
              message('未做修改',$this->createWebUrl('users')); 
            }
            
        }
        include $this->template('addmoney');
    }

    //流量主设置
    public function doWebActivityset(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['activitypic'] = $_GPC['activitypic'];
            $data['applypic'] = $_GPC['applypic'];
            $data['rule'] = $_GPC['rule'];
            $data['updatetip'] = $_GPC['updatetip'];
            $data['updatepic'] = $_GPC['updatepic'];
            $data['updatetipcolor'] = $_GPC['updatetipcolor'];
                      
            $ishave = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('activityset'));
            }
        }
        $info = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        include $this->template('activityset');
    }

    //海报设置
    public function doWebPoster(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['sweattext'] = $_GPC['sweattext'];
            $data['icon'] = $_GPC['icon'];
            $data['posterpic'] = $_GPC['posterpic'];
            $data['posterpic2'] = $_GPC['posterpic2'];
            $data['posterpic3'] = $_GPC['posterpic3'];
            $data['comeon'] = $_GPC['comeon'];
            $data['sweatcolor'] = $_GPC['sweatcolor'];
            $data['goodstop'] = $_GPC['goodstop'];
                      
            $ishave = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('poster'));
            }
        }
        $info = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        if(empty($info['posterpic'])){
            $info['posterpic'] = $_W['siteroot']."addons/hc_step/template/img/tu1.jpg";
        }
        if(empty($info['posterpic2'])){
            $info['posterpic2'] = $_W['siteroot']."addons/hc_step/template/img/tu2.jpg";
        }
        if(empty($info['posterpic3'])){
            $info['posterpic3'] = $_W['siteroot']."addons/hc_step/template/img/tu3.jpg";
        }

        include $this->template('poster');
    }

    //海报设置
    public function doWebSignin(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['signsharemoney'] = $_GPC['signsharemoney'];
            $data['signpic'] = $_GPC['signpic'];
            $data['signsharetext'] = $_GPC['signsharetext'];
            $data['signicon'] = $_GPC['signicon'];
            $data['signtext'] = $_GPC['signtext'];
            $data['signtextcolor'] = $_GPC['signtextcolor'];
            $data['is_qian'] = $_GPC['is_qian'];
                      
            $ishave = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('signin'));
            }
        }
        $info = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        include $this->template('signin');
    }

    //红包设置
    public function doWebHongbaoset(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['hongbaobg'] = $_GPC['hongbaobg'];
            $data['hongbaotext'] = $_GPC['hongbaotext'];
                      
            $ishave = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('hongbaoset'));
            }
        }
        $info = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        include $this->template('hongbaoset');
    }

    //问题管理
    public function doWebQuestion() {
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_question',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        foreach($list as $key=>$val){
            $list[$key]['createtime'] = date('Y-m-d H:i',$val['createtime']);
        }
        $page = pagination($total, $pageindex, $pagesize);

        include $this->template('question');
    }


   //添加问题
    public function doWebQuestion_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            $data['createtime'] = time();
            empty($_GPC['title'])?'':$data['title'] = $_GPC['title'];
            empty($_GPC['content'])?'':$data['content'] = $_GPC['content'];
            empty($_GPC['enabled'])?'':$data['enabled'] = $_GPC['enabled'];

            $result = pdo_insert('hcstep_question', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('question'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['createtime'] = time();
            empty($_GPC['title'])?'':$data['title'] = $_GPC['title'];
            empty($_GPC['content'])?'':$data['content'] = $_GPC['content'];
            $data['enabled'] = $_GPC['enabled'];

            $result = pdo_update('hcstep_question', $data, array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('question'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_question', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('question'));
            }
        }
        $info = pdo_get('hcstep_question',array('id'=>$id));

        include $this->template('question_post');
    }

    //活动管理
    public function doWebHuodong() {
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_huodong',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        foreach($list as $k=>$v){
            if($v['jump'] == 1){
                $list[$k]['jump'] = '抽奖';
            }elseif($v['jump'] == 2){
                $list[$k]['jump'] = '步数挑战';
            }elseif($v['jump'] == 3){
                $list[$k]['jump'] = '步数商城';
            }elseif($v['jump'] == 4){
                $list[$k]['jump'] = '跳转小程序';
            }elseif($v['jump'] == 5){
                $list[$k]['jump'] = '跳转H5';
            }elseif($v['jump'] == 6){
                $list[$k]['jump'] = '单页';
            }elseif($v['jump'] == 7){
                $list[$k]['jump'] = '看视频';
            }
        }
        $page = pagination($total, $pageindex, $pagesize);

        include $this->template('huodong');
    }


   //添加活动
    public function doWebHuodong_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['jump'] = $_GPC['jump'];
            $data['entrypic'] = $_GPC['entrypic'];
            $data['xcxpath'] = $_GPC['xcxpath'];
            $data['xcxappid'] = $_GPC['xcxappid'];
            $data['h5'] = $_GPC['h5'];
            $data['diypic'] = $_GPC['diypic'];
            $data['ad'] = trim($_GPC['ad']);
            $data['step'] = $_GPC['step'];
        
            $result = pdo_insert('hcstep_huodong', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('huodong'));
            }
        }

        if($_GPC['act']=='auto'){
            $data1['uniacid'] = $_W['uniacid'];
            $data1['displayorder'] = 1;
            $data1['jump'] = 1;
            $data1['entrypic'] = $_W['siteroot']."addons/hc_step/template/img/choujiang.png";

            $data2['uniacid'] = $_W['uniacid'];
            $data2['displayorder'] = 2;
            $data2['jump'] = 2;
            $data2['entrypic'] = $_W['siteroot']."addons/hc_step/template/img/tiaozhan.png";

            $data3['uniacid'] = $_W['uniacid'];
            $data3['displayorder'] = 3;
            $data3['jump'] = 3;
            $data3['entrypic'] = $_W['siteroot']."addons/hc_step/template/img/shangcheng.png";

            $data4['uniacid'] = $_W['uniacid'];
            $data4['displayorder'] = 4;
            $data4['jump'] = 4;
            $data4['entrypic'] = $_W['siteroot']."addons/hc_step/template/img/xcx.png";

            $data4['uniacid'] = $_W['uniacid'];
            $data4['displayorder'] = 5;
            $data4['jump'] = 7;
            $data4['entrypic'] = $_W['siteroot']."addons/hc_step/template/img/shipin.png";
        
            $result = pdo_insert('hcstep_huodong', $data1);
            $result = pdo_insert('hcstep_huodong', $data2);
            $result = pdo_insert('hcstep_huodong', $data3);
            $result = pdo_insert('hcstep_huodong', $data4);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('huodong'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['jump'] = $_GPC['jump'];
            $data['entrypic'] = $_GPC['entrypic'];
            $data['xcxpath'] = $_GPC['xcxpath'];
            $data['xcxappid'] = $_GPC['xcxappid'];
            $data['h5'] = $_GPC['h5'];
            $data['diypic'] = $_GPC['diypic'];
            $data['ad'] = trim($_GPC['ad']);
            $data['step'] = $_GPC['step'];

            $result = pdo_update('hcstep_huodong', $data, array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('huodong'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_huodong', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('huodong'));
            }
        }

        if($_GPC['act']=='display'){
            $result = pdo_update('hcstep_huodong',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('huodong'));
            }
        }
        $info = pdo_get('hcstep_huodong',array('id'=>$id));
        $info['entrypic1'] = $_W['siteroot']."addons/hc_step/template/img/choujiang.png";
        $info['entrypic2'] = $_W['siteroot']."addons/hc_step/template/img/tiaozhan.png";
        $info['entrypic3'] = $_W['siteroot']."addons/hc_step/template/img/shangcheng.png";
        $info['entrypic4'] = $_W['siteroot']."addons/hc_step/template/img/xcx.png";
        $info['entrypic5'] = $_W['siteroot']."addons/hc_step/template/img/shipin.png";

        include $this->template('huodong_post');
    }

    //商品兑换记录
    public function doWebExchange() {
        global $_W,$_GPC;
        
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==1){
            $where['user_id LIKE'] = '%'.$keyword.'%';
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==2){
            $where['userName'] = $keyword;
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==3){
            $where['telNumber'] = $keyword;
        }
        $where['uniacid'] = $_W['uniacid'];
        $where['status !='] = 2;

        if($_GPC['type'] != 0 and !empty($_GPC['type'])){
           $where['type'] = $_GPC['type']-1; 
        }else{
           $where['type <'] = 10;
        }
             
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_orders',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('hcstep_orders',array('uniacid'=>$_W['uniacid']), array(),'','id desc');
        
        foreach ($list as $k => $v) {
           $user = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $goods = pdo_get('hcstep_goods',array('id'=>$v['goods_id'],'uniacid'=>$_W['uniacid']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['goods_name'] = $goods['goods_name'];
           $list[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
        }   

        include $this->template('exchange');
    }

    //奖品兑换记录
    public function doWebWin_exchange() {
        global $_W,$_GPC;
        
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==1){
            $where['user_id LIKE'] = '%'.$keyword.'%';
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==2){
            $where['userName'] = $keyword;
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==3){
            $where['telNumber'] = $keyword;
        }
        $where['uniacid'] = $_W['uniacid'];
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_winlog',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('hcstep_winlog',array('uniacid'=>$_W['uniacid']), array(),'','id desc');
        
        foreach ($list as $k => $v) {
           $user = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $goods = pdo_get('hcstep_awards',array('id'=>$v['goods_id'],'uniacid'=>$_W['uniacid']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           
           if($goods['awards_type'] == 1){
              $list[$k]['goods_name'] = $goods['goods_name'];
           }
           if($goods['awards_type'] == 2){
              $list[$k]['goods_name'] = $goods['awards_coin'].'步数币';
           }
           if($goods['awards_type'] == 3){
              $list[$k]['goods_name'] = $goods['awards_money'].'元红包';
           }
           $list[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
        }        

        include $this->template('win_exchange');
    }

    //步数兑换记录
    public function doWebCoin_exchange() {
        global $_W,$_GPC;
        
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword'])){
            $where['user_id LIKE'] = '%'.$keyword.'%';
        }
        $where['uniacid'] = $_W['uniacid'];
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_bushulog',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('hcstep_bushulog',array('uniacid'=>$_W['uniacid']));
        
        foreach ($list as $k => $v) {
           $user = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['timestamp'] = date('Y-m-d H:i:s',$v['timestamp']);
        }        

        include $this->template('coin_exchange');
    }

    //步数兑换记录
    public function doWebBushulog(){
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        $where['user_id'] = $_GPC['user_id'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_bushulog',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('hcstep_bushulog',array('uniacid'=>$_W['uniacid']));
        
        foreach ($list as $k => $v) {
           $user = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['timestamp'] = date('Y-m-d H:i:s',$v['timestamp']);
        }        

        include $this->template('bushulog');
    }

    //核销记录
    public function doWebHexiaolog(){
        global $_W,$_GPC;
        $shoplist = pdo_getall('hcstep_shop',array('uniacid'=>$_W['uniacid']));       
        $keyword = $_GPC['keyword']; 

        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==1 and $_GPC['aaa'] ==1){
            $where['user_id LIKE'] = $keyword;     
            $where['hexiaostatus'] = 1;
        }elseif(empty($_GPC['keyword']) and $_GPC['order_status'] ==1 and $_GPC['aaa'] ==1){
            $where['hexiaostatus'] = 1;
        }

        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==1 and $_GPC['aaa'] ==2){
            $uid = pdo_getcolumn('hcstep_users',array('uniacid'=>$_W['uniacid'],'nick_name like'=>$keyword),array('user_id'));
            $where['user_id LIKE'] = $uid;     
            $where['hexiaostatus'] = 1;
        }elseif(empty($_GPC['keyword']) and $_GPC['order_status'] ==1 and $_GPC['aaa'] ==2){
            $where['hexiaostatus'] = 1;
        }

        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==2 and $_GPC['aaa'] ==1){
            $where['user_id'] = $keyword;
            $where['hexiaostatus'] = 0;
        }elseif(empty($_GPC['keyword']) and $_GPC['order_status'] ==2 and $_GPC['aaa'] ==1){
            $where['hexiaostatus'] = 0;
        }

        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==2 and $_GPC['aaa'] ==2){
            $uid = pdo_getcolumn('hcstep_users',array('uniacid'=>$_W['uniacid'],'nick_name like'=>$keyword),array('user_id'));
            $where['user_id LIKE'] = $uid; 
            $where['hexiaostatus'] = 0;
        }elseif(empty($_GPC['keyword']) and $_GPC['order_status'] ==2 and $_GPC['aaa'] ==2){
            $where['hexiaostatus'] = 0;
        }

        $where['uniacid'] = $_W['uniacid'];
        $where['type >'] = 9;
        $where['status !='] = 2;
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $listlist = pdo_getslice('hcstep_orders',$where,array($pageindex, $pagesize),$total,array(),'','time desc');
        if(!empty($_GPC['shop_id'])){

            $shopgoods = pdo_getall('hcstep_goods',array('uniacid'=>$_W['uniacid'],'shop_id'=>$_GPC['shop_id']));
            foreach ($shopgoods as $k => $v){
                $goodsidlist[] = $v['id'];
            }
            foreach ($listlist as $k => $v) {
                if(in_array($v['goods_id'],$goodsidlist)){
                    $list[] = $v;
                }
            }
        }else{
            $list = $listlist;
        }
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('hcstep_bushulog',array('uniacid'=>$_W['uniacid']));
        
        foreach ($list as $k => $v) {
           $user = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $goods = pdo_get('hcstep_goods',array('uniacid'=>$_W['uniacid'],'id'=>$v['goods_id']));
           $shop = pdo_get('hcstep_shop',array('uniacid'=>$_W['uniacid'],'id'=>$goods['shop_id']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['goodsname'] = $goods['goods_name'];
           $list[$k]['shopname'] = $shop['shopname'];
           $list[$k]['shop_userid'] = $shop['user_id'];
           if(!empty($v['hexiaotime'])){
             $list[$k]['hexiaotime'] = date('Y-m-d H:i:s',$v['hexiaotime']);  
           } 
           if($v['endtime'] < time() and $v['endtime']>0){
             $list[$k]['hexiaostatus'] = 2;  
           }
           $list[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
           if($v['endtime'] >0){
              $list[$k]['endtime'] = date('Y-m-d H:i:s',$v['endtime']);
           }else{
              $list[$k]['endtime'] = '无限制';
           }
           
        }        

        include $this->template('hexiaolog');
    }

    //挑战记录
    public function doWebTiaozhanlog(){
        global $_W,$_GPC;
        $tomorrow = date('Y-m-d',strtotime("+1 day"));      
        $today=date('Y-m-d',time());      
        $yesterday = date('Y-m-d',strtotime("-1 day"));

        $where['uniacid'] = $_W['uniacid'];
        $where['user_id'] = $_GPC['user_id'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;
        $list = pdo_getslice('hcstep_activitylog',$where,array($pageindex, $pagesize),$total,array(),'','timestamp desc');
        $page = pagination($total, $pageindex, $pagesize);

        foreach ($list as $k => $v){
              $activity = pdo_get('hcstep_activity',array('uniacid' => $_W['uniacid'],'id'=>$v['aid']));
              $list[$k]['name'] = $activity['step'];
              $list[$k]['entryfee'] = $activity['entryfee'];
              if($v['time'] == $tomorrow){
                $list[$k]['status'] = '未开赛';
              }elseif($v['time'] == $today){
                $list[$k]['status'] = '进行中';
              }else{
                if($v['status'] == 0){
                    $list[$k]['status'] = '挑战失败';
                }else{
                    $list[$k]['status'] = '挑战成功';
                }
              }           
          }       

        include $this->template('tiaozhanlog');
    }

    //邀请记录
    public function doWebInvitelog(){
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        $where['user_id'] = $_GPC['user_id'];
        $user_id = $_GPC['user_id'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;
        $list = pdo_getslice('hcstep_invitelog',$where,array($pageindex, $pagesize),$total,array(),'','invite_time desc');

        $page = pagination($total, $pageindex, $pagesize);
        
        foreach ($list as $k => $v) {
            $user = pdo_get('hcstep_users',array('user_id'=>$v['sonid'],'uniacid'=>$_W['uniacid']));
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['head_pic'] = $user['head_pic'];  
            $list[$k]['invite_time'] = date('Y-m-d H:i',$v['invite_time']);
        }

        $count = count($list);         

        include $this->template('invitelog');
    }

    //商品邀请好友记录
    public function doWebGoodsinvitelog(){
        global $_W,$_GPC;
        
        $order = pdo_get('hcstep_orders',array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
        $where['uniacid'] = $_W['uniacid'];
        $where['goods_id'] = $order['goods_id'];
        $where['user_id'] = $order['user_id'];

        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;
        $list = pdo_getslice('hcstep_peoplelog',$where,array($pageindex, $pagesize),$total,array(),'','time desc');

        $page = pagination($total, $pageindex, $pagesize);
        
        foreach ($list as $k => $v) {
            $user = pdo_get('hcstep_users',array('user_id'=>$v['son_id'],'uniacid'=>$_W['uniacid']));
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['head_pic'] = $user['head_pic'];  
            $list[$k]['time'] = date('Y-m-d H:i',$v['time']);
        } 

        include $this->template('goodsinvitelog');
    }

    //邀请记录
    public function doWebHongbaolog(){
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        $where['user_id'] = $_GPC['user_id'];

        $user_id = $_GPC['user_id'];

        $set = pdo_get('hcstep_set', array('uniacid' => $_W['uniacid']));
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_hongbaolog',$where,array($pageindex, $pagesize),$total,array(),'','invite_time desc');

        $page = pagination($total, $pageindex, $pagesize);
        
        foreach ($list as $k => $v) {
            $user = pdo_get('hcstep_users',array('user_id'=>$v['sonid'],'uniacid'=>$_W['uniacid']));
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['head_pic'] = $user['head_pic'];  
            $list[$k]['invite_time'] = date('Y-m-d H:i',$v['invite_time']);
        }

        $count = count($list);         

        include $this->template('hongbaolog');
    }

    //发货
    public function doWebFahuo() {
        global $_W,$_GPC;
        $id = $_GPC['id'];      
        if($_GPC['op'] == 'shangpin'){
            $info = pdo_get('hcstep_orders',array('id'=>$id));
            $type = 'shangpin';
        }
        if($_GPC['act'] == 'shangpin'){
            $info = pdo_get('hcstep_orders',array('id'=>$id));
            $data['status'] = 1;
            $data['express'] = $_GPC['express'];
            $data['expressname'] = $_GPC['expressname'];
            $data['fahuotime'] = time();
            $res = pdo_update('hcstep_orders',$data, array('id' => $_GPC['id']));
            //模板消息
            $formid=pdo_getall('hcstep_formid', array('user_id' => $info['user_id'],'status'=>0), array() , '',array('id DESC') , array());
            if(!empty($formid[0])){
                $formid[0]['orderid'] = $info['id'];
                $aa=$this->fahuotpl($formid[0]);
            }

            if($res){
                message('发货成功',$this->createWebUrl('exchange'),'success');
            }else{
                message('已发货',$this->createWebUrl('exchange'),'error');
            }
        }
        if($_GPC['op'] == 'jiangpin'){
            $info = pdo_get('hcstep_winlog',array('id'=>$id));
            $type = 'jiangpin';
        }
        if($_GPC['act'] == 'jiangpin'){
            $data['status'] = 1;
            $data['express'] = $_GPC['express'];
            $data['expressname'] = $_GPC['expressname'];
            $data['fahuotime'] = time();
            $res = pdo_update('hcstep_winlog',$data, array('id' => $_GPC['id']));
            if($res){
                message('发货成功',$this->createWebUrl('win_exchange'),'success');
            }else{
                message('已发货',$this->createWebUrl('win_exchange'),'error');
            }
        }
    
        include $this->template('fahuo');	
    }

    public function doWebAdv() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_adv',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('adv');
    }
    //幻灯片
    public function doWebAdv_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['displayorder'])?'':$data['displayorder'] = $_GPC['displayorder'];
            empty($_GPC['advname'])?'':$data['advname'] = $_GPC['advname'];
            //empty($_GPC['link'])?'':$data['link'] = $_GPC['link'];
            empty($_GPC['thumb'])?'':$data['thumb'] = $_GPC['thumb'];
            empty($_GPC['enabled'])?'':$data['enabled'] = $_GPC['enabled'];
            empty($_GPC['jump'])?'':$data['jump'] = $_GPC['jump'];
            empty($_GPC['xcxpath'])?'':$data['xcxpath'] = $_GPC['xcxpath'];
            empty($_GPC['xcxappid'])?'':$data['xcxappid'] = $_GPC['xcxappid'];
            empty($_GPC['h5'])?'':$data['h5'] = $_GPC['h5'];
            empty($_GPC['tippic'])?'':$data['tippic'] = $_GPC['tippic'];

            $result = pdo_insert('hcstep_adv', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('adv'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['advname'] = $_GPC['advname'];
            //$data['link'] = $_GPC['link'];
            $data['thumb'] = $_GPC['thumb'];
            $data['enabled'] = $_GPC['enabled'];
            $data['jump'] = $_GPC['jump'];
            $data['xcxpath'] = $_GPC['xcxpath'];
            $data['xcxappid'] = $_GPC['xcxappid'];
            $data['h5'] = $_GPC['h5'];
            $data['tippic'] = $_GPC['tippic'];

            $result = pdo_update('hcstep_adv', $data, array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('adv'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_adv', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('adv'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('hcstep_adv',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('adv'));
            }
        }
        $info = pdo_get('hcstep_adv',array('id'=>$id));

        include $this->template('adv_post');
    }

    //首页分类
    public function doWebIndexsort() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        $where['type'] = 1; 
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_sort',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('indexsort');
    }
    //首页分类
    public function doWebIndexsort_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['displayorder'])?'':$data['displayorder'] = $_GPC['displayorder'];
            empty($_GPC['advname'])?'':$data['advname'] = $_GPC['advname'];
            empty($_GPC['enabled'])?'':$data['enabled'] = $_GPC['enabled'];
            empty($_GPC['is_distance'])?'':$data['is_distance'] = $_GPC['is_distance'];
            $data['type'] = 1;//首页分类

            $result = pdo_insert('hcstep_sort', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('indexsort'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['advname'] = $_GPC['advname'];
            $data['enabled'] = $_GPC['enabled'];
            $data['is_distance'] = $_GPC['is_distance'];
            $data['type'] = 1;//首页分类

            $result = pdo_update('hcstep_sort', $data, array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('indexsort'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_sort', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('indexsort'));
            }
        }
        $info = pdo_get('hcstep_sort',array('id'=>$id));

        include $this->template('indexsort_post');
    }

    //分类
    public function doWebSort() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        $where['type'] = 2; 
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_sort',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('sort');
    }
    //分类
    public function doWebSort_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['displayorder'])?'':$data['displayorder'] = $_GPC['displayorder'];
            empty($_GPC['advname'])?'':$data['advname'] = $_GPC['advname'];
            empty($_GPC['enabled'])?'':$data['enabled'] = $_GPC['enabled'];
            $data['type'] = 2;//首页分类

            $result = pdo_insert('hcstep_sort', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('sort'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['advname'] = $_GPC['advname'];
            $data['enabled'] = $_GPC['enabled'];
            $data['type'] = 2;//首页分类

            $result = pdo_update('hcstep_sort', $data, array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('sort'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_sort', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('sort'));
            }
        }
        $info = pdo_get('hcstep_sort',array('id'=>$id));

        include $this->template('sort_post');
    }

    public function doWebIcon() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_icon',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('icon');
    }
    //幻灯片
    public function doWebIcon_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['displayorder'])?'':$data['displayorder'] = $_GPC['displayorder'];
            empty($_GPC['advname'])?'':$data['advname'] = $_GPC['advname'];
            empty($_GPC['thumb'])?'':$data['thumb'] = $_GPC['thumb'];
            empty($_GPC['enabled'])?'':$data['enabled'] = $_GPC['enabled'];
            empty($_GPC['jump'])?'':$data['jump'] = $_GPC['jump'];
            empty($_GPC['xcxpath'])?'':$data['xcxpath'] = $_GPC['xcxpath'];
            empty($_GPC['xcxappid'])?'':$data['xcxappid'] = $_GPC['xcxappid'];
            empty($_GPC['runpic'])?'':$data['runpic'] = $_GPC['runpic'];
            empty($_GPC['advnamecolor'])?'':$data['advnamecolor'] = $_GPC['advnamecolor'];
            empty($_GPC['h5'])?'':$data['h5'] = $_GPC['h5'];
            empty($_GPC['tippic'])?'':$data['tippic'] = $_GPC['tippic'];

            $result = pdo_insert('hcstep_icon', $data);            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('icon'));
            }
        }
        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['advname'] = $_GPC['advname'];
            //$data['link'] = $_GPC['link'];
            $data['thumb'] = $_GPC['thumb'];
            $data['enabled'] = $_GPC['enabled'];
            $data['jump'] = $_GPC['jump'];
            $data['xcxpath'] = $_GPC['xcxpath'];
            $data['xcxappid'] = $_GPC['xcxappid'];
            $data['runpic'] = $_GPC['runpic'];
            $data['advnamecolor'] = $_GPC['advnamecolor'];
            $data['h5'] = $_GPC['h5'];
            $data['tippic'] = $_GPC['tippic'];

            $result = pdo_update('hcstep_icon', $data, array('id'=>$_GPC['id']));            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('icon'));
            }
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_icon', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('icon'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('hcstep_icon',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('icon'));
            }
        }
        $info = pdo_get('hcstep_icon',array('id'=>$id));

        include $this->template('icon_post');
    }
    //步友圈动态
    public function doWebByq(){
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_dt',$where,array($pageindex, $pagesize),$total,array(),'','time desc');
        foreach ($list as $k => $v) {
            $user = pdo_get('hcstep_users',array('user_id'=>$v['user_id']));
            $topic = pdo_get('hcstep_topic',array('id'=>$v['topic_id']));
            $list[$k]['content_img'] = json_decode($v['content_img']);
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['head_pic'] = $user['head_pic'];
            $list[$k]['topic'] = $topic['title'];
            $list[$k]['time'] = date("Y-m-d H:i:s",$v['time']);
            if(mb_strlen($v['content']) > 20){
                $list[$k]['content'] = mb_substr($v['content'],0,20,'utf-8').'...';
            }
        }       
        $page = pagination($total, $pageindex, $pagesize);

        include $this->template('byq');
    }

    public function doWebByqdo(){
        global $_W,$_GPC;
        $id = $_GPC['id'];
        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_dt', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('byq'));
            }
        }
        if($_GPC['act']=='shenhe'){
            $result = pdo_update('hcstep_dt',array('status'=>1),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('byq'));
            }
        }
    }
    //步友圈话题
    public function doWebTopic(){
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_topic',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('topic');
    }
    //幻灯片
    public function doWebTopic_post(){
        global $_W,$_GPC;
        $id = $_GPC['id'];
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['title'] = $_GPC['title'];
            $data['toppic'] = $_GPC['toppic'];
            $data['status'] = $_GPC['status'];

            $result = pdo_insert('hcstep_topic', $data);            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('topic'));
            }
        }
        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['title'] = $_GPC['title'];
            $data['toppic'] = $_GPC['toppic'];
            $data['status'] = $_GPC['status'];

            $result = pdo_update('hcstep_topic', $data, array('id'=>$_GPC['id']));            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('topic'));
            }
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_topic', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('topic'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('hcstep_topic',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('topic'));
            }
        }
        $info = pdo_get('hcstep_topic',array('id'=>$id));

        include $this->template('topic_post');
    }

    //任务列表
    public function doWebMission(){
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_mission',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        foreach ($list as $k => $v) {
            if($v['mission_type'] == 0){
                $list[$k]['mission_type'] = '邀请好友';
            }
            if($v['mission_type'] == 1){
                $list[$k]['mission_type'] = '跳转小程序';
            }
            if($v['mission_type'] == 2){
                $list[$k]['mission_type'] = '首次发帖';
            }
            if($v['mission_type'] == 3){
                $list[$k]['mission_type'] = '绑定手机号';
            }
            if($v['mission_type'] == 4){
                $list[$k]['mission_type'] = '激励视频';
            }
        }
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('mission');
    }
    //添加任务
    public function doWebMission_post(){
        global $_W,$_GPC;
        $id = $_GPC['id'];
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['title'] = $_GPC['title'];
            $data['title2'] = $_GPC['title2'];
            $data['mission_type'] = $_GPC['mission_type'];
            $data['mission_icon'] = $_GPC['mission_icon'];
            $data['step'] = $_GPC['step'];
            $data['appid'] = $_GPC['appid'];
            $data['path'] = $_GPC['path'];
            $data['ad'] = trim($_GPC['ad']);

            $result = pdo_insert('hcstep_mission', $data);            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('mission'));
            }
        }
        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['title'] = $_GPC['title'];
            $data['title2'] = $_GPC['title2'];
            $data['mission_type'] = $_GPC['mission_type'];
            $data['mission_icon'] = $_GPC['mission_icon'];
            $data['step'] = $_GPC['step'];
            $data['appid'] = $_GPC['appid'];
            $data['path'] = $_GPC['path'];
            $data['ad'] = trim($_GPC['ad']);

            $result = pdo_update('hcstep_mission', $data, array('id'=>$_GPC['id']));            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('mission'));
            }
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_mission', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('mission'));
            }
        }
        $info = pdo_get('hcstep_mission',array('id'=>$id));

        include $this->template('mission_post');
    }

    //红包裂变领取记录
     public function doWebMissionlog(){
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid']; 
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_missionlog',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        foreach ($list as $k => $v) {
           $user = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $misiion = pdo_get('hcstep_mission',array('uniacid'=>$_W['uniacid'],'id'=>$v['mission_id']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['mission_name'] = $misiion['title'];
           $list[$k]['time'] = date('Y-m-d H:i:s',$v['time']); 
        }   
               

        include $this->template('missionlog');

    }

    public function doWebHongbao() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_hongbao',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('hongbao');
    }
    //幻灯片
    public function doWebHongbao_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['displayorder'])?'':$data['displayorder'] = $_GPC['displayorder'];
            empty($_GPC['hongbaoname'])?'':$data['hongbaoname'] = $_GPC['hongbaoname'];
            empty($_GPC['hongbaomoney'])?'':$data['hongbaomoney'] = $_GPC['hongbaomoney'];
            empty($_GPC['enabled'])?'':$data['enabled'] = $_GPC['enabled'];
            empty($_GPC['hongbaopic'])?'':$data['hongbaopic'] = $_GPC['hongbaopic'];
            empty($_GPC['hongbaonamecolor'])?'':$data['hongbaonamecolor'] = $_GPC['hongbaonamecolor'];

            $result = pdo_insert('hcstep_hongbao', $data);            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('hongbao'));
            }
        }
        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['hongbaoname'] = $_GPC['hongbaoname'];
            $data['hongbaomoney'] = $_GPC['hongbaomoney'];
            $data['enabled'] = $_GPC['enabled'];
            $data['hongbaopic'] = $_GPC['hongbaopic'];
            $data['hongbaonamecolor'] = $_GPC['hongbaonamecolor'];

            $result = pdo_update('hcstep_hongbao', $data, array('id'=>$_GPC['id']));            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('hongbao'));
            }
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_hongbao', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('hongbao'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('hcstep_hongbao',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('hongbao'));
            }
        }
        $info = pdo_get('hcstep_hongbao',array('id'=>$id));

        include $this->template('hongbao_post');
    }

    public function doWebXuni() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_xuni',$where,array($pageindex, $pagesize),$total,array(),'','time desc');
        $page = pagination($total, $pageindex, $pagesize);

        foreach ($list as $k => $v) {
            $goods = pdo_get('hcstep_goods',array('id'=>$v['goods_id']));
            $list[$k]['goods_name'] = $goods['goods_name'];
        }

        //var_dump($list);

        include $this->template('xuni');
    }
    //虚拟订单
    public function doWebXuni_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['nick_name'])?'':$data['nick_name'] = $_GPC['nick_name'];
            empty($_GPC['head_pic'])?'':$data['head_pic'] = $_GPC['head_pic'];
            empty($_GPC['time'])?'':$data['time'] = $_GPC['time'];
            empty($_GPC['goods_id'])?'':$data['goods_id'] = $_GPC['goods_id'];

            $result = pdo_insert('hcstep_xuni', $data);            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('xuni'));
            }
        }
        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['nick_name'] = $_GPC['nick_name'];
            $data['head_pic'] = $_GPC['head_pic'];
            $data['time'] = $_GPC['time'];
            $data['goods_id'] = $_GPC['goods_id'];

            $result = pdo_update('hcstep_xuni', $data, array('id'=>$_GPC['id']));            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('xuni'));
            }
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_xuni', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('xuni'));
            }
        }
        $goods = pdo_getall('hcstep_goods',array('uniacid'=>$_W['uniacid']),array(),'','id asc');
        $info = pdo_get('hcstep_xuni',array('id'=>$id));

        //var_dump($goods);

        include $this->template('xuni_post');
    }
    //门店管理
    public function doWebShop() {
        global $_W,$_GPC;
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword'])){
            $where['shopname LIKE'] = '%'.$keyword.'%';
        }
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_shop',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        foreach ($list as $k => $v) {
            $user = pdo_get('hcstep_users',array('user_id'=>$v['user_id']));
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['head_pic'] = $user['head_pic'];
        }

        //var_dump($list);

        include $this->template('shop');
    }
    //虚拟订单
    public function doWebShop_post(){
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['shopname'])?'':$data['shopname'] = $_GPC['shopname'];
            empty($_GPC['logo'])?'':$data['logo'] = $_GPC['logo'];
            empty($_GPC['topbg'])?'':$data['topbg'] = $_GPC['topbg'];
            $data['sheng'] = $_GPC['dizhi']['province'];
            $data['shi'] = $_GPC['dizhi']['city'];
            $data['qu'] = $_GPC['dizhi']['district'];
            empty($_GPC['tel'])?'':$data['tel'] = $_GPC['tel'];
            empty($_GPC['address'])?'':$data['address'] = $_GPC['address'];
            empty($_GPC['starttime'])?'':$data['starttime'] = $_GPC['starttime'];
            empty($_GPC['endtime'])?'':$data['endtime'] = $_GPC['endtime'];
            $data['lat'] = $_GPC['map']['lat'];
            $data['lng'] = $_GPC['map']['lng'];

            $str =  explode(",",$_GPC['user_id']);
            foreach ($str as $k => $v) {
                $info = pdo_get('hcstep_users',array('user_id'=>$v));
                if(empty($info)){
                    message('用户不存在',$this->createWebUrl('shop'),'error');
                }
            }
            $result = pdo_insert('hcstep_shop', $data);            
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('shop'));
            }      
        }
        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['shopname'] = $_GPC['shopname'];
            $data['logo'] = $_GPC['logo'];
            $data['topbg'] = $_GPC['topbg'];
            $data['sheng'] = $_GPC['dizhi']['province'];
            $data['shi'] = $_GPC['dizhi']['city'];
            $data['qu'] = $_GPC['dizhi']['district'];
            $data['tel'] = $_GPC['tel'];
            $data['address'] = $_GPC['address'];
            $data['starttime'] = $_GPC['starttime'];
            $data['endtime'] = $_GPC['endtime'];
            $data['user_id'] = $_GPC['user_id'];
            $data['lat'] = $_GPC['map']['lat'];
            $data['lng'] = $_GPC['map']['lng'];

            $str =  explode(",",$_GPC['user_id']);
            foreach ($str as $k => $v) {
                $info = pdo_get('hcstep_users',array('user_id'=>$v));
                if(empty($info)){
                    message('用户不存在',$this->createWebUrl('shop'),'error');
                }
            }
            $result = pdo_update('hcstep_shop', $data, array('id'=>$_GPC['id']));            
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('shop'));
            }        
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_shop', array('id'=>$_GPC['id']));           
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('shop'));
            }
        }

        $info = pdo_get('hcstep_shop',array('id'=>$id));

        include $this->template('shop_post');
    }

    public function doWebKefu(){
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_kefu',$where,array($pageindex, $pagesize),$total,array(),'','id asc');
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('kefu');
    }
    //幻灯片
    public function doWebKefu_post(){
        global $_W,$_GPC;
        $id = $_GPC['id'];
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['kefu_keyword'])?'':$data['kefu_keyword'] = $_GPC['kefu_keyword'];
            empty($_GPC['kefu_title'])?'':$data['kefu_title'] = $_GPC['kefu_title'];
            empty($_GPC['kefu_img'])?'':$data['kefu_img'] = $_GPC['kefu_img'];
            empty($_GPC['kefu_gaishu'])?'':$data['kefu_gaishu'] = $_GPC['kefu_gaishu'];
            empty($_GPC['kefu_url'])?'':$data['kefu_url'] = $_GPC['kefu_url'];
            empty($_GPC['beizhu'])?'':$data['beizhu'] = $_GPC['beizhu'];

            $result = pdo_insert('hcstep_kefu', $data);
            //客服消息
            if(!empty($_GPC['kefu_keyword'])){
                $isrule = pdo_get('rule',array('module' => $_W['current_module']['name']));
                if(empty($isrule)){
                    pdo_insert('rule',array(
                        'uniacid'=> $_W['uniaccount']['uniacid'],
                        'name'   => $_W['current_module']['title'],
                        'module' => $_W['current_module']['name'],
                        'status' => 1
                        )
                    );
                    $rule_id = pdo_insertid();
                }else{
                    $rule_id = $isrule['id'];
                }
                
                $isrule_keyword = pdo_get('rule_keyword',array(
                    'rid'    => $rule_id,
                    'uniacid'=> $_W['uniaccount']['uniacid'],
                    'module' => $_W['current_module']['name'],
                    'content'=> $_GPC['kefu_keyword'],
                    )
                );

                if(empty($isrule_keyword)){
                    pdo_insert('rule_keyword',array(
                        'rid'    => $rule_id,
                        'uniacid'=> $_W['uniaccount']['uniacid'],
                        'module' => $_W['current_module']['name'],
                        'content'=> $_GPC['kefu_keyword'],
                        'type'   => 1,
                        'status' => 1
                        )
                    );
                }
            }

            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('kefu'));
            }
        }
        if($_GPC['act']=='edit'){
            $data['kefu_keyword'] = $_GPC['kefu_keyword'];
            $data['kefu_title'] = $_GPC['kefu_title'];
            $data['kefu_img'] = $_GPC['kefu_img'];
            $data['kefu_gaishu'] = $_GPC['kefu_gaishu'];
            $data['kefu_url'] = $_GPC['kefu_url'];
            $data['beizhu'] = $_GPC['beizhu'];

            $result = pdo_update('hcstep_kefu', $data, array('id'=>$_GPC['id']));

            if(!empty($_GPC['kefu_keyword'])){
                $isrule = pdo_get('rule',array('module' => $_W['current_module']['name']));
                if(empty($isrule)){
                    pdo_insert('rule',array(
                        'uniacid'=> $_W['uniaccount']['uniacid'],
                        'name'   => $_W['current_module']['title'],
                        'module' => $_W['current_module']['name'],
                        'status' => 1
                        )
                    );
                    $rule_id = pdo_insertid();
                }else{
                    $rule_id = $isrule['id'];
                }
                
                $isrule_keyword = pdo_get('rule_keyword',array(
                    'rid'    => $rule_id,
                    'uniacid'=> $_W['uniaccount']['uniacid'],
                    'module' => $_W['current_module']['name'],
                    'content'=> $_GPC['kefu_keyword'],
                    )
                );

                if(empty($isrule_keyword)){
                    pdo_insert('rule_keyword',array(
                        'rid'    => $rule_id,
                        'uniacid'=> $_W['uniaccount']['uniacid'],
                        'module' => $_W['current_module']['name'],
                        'content'=> $_GPC['kefu_keyword'],
                        'type'   => 1,
                        'status' => 1
                        )
                    );
                }
            }

            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('kefu'));
            }
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_kefu', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('kefu'));
            }
        }

        $info = pdo_get('hcstep_kefu',array('id'=>$id));

        include $this->template('kefu_post');
    }

    public function doWebActivity() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_activity',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);
        //var_dump($list);

        include $this->template('activity');
    }
    //幻灯片
    public function doWebActivity_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['displayorder'])?'':$data['displayorder'] = $_GPC['displayorder'];
            empty($_GPC['step'])?'':$data['step'] = $_GPC['step'];
            empty($_GPC['entryfee'])?'':$data['entryfee'] = $_GPC['entryfee'];
            /*empty($_GPC['starttime'])?'':$data['starttime'] = $_GPC['starttime'];
            empty($_GPC['endtime'])?'':$data['endtime'] = $_GPC['endtime'];
            empty($_GPC['rule'])?'':$data['rule'] = $_GPC['rule'];*/

            $result = pdo_insert('hcstep_activity', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('activity'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['step'] = $_GPC['step'];
            $data['entryfee'] = $_GPC['entryfee'];
            /*$data['starttime'] = $_GPC['starttime'];
            $data['endtime'] = $_GPC['endtime'];
            $data['rule'] = $_GPC['rule'];*/

            $result = pdo_update('hcstep_activity', $data, array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('activity'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('hcstep_activity', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('activity'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('hcstep_activity',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('activity'));
            }
        }
        $info = pdo_get('hcstep_activity',array('id'=>$id));

        include $this->template('activity_post');
    }

    public function doWebActivitylog(){
        global $_W,$_GPC;
        $aid = $_GPC['id'];
        $set = pdo_get('hcstep_activity', array('uniacid'=>$_W['uniacid'],'id'=>$aid));
        $step = $set['step'];

        $lastday = date('Y-m-d',strtotime("-1 day"));
        $today = date('Y-m-d',time());
        $zuotian = date('Y年m月d日',strtotime("-1 day"));

        $data['yesterday']['success'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'status !='=>0,'aid'=>$aid));
        $success = count($data['yesterday']['success']);
        $data['yesterday']['fail'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'status'=>0,'aid'=>$aid));
        $fail = count($data['yesterday']['fail']);
        $data['yesterday']['zong'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'aid'=>$aid));
        $zong = count($data['yesterday']['zong']);

        if ($success == 0){
            $jiangjin = 0;
        }else{
            $jiangjin = $zong * $set['entryfee'] / $success;
        }
        
        $list = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'aid'=>$aid));
        foreach ($list as $k => $v) {
            $user = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['time'] = date("Y-m-d H:i:s",$v['timestamp']);
            if($v['status'] == 1){
               $list[$k]['status'] = '已达标，未发奖';
               $list[$k]['jiangjin'] = $jiangjin;
            }elseif($v['status'] == 2){
               $list[$k]['status'] = '已达标，已发奖';
               $list[$k]['jiangjin'] = $jiangjin;
            }else{
               $list[$k]['status'] = '未达标';
               $list[$k]['jiangjin'] = 0;
            } 
        }
        
        include $this->template('activitylog');
    }

    public function doWebsendmoney(){
        
        global $_W, $_GPC;
        $aid = $_GPC['id'];
        $set = pdo_get('hcstep_activity', array('uniacid'=>$_W['uniacid'],'id'=>$aid));

        $lastday = date('Y-m-d',strtotime("-1 day"));
        $data['yesterday']['success'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'status'=>1,'aid'=>$aid));
        $success = count($data['yesterday']['success']);
        $data['yesterday']['fail'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'status'=>0,'aid'=>$aid));
        $fail = count($data['yesterday']['fail']);
        $data['yesterday']['zong'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'aid'=>$aid));
        $zong = count($data['yesterday']['zong']);

        $jiangjin = $zong * $set['entryfee'] / $success;
        
        $list = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'aid'=>$aid));

        foreach ($list as $k => $v) {
            if($v['status'] == 1){
                $user = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
                if(!empty($user)){
                    $nowmoney = $user['money'] + $jiangjin;
                    $faqian[] = pdo_update('hcstep_users',array('money' => $nowmoney), array('user_id'=>$v['user_id'],'uniacid' =>$_W['uniacid']));
                    $zhuangtai = pdo_update('hcstep_activitylog',array('status' => 2 ,'jiangjin' => $jiangjin), array('id'=>$v['id'],'uniacid' =>$_W['uniacid']));
                }else{
                    $zhuangtai = pdo_update('hcstep_activitylog',array('status' =>-1), array('id'=>$v['id'],'uniacid' =>$_W['uniacid']));
                }
            }
        }

        if ($faqian){
            message('发放成功',$this->createWebUrl('activity'),'success');
        }else{
            message('没有可发放的记录',$this->createWebUrl('activity'),'success');
        }

    }

    public function doWebSettime(){

        global $_W,$_GPC;
        $setup = pdo_get('hcstep_set',array('uniacid'=>$_W['uniacid']));
        $url1 = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=hc_step&do=auto&moneyauto=1';
        $url2 = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=hc_step&do=auto&msgauto=1';

        include $this->template('settime');
    }
    //赢口红
    public function doWebKouhong(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            $data['kouhong_sharetitle'] = $_GPC['kouhong_sharetitle'];
            $data['kouhong_sharepic'] = $_GPC['kouhong_sharepic'];
            $data['kouhong_ids'] = trim($_GPC['kouhong_ids']);
            
            $ishave = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('hcstep_message', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('hcstep_message', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('kouhong'));
            }
        }
        $info = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));

        include $this->template('kouhong');
    }

    //红包树记录
    public function doWebKouhonglog(){
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_kouhonglog',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('hcstep_bushulog',array('uniacid'=>$_W['uniacid']));
        
        foreach ($list as $k => $v) {
           $user = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['time'] = date('Y-m-d H:i:s',$v['createtime']);
           $list[$k]['nick_name'] = $user['nick_name'];
           if(!empty($v['invite_id'])){
              $list[$k]['invite'] = explode(",",$v['invite_id']);
              foreach ($list[$k]['invite'] as $key => $value) {
                  $user2 = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$value));
                  $list[$k]['aaa'][$key]['head_pic'] = $user2['head_pic'];
                  $list[$k]['aaa'][$key]['nick_name'] = $user2['nick_name'];
              }
           }else{
              $list[$k]['invite'] = '';
           }
           
        }     

        include $this->template('kouhonglog');
    }

    //红包树记录
    public function doWebKouhongwin(){
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        $where['status'] = 1;
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_kouhonglog',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        include $this->template('kouhongwin');
    }
    //红包裂变设置
    public function doWebFourhb(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $res['uniacid'] = $_W['uniacid'];
            $res['is_fourhb'] = $_GPC['is_fourhb'];
            $res['fourhb_sharetitle'] = $_GPC['fourhb_sharetitle'];
            $res['fourhb_sharepic'] = $_GPC['fourhb_sharepic'];
            $res['min_bighbmoney'] = $_GPC['min_bighbmoney'];
            $res['max_bighbmoney'] = $_GPC['max_bighbmoney'];
            $res['min_smallhbmoney'] = $_GPC['min_smallhbmoney'];
            $res['max_smallhbmoney'] = $_GPC['max_smallhbmoney'];
            $res['min_hbtxmoney'] = $_GPC['min_hbtxmoney'];
            $res['txinfo'] = $_GPC['txinfo'];
            $res['hbtext1'] = $_GPC['hbtext1'];
            $res['hbtext2'] = $_GPC['hbtext2'];
            $res['fourhb_mainpic'] = $_GPC['fourhb_mainpic'];
            $res['daijiesuo'] = $_GPC['daijiesuo'];
            $res['daikaiqi'] = $_GPC['daikaiqi'];
            $res['yikaiqi'] = $_GPC['yikaiqi'];
            $res['openhbpic'] = $_GPC['openhbpic'];
            $res['fourhb_coin'] = $_GPC['fourhb_coin'];
                      
            $ishave2 = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave2)){
                $aaa = pdo_update('hcstep_message', $res ,array('uniacid'=>$_W['uniacid']));
            }else{
                $aaa = pdo_insert('hcstep_message', $res);
            }
            if(!empty($aaa)){
                message('操作成功',$this->createWebUrl('fourhb'));
            }
        }
        $info = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));
        if(empty($info['fourhb_mainpic'])){
            $info['fourhb_mainpic'] = $_W['siteroot']."addons/hc_step/openhb.png";
        }
        include $this->template('fourhb');
    }
    //红包裂变领取记录
     public function doWebFourhblog(){
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid']; 
        if(!empty($_GPC['user_id'])){
            $where['user_id'] = $_GPC['user_id']; 
        }
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('hcstep_fourhblog',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        foreach ($list as $k => $v) {
           $fuser = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $suser = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['son_id']));
           $list[$k]['fhead_pic'] = $fuser['head_pic'];
           $list[$k]['fnick_name'] = $fuser['nick_name'];
           $list[$k]['shead_pic'] = $suser['head_pic'];
           $list[$k]['snick_name'] = $suser['nick_name'];
           $list[$k]['time'] = date('Y-m-d H:i:s',$v['time']); 
        }   
               

        include $this->template('fourhblog');

    }
    //微信企业付款提现
    public function doWebFourhbwith()
    {
        global $_W, $_GPC;
        $pindex = max(intval($_GPC['page']), 1);
        $psize = 10;
        $status=$_GPC['status'];
        $condition = "";

            if(empty($status)){
                $list=pdo_getslice('hcstep_hbwith', array('uniacid'=>$_W['uniacid']), array($pindex,$psize) , $total , array() , '' , array('status asc','id desc'));
            }else{
                if($status==2){
                    $stact=0;
                }else{
                    $stact=1;
                }
                $list=pdo_getslice('hcstep_hbwith', array('uniacid'=>$_W['uniacid'],'status'=>$stact), array($pindex,$psize) , $total , array() , '' , array('status asc','id desc'));
            }

        for($i=0;$i<count($list);$i++){
            $user=pdo_get('hcstep_users', array('user_id' => $list[$i]['user_id']));
            $list[$i]['nickname']=$user['nick_name'];
            $list[$i]['avatar']=$user['head_pic'];
        }
        $pager = pagination($total, $pindex, $psize);


        include $this->template("fourhbwith");
    }
    public function doWebHbtixian() {
        global $_W,$_GPC;
        //这个操作被定义用来呈现 管理中心导航菜单
        if($_GPC['op']=='send'){
            $tixian=pdo_get('hcstep_hbwith',array('id'=>$_GPC['id']));
            $user=pdo_get('hcstep_users', array('user_id' => $tixian['user_id']));
            $wxchat='';
            $data['openid']=$user['open_id'];
            $data['userid']=1;
            $data['refundid']=time();
            $data['money']=$tixian['money'];
            $aa=$this->wxbuild($data, $wxchat);
            if($aa['result_code']=='SUCCESS'){
                /*$gengxin = array(
                     'finishmoney' => $user['finishmoney']+$data['money'],
                     //'money'       => $data['money'] - $res['money'],
                     'waitmoney'   => $user['waitmoney'] - $data['money']
                );
                pdo_update('hcpdd_users',$gengxin, array('user_id' =>$user['user_id']));*/
                pdo_update('hcstep_hbwith', array('status' => 1,'partner_trade_no'=>$aa['payment_no'],'pay_time'=>time()), array('id'=>$_GPC['id']));
                message('发放成功',$this->createWebUrl('fourhbwith'),'success');
            }else{
                pdo_update('hcstep_hbwith', array('partner_trade_no'=>$aa['0']), array('id'=>$_GPC['id']));
            }
        }
        if($_GPC['op']=='del'){
            pdo_delete('hcstep_hbwith', array('id'=>$_GPC['id']));
        }
        include $this->doWebFourhbwith();
    }

    public function wxbuild($data, $wxchat){
        global $_GPC, $_W;
        $account = pdo_get('account_wxapp', array('uniacid' => $_W['uniacid']));
        $wxapp = pdo_get('uni_settings', array('uniacid' => $_W['uniacid']));
        $payment = unserialize($wxapp['payment']);
        $mch_id = $payment['wechat']['mchid'];;
        $signkey = $payment['wechat']['signkey'];//支付密钥
        $payurl = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $appid = $account['key'];
        $mchid = $mch_id;
        //判断有没有CA证书及支付信息
        if(empty($wxchat['api_cert']) || empty($wxchat['api_key']) || empty($wxchat['api_ca']) || empty($wxchat['appid']) || empty($wxchat['mchid'])){
            $wxchat['appid'] = $appid;
            $wxchat['mchid'] = $mchid;
            $wxchat['api_cert'] = dirname(__FILE__).'/cert/apiclient_cert'.$_W['uniacid'].'.pem';
            $wxchat['api_key'] = dirname(__FILE__).'/cert/apiclient_key'.$_W['uniacid'].'.pem';
            //$wxchat['api_ca'] = dirname(__FILE__).'/cert/rootca'.$_W['uniacid'].'.pem';
        }
        $webdata = array(
            'mch_appid' => $wxchat['appid'],
            'mchid'    => $wxchat['mchid'],
            'nonce_str' => rand(1,88888888).time(),
            'partner_trade_no'  => $data['refundid'], //商户丁单号，需要唯一
            'openid'    => $data['openid'],
            'check_name'=> 'NO_CHECK', //OPTION_CHECK不强制校验真实姓名, FORCE_CHECK：强制 NO_CHECK：
            'amount'    => $data['money'] * 100, //付款金额单位为分
            'desc'      => empty($data['desc'])? '佣金收入' : $data['desc'],
            'spbill_create_ip' => $this->getip(),
        );
        foreach ($webdata as $k => $v) {
            $tarr[] =$k.'='.$v;
        }
        sort($tarr);
        $sign = implode($tarr, '&');
        $sign .= '&key='.$signkey;
        $webdata['sign']=strtoupper(md5($sign));
        $wget = $this->array2xml($webdata);
        $res = $this->http_post($payurl, $wget, $wxchat);
        if(!$res){
            return array('status'=>1, 'msg'=>"Can't connect the server" );
        }
        $content = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
        if(strval($content->return_code) == 'FAIL'){
            return array('status'=>1, 'msg'=>strval($content->return_msg));
        }
        if(strval($content->result_code) == 'FAIL'){
            return array('status'=>1, 'msg'=>strval($content->err_code),':'.strval($content->err_code_des));
        }
        $rdata = array(
            'mch_appid'        => strval($content->mch_appid),
            'mchid'            => strval($content->mchid),
            'device_info'      => strval($content->device_info),
            'nonce_str'        => strval($content->nonce_str),
            'result_code'      => strval($content->result_code),
            'partner_trade_no' => strval($content->partner_trade_no),
            'payment_no'      => strval($content->payment_no),
            'payment_time'    => strval($content->payment_time),
        );
        return $rdata;
    }

    public function http_post($url, $param, $wxchat) {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        if (is_string($param)) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        if($wxchat){
            curl_setopt($oCurl,CURLOPT_SSLCERT,$wxchat['api_cert']);
            curl_setopt($oCurl,CURLOPT_SSLKEY,$wxchat['api_key']);
            curl_setopt($oCurl,CURLOPT_CAINFO,$wxchat['api_ca']);
        }
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        } 
    }

    /**
     * 将一个数组转换为 XML 结构的字符串
     * @param array $arr 要转换的数组
     * @param int $level 节点层级, 1 为 Root.
     * @return string XML 结构的字符串
     */
    public function array2xml($arr, $level = 1) {
        $s = $level == 1 ? "<xml>" : '';
        foreach($arr as $tagname => $value) {
            if (is_numeric($tagname)) {
                $tagname = $value['TagName'];
                unset($value['TagName']);
            }
            if(!is_array($value)) {
                $s .= "<{$tagname}>".(!is_numeric($value) ? '<![CDATA[' : '').$value.(!is_numeric($value) ? ']]>' : '')."</{$tagname}>";
            } else {
                $s .= "<{$tagname}>" . $this->array2xml($value, $level + 1)."</{$tagname}>";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s."</xml>" : $s;
    }
    public function getip() {
        static $ip = '';
        $ip = $_SERVER['REMOTE_ADDR'];
        if(isset($_SERVER['HTTP_CDN_SRC_IP'])) {
            $ip = $_SERVER['HTTP_CDN_SRC_IP'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] AS $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        }
        return $ip;
    }

    public function doWebpayset(){
        global $_W, $_GPC;
        if(!empty($_GPC['cert'])){
                file_put_contents(dirname(__FILE__)."/cert/apiclient_cert".$_W['uniacid'].".pem",$_GPC['cert']);
            }
        if(!empty($_GPC['key'])){
                file_put_contents(dirname(__FILE__)."/cert/apiclient_key".$_W['uniacid'].".pem",$_GPC['key']);
            }
        /*if(!empty($_GPC['rootca'])){
                file_put_contents(dirname(__FILE__)."/cert/rootca".$_W['uniacid'].".pem",$_GPC['rootca']);
            }*/
        include $this->template("payset");
    }
 //导出excel表格
    public function doWebexportDayInner(){
        global $_W,$_GPC;
        $list = pdo_getall('hcstep_orders',array('uniacid'=>$_W['uniacid'],'status'=>0,'type <'=>10));
        foreach ($list as $k => $v) {
           $goods = pdo_get('hcstep_goods',array('id'=>$v['goods_id'],'uniacid'=>$_W['uniacid']));
           $list[$k]['goods_name'] = $goods['goods_name'];
           $list[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
        }   
        /*foreach ($innerdata as $v) {
            if($v['mobile']){               
              $list[] = $v['mobile'];
            }
        }*/
        $table = '';
        $table .= "<table>
            <thead>
                <tr>
                    <th class='name'>联系人</th>
                    <th class='name'>邮编</th>
                    <th class='name'>地址</th>
                    <th class='name'>下单时间</th>
                    <th class='name'>商品</th>
                </tr>
            </thead>
            <tbody>";
        foreach ($list as $v) {
               $table .= "<tr><td class='name'>".$v['userName']."</td>
                              <td class='name'>".$v['postalCode']."</td>
                              <td class='name'>".$v['provinceName'].$v['cityName'].$v['countyName'].$v['detailInfo']."</td>
                              <td class='name'>".$v['time']."</td>
                              <td class='name'>".$v['goods_name']."</td></tr>";            
        }
        $table .= "</tbody>
        </table>";
//通过header头控制输出excel表格
        header("Pragma: public");  
        header("Expires: 0");  
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");  
        header("Content-Type:application/force-download");  
        header("Content-Type:application/vnd.ms-execl");  
        header("Content-Type:application/octet-stream");  
        header("Content-Type:application/download");;  
        header('Content-Disposition:attachment;filename="待发货订单.xls"');  
        header("Content-Transfer-Encoding:binary");          
        echo $table;
    }
//待发货奖品
    public function doWebexportDayInner2(){
        global $_W,$_GPC;
        $list = pdo_getall('hcstep_winlog',array('uniacid'=>$_W['uniacid'],'status'=>0));
        foreach ($list as $k => $v) {
           $goods = pdo_get('hcstep_awards',array('id'=>$v['goods_id'],'uniacid'=>$_W['uniacid']));
           $list[$k]['goods_name'] = $goods['goods_name'];
           $list[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
        }   
        /*foreach ($innerdata as $v) {
            if($v['mobile']){               
              $list[] = $v['mobile'];
            }
        }*/
        $table = '';
        $table .= "<table>
            <thead>
                <tr>
                    <th class='name'>联系人</th>
                    <th class='name'>邮编</th>
                    <th class='name'>地址</th>
                    <th class='name'>下单时间</th>
                    <th class='name'>商品</th>
                </tr>
            </thead>
            <tbody>";
        foreach ($list as $v) {
               $table .= "<tr><td class='name'>".$v['userName']."</td>
                              <td class='name'>".$v['postalCode']."</td>
                              <td class='name'>".$v['provinceName'].$v['cityName'].$v['countyName'].$v['detailInfo']."</td>
                              <td class='name'>".$v['time']."</td>
                              <td class='name'>".$v['goods_name']."</td></tr>";            
        }
        $table .= "</tbody>
        </table>";
//通过header头控制输出excel表格
        header("Pragma: public");  
        header("Expires: 0");  
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");  
        header("Content-Type:application/force-download");  
        header("Content-Type:application/vnd.ms-execl");  
        header("Content-Type:application/octet-stream");  
        header("Content-Type:application/download");;  
        header('Content-Disposition:attachment;filename="待发货订单.xls"');  
        header("Content-Transfer-Encoding:binary");          
        echo $table;
    }

    public function doMobileAuto(){

        /*ignore_user_abort(true);
        set_time_limit(0);
        $interval=60*60*12;*/
        global $_W,$_GPC;
        $setup = pdo_get('hcstep_set',array('uniacid'=>$_W['uniacid']));
        /*if(!empty($_GPC['moneyauto'])){
            pdo_update('hcstep_set',array('moneyauto'=>$_GPC['moneyauto']),array('uniacid'=>$_W['uniacid']));
        }
        if(!empty($_GPC['msgauto'])){
            pdo_update('hcstep_set',array('msgauto'=>$_GPC['msgauto']),array('uniacid'=>$_W['uniacid']));
        }*/
        //do{
            $config = pdo_get('hcstep_set',array('uniacid'=>$_W['uniacid']));
            if($_GPC['moneyauto']==1){
                $set = pdo_getall('hcstep_activity', array('uniacid'=>$_W['uniacid']));

                foreach ($set as $key => $val) {
                    $lastday = date('Y-m-d',strtotime("-1 day"));
                    $data['yesterday']['success'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'status'=>1,'aid'=>$val['id']));
                    $success = count($data['yesterday']['success']);
                    $data['yesterday']['fail'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'status'=>0,'aid'=>$val['id']));
                    $fail = count($data['yesterday']['fail']);
                    $data['yesterday']['zong'] = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'aid'=>$val['id']));
                    $zong = count($data['yesterday']['zong']);

                    $jiangjin = $zong * $val['entryfee'] / $success;
                    
                    $list = pdo_getall('hcstep_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'aid'=>$val['id']));

                    foreach ($list as $k => $v) {
                        if($v['status'] == 1){
                            $user = pdo_get('hcstep_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
                            if(!empty($user)){
                                $nowmoney = $user['money'] + $jiangjin;
                                $faqian[] = pdo_update('hcstep_users',array('money' => $nowmoney), array('user_id'=>$v['user_id'],'uniacid' =>$_W['uniacid']));
                                $zhuangtai = pdo_update('hcstep_activitylog',array('status' => 2 ,'jiangjin' => $jiangjin), array('id'=>$v['id'],'uniacid' =>$_W['uniacid']));
                            }else{
                                $zhuangtai = pdo_update('hcstep_activitylog',array('status' =>-1), array('id'=>$v['id'],'uniacid' =>$_W['uniacid']));
                            }
                        }
                    }
                }               
                /*flush();
                ob_flush();
                sleep($interval);

                file_put_contents(dirname(__FILE__)."/money.txt",$interval);*/
            }
            if($_GPC['msgauto']==1){
                ob_end_clean();
                global $_GPC, $_W;
                $users=pdo_getall('hcstep_users', array('uniacid' => $_W['uniacid']));
                for($i=0;$i<count($users);$i++){
                    $formid=pdo_getall('hcstep_formid', array('user_id' => $users[$i]['user_id'],'status'=>0), array() , '',array('id DESC') , array());
                    if(!empty($formid[0])){
                        $aa=$this->getMessage($formid[0]);
                    }
                }               
                /*flush();
                ob_flush();
                sleep($interval);

                file_put_contents(dirname(__FILE__)."/message.txt",$interval);*/
            }
        //}while(true);
    }

    //模板消息
    public function doWebMsg(){
        ob_end_clean();
        global $_GPC, $_W;
        $users=pdo_getall('hcstep_users', array('uniacid' => $_W['uniacid']));
        for($i=0;$i<count($users);$i++){
            $formid=pdo_getall('hcstep_formid', array('user_id' => $users[$i]['user_id'],'status'=>0), array() , '',array('id DESC') , array());
            if(!empty($formid[0])){
                $aa=$this->getMessage($formid[0]);
            }
        }
        echo "发送成功，请关闭";
    }

    //发奖模板消息
    public function doWebFajiang(){
        ob_end_clean();
        global $_GPC, $_W;
        $users=pdo_getall('hcstep_users', array('uniacid' => $_W['uniacid']));
        for($i=0;$i<count($users);$i++){
            $formid=pdo_getall('hcstep_formid', array('user_id' => $users[$i]['user_id'],'status'=>0), array() , '',array('id DESC') , array());
            if(!empty($formid[0])){
                $aa=$this->getMessage($formid[0]);
            }
        }
        echo "发送成功，请关闭";
    }

    public function getMessage($formid) {
        global $_GPC, $_W;
        $user=pdo_get('hcstep_users', array('user_id' => $formid['user_id']));
        $setup = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));      
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$this->wx_get_token();
        $data['touser']=$user['open_id'];
        $data['template_id']=$setup['msgid'];
        //$setup=json_decode($setup,true);
        $data['form_id']=$formid['formid'];
        $data['page']='hc_step/pages/index/index';
        $data['data']['keyword1']['value']=$setup['keyword1'];
        $data['data']['keyword1']['color']='#173177';
        $data['data']['keyword2']['value']=$setup['keyword2'];
        $data['data']['keyword2']['color']='#173177';
        $data['data']['keyword3']['value']=$setup['keyword3'];
        $data['data']['keyword3']['color']='#000000';
        $json = json_encode($data);
        $dete=$this->api_notice_increment($url,$json);
        pdo_update('hcstep_formid', array('status' => 1), array('id' => $formid['id']));
        return $dete;
    }

    public function fahuotpl($formid){
        global $_GPC, $_W;
        $user=pdo_get('hcstep_users', array('user_id' => $formid['user_id']));
        $info = pdo_get('hcstep_orders',array('id'=>$formid['orderid']));
        $goods = pdo_get('hcstep_goods',array('id'=>$info['goods_id']));
        $setup = pdo_get('hcstep_message', array('uniacid' => $_W['uniacid']));      
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$this->wx_get_token();
        $data['touser']=$user['open_id'];
        $data['template_id']=$setup['fahuomsgid'];
        //$setup=json_decode($setup,true);
        $data['form_id']=$formid['formid'];
        $data['page']='hc_step/pages/index/index';
        $data['data']['keyword1']['value']=$info['expressname'];
        $data['data']['keyword1']['color']='#173177';
        $data['data']['keyword2']['value']=date("Y-m-d",$info['fahuotime']);
        $data['data']['keyword2']['color']='#173177';
        $data['data']['keyword3']['value']=$goods['goods_name'];
        $data['data']['keyword3']['color']='#000000';
        $data['data']['keyword4']['value']=$info['express'];
        $data['data']['keyword4']['color']='#000000';
        $json = json_encode($data);
        $dete=$this->api_notice_increment($url,$json);
        pdo_update('hcstep_formid', array('status' => 1), array('id' => $formid['id']));
        return $dete;
    }

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

    function wx_get_token() {
        global $_GPC, $_W;
        $appid=$_W['account']['key'];
        $AppSecret=$_W['account']['secret'];
        $res = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$AppSecret);
        $res = json_decode($res, true);
        $token = $res['access_token'];
        return $token;
    }



}