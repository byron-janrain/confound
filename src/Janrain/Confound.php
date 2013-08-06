<?php
namespace Janrain;

class Confound implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $data;

    public function __construct(\ArrayObject $data = null)
    {
        $this->data = new \ArrayObject();
        self::flatten($this->data, $data);
    }

    public function offsetGet($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function offsetSet($key, $val) {
        $this->data[$key] = $val;
    }

    public function offsetUnset($key)
    {
        #config data may be settable to nothing, but it's not playing nice to remove keys
    }

    public function offsetExists($key)
    {
        return true;
    }

    protected static $REGISTRY = [];

    protected static function flatten(&$out, &$crawl = null, $key = '')
    {
        $crawl = $crawl ?: $out;
        if (is_array($crawl) || is_object($crawl)) {
            foreach ($crawl as $k => &$v) {
                if (is_array($crawl)) {
                    $newKey = $key ? "{$key}[{$k}]" : "[{$k}]";
                } else {
                    $newKey = $key ? "{$key}.{$k}" : $k;
                }
                self::flatten($out, $v, $newKey);
            }
        } else {
            $out[$key] = $crawl;
        }
    }

    public function getIterator()
    {
        return $this->data->getIterator();
    }

    public function count()
    {
        return count($this->data);
    }

    public function getAO()
    {
        return $this->data;
    }
}

$str1 = <<<END
{
    "key":"value",
    "objectkey" : {
        "okey" : "ovalue",
        "oakey" :[
            "value",
            {"key":"value"}
        ]
    },
    "arraykey":[
        "avalue",
        {"aokey":"aovalue"}
    ]
}
END;

$str2 = <<<END
[
    "key",
    {
        "objectkey":"objectval",
        "objectkey2":"objectval2"
    },
    1,
    {
        "nested key": {
            "nestedobjkey":"nestedobjkeyval"
        },
        "nested arr": [
            1,
            "3",
            {"nestedobjkey2":"nestobjval2"}
        ]
    }
]
END;
/*
[key] = 'value'
[objectkey->okey] = 'ovalue'
[objectkey->oakey[0]] = value
[objectkey->oakey[1]] = {key->value}
[objectkey->oakey[1]->key] = value
[arraykey[0]]
*/
$native = new \ArrayObject(json_decode($str2));
//echo \json_last_error_msg();
//var_dump($native);

$config = new Confound();

$config = new Confound($native);
//$config['objectkey.oakey[0]'] = 'value2';
var_dump($config->getAO());
var_dump($config['objectkey.oakey[0]']);

interface Decoder{}
class JsonDecoder implements Decoder{}
class XmlDecoder implements Decoder{}
class IniDecoder implements Decoder{}

/*

Janrain\Counfound::createFromFile(new SPLFileObject('path'))


*/
