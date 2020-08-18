<?php
/**
 * 使用Dijkstra算法计算从起始点到其他任一节点的最短路径长度 
 */


/**
 * 这是一个节点类
 */
class Node {
    //标记该节点是否已得到最短路径
    var $isMarked = false;

    //标记该节点离起始节点的最短路径长度,等于0表示起点
    var $minPathDistance = PHP_INT_MAX;

    //标记节点名称
    var $name;

    public function __construct($name, $distance=null) {
        $this->name = $name;
        if (!is_null($distance) && $distance === 0) {
            $this->minPathDistance = $distance;
            $this->isMarked = true;
        }
        
    }
}

class Graph {
    //未访问到的节点
    private $unVasited = [];

    //节点之间的距离，是一个邻接矩阵，保存任意两个节点间的距离
    private $edges;

    //所有节点
    private $nodes;

    //起始节点的下标
    private $startNodeIndex;

    public function __construct(array $nodes, array $edges, int $startNodeIndex) {
        $this->nodes = $nodes;
        $this->edges = $edges;
        $this->startNodeIndex = $startNodeIndex;
        $this->initial();
    }

    //标记起点，构造未访问节点数组
    private function initial() 
    {  
        //在邻接矩阵里查找与起始节点相邻的节点（找路径最短的）
        $arrEdge = $this->edges[$this->startNodeIndex];
        $min = min(array_filter($arrEdge));//过滤掉路径长度为0的本元素

        $nodeLen = count($arrEdge);

        //构造未访问到的节点
        for($i=0; $i<$nodeLen; ++$i) {
            if ($i == $this->startNodeIndex) {
                continue;
            }
            if ($arrEdge[$i] < PHP_INT_MAX) {
                if ($arrEdge[$i] == $min) {//将找到的节点标注已获取最短路径
                    $this->nodes[$i]->isMarked = true;  
                    $this->nodes[$i]->minPathDistance = $min;                  
                } else {
                    $this->nodes[$i]->minPathDistance = $arrEdge[$i];
                    $this->unVisited[$i] = $this->nodes[$i];
                }
            } else {
                $this->unVisited[$i] = $this->nodes[$i];
            }
        } 
        $this->stepPrint();      
    }

    /**
     * 处理所有未访问的节点，成功后从未访问数组中删除
     * @return 
     */
    public function search()
    {
        //将未访问到的节点遍历一遍，处理完毕后将找到的节点从$unVisited中删除
        while (!empty($this->unVisited)) {           
            //查询到与已标记节点相邻的节点，如果有多个相邻的节点，则比较各节点路径长度
            $indexVisited = [];    
            foreach ($this->nodes as $k => $v) {
                if ($this->nodes[$k]->isMarked) {
                    $indexVisited[] = $k;
                }
            }
            
            $minLen = PHP_INT_MAX;
            $minIndex = 0;
            foreach ($this->unVisited as $i=>$node) {
                //更新与已标记节点相邻的节点的距离
                $currLen = PHP_INT_MAX;
                foreach ($indexVisited as $j) {
                    if ($this->edges[$i][$j] > 0 && $this->edges[$i][$j] < PHP_INT_MAX) {//相邻
                        // echo "i:{$i},j={$j}, currLen={$currLen}<br/>";
                        if ($currLen > $this->edges[$i][$j] + $this->nodes[$j]->minPathDistance) {
                             $currLen = $this->edges[$i][$j] + $this->nodes[$j]->minPathDistance;
                        }
                    }
                }
                $this->unVisited[$i]->minPathDistance = $currLen;

                //找到路径最短的那条线
                if ($minLen > $node->minPathDistance && $node->minPathDistance>0) {
                    $minLen = $node->minPathDistance;
                    $minIndex = $i;
                }
            }
            //修改即将删除的节点为已标记
            $this->nodes[$minIndex]->isMarked = true;
            //更新最短线上的节点的路径长度
            $this->nodes[$minIndex]->minPathDistance = $minLen;
            //从未访问节点中删除已查询到的节点
            unset($this->unVisited[$minIndex]);
            $this->stepPrint();
            
        }
    }

    /**
     * 打印每一步的结果
     * @return [type] [description]
     */
    private function stepPrint()
    {
        $visited = [];
        foreach ($this->nodes as $k => $v) {
            if ($this->nodes[$k]->isMarked) {
                $visited[] = $v->name;
            }
        }
        $unvisit = [];
        foreach($this->unVisited as $k=>$v) {
            $unvisit[] = $v->name;
        }
        echo "S:".json_encode($visited)."<br/>";
        echo "U:".json_encode($unvisit)."<br/>";
        echo '<br/>';
    }

    /**
     * 打印最终结果
     * @return [type] [description]
     */
    public function printGraph()
    {
        $len = count($this->nodes);
        for ($i=0; $i<$len; ++$i) {
            echo "{$this->nodes[$i]->name}({$this->nodes[$i]->minPathDistance}),";
        }
        echo "<br/>";
    }
}



//构造节点数组
$nodes = [];
$node = new Node('A');
$nodes[] = $node;
$node = new Node('B');
$nodes[] = $node;
$node = new Node('C');
$nodes[] = $node;
$node = new Node('D', 0);//D节点是起始节点
$nodes[] = $node;
$startNodeIndex = count($nodes)-1;   //起始节点的下标
$node = new Node('E');
$nodes[] = $node;
$node = new Node('F');
$nodes[] = $node;
$node = new Node('G');
$nodes[] = $node;

//节点之间是否有弧边，弧边的长度,0-6分别对应nodes中的下标
$max = PHP_INT_MAX;
//节点A      A     B     C     D     E     F    G 
$edges[0] = [0,    12,  $max, $max, $max,  16,  14 ];
$edges[1] = [12,   0,   10,   $max, $max,   7,  $max];
$edges[2] = [$max, 10,  0,      3,    5,   6,   $max];
$edges[3] = [$max, $max, 3,     0,    4,  $max, $max];
$edges[4] = [$max, $max, 5,     4,    0,   2,     8 ];
$edges[5] = [16,   7,    6,   $max,   2,   0,     8 ];
$edges[6] = [14,   $max, $max, $max,  8,   9,     0 ];


$graph = new Graph($nodes, $edges, $startNodeIndex);
$graph->search();
$graph->printGraph();

