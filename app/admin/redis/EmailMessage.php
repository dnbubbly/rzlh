<?php

namespace app\admin\controller;


use think\queue\Job;

class EmailMessage
{
    public function fire(Job $job, $data){
        $data = json_decode($data, true);
        if ($this->doJob($data)){
            $job->delete();
        }else{
            if ($job->attempts() > 3){
                print_r("\n 邮件发送超时:" . $data['orderId'] . '\n ');
                $job->delete();
            }
        }
    }
    
    public function doJob($data){
        print_r("\n 发送邮件:" . $data['email'] .'\n ');
        return true;
    }
}