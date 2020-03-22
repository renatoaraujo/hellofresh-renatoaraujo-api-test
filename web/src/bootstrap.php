<?php
declare(strict_types=1);

namespace
{
    require_once __DIR__ . '/../vendor/autoload.php';
}

namespace HelloFresh
{
    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
    use Symfony\Component\Config\FileLocator;
    use Symfony\Component\Dotenv\Dotenv;
    use RuntimeException;

    final class RuntimeContext
    {
        /** @var RuntimeContext */
        private static $instance;

        /** @var ContainerInterface */
        private $container;

        private function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

        public static function init(): void
        {
            if (self::$instance != null) {
                return;
            }

            self::loadEnv();

            $container = self::initServiceContainer();

            self::$instance = new self($container);
        }

        public static function initServiceContainer(): ContainerBuilder
        {
            $container = new ContainerBuilder();
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
            $loader->load(__DIR__ . '/../config/services.yaml');
            $container->compile(true);

            return $container;
        }

        public static function get(string $id)
        {
            return self::$instance->container->get($id);
        }

        public static function getContainer(): ContainerInterface
        {
            return self::$instance->container;
        }

        public static function loadEnv()
        {
            if (!getenv('APP_ENV')) {
                if (!class_exists(Dotenv::class)) {
                    throw new RuntimeException('APP_ENV environment variable is not defined.');
                }

                $envFile = __DIR__ . '/../.env';

                if (!is_file($envFile)) {
                    throw new RuntimeException('Environment variable file missing.');
                }

                (new Dotenv())->load($envFile);
            }
        }
    }

    RuntimeContext::init();
}
