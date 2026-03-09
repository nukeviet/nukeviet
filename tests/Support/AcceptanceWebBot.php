<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceWebBot extends \Codeception\Actor
{
    // phpcs:disable
    use _generated\AcceptanceWebBotActions;

    /**
     * @return string
     */
    public function getDomain()
    {
        return ($_ENV['HTTPS'] == 'on' ? 'https://' : 'http://') . $_ENV['HTTP_HOST'];
    }
}
