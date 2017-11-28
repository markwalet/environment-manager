<?php

use MarkWalet\EnvironmentManager\Adapters\FileEnvironmentAdapter;
use PHPUnit\Framework\TestCase;

class FileEnvironmentAdapterTest extends TestCase
{
    /**
     * @var string
     */
    private $path;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->path = __DIR__ . '/.env.testing';
        $content = "TEST1=value1".PHP_EOL."TEST2=value2".PHP_EOL."TEST3=value3";

        file_put_contents($this->path, $content);
    }

    protected function tearDown()
    {
        unlink($this->path);
    }

    /** @test */
    public function can_read_file()
    {
        $adapter = new FileEnvironmentAdapter($this->path);

        $content = $adapter->read();

        $this->assertEquals("TEST1=value1".PHP_EOL."TEST2=value2".PHP_EOL."TEST3=value3", $content);
    }

    /** @test */
    public function can_write_file()
    {
        $adapter = new FileEnvironmentAdapter($this->path);

        $adapter->write("TEST1=updated".PHP_EOL."TEST2=updated");
        $content = $adapter->read();

        $this->assertEquals("TEST1=updated".PHP_EOL."TEST2=updated", $content);
    }
}