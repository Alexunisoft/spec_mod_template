<?php /** @noinspection ALL */


namespace Alexunisoft\SpecModTemplate\Initialization;


use FilesystemIterator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class InstallationHandler
{

    /**
     * Package seeders path.
     * @var string
     */
    private $seeds_path = "";

    /**
     * Package config path.
     * @var string
     */
    private $config_path = "";

    /**
     * Filesystem Component.
     * @var Filesystem
     */
    private $fs = "";

    /**
     * InstallationHandler constructor.
     */
    public function __construct()
    {
        $this->seeds_path = __DIR__ . "/../../database/seeds/";
        $this->config_path = __DIR__ . "/../../config/";
        $this->fs = new Filesystem();
    }

    /**
     * Execute the initialization sequence required
     * for the project to utilize the package.
     */
    public function executeInitializationSequence()
    {
        $this->runMigrations();
        $this->publishSeeds();
        $this->publishConfig();

        $this->runSeeds();
    }

    /**
     * Run migrations of the package.
     */
    private function runMigrations()
    {
        Artisan::call("migrate");
    }

    /**
     * Publish database seeds of the package.
     */
    private function publishSeeds()
    {
        $fs = $this->fs;
        $fs_iter = new FilesystemIterator($this->seeds_path);
        while ($fs_iter->valid()) {
            $seeder = $fs_iter->getFilename();
            if (!$fs->exists(database_path("seeds/$seeder"))) {
                $fs->copy($fs_iter->getPathname(), database_path("seeds/" . $fs_iter->getFilename()));
            }
            $fs_iter->next();
        }
    }

    /**
     * Run database seeders of the package.
     */
    private function runSeeds()
    {
        Artisan::call("db:seed");
    }

    /**
     * Publish config files.
     */
    private function publishConfig()
    {
        $fs = $this->fs;
        $fs_iter = new FilesystemIterator($this->config_path);
        while ($fs_iter->valid()) {
            $config = $fs_iter->getFilename();
            if (!$fs->exists(config_path($config))) {
                $fs->copy($fs_iter->getPathname(), config_path($fs_iter->getFilename()));
            }
            $fs_iter->next();
        }
    }

    /**
     * Execute the removal sequence required
     * for the project to deactive the package.
     */
    public function executeRemovalSequence()
    {
        $this->deleteConfigFiles();
        $this->deleteSeedFiles();
    }

    /**
     * Delete seeder files from platform.
     */
    private function deleteSeedFiles()
    {
        $fs = $this->fs;
        $fs_iter = new FilesystemIterator($this->seeds_path);
        while ($fs_iter->valid()) {
            $seeder = $fs_iter->getFilename();
            if ($fs->exists(database_path("seeds/$seeder"))) {
                $fs->delete(database_path("seeds/" . $fs_iter->getFilename()));
            }
            $fs_iter->next();
        }
    }

    /**
     * Remove config files from platform.
     */
    private function deleteConfigFiles()
    {
        $fs = $this->fs;
        $fs_iter = new FilesystemIterator($this->config_path);
        while ($fs_iter->valid()) {
            $config = $fs_iter->getFilename();
            if ($fs->exists(config_path($config))) {
                $fs->delete(config_path($fs_iter->getFilename()));
            }
            $fs_iter->next();
        }
    }
}
