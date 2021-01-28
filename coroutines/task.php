<?php
/**
 为了实现多任务调度, 首先实现“任务” -- 一个用轻量级的包装的协程函数类
 这是一个任务类，通过任务id来区分不同的任务，
 通过迭代器来发送或接收数据
 **/

class Task {
    protected $taskId;
    protected $coroutine;
    protected $sendValue = null;
    protected $beforeFirstYield = true;

    //构造函数
    public function __construct($taskId, Generator $coroutine) {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    //TaskId读取器
    public function getTaskId() {
        return $this->taskId;
    }

    //SendValue写入
    public function setSendValue($sendValue) {
        $this->sendValue = $sendValue;
    }

    //发送或接收迭代器中的值
    public function run() {
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }

    //当generator中的元素遍历完毕时，valid()返回false
    public function isFinished() {
        return !$this->coroutine->valid();
    }
}