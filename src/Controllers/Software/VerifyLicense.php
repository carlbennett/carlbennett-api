<?php

namespace CarlBennett\API\Controllers\Software;

use \CarlBennett\API\Libraries\Core\HTTPCode;

class VerifyLicense extends \CarlBennett\API\Controllers\Base
{
    public const VERIFYLICENSE_STATUS_BAD_REQUEST = ['code' => 0, 'message' => 'Bad request'];
    public const VERIFYLICENSE_STATUS_NOT_FOUND   = ['code' => 1, 'message' => 'Unknown license'];
    public const VERIFYLICENSE_STATUS_INACTIVE    = ['code' => 2, 'message' => 'Inactive license'];
    public const VERIFYLICENSE_STATUS_ACTIVE      = ['code' => 3, 'message' => 'Active license'];

    public function __construct()
    {
        $this->model = new \CarlBennett\API\Models\Software\VerifyLicense();
    }

    public function invoke(?array $args): bool
    {
        $this->model->_responseCode = HTTPCode::BAD_REQUEST;
        $this->model->result = [];
        $result = &$this->model->result;

        $result['issue_date'] = null;
        $result['label'] = null;
        $result['license'] = null;
        $result['paypal_transaction'] = null;
        $result['status'] = self::VERIFYLICENSE_STATUS_BAD_REQUEST;

        $data = \CarlBennett\API\Libraries\Core\Router::query();
        $result['license'] = $data['license'] ?? null;

        if (\is_null($result['license']) || empty($result['license']))
        {
            return true;
        }

        try
        {
            $license = new \CarlBennett\API\Libraries\Software\License($result['license']);
        }
        catch (\InvalidArgumentException)
        {
            // Malformed license string; bad request
            $result['status']['message'] = 'Bad request: Malformed license string';
            return true;
        }
        catch (\CarlBennett\API\Exceptions\SoftwareLicenseNotFoundException)
        {
            $this->model->_responseCode = HTTPCode::NOT_FOUND;
            $result['status'] = self::VERIFYLICENSE_STATUS_NOT_FOUND;
            return true;
        }

        $result['issue_date'] = $license->getIssuedDateTime();
        if ($result['issue_date'] instanceof \DateTimeInterface) $result['issue_date'] = $result['issue_date']->format(\DATE_RFC2822);
        $result['label'] = $license->getLabel();
        $result['paypal_transaction'] = $license->getPayPalTransaction();

        $this->model->_responseCode = HTTPCode::OK;
        $result['status'] = $license->isActive() ? self::VERIFYLICENSE_STATUS_ACTIVE : self::VERIFYLICENSE_STATUS_INACTIVE;
        return true;
    }
}
