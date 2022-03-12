<?php
class baiduseo_link{
    const url='';
    const base= '';
    const timeout = '120';
    const redirection = '10';
    const connecttimeout ='120';
    const sslverify = false;
    
    function __construct($data) {
        $this->base();
        $this->url = $data['http'].$data['url'].$this->base.$data['keywords'];
    }
    public function url(){
        return $this->url;
    }
    public function data(){
        $url = $this->url();
        if($this->type){
            $url = $url."?url={'www.seohnzz.com'}&url1=".md5('www.seohnzz.com'.BAIDUSEO_SALT).'&type=1';
        }else{
            $url = $url."?url={'www.seohnzz.com'}&url1=".md5('www.seohnzz.com'.BAIDUSEO_SALT);
        }
        $defaults = array(
            'timeout' =>baiduseo_link::timeout,
            'connecttimeout'=>baiduseo_link::connecttimeout,
            'redirection' => baiduseo_link::redirection,
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'sslverify' => baiduseo_link::sslverify,
        );
        $list = wp_remote_get($url,$defaults);
        if(!is_wp_error($list)){
            $list = wp_remote_retrieve_body($list);
            $data = json_decode($list,'true');
        }else{
            $data = [];
        }
        return $data;
    }
    public function base(){
        $this->base ='/'.BAIDUSEO_BASE.'/Baidu/';
    }
}