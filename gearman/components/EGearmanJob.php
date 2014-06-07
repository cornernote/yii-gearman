<?php
/**
 * File contains class EGearmanJob
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class EGearmanJob implements worker job interface realisation for gearman {@link http://gearman.org/}.
 * Object is transfer data to handler. Data may be only string, transfer don't use php- or JSON-serialisation.
 * It's not recommended use language-specific serialization, because workers may be written at another languages,
 * like C, C++, Python or Ruby.
 *
 * This is a wrapper to gearman job object. It's need to use alternative no-gearman realisation.
 */
class EGearmanJob extends CComponent implements IGearmanJob
{
    /**
     * @var GearmanJob
     */
    private $_job;

    /**
     * Constructor.
     *
     * @param GearmanJob $job
     */
    public function __construct($job)
    {
        $this->_job = $job;
    }

    /**
     * Get gearman API called command name.
     *
     * @return string
     */
    public function getCommandName()
    {
        return $this->_job->functionName();
    }

    /**
     * Get stream identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_job->unique();
    }

    /**
     * Get data sending by task creator.
     *
     * @return string
     */
    public function getWorkload()
    {
        return $this->_job->workload();
    }

    /**
     * Sends result data and the complete status update for this job.
     *
     * @link http://php.net/manual/en/gearmanjob.sendcomplete.php
     * @param $data
     * @internal param string $result Serialized result data
     * @return bool
     */
    public function sendComplete($data)
    {
        return $this->_job->sendComplete($data);
    }

    /**
     * Send exception to server or client error message.
     *
     * @param Exception|string $exception
     * @return bool
     */
    public function sendException($exception)
    {
        return $this->_job->sendException($exception);
    }
}