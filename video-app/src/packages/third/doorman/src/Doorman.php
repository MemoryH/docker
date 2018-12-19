<?php

namespace Third\Doorman;

use Third\Doorman\Exceptions\DoormanException;
use Third\Doorman\Exceptions\ExpiredInviteCode;
use Third\Doorman\Exceptions\InvalidInviteCode;
use Third\Doorman\Exceptions\MaxUsesReached;
use Third\Doorman\Exceptions\NotYourInviteCode;
use Third\Doorman\Models\Invite;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class Doorman
{
    public $error = '';

    /**
     * @param             $code
     * @param string|null $email
     */
    public function redeem($code, string $email = null)
    {
        $invite = $this->prep($code, $email);

        $invite->increment('uses');
    }

    /**
     * @param             $code
     * @param string|null $email
     *
     * @return bool
     */
    public function check($code, string $email = null)
    {
        try {
            $this->prep($code, $email);
            return true;
        } catch (DoormanException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param             $code
     * @param string|null $email
     *
     * @return \Third\Doorman\Models\Invite
     * @throws \Third\Doorman\Exceptions\ExpiredInviteCode
     * @throws \Third\Doorman\Exceptions\InvalidInviteCode
     * @throws \Third\Doorman\Exceptions\MaxUsesReached
     * @throws \Third\Doorman\Exceptions\NotYourInviteCode
     */
    protected function prep($code, string $email = null)
    {
        $this->error = '';
        $invite = $this->lookupInvite($code);
        $this->validateInvite($invite, $email);

        return $invite;
    }

    /**
     * @param $code
     *
     * @return \Third\Doorman\Models\Invite
     * @throws \Third\Doorman\Exceptions\InvalidInviteCode
     */
    protected function lookupInvite($code): Invite
    {
        try {
            return Invite::where('code', '=', Str::upper($code))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new InvalidInviteCode(trans('doorman::messages.invalid', [ 'code' => $code ]));
        }
    }

    /**
     * @param \Third\Doorman\Models\Invite $invite
     * @param string|null                      $email
     *
     * @throws \Third\Doorman\Exceptions\ExpiredInviteCode
     * @throws \Third\Doorman\Exceptions\MaxUsesReached
     * @throws \Third\Doorman\Exceptions\NotYourInviteCode
     */
    protected function validateInvite(Invite $invite, string $email = null)
    {
        if ($invite->isFull()) {
            throw new MaxUsesReached(trans('doorman::messages.maxed', [ 'code' => $invite->code ]));
        }

        if ($invite->hasExpired()) {
            throw new ExpiredInviteCode(trans('doorman::messages.expired', [ 'code' => $invite->code ]));
        }

        if ($invite->isRestricted() && !$invite->isRestrictedFor($email)) {
            throw new NotYourInviteCode(trans('doorman::messages.restricted', [ 'code' => $invite->code ]));
        }
    }

    public function generate()
    {
        return app(Generator::class);
    }
}
