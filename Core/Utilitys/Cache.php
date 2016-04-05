<?php

namespace Core\Utilitys;

/**
 * Classe de gerenciamento de Cache
 *
 * @author Lucas Pinheiro
 */
class Cache {

    /**
     * Tempo padrão de cache
     * 
     * @var string
     */
    private static $time = '5 minutes';

    /**
     * Local onde o cache será salvo
     * 
     * Definido pelo construtor
     * 
     * @var string
     */
    private $folder;

    /**
     * Construtor
     * 
     * Inicializa a classe e permite a definição de onde os arquivos
     * serão salvos. Se o parâmetro $folder for ignorado o local dos
     * arquivos temporários do sistema operacional será usado
     * 
     * @uses Cache::setFolder() Para definir o local dos arquivos de cache
     * 
     * @param string $folder Local para salvar os arquivos de cache (opcional)
     * 
     * @return void
     */
    public function __construct($folder = null) {
        $this->setFolder($folder);
    }

    public function setTime($time) {
        self::$time = $time;
    }

    /**
     * Define onde os arquivos de cache serão salvos
     * 
     * Irá verificar se a pasta existe e pode ser escrita, caso contrário
     * uma mensagem de erro será exibida
     * 
     * @param string $folder Local para salvar os arquivos de cache (opcional)
     * 
     * @return void
     */
    protected function setFolder($folder = null) {
        $folder = ROOT . 'src' . DS . 'tmp' . DS . 'cache' . DS . trim($folder, DS);
        try {
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            if (file_exists($folder) && is_dir($folder) && is_writable($folder)) {
                $this->folder = $folder;
            } else {
                throw new Exception('Não foi possível acessar a pasta de cache');
            }
        } catch (Exception $exception) {
            $ex = new MyException();
            $ex->show_exception($exception);
        }
    }

    /**
     * Gera o local do arquivo de cache baseado na chave passada
     * 
     * @param string $key Uma chave para identificar o arquivo
     * 
     * @return string Local do arquivo de cache
     */
    protected function generateFileLocation($key) {
        return $this->folder . DS . sha1($key) . '.tmp';
    }

    /**
     * Cria um arquivo de cache
     * 
     * @uses Cache::generateFileLocation() para gerar o local do arquivo de cache
     * 
     * @param string $key Uma chave para identificar o arquivo
     * @param string $content Conteúdo do arquivo de cache
     * 
     * @return boolean Se o arquivo foi criado
     */
    protected function createCacheFile($key, $content) {
        $filename = $this->generateFileLocation($key);

        try {
            $file = file_put_contents($filename, $content);
            if (!$file) {
                throw new \Exception('Não foi possível criar o arquivo de cache');
            }
            return $file;
        } catch (\Exception $exception) {
            $ex = new \Core\MyException();
            $ex->show_exception($exception);
        }
    }

    /**
     * Salva um valor no cache
     * 
     * @uses Cache::createCacheFile() para criar o arquivo com o cache
     * 
     * @param string $key Uma chave para identificar o valor cacheado
     * @param mixed $content Conteúdo/variável a ser salvo(a) no cache
     * @param string $time Quanto tempo até o cache expirar (opcional)
     * 
     * @return boolean Se o cache foi salvo
     */
    public function save($key, $content, $time = null) {
        $time = strtotime(!is_null($time) ? $time : self::$time);
        $content = serialize([
            'expires' => $time,
            'content' => $content]);

        return $this->createCacheFile($key, $content);
    }

    /**
     * Consulta de um cache existe
     * 
     * @param string $key Uma chave para identificar o valor cacheado
     * 
     * @return mixed Se o cache foi encontrado retorna o seu valor, caso contrário retorna NULL
     */
    public function read($key) {
        $filename = $this->generateFileLocation($key);
        if (file_exists($filename) && is_readable($filename)) {
            $cache = unserialize(file_get_contents($filename));
            if ($cache['expires'] > time()) {
                return $cache['content'];
            } else {
                unlink($filename);
            }
        }
        return null;
    }

    /**
     * Deleta todos os cache dde um determinado diretorio.
     * 
     * @return boolean
     */
    public function deleteAll() {
        if ($dh = opendir($this->folder)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' and $file != '..') {
                    if (file_exists($this->folder . DS . $file)) {
                        unlink($this->folder . DS . $file);
                    }
                }
            }
            closedir($dh);
        }
        return true;
    }

}
