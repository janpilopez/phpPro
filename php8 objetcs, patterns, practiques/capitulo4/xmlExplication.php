
<?php
class Conf
{

    private \SimpleXMLElement $xml;
    // Referencia Compartida: lastmatch actúa como una referencia a un nodo en $this->xml. Por lo tanto, modificar lastmatch afecta directamente al XML.    
    private \SimpleXMLElement $lastmatch;
    // Modificación Directa: Aunque lastmatch es una propiedad separada, los cambios realizados en ella se reflejan en el objeto XML original porque ambas apuntan al mismo lugar en la memoria.
    public function __construct(private string $file)
    {
        $this->xml = simplexml_load_file($file);
    }

    // Este método guarda el contenido del objeto $xml de vuelta en el archivo. Usa file_put_contents para escribir el XML en el archivo original.  
    public function write(): void
    {
        file_put_contents($this->file, $this->xml->asXML());
    }

    // XPath (XML Path Language): Es un lenguaje que permite seleccionar nodos en un documento XML. Es muy útil para realizar consultas complejas y navegar por estructuras jerárquicas de datos en archivos XM

    // Este método busca un elemento <item> en el XML donde el atributo name sea igual a $str.
    // Si encuentra coincidencias, almacena la primera en $lastmatch y la devuelve como cadena. Si no encuentra coincidencias, devuelve null.
    public function get(string $str): ?string
    {
        // /conf/item[@name=\"$str\"]: Esta consulta busca elementos <item> dentro de un elemento <conf> donde el atributo name sea igual al valor de $str.
        $matches = $this->xml->xpath("/conf/item[@name=\"$str\"]");
        // $matches: Almacena el resultado de la consulta, que será un array de elementos que coinciden con la búsqueda.
        if (count($matches)) {
            //lastmatch es un nodo referencia del xml, por tanto se modifica el xml directamente
            $this->lastmatch = $matches[0];
            return (string) $matches[0];
        }
        return null;
    }
    public function set(string $key, string $value): void
    {
        // Llama al método get con el $key. Si get devuelve un valor (es decir, el elemento existe), se modifica el valor del elemento encontrado.
        //  Esto establece el nuevo valor del primer elemento coincidente.
        if (!is_null($this->get($key))) {
            $this->lastmatch[0] = $value;
            return;
        }
        // Si el elemento no existe (es decir, get devolvió null), se agrega un nuevo elemento <item> con el valor $value y un atributo name igual a $key.
        // addChild: Crea un nuevo elemento hijo.
        // addAttribute: Agrega un atributo al nuevo elemento.
        $conf = $this->xml->conf;
        $this->xml->addChild('item', $value)->addAttribute('name', $key);
    }
}

// \The Conf class uses the SimpleXml extension to access name value pairs. Here’s the
// kind of format with which it is designed to work:
// <?xml version="1.0" ?>
<!-- <conf> -->
 <!-- <item name="user">bob</item> -->
 <!-- <item name="pass">newpass</item> -->
 <!-- <item name="host">localhost</item> -->
<!-- </conf> -->