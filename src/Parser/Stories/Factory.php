<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 10:10
 */

namespace Elgentos\Parser\Stories;

use Elgentos\Parser\Exceptions\StoriesExistsException;
use Elgentos\Parser\Exceptions\StoriesInvalidClassException;
use Elgentos\Parser\Exceptions\StoriesNotFoundException;
use Elgentos\Parser\Interfaces\StoriesInterface;

class Factory
{
    /** @var Factory */
    protected static $instance;

    /** @var array */
    protected $stories = [];

    public function __construct()
    {
        $this->defaults();
    }

    /**
     * Initialize default stories
     */
    protected function defaults()//: void
    {
        $this->stories['reader'] = Reader::class;
        $this->stories['builder'] = Builder::class;
    }

    /**
     * Create a instance
     *
     * @return Factory
     */
    protected static function instance(): Factory
    {
        if (! self::$instance) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    /**
     * Add new stories reference
     *
     * @param string $code
     * @param string $className
     * @throws StoriesExistsException
     * @throws StoriesInvalidClassException
     */
    public static function add(string $code, string $className)//: void
    {
        if (isset((self::instance())->stories[$code])) {
            throw new StoriesExistsException(sprintf('Cannot add factory "%s", already exists', $code));
        }

        self::set($code, $className);
    }

    /**
     * Set new stories reference
     *
     * @param string $code
     * @param string $className
     * @throws StoriesInvalidClassException
     */
    public static function set(string $code, string $className)//: void
    {
        if (! class_exists($className)) {
            throw new StoriesInvalidClassException(sprintf('Cannot add factory, class "%s" invalid', $className));
        }

        (self::instance())->stories[$code] = $className;
    }

    public static function addSingleton(string $code, StoriesInterface $stories)//: void
    {
        if (isset((self::instance())->stories[$code])) {
            throw new StoriesExistsException(sprintf('Cannot add factory "%s", already exists', $code));
        }

        self::setSingleton($code, $stories);
    }

    public static function setSingleton(string $code, StoriesInterface $stories)//: void
    {
        (self::instance())->stories[$code] = $stories;
    }

    /**
     * Create new instance for given stories interface
     *
     * @param string $code
     * @param mixed ...$arguments
     * @return StoriesInterface
     * @throws \Exception
     */
    public static function create(string $code, ...$arguments): StoriesInterface
    {
        $instance = self::instance();
        if (! isset($instance->stories[$code])) {
            throw new StoriesNotFoundException(sprintf('Cannot find stories for code "%s"', $code));
        }

        $stories = $instance->stories[$code];
        if ($stories instanceof StoriesInterface) {
            return $stories;
        }

        return new $stories(...$arguments);
    }

}
