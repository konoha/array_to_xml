<?php
    
    namespace johnkzn\array_to_xml\library;

    use XMLWriter;
    
    class arrayToXml extends XMLWriter
    {
        private $_newdoc = false;
        private $_startNewDoc = false;

        public function __construct(array $config = [])
        {
            $this->openMemory();
            $this->configure($config);
        }

        public function load($in)
        {
            foreach ($in as $element) {
                if (!is_array($element) || !isset($element['tag'])) {
                    continue;
                }
                $tag = $element['tag'];
                $attributes = [];
                if (isset($element['attributes']) && is_array($element['attributes'])) {
                    $attributes = $element['attributes'];
                }
                if (isset($element['content'])) {
                    $content = $element['content'];
                } elseif (isset($element['elements']) && is_array($element['elements'])) {
                    $content = $element['elements'];
                }
                $this->startElement($tag);
                if (is_array($attributes)) {
                    foreach ($attributes as $attribute => $value) {
                        $this->writeAttribute($attribute, $value);
                    }
                }
                if (isset($content)) {
                    if (is_array($content)) {
                        $this->load($content);
                    } else {
                        $this->text($content);
                    }
                }
                $this->endElement();  
            }
            return $this;
        }

        public function out()
        {
            return $this->preparing()->outputMemory();
        }

        protected function preparing()
        {
            if (!$this->_newdoc) {
                if ($this->_startNewDoc) {
                    $this->endDocument();
                }
                $this->_newdoc = true;
            }
            return $this;
        }

        protected function configure(array $config)
        {
            if (isset($config['indentString'])) {
                $this->configIndentString($config['indentString']);
            } else {
                $this->configIndentString(str_repeat(' ', 4));
            }
    
            if (isset($config['startDocument'])) {
                $this->configStartDocument($config['startDocument']);
            } else {
                $this->configStartDocument(['1.0', 'UTF-8']);
            }
        }

        protected function configIndentString($string)
        {
            if ($string !== false) {
                $this->setIndent(true);
                parent::setIndentString($string);
            }
        }

        protected function configStartDocument($arguments)
        {
            if (is_array($arguments)) {
                if (call_user_func_array([$this, 'startDocument'], $arguments)) {
                    $this->_startNewDoc = true;
                }
            }
        }
    }