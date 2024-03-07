<?php

namespace CarlBennett\API\Controllers\Slack;

use \CarlBennett\API\Libraries\Core\HTTPCode;
use \CarlBennett\API\Libraries\Core\Router;

class Webhook extends \CarlBennett\API\Controllers\Base
{
    public function __construct()
    {
        $this->model = new \CarlBennett\API\Models\Slack\Webhook();
    }

    public function invoke(?array $args): bool
    {
        if (Router::requestMethod() != Router::METHOD_POST)
        {
            $this->model->_responseCode = HTTPCode::METHOD_NOT_ALLOWED;
            $this->model->_responseHeaders['Allow'] = Router::METHOD_POST;
            return true;
        }

        $data = Router::query();
        foreach ($data as $k => $v)
        {
            \CarlBennett\API\Libraries\Core\Logger::logMetric($k, $v);
        }

        $token = $data['token'] ?? null;
        $team_id = $data['team_id'] ?? null;
        $team_domain = $data['team_domain'] ?? null;
        $channel_id = $data['channel_id'] ?? null;
        $channel_name = $data['channel_name'] ?? null;
        $timestamp = $data['timestamp'] ?? null;
        $user_id = $data['user_id'] ?? null;
        $user_name = $data['user_name'] ?? null;
        $command = $data['command'] ?? null;
        $text = $data['text'] ?? null;
        $trigger_word = $data['trigger_word'] ?? null;

        if (empty($command) && !empty($trigger_word))
        {
            $command = $trigger_word;
            $text = \preg_replace("#<https?://.*\|(.*)>#i", "$1", $text);
        }

        if (\substr($text, 0, \strlen($command)) === $command)
        {
            $text = \substr($text, \strlen($command) + 1);
        }

        $command = \ltrim($command, './');

        $response = null;
        switch ($command)
        {
            case '8ball':
            case 'magic8ball':
            {
                $question = \trim($text);
                if (\strpos($question, "\n") !== false)
                {
                    $response = 'Only one-line sentences please!';
                }
                else if (\substr(\trim($question), -1) != "?")
                {
                    $response = 'Please ask me a question.';
                }
                else
                {
                    $response = '> ' . $question . "\n"
                        . (new \CarlBennett\API\Libraries\Fun\Magic8Ball())->getPrediction($question);
                }
                break;
            }
            case 'dig':
            case 'host':
            case 'nslookup':
            case 'whois':
            {
                $output = \CarlBennett\API\Libraries\Core\Shell::execute($command, $text);
                $response = empty($output) ?
                    'No output from the command-line program.' :
                    '```' . \CarlBennett\API\Libraries\Core\StringProcessor::stripExcessLines($output) . '```';
                break;
            }
            case 'geoip':
            {
                $ip = \trim($text);
                if (empty($ip))
                {
                    $response = 'Error: Please provide an IP address or hostname.';
                }
                else
                {
                    $geoinfo = \CarlBennett\API\Libraries\Core\GeoIP::getRecord($ip);
                    $response = 'query_address ' . $ip . "\n";
                    if ($geoinfo)
                    {
                        foreach ($geoinfo as $key => $val)
                        {
                            if (!empty($val))
                            {
                                $response .= 'geoinfo_' . $key . ' ' . $val . "\n";
                            }
                        }
                    }
                    else if (\is_bool($geoinfo))
                    {
                        $response .= 'geoinfo ' . ($geoinfo ? 'true' : 'false') . "\n";
                    }
                    else if (\is_null($geoinfo))
                    {
                        $response .= "geoinfo null\n";
                    }
                    else
                    {
                        $response .= 'geoinfo ' . \gettype($geoinfo) . "\n";
                    }
                    $response = '```' . $response . '```';
                }
                break;
            }
            default:
            {
                $response = 'invalid_command: ' . $command;
            }
        }

        if (isset($trigger_word))
        {
            $this->model->result = \json_encode(['text' => $response]);
        }
        else
        {
            $this->model->result = $response;
        }

        $this->model->_responseCode = HTTPCode::FOUND;
        return true;
    }
}
