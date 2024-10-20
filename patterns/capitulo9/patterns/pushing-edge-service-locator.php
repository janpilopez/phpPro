<?php
// Llevando al límite: localizador de servicios
// Prometí que este capítulo trataría la lógica de la creación de objetos, eliminando
// la forma furtiva de pasarse la pelota a otro, como ocurre en muchos ejemplos orientados a objetos. Sin embargo, algunos patrones aquí han
// esquivado astutamente la parte de toma de decisiones de la creación de objetos, si no la creación misma.
// El patrón Singleton no es culpable. La lógica para la creación de objetos está incorporada y
// es inequívoca. El patrón Abstract Factory agrupa la creación de familias de productos en
// creadores concretos distintos. Sin embargo, ¿cómo decidimos qué creador concreto utilizar?
// El patrón Prototype nos presenta un problema similar. Ambos patrones manejan
// la creación de objetos, pero postergan la decisión sobre qué objeto o grupo de objetos
// debería crearse.
// El creador concreto particular que elige un sistema a menudo se decide de acuerdo con el valor de un cambio de configuración de algún tipo. Esto podría estar ubicado en una base de datos,
// un archivo de configuración o un archivo de servidor (como el archivo de configuración de nivel de directorio de Apache,
// generalmente llamado .htaccess), o incluso podría estar codificado como una variable o propiedad de PHP.
// Como las aplicaciones PHP deben reconfigurarse para cada solicitud o llamada CLI, es necesario que la inicialización del script sea lo menos complicada posible. Por este motivo, a menudo opto por codificar indicadores de configuración en código PHP. Esto se puede hacer a mano o escribiendo un script que
// genere automáticamente un archivo de clase. Aquí hay una clase básica que incluye un indicador para los tipos de protocolo de calendario:
class Settings
{
    public static string $COMMSTYPE = 'Mega';
}
// Ahora que tengo una bandera (por poco elegante que sea), puedo crear una clase que la use para decidir
// qué CommsManager servir cuando se lo solicite. Es bastante común ver un Singleton usado en
// conjunción con el patrón Abstract Factory, así que hagámoslo:
class AppConfig
{
    private static ?AppConfig $instance = null;
    private CommsManager $commsManager;
    private function __construct()
    {
        // will run once only
        $this->init();
    }
    private function init(): void
    {
        switch (Settings::$COMMSTYPE) {
            case 'Mega':
                $this->commsManager = new MegaCommsManager();
                break;
            default:
                $this->commsManager = new BloggsCommsManager();
        }
    }
    public static function getInstance(): AppConfig
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function getCommsManager(): CommsManager
    {
        return $this->commsManager;
    }
}
// La clase AppConfig es un Singleton estándar. Por ese motivo, puedo obtener una instancia de AppConfig
// en cualquier parte del sistema y siempre obtendré la misma. El método init()
// es invocado por el constructor de la clase y, por lo tanto, solo se ejecuta una vez en un proceso.
// Prueba la propiedad Settings::$COMMSTYPE, instanciando un objeto CommsManager
// concreto según su valor. Ahora mi script puede obtener un objeto CommsManager y trabajar
// con él sin saber nunca acerca de sus implementaciones concretas o las clases concretas
// que genera:


$commsMgr = AppConfig::getInstance()->getCommsManager();
$commsMgr->getApptEncoder()->encode();
// Dado que AppConfig administra el trabajo de búsqueda y creación de componentes para nosotros, es
// una instancia de lo que se conoce como el patrón Service Locator. Es elegante, pero introduce
// una dependencia más benigna que la instanciación directa. Cualquier clase que use su servicio debe
// invocar explícitamente este monolito, vinculándolos al sistema más amplio. Por este motivo, algunos
// prefieren otro enfoque.