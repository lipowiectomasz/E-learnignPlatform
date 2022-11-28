<?php

    namespace test;

    class testElement{

        public $id;
        public $element;
        public $tresc;
        public $grafika;

        public function __construct($id, $element, $tresc, $grafika){
            $this->id = $id;
            $this->element = $element;
            $this->tresc = $tresc;
            $this->grafika = $grafika;
        }

    }
