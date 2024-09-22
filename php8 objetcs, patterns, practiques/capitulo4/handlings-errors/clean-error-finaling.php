<?php

function hola(): void
{
    $fh = fopen('/tmp/log.txt', 'a');
    try {
        #Aunque los bloques catch solo se ejecutan condicionalmente cuando se generan excepciones coincidentes,
        #siempre se ejecuta un bloque finally, independientemente de si se genera o no una excepción dentro del bloque try.
        fputs($fh, "start\n");
        $conf = new Conf(dirname(FILE) . '/conf.not-there.xml');
        print 'user: ' . $conf->get('user') . "\n";
        print 'host: ' . $conf->get('host') . "\n";
        $conf->set('pass', 'newpass');
        $conf->write();
    } catch (FileException $e) {
        // permissions issue or non-existent file
        fputs($fh, "file exception\n");
        // fclose($fh); #before, YA NO ES NECESARIO, VA AL FINAL

        //throw $e;
    } catch (XmlException $e) {
        fputs($fh, "xml exception\n");
        // broken xml
    } catch (ConfException $e) {
        fputs($fh, "conf exception\n");
        // wrong kind of XML file
    } catch (Exception $e) {
        fputs($fh, "general exception\n");
        // backstop: should not be called
        
    } 
    //el finally se ejecuta siempre, nos puede servir para cerrar conexiones, liberar recursos, etc
    //que pueden no ser necesariamente capturados por el catch 
    finally {
        fputs($fh, "end\n");
        fclose($fh);
        // Debido a que la escritura del registro y la invocación de fclose() están envueltas en este bloque finally,
        // estas instrucciones se ejecutarán incluso si, como es el caso cuando se captura una FileException,
        // la excepción se vuelve a generar, al final pasara por aqui
        //EN RESUMEN SIEMPRE SE EJECUTARA, ES COMO UN COMODIN
    }

    // Nota: Se ejecutará un bloque finally si un bloque catch invocado vuelve a generar una
    // excepción o devuelve un valor. Sin embargo, llamar a die() o exit() en un bloque try o catch
    // finalizará la ejecución del script y el bloque finally no se ejecutará.


    
}

hola();