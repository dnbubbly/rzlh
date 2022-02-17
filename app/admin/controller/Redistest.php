<?php
namespace app\admin\controller;

use app\BaseController;
use think\facade\Queue;

class Redistest extends BaseController
{
    public function index()
    {
//         echo phpinfo();exit();
        // 1.当前任务由哪个类来负责处理
        // 当轮到该任务时，系统将生成该类的实例，并调用其fire方法
        $jobHandlerClassName = 'app\admin\controller\JobMessage';
        
        // 2.当任务归属的队列名称，如果为新队列，会自动创建
        $jobQueueName = "helloJobQueue";
        
        // 3.当前任务所需业务数据，不能为resource类型，其他类型最终将转化为json形式的字符串
        $jobData = ['ts' => time(), 'bizId' => uniqid(), 'a' => 1];
        
        // 4.将该任务推送到消息列表，等待对应的消费者去执行
        // 入队列，later延迟执行，单位秒，push立即执行
        $isPushed = Queue::later(10, $jobHandlerClassName, $jobData, $jobQueueName);
        
        // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
        if ($isPushed !== false) {
            echo '推送成功';
        } else {
            echo '推送失败';
        }
    }
    
    /**
     * 多模块延迟队列实现
     */
    public function pay(){
        $orderData = [
            "orderId" => uniqid()
        ];
        $isPushed = Queue::later(60, "app\api\controller\PayMessage", json_encode($orderData), "helloJobQueue");
        if ($isPushed)echo "\n 订单支付成功 \n";
        
        $email = [
            "email" => "1234567890@qq.com"
        ];
        $isPushed = Queue::later(120, "app\api\controller\EmailMessage", json_encode($email), "helloJobQueue");
        if ($isPushed)echo "\n 邮件发送成功 \n";
    }
}