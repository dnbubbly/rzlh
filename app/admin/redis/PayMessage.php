<?php

namespace app\admin\controller;


use think\queue\Job;

class PayMessage
{
    public function fire(Job $job, $data){
        $data = json_decode($data, true);
        if ($this->doJob($data)){
            $job->delete();
        }else{
            if ($job->attempts() > 3){
                print_r("订单超时:" . $data['orderId']);
                $job->delete();
            }
        }
    }
    
    public function doJob($data){
        print_r("发送支付成功通知:" . $data['orderId'] );
        return true;
    }
}