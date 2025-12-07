<?php
namespace JasperPHP;

class JasperPHP
{
    protected string $javaPath;
    protected string $jasperStarterJar;
    protected string $jdbcDir;
    protected string $mainClass;

    protected string $dbUser;
    protected string $dbPass;
    protected string $dbHost;
    protected string $dbName;
    protected int $dbPort;
    protected string $dbDriver;

    protected ?string $lastCommand = null;
    protected ?string $lastOutput = null;

    public function __construct()
    {
        $this->javaPath = '"C:\Program Files\Java\jdk1.8.0_202\bin\java.exe"';
        $this->jasperStarterJar = 'lib/jasperstarter.jar';
        $this->jdbcDir = 'lib';
        $this->mainClass = 'de.cenote.jasperstarter.App';

        $this->dbUser = '';
        $this->dbPass = '';
        $this->dbHost = '';
        $this->dbName = '';
        $this->dbPort = 3306;
        $this->dbDriver = 'com.mysql.cj.jdbc.Driver';
    }

    public function setDbConfig(string $user, string $pass, string $host, string $name, int $port = 3306, string $driver = 'com.mysql.cj.jdbc.Driver', string $jdbcDir = null): self
    {
        $this->dbUser = $user;
        $this->dbPass = $pass;
        $this->dbHost = $host;
        $this->dbName = $name;
        $this->dbPort = $port;
        $this->dbDriver = $driver;
        if ($jdbcDir !== null) {
            $this->jdbcDir = $jdbcDir;
        }
        return $this;
    }

    public function process(string $input, string $output, array $formats = ['pdf'], array $parameters = []): bool
    {
        // En Windows classpath usa ';', en Linux ':'
        $classpathSeparator = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? ';' : ':';

        $classpath = $this->jasperStarterJar;

        $formatsStr = implode(',', $formats);

        $cmd = $this->javaPath
            . ' -cp "' . $classpath . '"'
            . ' ' . $this->mainClass
            . ' process'
            . ' "' . $input . '"'
            . ' -o "' . $output . '"'
            . ' -f ' . $formatsStr
            . ' -t mysql'
            . ' -u "' . $this->dbUser . '"';

        if (!empty($this->dbPass)) {
            $cmd .= ' -p "' . $this->dbPass . '"';
        }

        $cmd .= ' -H "' . $this->dbHost . '"'
            . ' -n "' . $this->dbName . '"'
            . ' --db-port "' . $this->dbPort . '"'
            . ' --db-driver "' . $this->dbDriver . '"'
            . ' --jdbc-dir "' . $this->jdbcDir . '"';

        foreach ($parameters as $key => $value) {
            $escapedValue = str_replace('"', '\\"', $value);
            $cmd .= ' -P ' . $key . '="' . $escapedValue . '"';
        }

        $this->lastCommand = $cmd;
        $this->lastOutput = shell_exec($cmd . ' 2>&1');

        return file_exists($output . '.pdf');
    }

    public function getLastCommand(): ?string
    {
        return $this->lastCommand;
    }

    public function getLastOutput(): ?string
    {
        return $this->lastOutput;
    }
}

