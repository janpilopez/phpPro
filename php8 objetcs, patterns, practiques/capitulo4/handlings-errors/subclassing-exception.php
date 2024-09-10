<!-- You can create classes that extend the Exception class as you would with any userdefined class. There are two reasons
why you might want to do this. First, you can extend
the class’s functionality. Second, the fact that a derived class defines a new class type can
aid error handling in itself.
You can, in fact, define as many catch blocks as you need for a try statement. The
particular catch block invoked will depend on the type of the thrown exception and the
class type hint in the argument list. Here are some simple classes that extend Exception:

class XmlException extends \Exception
{
public function construct(private \LibXmlError $error)
{
$shortfile = basename($error->file);
$msg = "[{$shortfile}, line {$error->line}, col {$error->column}]
{$error->message}";
$this->error = $error;
parent:: __construct($msg, $error->code);
}
public function getLibXmlError(): \LibXmlError
{
return $this->error;
}
}
class ConfException extends \Exception
{
}
class FileException extends \Exception
{
}
#############################################################
// Conf class...
public function __construct(private string $file)
{
if (! file_exists($file)) {
throw new FileException("file '$file' does not exist");
}
$this->xml = simplexml_load_file($file, null, LIBXML_NOERROR);
if (! is_object($this->xml)) {
throw new XmlException(libxml_get_last_error());
}
$matches = $this->xml->xpath("/conf");
if (! count($matches)) {
throw new ConfException("could not find root element: conf");
}
}
public function write(): void
{
if (! is_writeable($this->file)) {
throw new FileException("file '{$this->file}' is not writeable");
}
file_put_contents($this->file, $this->xml->asXML());
}
 -->
<?php
class Runner
{
    public static function init()
    {
        try {
            $conf = new Conf(__DIR__ . '/conf.broken.xml');
            print 'user: ' . $conf->get('user') . "\n";
            print 'host: ' . $conf->get('host') . "\n";
            $conf->set('pass', 'newpass');
            $conf->write();
        } catch (FileException $e) {
            // permissions issue or non-existent file throw $e;
        } catch (XmlException $e) {
            // broken xml
        } catch (ConfException $e) {
            // wrong kind of XML file
        } catch (\Exception $e) {
            // Suele ser una buena idea tener un bloque de “respaldo” como este
            //es el mas generico no deberia ejecutarse ya que segun el desarrollo solo habrian los 3 tipos de excepciones
            // backstop: should not be called
        }
    }
}

class XmlException extends \Exception
{
    private $error;
    // public function construct(private \LibXmlError $error)
    // {
    //     $shortfile = basename($error->file);
    //     $msg = "[{$shortfile}, line {$error->line}, col {$error->column}] {$error->message}";
    //     $this->error = $error;
    //     parent::__construct($msg, $error->code);
    // }
    public function getLibXmlError(): \LibXmlError
    {
        return $this->error;
    }
}
// listing 04.70
class FileException extends \Exception
{
}
// listing 04.71
class ConfException extends \Exception
{
}
