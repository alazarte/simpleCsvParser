<?php
class ParseCsv
{
    private $timestamp, $header;

    function __construct()
    {
        $this->timestamp = array();
    }

    private function newTimestamp($key)
    {
        $this->timestamp[$key] = array();
    }

    private function adds($data)
    {
        if(isset($this->timestamp[$data[3]]["sums"][$data[0].",".$data[1]]))
            $this->timestamp[$data[3]]["sums"][$data[0].",".$data[1]] += $data[4];
        else
            $this->timestamp[$data[3]]["sums"][$data[0].",".$data[1]] = $data[4];
    }

    private function counts($data)
    {
        if(isset($this->timestamp[$data[3]]["count"][$data[0].",".$data[1]]))
            $this->timestamp[$data[3]]["count"][$data[0].",".$data[1]] ++;
        else
            $this->timestamp[$data[3]]["count"][$data[0].",".$data[1]] = 1;
    }

    private function averages()
    {
        foreach($this->timestamp as $tms => $tuple)
        {
            foreach($tuple["count"] as $dev_obj => $count)
            {
                $this->timestamp[$tms]["avg"][$dev_obj] = $this->timestamp[$tms]["sums"][$dev_obj] / $count;
            }
        }
        unset($this->timestamp["count"]);
    }

    public function readCsv($file)
    {
        if (($handle = fopen($file,"r")) !== FALSE)
        {
            $this->header = ($data = fgetcsv($handle,50,",") !== FALSE) ? $data : array(null);
            while(($data = fgetcsv($handle,50,",")) !== FALSE)
            {
                if(array(null) !== $data)
                {
                    $this->adds($data);
                    $this->counts($data);
                }
            }
        }
    }

    private function cleanFiles()
    {
        foreach(array_keys($this->timestamp) as $key)
        {
            file_put_contents("../data/results_$key.csv","");
        }
    }

    private function writeHeaders()
    {
        foreach(array_keys($this->timestamp) as $key)
        {
            file_put_contents("../data/results_$key.csv","");
        }
    }

    public function write()
    {
        $this->cleanFiles();
        $this->writeHeader();
        foreach($this->timestamp as $tms => $data)
        {
            foreach($data as $op_type => $tuple)
            {
                foreach($tuple as $dev_obj => $val)
                {
                    file_put_contents("../data/results_$tms.csv", "$dev_obj,$op_type,$tms,$val\n",FILE_APPEND);
                }
            }
        }
    }
}

$p = new ParseCsv();
$p->readCsv($argv[1]);
$p->write();
?>
