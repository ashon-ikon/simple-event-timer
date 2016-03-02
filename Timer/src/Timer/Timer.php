<?php
/**
 * Very simpler timer which can be used to get
 * duration of events
 *
 */
namespace Timer;

/**
 * Class Timer
 * @package Timer
 */
class Timer
{

    /**
     * Holds the time timer started
     * @var double
     */
    protected static $startTime;
    
    /**
     * Holds the time timer finishes
     * @var double
     */
    protected static $endTime;
    
    /**
     * Gets the duration between events
     * @param $startTime
     * @param null $endTime
     * @param bool $asString
     * @param bool $showMilli
     * @return float|string
     */
    public static function duration($startTime, $endTime = null, $asString = true, $showMilli = true)
    {
        if (is_null($endTime)) {
            $endTime = static::microtimeFloat();
        }

        $duration = $endTime - $startTime;
        if ($asString) {
            $duration = static::timeToString($duration, false, $showMilli);
        }

        return $duration;
    }

    /**
     * Converts time duration to fancy time
     * @param $duration
     * @param bool $ignoreZero
     * @param bool $showMilli
     * @return string
     */
    protected static function timeToString($duration, $ignoreZero = false, $showMilli = false)
    {
        $durationRaw = $duration; // Could be floating point
        $duration    = (int)$duration;
        $return      = '';

        if ($ignoreZero && $duration == 0) {
            return $return;
        }

        if ($showMilli && $durationRaw < 1 && $durationRaw != 0) {
            // Use raw timing..
            $duration = $durationRaw * 1000;
            $return   = number_format($duration, 3) . ' millisecond' . ($duration > 1 ? 's' : '');
        } else {
            if ($duration < 60) {
                $return = $duration . ' second' . ($duration > 1 ? 's' : '');
                if ($showMilli && $durationRaw > $duration) {
                    $remainder = $durationRaw - $duration;
                    $return .= ' ' . self::timeToString($remainder, false, true);
                }
            } elseif ($duration < (60 * 60)) {
                $raw       = intval($duration / 60);
                $remainder = $duration % 60;
                $min       = number_format($raw, 0);
                $return    = $min . ' minute' . ($min > 1 ? 's' : '') . ' ' . self::timeToString($remainder, true);
            } elseif ($duration < (60 * 60 * 24)) {
                $raw       = intval($duration / (60 * 60));
                $remainder = $duration % (60 * 60);
                $hour      = number_format($raw, 0);
                $return    = $hour . ' hour' . ($hour > 1 ? 's' : '') . ' ' . self::timeToString($remainder, true);
            } elseif ($duration >= (60 * 60 * 24)) {
                $raw       = intval($duration / (60 * 60 * 24));
                $remainder = $duration % (60 * 60 * 24);
                $day       = number_format($raw, 0);
                $return    = $day . ' day' . ($day > 1 ? 's' : '') . ' ' . self::timeToString($remainder, true);
            }
        }

        return trim($return);
    }

    /**
     * Generates time with milli-seconds
     *
     * Influenced by http://php.net/manual/en/function.microtime.php#refsect1-function.microtime-examples
     * @return float
     */
    public static function microtimeFloat()
    {
        list($sec, $micro) = explode(" ", microtime());

        return ((float)$sec + (float)$micro);

    }

    /**
     * Sets the start time
     * @return float
     */
    public static function startTimer()
    {
        static::$startTime = static::microtimeFloat();
        static::$endTime = null;

        return static::$startTime;
    }


    /**
     * Returns the duration between the last start time and this function call (now)
     * @return float|string
     */
    public static function stopTimer()
    {
        if (!static::$startTime) {
            static::startTimer();
        }
        static::$endTime = static::microtimeFloat();

        return static::duration(static::$endTime - static::$startTime);
      
    }
}


