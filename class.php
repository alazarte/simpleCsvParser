<?php
class ParseCsv
{
    private $timestamp;

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

    public function readCsv($file)
    {
        if (($handle = fopen($file,"r")) !== FALSE)
        {
            $header = ($data = fgetcsv($handle,50,",") !== FALSE) ? $data : array(null);
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

    public function write()
    {
        foreach($this->timestamp as $key1 => $subarray)
        {
            foreach($subarray as $key2 => $operation)
            {
                foreach($operation as $key3 => $val)
                {
                    file_put_contents("results_$key1.csv", "$key3,$key2,$key1,$val\n",FILE_APPEND);
                }
            }
        }
    }

    public function show()
    {
        foreach($this->timestamp as $key1 => $subarray)
        {
            foreach($subarray as $key2 => $operation)
            {
                foreach($operation as $key3 => $val)
                {
                    echo "$key3,$key2,$key1,$val\n";
                }
            }
        }
    }

}

$p = new ParseCsv();
$p->readCsv($argv[1]);
$p->write();
?>
