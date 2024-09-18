<?php
// And here’s some namespaced code:
namespace popp\ch05\batch04\util;


class TreeLister
{
    public static function helloWorld(): void
    {
        print "hello from 12" . __NAMESPACE__ . "\n";
    }
}