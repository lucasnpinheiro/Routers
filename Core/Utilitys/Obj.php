<?php

namespace Core\Utilitys;

class Obj implements \ArrayAccess, \Countable, \Iterator, \Serializable {

    private $items = [];
    private $position = 0;
    private $pathSeparator = '.';

    /**
     * ArrayFinder constructor.
     *
     * @param array $items Content of the array
     */
    public function __construct(array $items = []) {
        //$this->items = $this->object_to_array($items);
        $this->items = $items;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset) {
        if (strpos($offset, $this->pathSeparator) !== false) {

            $explodedPath = explode($this->pathSeparator, $offset);
            $lastOffset = array_pop($explodedPath);

            $offsetExists = false;
            $containerPath = implode($this->pathSeparator, $explodedPath);

            $this->callAtPath($containerPath, function($container) use ($lastOffset, &$offsetExists) {
                $offsetExists = isset($container[$lastOffset]);
            });

            return $offsetExists;
        } else {
            return isset($this->items[$offset]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset) {
        if (!$this->offsetExists($offset)) {
            $this->offsetSet($offset, new Obj());
        }
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset) {
        $path = explode($this->pathSeparator, $offset);
        $pathToUnset = array_pop($path);

        $this->callAtPath(implode($this->pathSeparator, $path), function(&$offset) use (&$pathToUnset) {
            unset($offset[$pathToUnset]);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function count() {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function current() {
        $keys = array_keys($this->items);
        return $this->items[$keys[$this->position]];
    }

    /**
     * {@inheritdoc}
     */
    public function next() {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function key() {
        $keys = array_keys($this->items);
        return $keys[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function valid() {
        $keys = array_keys($this->items);
        return isset($keys[$this->position]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind() {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize() {
        return serialize($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($items) {
        $this->items = unserialize($items);
    }

    /**
     * Change the path separator of the array wrapper.
     *
     * By default, the separator is: .
     *
     * @param string $separator Separator to set.
     *
     * @return ArrayFinder Current instance.
     */
    public function changeSeparator($separator) {
        $this->pathSeparator = $separator;
        return $this;
    }

    /**
     * Return a value from the array corresponding to the path.
     * If the path is not set in the array, then $default is returned.
     *
     * ex:
     * $a = ['a' => ['b' => 'yeah']];
     * echo $this->get('a.b'); // yeah
     * echo $this->get('a.b.c', 'nope'); // nope
     *
     * @param string|int|null $path Path to the value. If null, return all the items.
     * @param mixed $default Default value to return when path is not contained in the array.
     *
     * @return mixed|null Value on the array corresponding to the path, null if the key does not exist.
     */
    public function get($path = null, $default = null) {
        if ($path === null) {
            return $this->items;
        }

        $value = $default;
        $this->callAtPath($path, function(&$offset) use (&$value) {
            $value = $offset;
        });

        return $value;
    }

    /**
     * Insert a value to the array at the specified path.
     *
     * ex:
     * $this->set('a.b', 'yeah); // ['a' => ['b' => 'yeah']]
     *
     * @param string $path Path where the values will be insered.
     * @param mixed $value Value ti insert.
     *
     * @return ArrayFinder Current instance.
     */
    public function set($path, $value) {
        if ($value === 'false' OR $value === 'FALSE') {
            $value = FALSE;
        } else if ($value === 'true' OR $value === 'TRUE') {
            $value = TRUE;
        } else if ($value === 'null' OR $value === 'NULL') {
            $value = NULL;
        }
        $this->callAtPath($path, function(&$offset) use ($value) {
            $offset = $value;
        }, true);

        return $this;
    }

    private function callAtPath($path, callable $callback, $createPath = false, &$currentOffset = null) {
        if ($currentOffset === null) {
            $currentOffset = &$this->items;

            if (is_string($path) && $path == '') {
                $callback($currentOffset);
                return;
            }
        }

        $explodedPath = explode($this->pathSeparator, $path);
        $nextPath = array_shift($explodedPath);

        if (!isset($currentOffset[$nextPath])) {
            if ($createPath) {
                $currentOffset[$nextPath] = [];
            } else {
                return;
            }
        }

        if (count($explodedPath) > 0) {
            $this->callAtPath(implode($this->pathSeparator, $explodedPath), $callback, $createPath, $currentOffset[$nextPath]);
        } else {
            @$callback($currentOffset[$nextPath]);
        }
    }

    public function object_to_array($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }

    public function __get($name) {
        return $this->offsetGet($name);
    }

    public function __set($name, $value) {
        return $this->offsetSet($name, $value);
    }

    public function __isset($name) {
        return isset($this->itens[$name]);
    }

    public function __unset($name) {
        unset($this->itens[$name]);
    }

}
