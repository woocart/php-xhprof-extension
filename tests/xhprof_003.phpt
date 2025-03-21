--TEST--
Tideways: Test Class Methods, Constructors, Destructors.
--FILE--
<?php

include_once dirname(__FILE__).'/common.php';

class C {
  private static $_static_attr = "i am a class static";
  private $_attr;
  function __construct($attr) {
    echo "In constructor...\n";
    $this->_attr = $attr;
  }

  private static function inner_static() {
    return C::$_static_attr;
  }

  public static function outer_static() {
    return C::inner_static();
  }

  public function get_attr() {
    return $this->_attr;
  }

  function __destruct() {
    echo "Destroying class {$this->_attr}\n";
  }
}


tideways_xhprof_enable();

// static methods
echo C::outer_static() . "\n";

// constructor
$obj = new C("Hello World");

// instance methods
$obj->get_attr();

// destructor
$obj = null;


$output = tideways_xhprof_disable();

echo "Profiler data for 'Class' tests:\n";
print_canonical($output);
echo "\n";

?>
--EXPECT--
i am a class static
In constructor...
Destroying class Hello World
Profiler data for 'Class' tests:
C::outer_static==>C::inner_static       : cp=xhprof_003.php; ct=       1; wt=*;
main()                                  : ct=       1; wt=*;
main()==>C::__construct                 : cp=xhprof_003.php; ct=       1; wt=*;
main()==>C::__destruct                  : cp=xhprof_003.php; ct=       1; wt=*;
main()==>C::get_attr                    : cp=xhprof_003.php; ct=       1; wt=*;
main()==>C::outer_static                : cp=xhprof_003.php; ct=       1; wt=*;
main()==>tideways_xhprof_disable        : ct=       1; wt=*;
